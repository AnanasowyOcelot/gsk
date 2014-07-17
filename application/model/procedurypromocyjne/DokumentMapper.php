<?php

class Model_ProceduryPromocyjne_DokumentMapper extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'procedury_promocyjne_files';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_ProceduryPromocyjne_DokumentEntity';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'nazwa' => array('nazwa', Core_Mapper::T_VARCHAR),
            'dataUtworzenia' => array('data_utworzenia', Core_Mapper::T_DATETIME_CREATED),
            'dataAktualizacji' => array('data_aktualizacji', Core_Mapper::T_DATETIME_UPDATED),

        );
    }

    public function findNewestFileId()
    {
        $this->filterOrderBy('dataAktualizacji', 'DESC');
        $this->filterLimit(1);
        $ids = $this->findIds();
        return $ids[0];
    }
}
