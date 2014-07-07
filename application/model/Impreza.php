<?php

class Model_Impreza
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function __construct($id = 0)
	{
		if((int)$id > 0)
		{
			$this->pobierz($id);
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapytanie($dane_in)
	{
		$dane = array();
		
		if(count($dane_in)>0)
		{
			$dane = array();
		
		
			foreach ($dane_in as $index => $tmp_dane)
			{
				$dane[$tmp_dane['name']] = $tmp_dane['value'];
			}
			
			$dane_out = '<pre>'.print_r($dane,1).'<pre>'. ">>>>".Core_Config::get("SMTP_EMAIL_HOST");
			
			$html_msg = '
			<b>Rodzaj imprezy: </b>'.$dane['rodzaj_imprezy'].'<br>
			<b>Planowana data: </b>'.$dane['planowana_data'].'<br>
			<b>Lokalizacja: </b>'.$dane['lokalizacja'].'<br>
			<b>Liczba osób: </b>'.$dane['liczba_osob'].'<br>
			<b>Czas trwania: </b>'.$dane['czas_trwania'].'<br>
			<b>Osoba do kontaktu: </b>'.$dane['osoba_do_kontaktu'].'<br>			
			<b>E-mail / telefon: </b>'.$dane['email_telefon'].'<br>			
			';
			

			$to = array();
			$to[] = Core_Config::get("FORM_EMAIL");	
			//$to[] = "hzdziarski@sybase.com.pl";
			
			$subject = "Zapytanie o impreze";
			$message = $html_msg;
			$from_email = "";
			$from_name = "Parabellum";
			Core_Narzedzia::wyslijWiadomoscEmail($to, $subject, $message, $from_email, $from_name);
			
			
			return 1;
		}
	}

}
