<?php

class Model_ProceduryPromocyjne_PromocjaMapper extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'procedury_promocyjne_promocje';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_ProceduryPromocyjne_PromocjaEntity';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'klient_id' => array('klient_id', Core_Mapper::T_INT),
            'subbrand' => array('subbrand', Core_Mapper::T_VARCHAR),
            'produkt' => array('produkt', Core_Mapper::T_VARCHAR),
            'termin' => array('termin', Core_Mapper::T_VARCHAR),
            'termin_rabatu_OD' => array('termin_rabatu_OD', Core_Mapper::T_VARCHAR),
            'gazetka' => array('gazetka', Core_Mapper::T_INT),
            'cena_rekomendowana' => array('cena_rekomendowana', Core_Mapper::T_VARCHAR),
            'forma_promocji' => array('forma_promocji', Core_Mapper::T_VARCHAR),
            'dodatkowa_lokalizacja' => array('dodatkowa_lokalizacja', Core_Mapper::T_VARCHAR),
            'ilosc_dodatkowych_lokalizacji' => array('ilosc_dodatkowych_lokalizacji', Core_Mapper::T_INT),
            'uwagi' => array('Uwagi', Core_Mapper::T_VARCHAR),



        );
    }
}
