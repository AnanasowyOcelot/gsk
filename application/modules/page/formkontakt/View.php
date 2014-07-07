<?php

class formkontakt_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'formkontakt';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	
    //============================================================================
    function wyswietlFormularz($jezyk_id) {        
        $html = '';
    
    
        $a_naglowki = array();
        
        $a_naglowki[1]['imie_i_nazwisko'] = "Imię i nazwisko";
        $a_naglowki[1]['adres_email'] = "Adres e-mail";
        $a_naglowki[1]['tresc'] = "Treść wiadomości";
        $a_naglowki[1]['wyczysc'] = "wyczyść";
        $a_naglowki[1]['wyslij'] = "wyślij";
        
        $a_naglowki[2]['imie_i_nazwisko'] = "Name and surname";
        $a_naglowki[2]['adres_email'] = "E-mail address";
        $a_naglowki[2]['tresc'] = "Message";
        $a_naglowki[2]['wyczysc'] = "clear";
        $a_naglowki[2]['wyslij'] = "send";
        
        $this->sm->assign('naglowki', $a_naglowki);
        $this->sm->assign('jezyk_id', $jezyk_id);
        $html .=  $this->sm->fetch($this->moduleTemplateDir . 'form.tpl');    
        
        return $html;
    }
    
};
