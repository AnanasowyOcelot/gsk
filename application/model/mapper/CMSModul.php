<?php

class Model_Mapper_CMSModul extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable() {
        return 'cms_moduly';
    }
    
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass() {
        return 'Model_DataObject_CMSModul';
    }
    
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function getPrimaryKey() {
        return 'index';
    }
    
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription() {
        return array(
            'index'   => array('modul_index',   Core_Mapper::T_INT     ),
            'id'      => array('modul_id',      Core_Mapper::T_VARCHAR ),
            'nazwa'   => array('modul_nazwa',   Core_Mapper::T_VARCHAR ),
            'aktywny' => array('modul_aktywny', Core_Mapper::T_INT     )
        );
    }
}
