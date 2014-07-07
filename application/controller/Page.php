<?php
class Controller_Page extends Core_Controller
{
	private  $o_responseOut ;
	private $tresc = '';
	public function indexAction(Core_Request $o_requestIn) {
		$this->o_responseOut = new Core_Response();
		
		$modul = 'podstrona';//$o_requestIn->getModul();
		$akcja = 'pobierz';//$o_requestIn->getAkcja();
		$router = $o_requestIn->getRoute();	
		$refererRoute = $o_requestIn->getRefererRoute();
		if(isset($router['modul']))
		{
			$modul = $router['modul'];
		}
		if(isset($router['akcja']))
		{
			$akcja = $router['akcja'];
		}
				
		try {
			$nazwaKlasyKontrolera = $modul.'_Controller';
			if( class_exists($nazwaKlasyKontrolera) )
			{
				// ??????????????????????????????????????????????????????/
				//$kontroler = new $nazwaKlasyKontrolera($o_requestIn->getParametry());
				$kontroler = new $nazwaKlasyKontrolera($o_requestIn);
				// czy
				//$kontroler = new $nazwaKlasyKontrolera();
				//??????????????????????????????????????????????????????/?
				if($akcja != '') {
					$funkcja = $akcja.'Action';
					if( method_exists( $kontroler, $funkcja ) ) {
						
						$o_modulResponse = new Core_Response();						
						$o_modulResponse = $kontroler->{$funkcja}($router, $refererRoute);
						
						switch ($o_modulResponse->getContentType())
						{
							case 'ajax':
								$this->o_responseOut->setContent($o_modulResponse->getContent());
								$this->o_responseOut->setContentType($o_modulResponse->getContentType());
								break;
							case 'plik':
								break;
							default:
								$this->o_responseOut->setParametry($o_modulResponse->getParametry());
								$o_modulResponse->setTemplateDir(Core_Config::get('modules_path').$modul . '/views/');								
								$o_modulResponse->dodajParametr('jezyk_id',Core_Config::get("jezyk_id"));
																								
								$id_podstrona = $o_modulResponse->getPodstronaId();
								
								if((int)$id_podstrona==0)
								{
									echo "JAKIS BLAD np braku szalbonu";
									$id_podstrona = 47; //glowna
								}
		
		
								$o_elements = new  Controller_PageElement();
								$elementy  = $o_elements->pobierzElementyStrony($id_podstrona, $router, $o_requestIn);
                                
								$o_modulResponse->setParametry($elementy);
								
								$tresc = $o_modulResponse->render();
								
								$this->o_responseOut->setHeaders($o_modulResponse->getHeaders());
								$this->o_responseOut->setContent($tresc);
								$this->o_responseOut->setContentType($o_modulResponse->getContentType());
								$this->o_responseOut->setLayoutTemplate($o_modulResponse->getLayoutTemplate());
								break;
						}
						if(count($o_modulResponse->getHeaders())>0)
						{
							$this->o_responseOut->setHeaders($o_modulResponse->getHeaders());
						}
					} else {
						//    throw new Exception('Nieprawidłowa akcja.');
						$this->o_responseOut->addError('Nieprawidłowa akcja => '.$akcja);
					}
				}
			} else {
				//    throw new Exception('Nieprawidłowa akcja.');
				$this->o_responseOut->addError('Nieprawidłowa modul =>'.$modul);
			}
			//****************************** MENU ************************************************
			//$menu = new Plugin_MenuPodstrony();
			//$this->assign('menuGlowne', $menu->render());
			//$this->o_responseOut->dodajParametr('menuGlowne', $menu->render());
			//****************************** MENU ************************************************
		} catch (Exception $error) {
			echo $error->getMessage();
		}
		
		//======================================  MENU  =====================================================
		$url = '';
		if(isset($router['url']))
		{
			$url = $router['url'];
		}
		
		if(isset($o_modulResponse))
		{
			if($o_modulResponse instanceof  Core_Response  )
			{
				if($o_modulResponse->getRefererURL()!='')
				{
					$url = $o_modulResponse->getRefererURL();
				}
				$menuPodstrony = new Plugin_MenuPodstrony();
								
				$menuPodstronyGora = $menuPodstrony->wyswietlMenuPodstronyGora($router['jezyk_id'],$url);
				
				$menuPodstronyDol = $menuPodstrony->wyswietlMenuPodstronyDol($router['jezyk_id'],$url);
				
				
				$this->o_responseOut->dodajParametr('menuPodstronyGora', $menuPodstronyGora);
				$this->o_responseOut->dodajParametr('menuPodstronyDol', $menuPodstronyDol);
	
				if(isset($url))
				{
					$menuPodstronyPodrzedne = $menuPodstrony->wyswietlMenuPodstronyPodrzedne($router['jezyk_id'],$url);
					$this->o_responseOut->dodajParametr('menuPodstronyPodrzedne', $menuPodstronyPodrzedne);
				}
			}
		}
		//======================================  GALERIA  ===================================================
		
//		$o_jezyk = new Model_Jezyk();
//		$o_jezyk->pobierzPrzezSkrot($router['jezyk']);
//		
//		$jezyk_id = $o_jezyk->id;
//		
//		$o_galeria = new Model_Galeria(14);
		
		//$o_galeria->pobierzeGaleriePodstrony(84,$jezyk_id);
		
//		echo "Page.php<pre>";
//		print_r($o_jezyk);
//		//print_r($o_galeria);
//		echo "</pre>";
		//======================================  ELEMENTY PODSTRONA =======================================
		$id_podstrona = 47;
		
		
		$o_elements = new  Controller_PageElement();
		$elementy  = $o_elements->pobierzElementyStrony($id_podstrona,$router['jezyk_id']);
		$this->o_responseOut->setParametry($elementy);
		
		
	
		return $this->o_responseOut;
	}
};
/* COKOLWIEK */
