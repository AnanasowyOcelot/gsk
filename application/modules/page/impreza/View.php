<?php
class impreza_View extends Core_View
{
	public function __construct() {
		$this->modul = 'impreza';
		parent::__construct();
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}

	//============================================================================
	function wyswietlZapytaj($jezyk_id) {

		$html = '';
		$this->sm->assign('jezyk_id', $jezyk_id);
		$this->sm->assign('jezyk_skrot', $jezyk_skrot);

		$a_naglowki = array();

		$a_naglowki[1]['tytul'] ="Imprezy";
		$a_naglowki[1]['opis'] ="Jeśli jesteś zainteresowany organizacją imprezy wypełnij poniższy formularz a my skontaktujemy się z Tobą aby szczegółowo przedyskutować Twoje oczekiwania.";
		$a_naglowki[1]['rodzaj_imprezy'] ="Rodzaj imprezy";
		$a_naglowki[1]['planowana_data'] ="Planowana data";
		$a_naglowki[1]['lokalizacja'] ="Lokalizacja";
		$a_naglowki[1]['liczba_osob'] ="Liczba osób";
		$a_naglowki[1]['czas_trwania'] ="Czas trwania";
		$a_naglowki[1]['osoba_do_kontaktu'] ="Osoba do kontaktu";
		$a_naglowki[1]['imie_i_nazwisko'] ="Imię i nazwisko";
		$a_naglowki[1]['email_telefon'] ="E-mail / telefon";
		$a_naglowki[1]['uwagi'] ="Uwagi";
		$a_naglowki[1]['imie'] ="Imię";
		$a_naglowki[1]['nazwisko'] ="Nazwisko";
		$a_naglowki[1]['adres'] ="Adres";
		$a_naglowki[1]['kod'] ="Kod pocztowy";
		$a_naglowki[1]['miasto'] ="Miejscowość";
		$a_naglowki[1]['telefon'] ="Numer telefonu";
		$a_naglowki[1]['email'] ="Ares e-mail";
		$a_naglowki[1]['haslo'] ="Hasło";
		$a_naglowki[1]['haslo_potw'] ="Hasło potwierdzenie";
		$a_naglowki[1]['button'] ="wyślij";
		$a_naglowki[1]['button_wyczysc'] ="wyczyść";

		$a_naglowki[2]['tytul'] ="Events";
		$a_naglowki[2]['opis'] =" ";
		$a_naglowki[2]['rodzaj_imprezy'] ="Event type";
		$a_naglowki[2]['planowana_data'] ="Planned date";
		$a_naglowki[2]['lokalizacja'] ="Place";
		$a_naglowki[2]['liczba_osob'] ="How many people";
		$a_naglowki[2]['czas_trwania'] ="Duration";
		$a_naglowki[2]['osoba_do_kontaktu'] ="Contact person";
		$a_naglowki[2]['imie_i_nazwisko'] ="Imię i nazwisko en";
		$a_naglowki[2]['email_telefon'] ="E-mail / phone";
		$a_naglowki[2]['uwagi'] ="Uwagi en";
		$a_naglowki[2]['imie'] ="Imię en";
		$a_naglowki[2]['nazwisko'] ="Nazwisko en";
		$a_naglowki[2]['adres'] ="Adres en";
		$a_naglowki[2]['kod'] ="Kod pocztowy en";
		$a_naglowki[2]['miasto'] ="Miejscowość en";
		$a_naglowki[2]['telefon'] ="Numer telefonu en";
		$a_naglowki[2]['email'] ="Ares e-mail en";
		$a_naglowki[2]['haslo'] ="Hasło";
		$a_naglowki[2]['haslo_potw'] ="Hasło potwierdzenie";
		$a_naglowki[2]['button'] ="send";
		$a_naglowki[2]['button_wyczysc'] ="clear";

		$this->sm->assign('naglowki', $a_naglowki);

		if(isset($_SESSION['zalogowany']))
		{
			$this->sm->assign('zalogowany_imie', $_SESSION['zalogowany_imie']);
			$this->sm->assign('zalogowany_nazwisko', $_SESSION['zalogowany_nazwisko']);
			$this->sm->assign('zalogowany_telefon', $_SESSION['zalogowany_telefon']);
			$this->sm->assign('zalogowany_email', $_SESSION['zalogowany_email']);
		}
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'zapytaj.tpl');

		return $html;
	}
};
