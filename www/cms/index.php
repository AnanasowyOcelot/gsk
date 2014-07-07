<?php
	error_reporting(E_ALL);
	
	ob_start();
	
	require_once('../../application/core/Config.php');
	Core_Config::loadIni('../../application/configs/app.ini');
	Core_Config::loadIni('../../application/configs/appCms.ini');


    require_once(Core_Config::get('libs_path') . 'smarty/libs/Smarty.class.php');
    require_once(Core_Config::get('libs_path') . 'adodb5/adodb-exceptions.inc.php');
    require_once(Core_Config::get('libs_path') . 'adodb5/adodb.inc.php');
    require_once(Core_Config::get('libs_path') . 'PHPExcel_1.7.9/PHPExcel.php');
    require_once(Core_Config::get('libs_path') . 'ckeditor/ckeditor.php');

    require_once Core_Config::get('libs_path') . 'Zend_2.2.6/Loader/StandardAutoloader.php';
    $loader = new Zend\Loader\StandardAutoloader();
    $loader->registerNamespace('Zend', Core_Config::get('libs_path') . 'Zend_2.2.6');
    $loader->register();

    session_start();
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	function autoloader($s_className)
	{
		$a_tmp = explode('_', $s_className);
		$ilosc = count($a_tmp);
		$s_sciezka = '';
		for($i = 0; $i < $ilosc - 1; $i++) {
			$s_sciezka .= strtolower($a_tmp[$i]).'/';
		}
		$s_className = $a_tmp[$ilosc - 1];
	
		$s_sciezkaPliku = Core_Config::get('application_path').$s_sciezka.$s_className.'.php';
		if(file_exists($s_sciezkaPliku)) {
			require_once $s_sciezkaPliku;
		} else {
			$s_sciezkaPliku = Core_Config::get('modules_path').$s_sciezka.$s_className.'.php';
			if(file_exists($s_sciezkaPliku)) {
				require_once $s_sciezkaPliku;
			}
		}
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	spl_autoload_register('autoloader');
	
	$s_modul = 'index';
	$s_akcja = 'index';

	$path = urldecode( $_SERVER['REQUEST_URI'] );
	
	$a_pathElements = explode('/', trim($path), 5);
	$a_pathElements = array_filter($a_pathElements);
	
	$parametryGET = array();

	if (count($a_pathElements) > 0) {
		if ($a_pathElements[1] == 'cms') {
			if (isset($a_pathElements[2]) && $a_pathElements[2] != '') {
				$s_modul = strtolower($a_pathElements[2]);
			}
			if (isset($a_pathElements[3]) && $a_pathElements[3] != '') {
				$s_akcja = strtolower($a_pathElements[3]);
			}
			if (isset($a_pathElements[4]) && $a_pathElements[4] != '') {
				$params = explode(',', $a_pathElements[4]);
				foreach ($params as $paramTmp) {
					$parametr                   = explode(':', $paramTmp, 2);
                    if(isset($parametr[1])) {
                        $parametryGET[$parametr[0]] = $parametr[1];
                    }
				}
			}
		}
	}

	$o_admin = new Model_Administrator();
	if (!isset($_SESSION['cmsAdminId']) && $s_modul!="login")
	{
		header('Location:/cms/login');

	}
	else 
	{
		$o_admin = new Model_Administrator();
		$o_admin->pobierz($_SESSION['cmsAdminId']);
        //$_SESSION['admin_dane']= $o_admin->imie." ".$o_admin->nazwisko." - ".$o_admin->grupa_nazwa;
		//$_SESSION['admin_uprawnienia'] = $o_admin->uprawnienia;
	}
	
	
	
	
	/************************************ ENGINE  *******************************************/
	
	$o_request = new Core_Request();
	$o_request->setUrl($path);
	$o_request->setModul($s_modul);
	$o_request->setAkcja($s_akcja);
	$o_request->setPliki($_FILES);
	
	//======== parametry
	$a_requestParams = array_merge($_REQUEST, $parametryGET);
	$a_requestParams = array_filter($a_requestParams);
	$o_request->setParametry($a_requestParams);
	$o_request->setParametryGet($parametryGET);
	
	//if(isset($_SESSION['admin_uprawnienia']))
	if(count($o_admin->uprawnienia)>0 && $o_admin instanceof  Model_Administrator )
	{
		//$o_request->setUprawnienia($_SESSION['admin_uprawnienia']);
		$o_request->setUprawnienia($o_admin->uprawnienia);
	}
	
	//echo "<pre>UPRAWNIENIA (index.php)";
	//Core_Narzedzia::drukuj($o_admin->uprawnienia);
	
	$o_response = new Core_Response();
	$klasaFrontControllera = Core_Config::get('front_controller_class');	
	
	
	$o_kontroler = new $klasaFrontControllera();	
	$o_response = $o_kontroler->indexAction($o_request);
	//settype($o_response, 'Core_Response');	
	
	$o_response->setTemplateDir(Core_Config::get('views_path'));
	
	if ($o_response->getLayoutTemplate()=='')
	{
		$o_response->setModuleTemplate('mainLayout');
	}
	else {
		$o_response->setModuleTemplate($o_response->getLayoutTemplate());
	}
	
	//test :) $o_response->setHeaderParametr("Location","http://www.onet.pl");
	$html = '';
	
	if(count($o_response->getHeaders())>0)
	{
		$html .= $o_response->headerList();
	}
	//tu to ( komunikaty ) przenioslem bo jak sie cos wykrzaczy to sie  hearery nie wykonaja bo bedzie info ze cos  zostalo juz wyslane do przegladarki
	
	if(count($o_response->getPlikiJS()>0))
	{
		$html_js = '';
		foreach ($o_response->getPlikiJS() as $plik_js)
		{
			
			$plik = "../../application/modules/cms/".$plik_js;
			$len = filesize($plik);			
			$fp = fopen($plik,"r");
			$html_js .=  fread($fp, $len);
			
			fclose($fp);
		}
		
		$o_response->dodajParametr('js_body', $html_js);
	}
	
	$komunikaty = '';
	$komunikaty = ob_get_contents();
	ob_end_clean();

	$o_response->dodajParametr('bledy', implode("<br>",$o_response->getErrors()));
	$o_response->dodajParametr('komunikat', $komunikaty);
	
	switch ($o_response->getContentType())
	{
		case 'ajax':
			$html =  $o_response->getContent();
			break;
		case 'plik':
			break;
		default:
			$html .= $o_response->render();
			break;
	}
	
	
	
	echo $html;
	
	
	/************************************ ENGINE  *******************************************/
	if ($o_response->getContentType() != 'ajax') {
		echo Plugin_DebugConsole::render(array(
			'komunikaty' => $komunikaty,
			'modul'      => $s_modul,
			'akcja'      => $s_akcja,
			'parametry'  => $a_requestParams
		));
	}
