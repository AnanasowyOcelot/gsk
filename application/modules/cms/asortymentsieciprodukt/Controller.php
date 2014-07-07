<?php

class asortymentsieciprodukt_Controller extends Core_CMS_Module_Controller
{
	public function __construct()
	{
		$modul           = 'asortymentsieciprodukt';
		$mapperClassName = 'Model_AsortymentSieci_ProduktMapper';
		parent::__construct($modul, $mapperClassName, array(
			'liczbaNaStrone' => 50
		));
	}

}
