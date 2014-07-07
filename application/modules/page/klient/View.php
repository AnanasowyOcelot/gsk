<?php
class klient_View extends Core_View
{
	public function __construct() {
		$this->modul = 'klient';
		parent::__construct();
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	//============================================================================
	function wyswietlLogowanie($jezyk_id, $url) {

		$a_naglowki = array();

		$a_naglowki[1]['tytul'] ="Logowanie";
		$a_naglowki[1]['button'] ="zaloguj";
		$a_naglowki[1]['email'] ="Login (e-mail)";
		$a_naglowki[1]['haslo'] ="Hasło";
		$a_naglowki[1]['zapomnialem_hasla'] ="zapomniałem hasła";

		$a_naglowki[2]['tytul'] ="Login";
		$a_naglowki[2]['button'] ="login";
		$a_naglowki[2]['email'] ="Login (e-mail)";
		$a_naglowki[2]['haslo'] ="Password";
		$a_naglowki[2]['zapomnialem_hasla'] ="forgot password";

		$html = '';

		$this->sm->assign('naglowki', $a_naglowki);
		$this->sm->assign('jezyk_id', $jezyk_id);
		$this->sm->assign('jezyk_skrot', $jezyk_skrot);
		$this->sm->assign('url', $url);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'logowanie.tpl');

		return $html;
	}

	//============================================================================
	function wyswietlZmianeHasla($jezyk_id) {

		$a_naglowki = array();

		$a_naglowki[1]['tytul'] ="Nowe hasło";
		$a_naglowki[1]['button'] ="wyslij";
		$a_naglowki[1]['email'] ="Login (e-mail)";
		$a_naglowki[1]['haslo'] ="Nowe hasło";
		$a_naglowki[1]['haslo_potw'] ="Powtórz hasło";
		

		$a_naglowki[2]['tytul'] ="New password";
		$a_naglowki[2]['button'] ="send";
		$a_naglowki[2]['email'] ="Login (e-mail)";
		$a_naglowki[2]['haslo'] ="New password";
		$a_naglowki[2]['haslo_potw'] ="Repeat password";
		

		$html = '';

		$this->sm->assign('naglowki', $a_naglowki);
		$this->sm->assign('jezyk_id', $jezyk_id);		
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'zmianaHasla.tpl');

