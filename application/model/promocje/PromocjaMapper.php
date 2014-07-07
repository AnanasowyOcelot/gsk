<?php

class Model_Promocje_PromocjaMapper extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'promocje';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_Promocje_PromocjaEntity';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'nazwa' => array('nazwa', Core_Mapper::T_VARCHAR),
            'kod_icoguar' => array('kod_icoguar', Core_Mapper::T_VARCHAR),
            'cena_zakupu_nagrody' => array('cena_zakupu_nagrody', Core_Mapper::T_FLOAT),
            'aktywna' => array('aktywna', Core_Mapper::T_INT),
            'data' => array('data', Core_Mapper::T_DATETIME),
            'dataUtworzenia' => array('data_utworzenia', Core_Mapper::T_DATETIME_CREATED),
            'dataAktualizacji' => array('data_aktualizacji', Core_Mapper::T_DATETIME_UPDATED)
        );
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    protected function buildObject(array $tableRow)
    {
        $object = parent::buildObject($tableRow);

        $object->clearEtapy();
        foreach ($this->findEtapy($object->id) as $etap) {
            $object->addEtap($etap);
        }

        return $object;
    }

    private function findEtapy($rekordId)
    {
        $etapMapper = new Model_Promocje_EtapMapper();
        $sql = 'SELECT etap_id
            FROM promocje_etapy_do_promocji
            WHERE promocja_id = "' . (int)$rekordId . '"
            ';
        $db = Core_DB::instancja();
        $res = $db->query($sql);
        $etapy = array();
        foreach ($res as $row) {
            $etapy[] = $etapMapper->findOneById($row['etap_id']);
        }
        return $etapy;
    }

    public function save(Model_Promocje_PromocjaEntity $o)
    {
        parent::save($o);

        if ($o->id > 0) {
            $this->deleteEtapy($o);

            $db = Core_DB::instancja();
            foreach ($o->getEtapy() as $etap) {
                $sqlInsert = 'INSERT INTO promocje_etapy_do_promocji
                SET
                    etap_id = "' . (int)$etap->id . '",
                    promocja_id = "' . (int)$o->id . '"
                    ';
                $db->query($sqlInsert);
            }
        }
    }

    private function deleteEtapy(Model_Promocje_PromocjaEntity $o)
    {
        $db = Core_DB::instancja();
        $sqlDelete = 'DELETE FROM promocje_etapy_do_promocji
                WHERE promocja_id = "' . (int)$o->id . '"
                ';
        $db->query($sqlDelete);
    }

    public function delete(Model_Promocje_PromocjaEntity $o)
    {
        $this->deleteEtapy($o);
        parent::delete($o);
    }
}
