<?php

class index_Controller extends Core_ModuleController
{
    public function __construct() {
        $this->modul = 'index';
        parent::__construct();
    }
    
    public function indexAction() {

     
        $this->assign('link', '/'.$this->modul.'/');
        
        $o_indexResponse = new Core_Response();
        
        $o_indexResponse->setModuleTemplate("index");
        
        return $o_indexResponse;
    }
};
