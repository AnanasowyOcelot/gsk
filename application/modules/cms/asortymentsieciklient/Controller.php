<?php

class asortymentsieciklient_Controller extends Core_CMS_Module_Controller
{
	public function __construct()
	{
		$modul           = 'asortymentsieciklient';
		$mapperClassName = 'Model_AsortymentSieci_KlientMapper';
		parent::__construct($modul, $mapperClassName, array(
			'liczbaNaStrone' => 50
		));
	}

	/**
	 * @param Core_Request $o_requestIn
	 * @param Model_AsortymentSieci_KlientEntity $rekord
	 * @return Model_AsortymentSieci_KlientEntity
	 */
	protected function handleRecordBeforeSave(Core_Request $o_requestIn, Model_AsortymentSieci_KlientEntity $rekord)
	{
		$rekord->setPostedFiles($o_requestIn->getPliki());
		return $rekord;
	}
}
