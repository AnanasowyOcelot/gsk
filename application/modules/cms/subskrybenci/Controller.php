<?php

class subskrybenci_Controller extends Core_CMS_Module_Controller
{
	public function __construct()
	{
		$modul           = 'subskrybenci';
		$mapperClassName = 'Model_SubskrybentMapper';
		parent::__construct($modul, $mapperClassName);
	}

	//================================================================================
	/**
	 * @param Core_Request $o_requestIn
	 * @return Core_Response
	 */
	public function csvAction(Core_Request $o_requestIn)
	{
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate('csv');
		$komunikaty = array();


		$mapper  = $this->getNewMapper();
		$objects = $mapper->find();

		$data = array();
		foreach ($objects as $o_subskrybent) {
			//$komunikaty[] = array('ok', $o_subskrybent->email);
			$data[] = array($o_subskrybent->email);
		}
		$fileUrl = $this->outputCSV($data);


		$o_indexResponse->dodajParametr('fileUrl', $fileUrl);
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr('fileUrl', $fileUrl);
		return $o_indexResponse;
	}

	private function outputCSV($data)
	{
		$fileDir = 'www/cms/tmp';
		if (!file_exists(Core_Config::get('server_path') . $fileDir)) {
			mkdir(Core_Config::get('server_path') . $fileDir, 0777, true);
		}

		$filePath  = $fileDir . '/subskrybenci.csv';
		$outstream = fopen(Core_Config::get('server_path') . $filePath, "w");
		foreach ($data as $row) {
			fputcsv($outstream, $row);
		}
		fclose($outstream);

		$fileUrl = Core_Config::get('www_url') . $filePath;
		return $fileUrl;
	}

	//================================================================================
	/**
	 * return Core_Form_Form
	 */
	protected function getForm(Model_Subskrybent $rekord)
	{
		$form = new Core_Form_Form();

		$field        = new Core_Form_FieldHidden();
		$field->name  = 'wymagane';
		$field->value = 'email';
		$form->addField($field);

		$field        = new Core_Form_FieldHidden();
		$field->name  = 'r[id]';
		$field->value = $rekord->id;
		$form->addField($field);

		$field        = new Core_Form_FieldText();
		$field->name  = 'r[email]';
		$field->label = 'Email';
		$field->value = $rekord->email;
		$form->addField($field);

		return $form;
	}
}

;
