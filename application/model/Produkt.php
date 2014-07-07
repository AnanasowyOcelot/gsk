<?php

class Model_Produkt
{
    public $id = '';

    public $kategoria_id = 0;
    public $ean = '';
    public $ean_opakowania = '';
    public $sztuk_w_opakowaniu = '';
    public $pkwiu = '';
    public $cena_szt = 0;
    public $cena_op = 0;
    public $typ = 0;
    public $zdjecie_1 = '';
    public $zdjecie_2 = '';

    public $nazwa = array();
    public $nazwa_dluga = array();
    public $opis = array();
    public $aktywny = array();
    public $miejsce = array();

    public $atrybuty = array();
    public $numery_ean = array();

    public $pliki = array();
    public $errors = array();

    public $tagIds = array();
    private $tags = array();

    //========= parametry filtrowania =============
    public $rekordy = array();
    public $ilosc_rekordow = 0;

    public $filtr_id = '';
    public $filtr_kategoria_id = '';
    public $filtr_aktywny = '';
    public $filtr_nazwa = '';
    public $filtr_ean = '';
    public $filtr_jezyk_id = '';
    public $filtr_sortuj_po = '';
    public $filtr_sortuj_jak = '';
    public $filtr_strona = '';
    public $filtr_ilosc_wynikow = '';
    public $filtr_maks = '';

