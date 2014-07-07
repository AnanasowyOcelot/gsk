<?php
	error_reporting(E_ALL);
	
	ob_start();
	session_start();
	//define('ADODB_ASSOC_CASE', 2);
	
//	define('SMTP_EMAIL_HOST','kiwi.home.pl');
//	define('SMTP_EMAIL_USER','dev@kiwisoft.pl');
//	define('SMTP_EMAIL_PASS','DeV_2009');
//	
//	define('SMTP_EMAIL','dev@kiwisoft.pl');
//	define('SMTP_EMAIL_NAME','DevSystem');
	
	/********************************************************************************************/
	/****************************** DEFINICJE KLAS ******************************************/
	/********************************************************************************************/
	require_once('../../application/core/Config.php');
	Core_Config::loadIni('../../application/configs/app.ini');	
	Core_Config::loadIni('../../application/configs/appPage.ini');
	
	
	
	require_once(Core_Config::get('libs_path').'smarty/libs/Smarty.class.php');
	require_once(Core_Config::get('libs_path').'adodb5/adodb-exceptions.inc.php');
	require_once(Core_Config::get('libs_path').'adodb5/adodb.inc.php');	
	require_once(Core_Config::get('libs_path').'phpmailer/class.phpmailer.php');
	require_once(Core_Config::get('libs_path').'ckeditor/ckeditor.php');
	
	
	
	
	include_once('../../application/route/RouteCollection.php');	
	include_once('../../application/route/UrlMatcher.php');
	include_once('../../application/route/Route.php');
	include_once('../../application/route/RouteCompiler.php');
	include_once('../../application/route/CompiledRoute.php');
	
	
	/********************************************************************************************/
	/****************************** DEFINICJE REGUL ROUTERA****************************/
	/********************************************************************************************/
	
	
	$routes = new RouteCollection();
	
	//============================================================
	$routes->add('homepage', new Route('/{jezyk}', array('rule' => 'home', 'url'=>'home', 'jezyk' => 'pl',), array(  'jezyk' => 'pl|en')));
		
	//============================================================
	$reg = '/{jezyk}/produkt/{url}';
	$o_router = new Route($reg, array('rule' => 'produkt_url','jezyk' => 'pl'));
	$routes->add('route_produkt', $o_router);
	
	//============================================================
	$reg = '/szukaj/{parametry}';
	$o_router = new Route($reg, array('rule' => 'szukaj'));
	$routes->add('route_szukaj', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/gal/{url}';
	$o_router = new Route($reg, array('rule' => 'galeria_url', 'modul'=>'galeria','akcja'=>'pobierz', 'strona' => 1,'jezyk' => 'pl'), array(  'jezyk' => 'pl|en'));
	$routes->add('route_galeria', $o_router);
	
	//============================================================
	    $reg = '/{jezyk}/newsletter';
	    $o_router = new Route($reg, array('rule' => 'newsletter', 'modul'=>'newsletter','akcja'=>'panelAjax','jezyk' => 'pl'), array(  'jezyk' => 'pl|en'));
	    $routes->add('route_newsletter', $o_router);
	    
	    //============================================================
	$reg = '/{jezyk}/onas';
	$o_router = new Route($reg, array('rule' => 'onas', 'modul'=>'onas','akcja'=>'indexAjax','jezyk' => 'pl'), array(  'jezyk' => 'pl|en'));
	$routes->add('route_onas', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/box/{url}';
	$o_router = new Route($reg, array('rule' => 'box_url', 'modul'=>'box','akcja'=>'pobierz', 'strona' => 1,'jezyk' => 'pl'), array(  'jezyk' => 'pl|en'));
	$routes->add('route_box', $o_router);
	
	//============================================================
	/*
	$reg = '/{jezyk}/szkolenie/{url}';
	$o_router = new Route($reg, array('rule' => 'szkolenie_url', 'modul'=>'szkolenie','akcja'=>'pobierz', 'strona' => 1,'jezyk' => 'pl'), array(  'jezyk' => 'pl|en'));
	$routes->add('szkolenie_url', $o_router);*/
	
	//============================================================
	$reg = '/{jezyk}/gal/{url}/{strona}';
	$o_router = new Route($reg, array('rule' => 'galeria_url', 'modul'=>'galeria','akcja'=>'pobierz', 'strona' => 1,'jezyk' => 'pl'), array( 'strona' => '\d+',  'jezyk' => 'pl|en'));
	$routes->add('route_galeria', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/kategoria/{url}/{strona}';
	$o_router = new Route($reg, array('rule' => 'kategoria_url', 'modul'=>'kategoria','akcja'=>'pobierz', 'strona' => 1,'jezyk' => 'pl'), array( 'strona' => '\d+',  'jezyk' => 'pl|en'));
	$routes->add('route_kat_strona', $o_router);
	
	$reg = '/{jezyk}/kategoria/{url}/{strona}/{parametry}';
	$o_router = new Route($reg, array('rule' => 'kategoria_url_strona_params','modul'=>'kategoria','akcja'=>'pobierz','jezyk' => 'pl'), array( 'strona' => '\d+',  'jezyk' => 'pl|en'));
	$routes->add('route_kat_strona_param', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/{url}';
	$o_router = new Route($reg, array('rule' => 'podstrona','modul'=>'podstrona', 'akcja'=>'pobierz', 'strona' => 1,'jezyk' => 'pl', 'parametry'=>''), array( 'strona' => '\d+',  'jezyk' => 'pl|en'));
	$routes->add('route_podstrona', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/{url}/{strona}';
	$o_router = new Route($reg, array('rule' => 'podstrona_strona','modul'=>'podstrona', 'akcja'=>'pobierz', 'strona' => 1, 'jezyk' => 'pl', 'parametry'=>''), array('strona' => '\d+',   'jezyk' => 'pl|en'));
	$routes->add('route_podstrona_strona', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/{url}/{parametry}';
	$o_router = new Route($reg, array('rule' => 'podstrona_parametry','modul'=>'podstrona', 'akcja'=>'pobierz', 'strona' => 1, 'jezyk' => 'pl', 'parametry'=>''), array(   'jezyk' => 'pl|en'));
	$routes->add('route_podstrona_parametry', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/{url}/{strona}/{parametry}';
	$o_router = new Route($reg, array('rule' => 'podstrona_parametry_strona','modul'=>'podstrona', 'akcja'=>'pobierz', 'strona' => 1, 'jezyk' => 'pl', 'parametry'=>''), array('strona' => '\d+',   'jezyk' => 'pl|en'));
	$routes->add('route_podstrona_strona_parametry', $o_router);
	
	//============================================================
	$reg = '/{modul}/{akcja}';
	$o_router = new Route($reg, array('rule' => 'modul_akcja'));
	$routes->add('route_modul_akcja', $o_router);
	
	//============================================================
	$reg = '/{modul}/{akcja}/{parametry}';
	$o_router = new Route($reg, array('rule' => 'modul_akcja_parametry','jezyk' => 'pl'));
	$routes->add('route_modul_akcja_parametry', $o_router);
	
	//============================================================
	// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxjakby co to zakomentowac 
	$reg = '/{jezyk}/{modul}/{akcja}';
	$o_router = new Route($reg, array('rule' => 'modul_akcja_jezyk','jezyk' => 'pl'), array(  'jezyk' => 'pl|en'));
	$routes->add('route_modul_akcja_jezyk', $o_router);
	
	//============================================================
	$reg = '/{jezyk}/{modul}/{akcja}/{parametry}';
	$o_router = new Route($reg, array('rule' => 'modul_akcja_parametry_jezyk','jezyk' => 'pl'));
	$routes->add('route_modul_akcja_parametry_jezyk', $o_router);
	
	//============================================================
	$routes->add('pusta', new Route('/{cos}', array('rule' => 'home', 'url'=>'strona-glowna', 'jezyk' => 'pl',), array(  'jezyk' => 'pl|en',)));
	
	$matcher = new UrlMatcher($routes);	
	$routeMatch = $matcher->match($_SERVER['REQUEST_URI']);	
	
	
	/**************************************************************************************************/
	/******************************** OBSŁUGA REQUEST URL ************************************/
	/**************************************************************************************************/
	echo "URL: ".$_SERVER['REQUEST_URI'];	
	if(count($routeMatch)>0)
	{		
		if(isset($routeMatch['parametry']))
		{			
			if($routeMatch['parametry']!='')
			{
				$params = explode(',', $routeMatch['parametry']);
				
				foreach($params as $paramTmp)
				{
					$parametr = explode(':', $paramTmp, 2);
					$parametry_lista[$parametr[0]] = $parametr[1];
				}
				$routeMatch['parametry_lista'] = $parametry_lista;
			}
		}
		
		
		$routeMatch['request_uri'] =$_SERVER['REQUEST_URI'];
//		echo "<pre>";
//		echo "URLmatch===>";
//		print_r($routeMatch);
//		echo "</pre>";
	}
	else
	{
		echo "<br><br>NIEZNANY ADRES";
	}
	
	
	/**************************************************************************************************/
	/******************************** OBSŁUGA REFFER URL**************************************/
	/**************************************************************************************************/
	$routeRefererMatch = '';
	if(isset($_SERVER['HTTP_REFERER']))
	{
		$refererURL = $_SERVER['HTTP_REFERER'];
		
		$refererURL = str_replace("http://","",$refererURL);
		$refererURL = str_replace($_SERVER['HTTP_HOST'],"",$refererURL);
		
		if($refererURL!="/")
		{
			$routeRefererMatch = $matcher->match($refererURL);
			$routeRefererMatch['request_uri'] =$_SERVER['HTTP_REFERER'];
		}
	}
	
	/*************************************************************************************************/
	/************************************ AUTOLOADER  *******************************************/
	/*************************************************************************************************/
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
	
	spl_autoload_register('autoloader');
	
	/*************************************************************************************************/
	/************************************ TEST CONFIGU  *****************************************/
	/*************************************************************************************************/
//	$o_router = new Controller_Router('../../application/configs/test.ini','staging');
//	echo 'test.ini<pre>';
//	print_r($o_router->_dataArray);
//	echo "</pre>";
//	$s_modul = 'index';
//	$s_akcja = 'index';
	
	$path = urldecode( $_SERVER['REQUEST_URI'] );	
	$parametryGET = array();
	/*************************************************************************************************/
	/************************************ JEZYK  ****************************************************/
	/*************************************************************************************************/
	$o_jezyk = new Model_Jezyk();
	$o_jezyk->pobierzPrzezSkrot($routeMatch['jezyk']);	
	
	
	if((int)$o_jezyk->id>0)
	{
		$routeMatch['jezyk_id'] = $o_jezyk->id;	
		Core_Config::set("jezyk_id",$o_jezyk->id);
		Core_Config::set("jezyk_skrot",$o_jezyk->skrot);
	}
	else 
	{
		$routeMatch['jezyk_id'] = 1;	
		Core_Config::set("jezyk_id",1);
		Core_Config::set("jezyk_skrot",'pl');
	}
	
	
	/*************************************************************************************************/
	 /************************************ ENGINE  **************************************************/
	 /*************************************************************************************************/
	$o_request = new Core_Request();
	$o_request->setUrl($path);
	$o_request->setRoute($routeMatch);
	$o_request->setRefererRoute($routeRefererMatch);
	$o_request->setPliki($_FILES);
	
	/*************************************************************************************************/
	/*****************************PRZEKAZANE PARAMETRY*************************************/
	/*************************************************************************************************/
	$a_requestParams = array_merge($_REQUEST, $parametryGET);
	$o_request->setParametry($a_requestParams);
		
	/*************************************************************************************************/
	/***************************** RESPONSE MODUL ********************************************/
	/*************************************************************************************************/
	$o_response = new Core_Response();
	
	$klasaFrontControllera = Core_Config::get('front_controller_class');
	$o_kontroler = new $klasaFrontControllera();
	$o_response = $o_kontroler->indexAction($o_request);
	
	
	$o_response->setTemplateDir(Core_Config::get('views_path'));
	
	if ($o_response->getLayoutTemplate()=='')
	{
		$o_response->setModuleTemplate('pageLayout');
	}
	else {
		$o_response->setModuleTemplate($o_response->getLayoutTemplate());
	}
	
	
	/***************************** BUDOWA STRONY **************************************/
	$html = '';
	
	/*************************************************************************************************/
	/***************************** NAGLOWKI - header*********************************************/
	/*************************************************************************************************/
	if(count($o_response->getHeaders())>0)
	{
		$html .= $o_response->headerList();
	}
	//tu to ( komunikaty ) przenioslem bo jak sie cos wykrzaczy to sie hearery nie wykonaja 
	//bo bedzie info ze cos zostalo juz wyslane do przegladarki
	
	/*************************************************************************************************/
	/************************** NAGLOWKI - javascript *********************************************/
	/*************************************************************************************************/
	if(count($o_response->getPlikiJS()>0))
	{
		$html_js = '';
		foreach ($o_response->getPlikiJS() as $plik_js)
		{
			$plik = "../../application/modules/cms/".$plik_js;
			$len = filesize($plik);			
			$fp = fopen($plik,"r");
			$html_js =  fread($fp, $len);
			fclose($fp);
		}
		
		$o_response->dodajParametr('js_body', $html_js);
	}
		
	$komunikaty = '';
	$komunikaty = ob_get_contents();
	ob_end_clean();
	
	/*************************************************************************************************/
	/***************************** KOMUNIKATY + BLEDY *****************************************/
	/*************************************************************************************************/
	$errors = implode("<br/>",$o_response->getErrors())."<br/>";
	$o_response->dodajParametr('errors', $errors );
	$o_response->dodajParametr('komunikat', $komunikaty);
	
	$o_response->dodajParametr('jezyk_id', $o_jezyk->id);
	$o_response->dodajParametr('jezyk_skrot', $o_jezyk->skrot);
    
    
    $a_naglowki = array();
    
    $a_naglowki[1]['logowanie'] = "Logowanie";
    $a_naglowki[1]['zarejestruj_sie'] = "Zarejestruj się";
    $a_naglowki[1]['sledz_nas_na'] = "Śledź nas na";
    $a_naglowki[1]['zmien_dane'] = "moje konto";

    $a_naglowki[2]['logowanie'] = "Log in";
    $a_naglowki[2]['zarejestruj_sie'] = "Register";
    $a_naglowki[2]['sledz_nas_na'] = "Follow us on";
	$a_naglowki[2]['zmien_dane'] = "my account";
    
    $o_response->dodajParametr('naglowki', $a_naglowki);
    
    $o_response->dodajParametr('url', $path );
    
    if(isset($routeMatch['url']))
    {
    	$o_response->dodajParametr('url_page', $routeMatch['url']);
    }
	
    
	if(isset($_SESSION['zalogowany']))
	{
		$o_response->dodajParametr('czy_zalogowany', $_SESSION['zalogowany']);
		$o_response->dodajParametr('zalogowany_imie', $_SESSION['zalogowany_imie']);
		$o_response->dodajParametr('zalogowany_nazwisko', $_SESSION['zalogowany_nazwisko']);
	}
	/*************************************************************************************************/
	/***************************** RENDER WYGLADU ********************************************/
	/*************************************************************************************************/		
	switch ($o_response->getContentType())
	{
		case 'ajax':			
			$html =  $o_response->getContent();
		
			break;
		case 'plik':
			break;
		default:
			$html .=$o_response->render();
			break;
	}
	
	/***************************** KONIEC **************************************************/
 	
	echo $html;
    //echo $komunikaty;
