<?php

class tagcategory_Controller extends Core_CMS_Module_Controller
{
    public function __construct()
    {
        $modul           = 'tagcategory';
        $mapperClassName = 'Model_Tag_CategoryMapper';
        parent::__construct($modul, $mapperClassName, array(
            'liczbaNaStrone' => 20
        ));
    }
}

