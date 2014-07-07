<?php

class formularz_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'formularz';

		parent::__construct();
	}

	//==================================================================================================
	private function sprawdzFormularzAction($dane_in)
	{
		$errors = array();

		$a_niewymagane = array_flip(explode(",",$dane_in['nie_wymagane']));
		foreach ($dane_in as $nazwa_pola => $wartosc)
		{
			//$errors[$nazwa_pola] = '';
			if($wartosc=='' && !isset($a_niewymagane[$nazwa_pola]))
			{
				$errors[$nazwa_pola] = 'error';

				if($nazwa_pola=='email')
				{
					//bla bla bla spr
					if(!Core_Narzedzia::emailValidation($wartosc))
					{
						$errors[$nazwa_pola] = 'error';
					}
				}
			}


		}


		return $errors;
	}
	//==================================================================================================
	public function wyswietlAction($route_in) 
	{

		$o_pobierzResponse = new Core_Response();

		$o_form = new formularz_View();
		$tresc = '';
		//$tresc .= '<pre>'.print_r($route_in,1).'</pre>';
		
		if($route_in['dane']['zapis']==1)
		{
			$errors = $this->sprawdzFormularzAction($route_in['dane']);
			if(count($errors)>0)
			{
				$tresc .= $o_form->formularzView($route_in['dane'], $route_in['jezyk_id'],$route_in['formularz_id'],$errors);
			}
			else
			{
				$obj_form = new Model_Formularz('hostessa_zgloszenie');
				$obj_form->zapisz($route_in['dane'],$route_in['pliki']);

				$tresc .= $o_form->komunikatView("Zgłoszenie zostało wysłane", $route_in['jezyk_id']);
			}

		}
		else
		{
			$tresc .= $o_form->formularzView($route_in['dane'], $route_in['jezyk_id'],$route_in['formularz_id']);
		}


		$o_pobierzResponse->setContent($tresc);


		return $o_pobierzResponse;
	}
	//==================================================================================================
};
