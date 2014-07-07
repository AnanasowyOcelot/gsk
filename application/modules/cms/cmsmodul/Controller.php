<?php

class cmsmodul_Controller extends Core_CMS_Module_Controller
{
	public function __construct()
	{
		$modul           = 'cmsmodul';
		$mapperClassName = 'Model_Mapper_CMSModul';
		parent::__construct($modul, $mapperClassName, array(
			'liczbaNaStrone' => 50
		));
	}
}
