<?php

class Core_Controller
{
	protected $db = null;
	//protected $params = array();
	//protected $request ;
	protected $errors = array();
	public $modul = '';
	public $akcja = '';

	// smarty
	private $a_assign = array();
	protected $modulePath = '';
	protected $templateDir = '';
	protected $template = '';
	protected $ajaxData = '';
	// koniec smarty

	public function __construct() {				
		$this->db = Core_DB::instancja();
	}

	public function setModulePath($s_path) {
		$this->modulePath = $s_path;
	}

	public function setTemplate($s_nazwa) {
		$this->template = $s_nazwa;
	}
	
	public function getTemplate() {
		return $this->template;
	}

	public function setModuleTemplate($s_nazwa) {
		$this->setTemplateDir(Core_Config::get('modules_path'));
		$this->template = $this->modul . '/views/' . $s_nazwa;
	}

	public function setTemplateDir($s_dir) {
		$this->templateDir = $s_dir;
	}
	
	public function getTemplateDir() {
		return $this->templateDir;
	}
	
	public function getErrors() {
		return $this->errors;
	}

	protected function assign($s_name, $s_val) {
		$this->a_assign[$s_name] = $s_val;
	}

	public function setAjaxData($data)
	{
		$this->ajaxData = $data;
	}

	public function getAjaxData()
	{
		return $this->a_assign['test'];
	}

	function getAssignedValue($key)
	{
		return $this->a_assign[$key];
	}
//	public function render() {
//		$o_sm = new Smarty();
//		$o_sm->template_dir = $this->templateDir;
//		$o_sm->compile_dir = Core_Config::get('server_path').'tmp/';
//		$o_sm->compile_check = true;
//		foreach($this->a_assign as $key => $value) {
//			$o_sm->assign($key, $value);
//		}
//		return $o_sm->fetch($this->template.'.tpl');
//	}

	public function renderJSON() {
		return json_encode($this->a_assign);
	}
};

?>
