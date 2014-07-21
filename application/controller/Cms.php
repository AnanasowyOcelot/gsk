<?php
class Controller_Cms extends Core_Controller
{
	private $o_responseOut;

	public function indexAction(Core_Request $o_requestIn)
	{
		$this->o_responseOut = new Core_Response();
		$modul               = $o_requestIn->getModul();
		$akcja               = $o_requestIn->getAkcja();

		if (Model_Administrator::czyUprawnienionyDoModulu($_SESSION['cmsAdminId'], $modul)) {

			try {
				$nazwaKlasyKontrolera = $modul . '_Controller';
				if (class_exists($nazwaKlasyKontrolera)) {
					$kontroler = new $nazwaKlasyKontrolera();

					if ($akcja != '') {
						$funkcja = $akcja . 'Action';
						if (method_exists($kontroler, $funkcja)) {
							$o_modulResponse = new Core_Response();
							$o_modulResponse = $kontroler->{$funkcja}($o_requestIn);

							switch ($o_modulResponse->getContentType()) {
								case 'ajax':
									$this->o_responseOut->setContentType($o_modulResponse->getContentType());
									$this->o_responseOut->setContent($o_modulResponse->getContent());
									break;
								case 'plik':
									break;
								default:
									$this->o_responseOut->setParametry($o_modulResponse->getParametry());

									// ???????????? nie wiem czy nie przeniesc tego do samej funkcjo render, mozna zapomniec ze tutaj sie to ustawia pozniej.
									$o_modulResponse->setTemplateDir(Core_Config::get('modules_path') . $modul . '/views/');
									//????????????????????????????????????????????????????????????????????????????????????????????????
									$tresc = $o_modulResponse->render();
									$this->o_responseOut->setHeaders($o_modulResponse->getHeaders());
									$this->o_responseOut->setErrors($o_modulResponse->getErrors());
									$this->o_responseOut->setContent($tresc);
									$this->o_responseOut->setPlikiJS($o_modulResponse->getPlikiJS());
									$this->o_responseOut->setContentType($o_modulResponse->getContentType());
									$this->o_responseOut->setLayoutTemplate($o_modulResponse->getLayoutTemplate());
									break;
							}
							if (count($o_modulResponse->getHeaders()) > 0) {
								$this->o_responseOut->setHeaders($o_modulResponse->getHeaders());
							}
						} else {
							$komunikaty   = array();
							$komunikaty[] = array('erro', "Nieprawidłowa akcja");
							$this->o_responseOut->dodajParametr('komunikaty', $komunikaty);
						}
					}
				} else {
					$podstrona = $this->db->GetRow('SELECT * FROM nawigacja WHERE url = "' . $modul . '"');

					if (isset($podstrona['id'])) {
						if ((int)$podstrona['id'] > 0) {
							$this->o_responseOut->setContent($podstrona['html']);
							$this->o_responseOut->setNaglowek($podstrona['nazwa']);
						}
					} else {
						$komunikaty   = array();
						$komunikaty[] = array('erro', "Nieprawidłowy moduł");
						$this->o_responseOut->dodajParametr('komunikaty', $komunikaty);
					}
				}
				if ($this->o_responseOut->getContentType() != 'ajax') {
					//****************************** MENU ************************************************
					$menu = new Plugin_MenuCMS($o_requestIn);
					$this->o_responseOut->dodajParametr('menuGlowne', $menu->render($modul, $akcja));
					//****************************** MENU ************************************************
				}
			} catch (Exception $error) {
				echo $error->getMessage();
			}
		} else {
			$komunikaty   = array();
			$komunikaty[] = array('erro', "Brak uprawnień do modułu");
			$this->o_responseOut->dodajParametr('komunikaty', $komunikaty);
		}

		return $this->o_responseOut;
	}
}
