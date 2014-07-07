<?php

class promocjeetapy_Controller extends Core_CMS_Module_Controller
{
	public function __construct()
	{
		$modul           = 'promocjeetapy';
		$mapperClassName = 'Model_Promocje_EtapMapper';
		parent::__construct($modul, $mapperClassName, array(
			'liczbaNaStrone' => 20
		));
	}
}
