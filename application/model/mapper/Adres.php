<?php

class Model_Mapper_Adres extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'adresy';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_DataObject_Adres';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'nazwa' => array('nazwa', Core_Mapper::T_VARCHAR),
            'firma' => array('firma', Core_Mapper::T_VARCHAR),
            'kraj' => array('kraj', Core_Mapper::T_VARCHAR),
            'adres' => array('adres', Core_Mapper::T_VARCHAR),
            'miejscowosc' => array('miejscowosc', Core_Mapper::T_VARCHAR),
            'kodPocztowy' => array('kod_pocztowy', Core_Mapper::T_VARCHAR),
            'ulica' => array('ulica', Core_Mapper::T_VARCHAR),
            'nrLokalu' => array('nr_lokalu', Core_Mapper::T_VARCHAR),
            'osobaOdpowiedzialna' => array('osoba_odpowiedzialna', Core_Mapper::T_VARCHAR),
            'telefon' => array('telefon', Core_Mapper::T_VARCHAR),
            'email' => array('email', Core_Mapper::T_VARCHAR),
            'aktywny' => array('aktywny', Core_Mapper::T_INT),
            'dataUtworzenia' => array('data_utworzenia', Core_Mapper::T_DATETIME_CREATED),
            'dataAktualizacji' => array('data_aktualizacji', Core_Mapper::T_DATETIME_UPDATED)
        );
    }
}
