<?php

class Core_Smarty
{
	private static $instance = null;
	private static $o_sm = null;

	//=====================================================
    private function __construct() {
		$this->o_sm = new Smarty();
		$this->o_sm->compile_dir = Core_Config::get('server_path').'tmp/';
		$this->sm->compile_check = true;
		$this->sm->force_compile = true;
		$this->sm->caching = 0;
	}

	//=====================================================
	public static function instancja()
	{
		if(!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	//=====================================================
	function __call($method, $args) {
		return call_user_func_array(array($this->o_sm, $method),$args);
	}
	
};
