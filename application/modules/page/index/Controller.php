<?php

class index_Controller extends Core_ModuleController
{
    public function __construct($params) {
        $this->modul = 'index';
        parent::__construct($params);
    }
    
    public function indexAction() {

        $o_indexResponse = new Core_Response();
        
        $o_indexResponse->setModuleTemplate("index");
        
        return $o_indexResponse;
    }
};
