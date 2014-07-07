<?php

class Model_Mapper_AtrybutWartosc extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'produkty_atrybuty_wartosci';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_DataObject_AtrybutWartosc';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function getPrimaryKey()
    {
        return 'id';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'produkt_id' => array('produkt_id', Core_Mapper::T_INT),
            'nazwa' => array('nazwa', Core_Mapper::T_VARCHAR),
            'wartosc' => array('wartosc', Core_Mapper::T_VARCHAR)
        );
    }
}