    public $table_prefix = "produkt";
    public $main_table = "produkty";
    public $opis_table = "produkty_opisy";

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function __construct($id = 0)
    {
        if ((int)$id > 0) {
            $this->pobierz($id);
        } else {
			$a_jezyki = Model_Jezyk::pobierzWszystkie();
			foreach ($a_jezyki as $idJezyka => $skrotJezyka) {
				$this->aktywny[$idJezyka] = 1;
			}
		}
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function zapisz()
    {
        $db = Core_DB::instancja();

        $plik_nazwa_z1 = '';
        $plik_nazwa_z2 = '';
        if (count($this->pliki) > 0) {
            $a_wymiary_z1[0]['szerokosc'] = '';
            $a_wymiary_z1[0]['wysokosc']  = '';

            $a_wymiary_z1[1]['szerokosc'] = '110';
            $a_wymiary_z1[1]['wysokosc']  = '90';

            $a_wymiary_z1[2]['szerokosc'] = '618';
            $a_wymiary_z1[2]['wysokosc']  = '506';

            $a_wymiary_z1[3]['szerokosc'] = '240';
            $a_wymiary_z1[3]['wysokosc']  = '340';

            $a_wymiary_z1[4]['szerokosc'] = '2400';
            $a_wymiary_z1[4]['wysokosc']  = '2400';

            $a_wymiary_z2[0]['szerokosc'] = '';
            $a_wymiary_z2[0]['wysokosc']  = '';

            $a_wymiary_z2[1]['szerokosc'] = '110';
            $a_wymiary_z2[1]['wysokosc']  = '90';

            $a_wymiary_z2[2]['szerokosc'] = '618';
            $a_wymiary_z2[2]['wysokosc']  = '506';

            $a_wymiary_z2[3]['szerokosc'] = '240';
            $a_wymiary_z2[3]['wysokosc']  = '340';

            $a_wymiary_z2[4]['szerokosc'] = '2400';
            $a_wymiary_z2[4]['wysokosc']  = '2400';

            foreach ($this->pliki as $nazwa => $dane) {

                if ($dane['tmp_name'] != "") {
                    $path = $dane['tmp_name'];

                    $plik_nazwa = time() . '_' . Core_Narzedzia::usunZnakiNiedozwolonePliki($dane['name']);
                    if ($nazwa == "z1") {
                        $plik_nazwa_z1 = $plik_nazwa;
                        $katalog_zdj   = Core_Config::get('images_path') . 'produkt/z1/';

                        foreach ($a_wymiary_z1 as $nr => $wymiar) {
                            $katalogWymiaru = $katalog_zdj . $nr;
                            if (!file_exists($katalogWymiaru)) {
                                mkdir($katalogWymiaru, 0777, true);
                            }
                            $sciezka = $katalogWymiaru . '/' . $plik_nazwa;
                            Core_Zdjecie::tworz_miniaturke($path, $sciezka, $wymiar['szerokosc'], $wymiar['wysokosc']);
                        }
                    }

                    if ($nazwa == "z2") {
                        $plik_nazwa_z2 = $plik_nazwa;
                        $katalog_zdj   = Core_Config::get('images_path') . 'produkt/z2/';

                        foreach ($a_wymiary_z2 as $nr => $wymiar) {
                            $katalogWymiaru = $katalog_zdj . $nr;
                            if (!file_exists($katalogWymiaru)) {
                                mkdir($katalogWymiaru, 0777, true);
                            }
                            $sciezka = $katalogWymiaru . '/' . $plik_nazwa;
                            Core_Zdjecie::tworz_miniaturke($path, $sciezka, $wymiar['szerokosc'], $wymiar['wysokosc']);
                        }
                    }
                }
            }
        }

        $rekord                                              = array();
        $rekord[$this->table_prefix . "_kategoria_id"]       = $this->kategoria_id;
        $rekord[$this->table_prefix . "_ean"]                = $this->ean;
        $rekord[$this->table_prefix . "_ean_opakowania"]     = $this->ean_opakowania;
        $rekord[$this->table_prefix . "_sztuk_w_opakowaniu"] = $this->sztuk_w_opakowaniu;
        $rekord[$this->table_prefix . "_pkwiu"]              = $this->pkwiu;
        $rekord[$this->table_prefix . "_typ"]                = $this->typ;
        $rekord[$this->table_prefix . "_cena_szt"]           = $this->cena_szt;
        $rekord[$this->table_prefix . "_cena_op"]            = $this->cena_op;

        if ($plik_nazwa_z1 != "") {
            $rekord[$this->table_prefix . "_zdjecie_1"] = $plik_nazwa_z1;
        }

        if ($plik_nazwa_z2 != "") {
            $rekord[$this->table_prefix . "_zdjecie_2"] = $plik_nazwa_z2;
        }

        if ((int)$this->id > 0) {
            $db->update($this->main_table, $rekord, $this->table_prefix . '_id = ' . (int)$this->id);
        } else {
            $db->insert($this->main_table, $rekord);
            $this->id = $db->last_insert_id($this->main_table);
        }


        if ((int)$this->id > 0) {
            $a_jezyki = Model_Jezyk::pobierzWszystkie();

            foreach ($a_jezyki as $idJezyka => $skrotJezyka) {
                $rekord                              = array();
                $rekord[$this->table_prefix . "_id"] = (int)$this->id;

                $rekord["jezyk_id"] = (int)$idJezyka;

                $rekord[$this->table_prefix . '_nazwa']       = $this->nazwa[$idJezyka];
                $rekord[$this->table_prefix . '_nazwa_dluga'] = $this->nazwa_dluga[$idJezyka];
                $rekord[$this->table_prefix . '_opis']        = $this->opis[$idJezyka];
                $rekord[$this->table_prefix . '_miejsce']     = $this->miejsce[$idJezyka];
                $rekord[$this->table_prefix . '_aktywny']     = $this->aktywny[$idJezyka];


                $queryCount = $db->query('SELECT * FROM ' . $this->opis_table . ' WHERE ' . $this->table_prefix . '_id = ' . (int)$this->id . ' AND jezyk_id = ' . (int)$idJezyka . '');
                if ($queryCount->RecordCount() > 0) {
                    $db->update($this->opis_table, $rekord, $this->table_prefix . '_id = ' . (int)$this->id . ' AND jezyk_id = ' . (int)$idJezyka . '');
                } else {
                    $db->insert($this->opis_table, $rekord);
                }
            }

            $this->zapiszAtrybuty($db);

            $this->zapiszNumeryEan($db);

            Model_Tag_Service::saveTagIdsForObject($this, $this->tagIds);
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    private function zapiszAtrybuty(Core_DB $db)
    {
        $atrMapper = new Model_Mapper_AtrybutWartosc();
        $sql_del   = 'DELETE FROM produkty_atrybuty_wartosci WHERE produkt_id = ' . (int)$this->id;
        $db->Execute($sql_del);
        foreach ($this->atrybuty as $atrybut) {
            $atrybut->produkt_id = $this->id;
            $atrMapper->save($atrybut);
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    private function zapiszNumeryEan(Core_DB $db)
    {
        $sql = 'DELETE FROM produkty_numery_ean
                WHERE produkt_id = ' . (int)$this->id;
        $db->query($sql);
        foreach ($this->numery_ean as $numerEan) {
            $sql = 'INSERT INTO produkty_numery_ean
                SET
                    produkt_id = ' . (int)$this->id . ',
                    ean = ' . mysql_real_escape_string($numerEan) . '
                ';
            $db->query($sql);
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function usun($id)
    {
        $db         = Core_DB::instancja();
        $komunikaty = array();

        if ($id > 0) {

            $sql_del = 'DELETE FROM ' . $this->main_table . ' WHERE ' . $this->table_prefix . '_id = ' . (int)$id;
            $db->Execute($sql_del);

            $sql_del = 'DELETE FROM ' . $this->opis_table . '  WHERE ' . $this->table_prefix . '_id = ' . (int)$id;
            $db->Execute($sql_del);

            $sql_del = 'DELETE FROM produkty_atrybuty_wartosci WHERE produkt_id = ' . (int)$id;
            $db->Execute($sql_del);

            $komunikaty[] = array('ok', 'Rekord o id = ' . (int)$id . ' został usunięty.');
        }

        return $komunikaty;
    }


    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function pobierz($id)
    {
        $db = Core_DB::instancja();

        if ((int)$id > 0) {
            $sql    = 'SELECT * FROM ' . $this->main_table . ' WHERE ' . $this->table_prefix . '_id = ' . (int)$id . ' LIMIT 1';
            $result = $db->get_row($sql);

            if (count($result) > 0) {
                $this->id                 = (int)$result[$this->table_prefix . '_id'];
                $this->kategoria_id       = $result[$this->table_prefix . "_kategoria_id"];
                $this->ean                = $result[$this->table_prefix . "_ean"];
                $this->ean_opakowania     = $result[$this->table_prefix . "_ean_opakowania"];
                $this->sztuk_w_opakowaniu = $result[$this->table_prefix . "_sztuk_w_opakowaniu"];
                $this->pkwiu              = $result[$this->table_prefix . "_pkwiu"];
                $this->typ                = $result[$this->table_prefix . "_typ"];
                $this->cena_szt           = $result[$this->table_prefix . "_cena_szt"];
                $this->cena_op            = $result[$this->table_prefix . "_cena_op"];
                $this->zdjecie_1          = $result[$this->table_prefix . "_zdjecie_1"];
                $this->zdjecie_2          = $result[$this->table_prefix . "_zdjecie_2"];

                $sql_opis    = 'SELECT * FROM ' . $this->opis_table . ' WHERE ' . $this->table_prefix . '_id = ' . (int)$this->id;
                $result_opis = $db->query($sql_opis);
                foreach ($result_opis as $opis) {
                    $this->nazwa[$opis['jezyk_id']]       = stripslashes($opis[$this->table_prefix . '_nazwa']);
                    $this->nazwa_dluga[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix . '_nazwa_dluga']);
                    $this->opis[$opis['jezyk_id']]        = stripslashes($opis[$this->table_prefix . '_opis']);
                    $this->miejsce[$opis['jezyk_id']]     = $opis[$this->table_prefix . '_miejsce'];
                    $this->aktywny[$opis['jezyk_id']]     = $opis[$this->table_prefix . '_aktywny'];
                }

                $atrybutyWartosciMapper = new Model_Mapper_AtrybutWartosc();
                $atrybutyWartosciMapper->filterBy('produkt_id', $this->id);
                $this->atrybuty = $atrybutyWartosciMapper->find();

                $this->numery_ean  = array();
                $sql_numery_ean    = 'SELECT * FROM produkty_numery_ean
                    WHERE produkt_id = ' . (int)$this->id;
                $result_numery_ean = $db->query($sql_numery_ean);
                foreach ($result_numery_ean as $eanRow) {
                    $this->numery_ean[] = $eanRow['ean'];
                }
            } else {
                $this->errors[] = 'Nie znaleziono rekordu o ' . $id . '.';
            }

            $this->tagIds = Model_Tag_Service::getTagIdsForObject($this);
            $this->tags = Model_Tag_Service::getTags($this->tagIds);
        }

        if (count($this->errors) > 0) {
            return false;
        } else {
            return true;
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function setFiles($pliki_in)
    {
        $this->pliki = $pliki_in;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function fromArray(array $r)
    {
        $this->id = (int)$r['id'];

        $pola = array(
            'kategoria_id',
            'ean',
            'ean_opakowania',
            'sztuk_w_opakowaniu',
            'pkwiu',
            'cena_szt',
            'cena_op',
            'typ'
        );

        foreach ($pola as $pole) {
            if (isset($r[$pole])) {
                $this->$pole = $r[$pole];
            }
        }

        $polaMultilang = array(
            'nazwa',
            'nazwa_dluga',
            'opis',
            'aktywny',
            'miejsce'
        );

        foreach ($polaMultilang as $pole) {
            if (isset($r[$pole]) && is_array($r[$pole])) {
                foreach ($r[$pole] as $jezykId => $wartosc) {
                    $this->{$pole}[$jezykId] = stripslashes($wartosc);
                }
            }
        }

        if (isset($r['atrybuty'])) {
            if (isset($r['atrybuty']['id']) && is_array($r['atrybuty']['id'])) {
                $this->atrybuty = array();
                foreach ($r['atrybuty']['id'] as $key => $atrId) {
                    $atr          = new Model_DataObject_AtrybutWartosc();
                    $atr->id      = (int)$atrId;
                    $atr->nazwa   = $r['atrybuty']['nazwa'][$key];
                    $atr->wartosc = $r['atrybuty']['wartosc'][$key];
                    if (trim($atr->nazwa . $atr->wartosc) != '') {
                        $this->atrybuty[] = $atr;
                    }
                }
            }
        }

        $this->numery_ean = array();
        if (isset($r['numery_ean']) && is_array($r['numery_ean'])) {
            foreach ($r['numery_ean'] as $ean) {
                if (trim($ean) != '') {
                    $this->numery_ean[] = $ean;
                }
            }
        }

        if (isset($r['tag'])) {
            $this->tagIds = array();
            foreach ($r['tag'] as $tagId => $isConnected) {
                if ((int)$isConnected > 0) {
                    $this->tagIds[] = $tagId;
                }
            }
        }
		$this->tags = Model_Tag_Service::getTags($this->tagIds);
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function pobierzPrzezUrl($jezyk_id, $url)
    {
        $db = Core_DB::instancja();

        $sql    = 'SELECT ' . $this->table_prefix . '_id AS id FROM ' . $this->opis_table . ' WHERE ' . $this->table_prefix . '_url = "' . mysql_real_escape_string(trim($url)) . '" AND jezyk_id = ' . (int)$jezyk_id;
        $rekord = $db->get_row($sql);

        if (count($rekord) == 0) {
            $this->errors[] = 'Nie ma takiego rekordu';
        } else {
            if (count($rekord) == 1) {
                $this->pobierz($rekord['id']);
            }
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function pobierzPrzezEan($ean)
    {
        $db = Core_DB::instancja();

        $sql    = 'SELECT ' . $this->table_prefix . '_id AS id FROM ' . $this->main_table . ' WHERE ' . $this->table_prefix . '_ean = "' . mysql_real_escape_string(trim($ean)) . '"';
        $rekord = $db->get_row($sql);

        if (count($rekord) == 0) {
            $this->errors[] = 'Nie ma takiego rekordu';
        } else {
            if (count($rekord) == 1) {
                $this->pobierz($rekord['id']);
            }
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function filtrujRekordy()
    {
        $db = Core_DB::instancja();

        $sql = 'SELECT
				t.' . $this->table_prefix . '_id AS id
			FROM
				' . $this->main_table . ' AS t,
				' . $this->opis_table . ' AS too
			WHERE
				t.' . $this->table_prefix . '_id = too.' . $this->table_prefix . '_id ';


        if ($this->filtr_id != '') {
            $sql .= ' AND too.' . $this->table_prefix . '_id=' . (int)$this->filtr_id;
        }
        if ($this->filtr_jezyk_id != '') {
            $sql .= ' AND too.jezyk_id=' . (int)$this->filtr_jezyk_id;
        }
        if ($this->filtr_nazwa != '') {
            $sql .= ' AND too.' . $this->table_prefix . '_nazwa LIKE "%' . mysql_real_escape_string($this->filtr_nazwa) . '%" ';
        }
        if ($this->filtr_ean != '') {
            $sql .= ' AND too.' . $this->table_prefix . '_nazwa = "' . mysql_real_escape_string($this->filtr_ean) . '" ';
        }
        if ($this->filtr_kategoria_id !== '') {
            $sql .= ' AND t.' . $this->table_prefix . '_kategoria_id = ' . (int)$this->filtr_kategoria_id . ' ';
        }

        if ($this->filtr_aktywny == '1') {
            $sql .= ' AND too.' . $this->table_prefix . '_aktywny = 1 ';
        } else {
            if ($this->filtr_aktywny == '0') {
                $sql .= ' AND too.' . $this->table_prefix . '_aktywny = 0 ';
            }
        }


        $sql_count = $sql;

        if ($this->filtr_sortuj_po != '') {
            switch ($this->filtr_sortuj_po) {
                case 'id':
                    $kolumna = ' t.' . $this->table_prefix . '_id ';
                    break;
                case 'nazwa':
                    $kolumna = ' too.' . $this->table_prefix . '_nazwa ';
                    break;
                case 'miejsce':
                    $kolumna = ' too.' . $this->table_prefix . '_miejsce ';
                    break;
                case 'aktywny':
                    $kolumna = ' too.' . $this->table_prefix . '_aktywny ';
                    break;
                default:
                    $kolumna = ' t.' . $this->table_prefix . '_id ';
                    break;
            }

            $sql .= ' ORDER BY ' . $kolumna;
            if ($this->filtr_sortuj_jak != '') {
                $sql .= ' ' . $this->filtr_sortuj_jak;
            }
        } else {
            $sql .= ' ORDER BY t.' . $this->table_prefix . '_id DESC';
        }

        if ($this->filtr_maks != '') {
            $sql .= ' LIMIT ' . (int)$this->filtr_maks . '';
        } else {
            if ($this->filtr_ilosc_wynikow != '' && $this->filtr_strona != '') {
                $sql .= ' LIMIT ' . ($this->filtr_ilosc_wynikow * $this->filtr_strona - $this->filtr_ilosc_wynikow) . ', ' . (int)$this->filtr_ilosc_wynikow . '';
            }
        }

        $result = $db->query($sql);
        foreach ($result as $row) {
            $this->rekordy[] = $row['id'];
        }

        $result_count         = $db->query($sql_count);
        $this->ilosc_rekordow = $result_count->RecordCount();
    }

    /**
     * @param string $ean
     * @return Model_Produkt
     */
    public static function findOneByEan($ean)
    {
        $db        = Core_DB::instancja();
        $sql       = 'SELECT produkt_id
            FROM produkty_numery_ean
            WHERE ean = "' . mysql_real_escape_string($ean) . '"
            LIMIT 1';
        $result    = $db->query($sql);
        $row       = $result->FetchRow();
        $produktId = $row['produkt_id'];
        if((int)$produktId == 0) {
            // TODO: wyszukac bezposrednio w tabeli produktow
        }
        return new Model_Produkt($produktId);
    }

	public function getTags()
	{
		return $this->tags;
	}

	public function addTag(Model_Tag_TagEntity $tag)
	{
		$this->tags[] = $tag;
		$this->tagIds[] = $tag->id;
	}
}
