<?php
class Controller_PageElement extends Core_Controller
{
	public function pobierzElementyStrony($id_podstrona, $route_in, $requestIn='')
	{
		$db = Core_DB::instancja();
		
		$id_jezyk = $route_in['jezyk_id'];
		$skrot_jezyk = $route_in['jezyk'];
		$parametry = $route_in['parametry'];
		
		$sql_elementy = "SELECT
					pe.pe_klucz AS klucz,
					pep.element_parametr AS parametr,
    					pep.element_tpl_nazwa AS tpl_nazwa
				FROM 
					page_elementy_podstrony AS pep, 
					page_elementy AS pe 
				WHERE 
					pe.pe_id = pep.element_id 
					AND pep.podstrona_id=".$id_podstrona;
		$result_podstrona_elementy = $db->query($sql_elementy);
		$a_elementy = array();
		if($result_podstrona_elementy->_numOfRows>0)
		{
			foreach($result_podstrona_elementy as $element)
			{
				//Core_Narzedzia::drukuj($element);
				
				switch ($element['klucz'])
				{
					/*case 'box_loga':
						$view = new partnerzy_View();
						$tresc = $view->BudujListe($id_jezyk);
						$a_elementy[$element['tpl_nazwa']] = $tresc;		
						break;*/
					
					case 'box_podstrona':
						$o_box = new box_View();
						$tresc = $o_box->boxPodstronaWidok($id_podstrona, $id_jezyk);
						$a_elementy[$element['tpl_nazwa']] = $tresc;
						
						break;						
					case 'box_form_kontakt':
						$o_controller = new formkontakt_Controller();
						$o_response = $o_controller->wyswietlAction(array(
						'jezyk_id' => $id_jezyk,
						'parametry' => $parametry									
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;						
					case 'box_animacja':
						$o_box = new box_View();
						$tresc = $o_box->boxAnimacja($element['parametr'], $id_jezyk);
						$a_elementy[$element['tpl_nazwa']] = $tresc;

						break;
					/*case 'box_stroje':						
						$o_stroje = new stroje_View();
						$tresc = $o_stroje->BudujListe($id_jezyk);
						$a_elementy[$element['tpl_nazwa']] = $tresc;						
						break;*/
					case 'box_lista_broni':												
						$o_controller = new bron_Controller();
						$o_response = $o_controller->wyswietlListeAction(array(
						'jezyk_id' => $id_jezyk,
						'parametry' => $parametry									
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;
					case 'box_menu_broni':												
						$o_controller = new bron_View();
						$html = $o_controller->BudujListeBroni($id_jezyk);		
						
						$a_elementy[$element['tpl_nazwa']] = $html;
						break;
					case 'box_aktualnosci':
						$o_controller = new aktualnosci_Controller();
						$o_response = $o_controller->wyswietlAction(array(
						'jezyk_id' => $id_jezyk,
						'parametry' => $parametry,
						'strona' => $route_in['strona']						
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;
					case 'box_video':						
						$o_controller = new video_Controller();
						$o_response = $o_controller->wyswietlAction(array(
						'jezyk_id' => $id_jezyk,
						'parametry' => $parametry,
						'strona' => $route_in['strona']						
						));
						//echo $o_response->getContent();
						//die();
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;
					/*case 'box_oferta':
						$a_elementy[$element['tpl_nazwa']] = "BOX OFERTA";
						break;*/
					/*case 'menu_dania':
						$o_controller = new menudania_Controller();
						$o_response = $o_controller->pobierzMenuAction(array(
						'jezyk_id' => $id_jezyk
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;*/
					case 'box_html':
						$o_box = new box_View();
						$tresc = $o_box->boxWidok($element['parametr'], $id_jezyk);
						$a_elementy[$element['tpl_nazwa']] = $tresc;
						break;
					case 'box_lista_szkolen':						
						$o_controller = new szkolenie_Controller();
						$o_response = $o_controller->wyswietlListeAction(array(
                        'id_podstrona' => $id_podstrona,
						'jezyk_id' => $id_jezyk,
						'jezyk_skrot' => $skrot_jezyk,
						'parametry' => $parametry										
						));						
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;						
					case 'lista_kart':						
						$o_controller = new karta_Controller();
						$o_response = $o_controller->wyswietlListeAction(array(
						'jezyk_id' => $id_jezyk,
						'jezyk_skrot' => $skrot_jezyk,
						'parametry' => $parametry										
						));						
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;	
					case 'box_szczegoly_szkolenia':
						$o_controller = new szkolenie_Controller();
						if((int)$element['parametr'] > 0) {
							$o_response = $o_controller->pobierzPrzezIdAction(array(
									'jezyk_id' => $id_jezyk,
									'szkolenie_url' => $parametry
								),
								$element['parametr']
							);
							$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						} else {
							$o_response = $o_controller->pobierzPrzezURLAction(array(
								'jezyk_id' => $id_jezyk,
								'szkolenie_url' => $parametry
							));
							$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						}
						break;
					case 'box_cennik':						
						$o_controller = new cennik_Controller();
						$o_response = $o_controller->wyswietlAction(array(
						'jezyk_id' => $id_jezyk,
						'jezyk_skrot' => $skrot_jezyk,
						'parametry' => $parametry										
						));						
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;
					case 'box_lista_nagrod':						
						$o_controller = new nagrody_Controller();
						$o_response = $o_controller->wyswietlListeAction(array(
						'jezyk_id' => $id_jezyk,
						'jezyk_skrot' => $skrot_jezyk																
						));						
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;		
					case 'box_punkty_user':						
						$o_controller = new klient_Controller();
						$o_response = $o_controller->wyswietlPunktyAction(array(
						'jezyk_id' => $id_jezyk						
						));						
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;			
					
					/*case 'box_galeria_mini':
						$o_controller = new galeria_Controller();
						$o_response = $o_controller->pobierzPrzezIdAction(array(
						'jezyk_id' => $id_jezyk,
						'galeria_id' => $element['parametr'],
						'galeria_typ' => 'mini'
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;*/
					case 'box_dane_teleadresowe':
						$o_controller = new daneteleadresowe_Controller();
						$o_response = $o_controller->wyswietlAction(array(
						'jezyk_id' => $id_jezyk
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;
					case 'box_rezerwacja_kalendarz':
						$o_controller = new rezerwacjakalendarz_Controller();
						$o_response = $o_controller->wyswietlAction(array(
						'jezyk_id' => $id_jezyk
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;
					case 'box_rezerwacja_formularz':
                        $o_controller = new rezerwacjaformularz_Controller();
                        $o_response = $o_controller->wyswietlAction(array(
                        'jezyk_id' => $id_jezyk,
                        'parametry' => $parametry                                    
                        ));
                        $a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
                        break;                        
                    
                    /*case 'box_slider':
						$a_elementy[$element['tpl_nazwa']] = '<div style="margin-bottom: 40px; border:2px solid #5a3822;"><img src="/www/page/img/glowne_zdjecie.png"></div>';
						break;*/
					/*case 'box_hostessy':
						$o_controller = new hostessy_Controller();
						$o_response = $o_controller->wyswietlAction(array(
						'jezyk_id' => $id_jezyk,
						'parametry' => $parametry
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;*/
					/*case 'formularz_zgloszenie':						
						//$o_form = new formularz_View();
						$o_form = new formularz_Controller();
						//$tresc = $o_form->formularzView($requestIn, $id_jezyk,$element['parametr']);
						$o_response = $o_form->wyswietlAction(array(
						'jezyk_id' => $id_jezyk,
						'formularz_id' => $element['parametr'],
						'dane' =>$requestIn->getParametry(),
						'pliki' =>$requestIn->getPliki()
						));
						$a_elementy[$element['tpl_nazwa']] = $o_response->getContent();
						break;*/
				}
			}
		}
		else
		{
			echo "BRAK ELEMENTOW";
		}
		//Core_Narzedzia::drukuj($a_elementy);
		return $a_elementy;
	}
	public function galeriaAction($idGalerii = 0) {
		$html = '';
		$galeria = new Model_Galeria($idGalerii);
		$html .= '<pre>'.print_r($galeria, 1).'</pre>';
		return $html;
	}
};