		return $html;
	}
	//============================================================================
	function wyswietlRejestracje($jezyk_id) {

		$html = '';
		$this->sm->assign('jezyk_id', $jezyk_id);
		//$this->sm->assign('jezyk_skrot', $jezyk_skrot);

		$a_naglowki = array();

		$a_naglowki[1]['tytul'] ="Rejestracja";
		$a_naglowki[1]['info'] ="Aby w pełni wykorzystać możliwości strony prosimy o rejestrację.";
		$a_naglowki[1]['imie'] ="Imię";
		$a_naglowki[1]['nazwisko'] ="Nazwisko";
		$a_naglowki[1]['adres'] ="Adres";
		$a_naglowki[1]['kod'] ="Kod pocztowy";
		$a_naglowki[1]['miasto'] ="Miejscowość";
		$a_naglowki[1]['telefon'] ="Numer telefonu";
		$a_naglowki[1]['email'] ="Ares e-mail";
		$a_naglowki[1]['dane_firmy'] ="Dane firmy ";
		$a_naglowki[1]['dane_klienta'] ="Dane klienta ";
		$a_naglowki[1]['firma_nazwa'] ="Nazwa";
		$a_naglowki[1]['firma_adres'] ="Adres";
		$a_naglowki[1]['firma_kod'] ="Kod pocztowy";
		$a_naglowki[1]['firma_miasto'] ="Miejscowość";
		$a_naglowki[1]['firma_nip'] ="Nip";
		$a_naglowki[1]['firma_telefon'] ="Telefon";
		$a_naglowki[1]['haslo'] ="Hasło";
		$a_naglowki[1]['haslo_potw'] ="Powtórz hasło";
		$a_naglowki[1]['akceptuje_regulamin'] ="akceptuję <a href='/www/page/download/Regulamin_KPp.pdf' target='_blank'>regulamin</a>";
		$a_naglowki[1]['button'] ="rejestruj";

		$a_naglowki[2]['tytul'] ="Register";
		$a_naglowki[2]['info'] =" ";
		$a_naglowki[2]['imie'] ="Name";
		$a_naglowki[2]['nazwisko'] ="Surname";
		$a_naglowki[2]['adres'] ="Address";
		$a_naglowki[2]['kod'] ="Post code";
		$a_naglowki[2]['miasto'] ="Town";
		$a_naglowki[2]['telefon'] ="Phone number";
		$a_naglowki[2]['email'] ="E-mail address";
		$a_naglowki[2]['dane_firmy'] ="Company data";
		$a_naglowki[2]['dane_klienta'] ="Client data";
		$a_naglowki[2]['firma_nazwa'] ="Name";
		$a_naglowki[2]['firma_adres'] ="Address";
		$a_naglowki[2]['firma_kod'] ="Post code";
		$a_naglowki[2]['firma_miasto'] ="Town";
		$a_naglowki[2]['firma_nip'] ="VATIN";
		$a_naglowki[2]['firma_telefon'] ="Phone";
		$a_naglowki[2]['haslo'] ="Password";
		$a_naglowki[2]['haslo_potw'] ="Repeat password";
		$a_naglowki[2]['akceptuje_regulamin'] ="accept <a href='/www/page/download/Regulamin_KPp.pdf' target='_blank'>terms</a>";
		$a_naglowki[2]['button'] ="register";

		$this->sm->assign('naglowki', $a_naglowki);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'rejestracja.tpl');

		return $html;
	}

	//============================================================================
	function wyswietlZmianeDanych($jezyk_id, Model_Klient $o_klient) {

		$html = '';
		$this->sm->assign('jezyk_id', $jezyk_id);
		//$this->sm->assign('jezyk_skrot', $jezyk_skrot);

		$a_naglowki = array();

		$a_naglowki[1]['tytul'] ="Moje konto";
		$a_naglowki[1]['info'] ="";
		$a_naglowki[1]['imie'] ="Imię";
		$a_naglowki[1]['nazwisko'] ="Nazwisko";
		$a_naglowki[1]['adres'] ="Adres";
		$a_naglowki[1]['kod'] ="Kod pocztowy";
		$a_naglowki[1]['miasto'] ="Miejscowość";
		$a_naglowki[1]['telefon'] ="Numer telefonu";
		$a_naglowki[1]['email'] ="Ares e-mail";
		$a_naglowki[1]['dane_firmy'] ="Dane firmy ";
		$a_naglowki[1]['dane_klienta'] ="Dane klienta ";
		$a_naglowki[1]['firma_nazwa'] ="Nazwa";
		$a_naglowki[1]['firma_adres'] ="Adres";
		$a_naglowki[1]['firma_kod'] ="Kod pocztowy";
		$a_naglowki[1]['firma_miasto'] ="Miejscowość";
		$a_naglowki[1]['firma_nip'] ="Nip";
		$a_naglowki[1]['firma_telefon'] ="Telefon";
		$a_naglowki[1]['haslo'] ="Hasło";
		$a_naglowki[1]['haslo_potw'] ="Powtórz hasło";
		$a_naglowki[1]['akceptuje_regulamin'] ="akceptuję <a href='/www/page/download/Regulamin_KPp.pdf' target='_blank'>regulamin</a>";
		$a_naglowki[1]['button'] ="zapisz";

		$a_naglowki[2]['tytul'] ="My account";
		$a_naglowki[2]['info'] =" ";
		$a_naglowki[2]['imie'] ="Name";
		$a_naglowki[2]['nazwisko'] ="Surname";
		$a_naglowki[2]['adres'] ="Address";
		$a_naglowki[2]['kod'] ="Post code";
		$a_naglowki[2]['miasto'] ="Town";
		$a_naglowki[2]['telefon'] ="Phone number";
		$a_naglowki[2]['email'] ="E-mail address";
		$a_naglowki[2]['dane_firmy'] ="Company data";
		$a_naglowki[2]['dane_klienta'] ="Client data";
		$a_naglowki[2]['firma_nazwa'] ="Name";
		$a_naglowki[2]['firma_adres'] ="Address";
		$a_naglowki[2]['firma_kod'] ="Post code";
		$a_naglowki[2]['firma_miasto'] ="Town";
		$a_naglowki[2]['firma_nip'] ="VATIN";
		$a_naglowki[2]['firma_telefon'] ="Phone";
		$a_naglowki[2]['haslo'] ="Password";
		$a_naglowki[2]['haslo_potw'] ="Repeat password";
		$a_naglowki[2]['akceptuje_regulamin'] ="accept <a href='/www/page/download/Regulamin_KPp.pdf' target='_blank'>terms</a>";
		$a_naglowki[2]['button'] ="save";

		$this->sm->assign('naglowki', $a_naglowki);
		$this->sm->assign('o_klient', $o_klient);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'zmiana_danych.tpl');

		return $html;
	}

	//============================================================================
	function wyswietlPunkty($jezyk_id, $o_klient,$zalogowany)
	{
		$html = '';


		$a_komunikaty = array();

		$a_komunikaty[1]['text_zalogowany'] = "Aktualnie posiadasz następującą ilość punktów w Klubie Parabellum";
		$a_komunikaty[1]['text_niezalogowany'] = "Aby zapisać się do klubu musisz być zalogwany";
		$a_komunikaty[1]['niezalogowany'] = "NIEZALOGOWANY";
		$a_komunikaty[1]['przylacz'] = "Przyłącz się ";
		$a_komunikaty[1]['zapisz_sie'] = "Zapisz się";


		$a_komunikaty[2]['text_zalogowany'] = "Your points number:";
		$a_komunikaty[2]['text_niezalogowany'] = "You have to be logged in to sign up to club";
		$a_komunikaty[2]['niezalogowany'] = "NOT LOGGED IN";
		$a_komunikaty[2]['przylacz'] = "Join";
		$a_komunikaty[2]['zapisz_sie'] = "Sign up";



		$this->sm->assign('klient', $o_klient);
		$this->sm->assign('zalogowany', $zalogowany);
		$this->sm->assign('komunikaty', $a_komunikaty);
		$this->sm->assign('imie', $_SESSION['zalogowany_imie']);
		$this->sm->assign('nazwisko', $_SESSION['zalogowany_nazwisko']);

		$this->sm->assign('jezyk_id', $jezyk_id);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'box_punkty.tpl');

		return $html;
	}
	//============================================================================

};
