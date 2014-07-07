<?php

class Model_AsortymentSieci_ProduktMapper extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'asortyment_sieci_produkty';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_AsortymentSieci_ProduktEntity';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id'               => array('id', Core_Mapper::T_INT),
            'produktId'        => array('produkt_id', Core_Mapper::T_INT),
            'produktNazwa'     => array('produkt_nazwa', Core_Mapper::T_VARCHAR),
            'kategoria'        => array('kategoria', Core_Mapper::T_VARCHAR),
            'segment'          => array('segment', Core_Mapper::T_VARCHAR),
            'ean'              => array('ean', Core_Mapper::T_VARCHAR),
            'nazwaSku'         => array('nazwa_sku', Core_Mapper::T_VARCHAR),
            'dataUtworzenia'   => array('data_utworzenia', Core_Mapper::T_DATETIME_CREATED),
            'dataAktualizacji' => array('data_aktualizacji', Core_Mapper::T_DATETIME_UPDATED)
        );
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function save(Model_AsortymentSieci_ProduktEntity $o)
    {
        parent::save($o);

        $db           = Core_DB::instancja();
        $klientMapper = new Model_AsortymentSieci_KlientMapper();
        foreach ($o->values as $value) {
            $klient  = $klientMapper->findOneByName($value['klient']);
            $wartosc = (int)$value['wartosc'];
            if ($wartosc > 0) {
                $db->query('INSERT INTO asortyment_sieci_produkty_klienci
                SET
                    produkt_id = "' . (int)$o->id . '",
                    klient_id = "' . (int)$klient->id . '",
                    wartosc = "' . (int)$wartosc . '"
                ');
            }
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * @param array $tableRow
     * @return Model_AsortymentSieci_ProduktEntity
     */
    protected function buildObject(array $tableRow)
    {
        $o = parent::buildObject($tableRow);

        $db = Core_DB::instancja();
        $o->values = array();
        if ($o->id > 0) {
            $res = $db->query('SELECT *
                FROM
                    asortyment_sieci_produkty_klienci aspk
                LEFT JOIN
                    asortyment_sieci_klienci ask
                ON
                    aspk.klient_id = ask.id
                WHERE
                    produkt_id = "' . (int)$o->id . '"
                ');
            $row = $res->FetchRow();
            $o->values[] = array(
                'klient' => $row['nazwa'],
                'wartosc' => $row['wartosc']
            );
        }

        return $o;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function deleteAll()
    {
        $db = Core_DB::instancja();
        $db->query('DELETE FROM `' . self::escape($this->getTable()) . '`
            WHERE 1 = 1');
    }
}
