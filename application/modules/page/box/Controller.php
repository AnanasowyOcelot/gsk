<?php

class box_Controller extends Core_ModuleController
{
	public function __construct($params) {
		$this->modul = 'box'; // taki sam jak nazwa katalogu i klasy
		parent::__construct($params);
	}
};
