<?php

class Core_View
{
	protected $db = null;
	protected $sm = null;
	protected $moduleTemplateDir ='';

	public function __construct() {
		$this->db = Core_DB::instancja();
		$this->sm = new Smarty();
		$this->sm->compile_dir = Core_Config::get('server_path').'tmp/';
		$this->sm->compile_check = true;
	}
};
