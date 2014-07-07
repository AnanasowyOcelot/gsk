<?php

class adres_Controller extends Core_CMS_Module_Controller
{
	public function __construct()
	{
		$modul           = 'adres';
		$mapperClassName = 'Model_Mapper_Adres';
		parent::__construct($modul, $mapperClassName, array(
			'liczbaNaStrone' => 20
		));
	}

}
