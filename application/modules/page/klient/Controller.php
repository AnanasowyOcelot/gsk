<?php
class klient_Controller extends Core_ModuleController
{
	public $params = array();
	public function __construct($params) {
		$this->modul = 'klient';
		$this->params = $params;
		parent::__construct();
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function panelLogowanieAction(array $route_in, $refererRoute_in = null)
	{
		
		$view = new klient_View();
		$html = $view->wyswietlLogowanie($this->params->getParametr('jezyk_id'),$this->params->getParametr('url'));
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($html);
		
		return $o_indexResponse;		
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function panelRejestracjaAction(array $route_in, $refererRoute_in = null)
	{
		
		$view = new klient_View();
		$html = $view->wyswietlRejestracje($this->params->getParametr('jezyk_id'));
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($html);
		
		return $o_indexResponse;		
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function panelZmianaDanychAction(array $route_in, $refererRoute_in = null)
	{
		$o_klient = new Model_Klient($_SESSION['klient_id']);

		$view = new klient_View();
		$html = $view->wyswietlZmianeDanych($this->params->getParametr('jezyk_id'), $o_klient);
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setContentType('ajax');
		$o_indexResponse->setContent($html);

		return $o_indexResponse;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function przypomnienieHaslaAction(array $route_in, $refererRoute_in = null)
	{
		$o_klient = new Model_Klient();
  		$result = $o_klient->zmianaHasla($this->params->getParametr('login'), $this->params->getParametr('passwd'), $this->params->getParametr('passwd_potw'),$this->params->getParametr('jezyk_id'));
		
  		//$result = $this->params->getParametr('login').' - '.$this->params->getParametr('passwd').' - '.$this->params->getParametr('passwd_potw');
		
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($result);
		
		return $o_indexResponse;			
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function panelHasloAction(array $route_in, $refererRoute_in = null)
	{
		
		$view = new klient_View();
		$html = $view->wyswietlZmianeHasla($this->params->getParametr('jezyk_id'));
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($html);
		
		return $o_indexResponse;		
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zmianahaslaAction(array $route_in, $refererRoute_in = null)
	{
		$token = $route_in['parametry_lista']['token'];
		$jezyk = $route_in['parametry_lista']['jezyk'];
		
		$o_klient = new Model_Klient();
  		$html = $o_klient->zmianaHaslaAktywacja($token,$jezyk);
  		
		
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->dodajParametr("komunikat",$html);	
		$o_indexResponse->setModuleTemplate("komunikat");
		$o_indexResponse->setContent($html);
		
		return $o_indexResponse;	
	}
	
	
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function logowanieAction(array $route_in, $refererRoute_in = null)
	{
		
		$router .= $this->params->getParametr('login').' '.$this->params->getParametr('haslo');
		
//		$view = new klient_View();
//		$html = $view->wyswietlLogowanie($route_in['jezyk_id']);
//		session_start();
//		$_SESSION['zalogowany'] = "1";
//		$_SESSION['klient_id'] = "1";
//		$_SESSION['zalogowany_imie'] = "Jan";
//		$_SESSION['zalogowany_nazwisko'] = "Kowalski";
		
		$o_klient = new Model_Klient();
  		//$result = $o_klient->zaloguj($this->params->getParametr('login'), $this->params->getParametr('haslo'), $route_in['jezyk_id']);
		$result = $o_klient->zaloguj($this->params->getParametr('login'), $this->params->getParametr('haslo'), $this->params->getParametr('jezyk_id'));
		
		
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($result);
		
		return $o_indexResponse;		
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function rejestracjaZapisAction(array $route_in, $refererRoute_in = null)
	{
		//$router = print_r($this->params->getParametr('daneform'),1);
		
		$o_user = new Model_Klient();
		
		$wynik = $o_user->zarejestruj($this->params->getParametr('daneform'));
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($wynik);
		
		return $o_indexResponse;		
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zmianaDanychZapisAction(array $route_in, $refererRoute_in = null)
	{
		$o_user = new Model_Klient($_SESSION['klient_id']);

		$wynik = $o_user->zmienDane($this->params->getParametr('daneform'));
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setContentType('ajax');
		$o_indexResponse->setContent($wynik);

		return $o_indexResponse;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function wylogowanieAction(array $route_in, $refererRoute_in = null)
	{
		
		if(isset($_SESSION['zalogowany']))
		{
			$_SESSION['zalogowany'] = null;
			session_destroy();
		}
		
		header('HTTP/1.1 301 Moved Permanently');
		header('Location:' . Core_Config::get('www_url'));
		exit();
			
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function wyswietlPunktyAction(array $route_in, $refererRoute_in = null) 
	{
		$zalogowany = 0;
		if(isset($_SESSION['klient_id']))
		{
			$klient_id = $_SESSION['klient_id'];
			
			if((int)$klient_id>0)
			{
				$zalogowany = 1;
				$o_klient = new Model_Klient($klient_id);
			}
		}
		
		$view = new klient_View();
		$html = $view->wyswietlPunkty($route_in['jezyk_id'], $o_klient, $zalogowany);
		
		$o_indexResponse = new Core_Response();	
		$o_indexResponse->setContent($html);
		return $o_indexResponse;
		
		
	}

}
