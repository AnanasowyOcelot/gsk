<?php

abstract class Core_Mapper
{
    const T_INT              = 'int';
    const T_FLOAT            = 'float';
    const T_VARCHAR          = 'varchar';
    const T_DATE             = 'date';
    const T_DATETIME         = 'datetime';
    const T_DATETIME_CREATED = 'datetime_created';
    const T_DATETIME_UPDATED = 'datetime_updated';
    const T_TEXT             = 'text';
    const T_TEXT_COMPRESSED  = 'text_compressed';

    private $filterFields = array(); // elementy tablicy: 'nazwaPola' => 'wartosc'
    private $filterMethods = array(); // elementy tablicy: 'nazwaPola' => 'metoda przyrownania'
    private $filterOrderBy = '';
    private $filterOrderHow = '';
    private $limit = 0;
    private $page = 0;
    private $perPage = 0;
    private $pageCount = 0;
    private $recordCount = 0;


    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    abstract function getTable();

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * zwraca nazwe klucza glownego
     * funkcja może zwracać nazwę pola lub array z kilkoma polami
     * @return string, array
     */
    function getPrimaryKey()
    {
        return 'id';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * zwraca nazwy kluczy glownych
     * @return array
     */
    private function getPrimaryKeys()
    {
        $primaryKeys = $this->getPrimaryKey();
        if (!is_array($primaryKeys)) {
            if ($primaryKeys === null) {
                $primaryKeys = array();
            } else {
                $primaryKeys = array($primaryKeys);
            }
        }
        return $primaryKeys;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * zwraca nazwy kluczy glownych w bazie
     * @return array
     */
    private function getPrimaryKeysNazwyWBazie()
    {
        $pola              = $this->getDescription();
        $primaryKeys       = $this->getPrimaryKeys();
        $primaryKeysWBazie = array();
        foreach ($primaryKeys as $primaryKey) {
            $primaryKeysWBazie[] = $pola[$primaryKey][0];
        }
        return $primaryKeysWBazie;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    protected static function escape($val)
    {
        $returnVal = null;
        if (is_array($val)) {
            $returnVal = array();
            foreach ($val as $value) {
                $returnVal[] = mysql_real_escape_string($value);
            }
        } else {
            $returnVal = mysql_real_escape_string($val);
        }
        return $returnVal;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * 'pole_w_obiekcie' => array('pole_w_bazie', 'typ')
     */
    abstract function getDescription();

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    abstract function getDataObjectClass(); // TODO: zmienic na getEntityClassName

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getNew()
    {
        $class  = $this->getDataObjectClass();
        $object = new $class();
        return $object;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * @param array $r
     * @param null $object
     * @return object|null
     */
    public function fromArray(array $r, $object = null)
    {
        if ($object == null) {
            $class  = $this->getDataObjectClass();
            $object = new $class();
        }

        $pola = $this->getDescription();

        foreach ($pola as $nazwaWObiekcie => $pole) {
            if(isset($r[$nazwaWObiekcie])) {
                $object->$nazwaWObiekcie = $r[$nazwaWObiekcie];
            }
        }

        return $object;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    protected function buildObject(array $tableRow)
    {
        $class  = $this->getDataObjectClass();
        $object = new $class();

        $pola = $this->getDescription();

        foreach ($pola as $nazwaWObiekcie => $pole) {
            $nazwaWBazie = $pole[0];
            $typ         = $pole[1];
            if (isset($tableRow[$nazwaWBazie])) {
                switch ($typ) {
                    case self::T_TEXT_COMPRESSED:
                        $object->$nazwaWObiekcie = gzuncompress($tableRow[$nazwaWBazie]);
                        break;
                    default:
                        $object->$nazwaWObiekcie = $tableRow[$nazwaWBazie];
                        break;
                }
            }
        }

        return $object;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * id moze byc pojedyncza wartoscia lub arrayem
     * @param mixed $id
     * @return null|object
     */
    public function findOneById($id)
    {
        $db = Core_DB::instancja();

        $pola = $this->getDescription();

        $polaPrimaryKey = $this->getPrimaryKeys();
        $a_selectWhere  = array();
        foreach ($polaPrimaryKey as $poleNazwa) {
            $poleNazwaWBazie = $pola[$poleNazwa][0];
            if (is_array($id)) {
                $poleWartosc = $id[$poleNazwa];
            } else {
                $poleWartosc = $id;
            }
            $a_selectWhere[] = '`' . self::escape($poleNazwaWBazie) . '` = "' . self::escape($poleWartosc) . '"';
        }

        $sqlSelect = 'SELECT *
        	FROM `' . self::escape($this->getTable()) . '`
        	WHERE ' . implode(' AND ', $a_selectWhere) . '
        	LIMIT 1';
        $result    = $db->get_row($sqlSelect);
        if (count($result) > 0) {
            $object = $this->buildObject($result);
            return $object;
        } else {
            return null;
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * @param mixed $szukaneId
     * @return boolean
     * @throws Exception
     */
    public function exists($szukaneId)
    {
        $db = Core_DB::instancja();

        $polaId = $this->getPrimaryKey();
        if (!is_array($polaId)) {
            $polaId = array($polaId);
        }

        if (!is_array($szukaneId)) {
            $szukaneId = array($polaId[0] => $szukaneId);
        }

        if (count($polaId) != count($szukaneId)) {
            throw new Exception('Nieprawidłowa liczba argumentów (funkcja "exists").');
        }

        $pola = $this->getDescription();

        $a_pobieranePola = array();
        $a_where         = array();
        foreach ($polaId as $poleId) {
            $poleIdNazwaWBazie  = $pola[$poleId][0];
            $poleSzukanaWartosc = $szukaneId[$poleId];
            $a_pobieranePola[]  = '`' . self::escape($poleIdNazwaWBazie) . '`';
            $a_where[]          = '`' . self::escape($poleIdNazwaWBazie) . '` = "' . (int)$poleSzukanaWartosc . '"';
        }

        $sqlSelect = 'SELECT ' . implode(', ', $a_pobieranePola) . '
        	FROM `' . self::escape($this->getTable()) . '`
        	WHERE ' . implode(' AND ', $a_where) . '
        	LIMIT 1';
        $result    = $db->get_row($sqlSelect);

        if (count($result) > 0) {
            return true;
        }
        return false;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function find()
    {
        return $this->helperFind(true);
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function findOne()
    {
        $this->filterLimit(1);
        $objects = $this->helperFind(true);
        if (count($objects) > 0) {
            return $objects[0];
        }
        return null;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function findIds()
    {
        return $this->helperFind(false);
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    private function helperFind($createObjects = false)
    {
        $db = Core_DB::instancja();

        $a_pola = $this->getDescription();

        $primaryKeysNazwyWBazie = $this->getPrimaryKeysNazwyWBazie();

        if ($createObjects == false && count($primaryKeysNazwyWBazie) == 0) {
            throw new Exception('Nie można pobrać listy id bez zdefiniowanego klucza głównego.');
        }

        if ($createObjects) {
            $sqlSelect = 'SELECT *';
        } else {
            $sqlSelect = 'SELECT `' . implode('`, `', self::escape($primaryKeysNazwyWBazie)) . '`';
        }

        $sqlCount = 'SELECT COUNT(*) AS record_count';
        $sqlTmp   = ' FROM `' . self::escape($this->getTable()) . '` WHERE 1 = 1';

        if (is_array($this->filterFields)) {
            foreach ($this->filterFields as $nazwaPola => $wartosc) {
                $metodaPorownania = $this->getFilterMethod($nazwaPola);

                $pole = $a_pola[$nazwaPola];
                if (is_array($pole)) {
                    $nazwaWBazie = $pole[0];
                    $typ         = $pole[1];

                    switch ($typ) {
                        case self::T_INT:
                            $sqlTmp .= ' AND `' . self::escape($nazwaWBazie) . '` ' . $metodaPorownania . ' "' . (int)$wartosc . '"';
                            break;
                        case self::T_FLOAT:
                            $sqlTmp .= ' AND `' . self::escape($nazwaWBazie) . '` ' . $metodaPorownania . ' "' . $this->toFloat($wartosc) . '"';
                            break;
                        case self::T_VARCHAR:
                        case self::T_TEXT:
                            $sqlTmp .= ' AND `' . self::escape($nazwaWBazie) . '` = "' . self::escape($wartosc) . '"';
                            break;
                        case self::T_DATE:
                            if ($wartosc === null) {
                                $sqlTmp .= ' AND `' . self::escape($nazwaWBazie) . '` ' . $metodaPorownania . ' "0000-00-00"';
                            } else {
                                $sqlTmp .= ' AND `' . self::escape($nazwaWBazie) . '` ' . $metodaPorownania . ' "' . self::escape($wartosc) . '"';
                            }
                            break;
                        case self::T_DATETIME:
                        case self::T_DATETIME_CREATED:
                        case self::T_DATETIME_UPDATED:
                            if ($wartosc === null) {
                                $sqlTmp .= ' AND `' . self::escape($nazwaWBazie) . '` ' . $metodaPorownania . ' "0000-00-00 00:00:00"';
                            } else {
                                $sqlTmp .= ' AND `' . self::escape($nazwaWBazie) . '` ' . $metodaPorownania . ' "' . self::escape($wartosc) . '"';
                            }
                            break;
                        case self::T_TEXT_COMPRESSED:
                            break;
                        default:
                            //throw Exception
                            break;
                    }
                }
            }
        }

        $sqlCount .= $sqlTmp;
        $sqlSelect .= $sqlTmp;

        if ($this->filterOrderBy != '') {
            $pole = $a_pola[$this->filterOrderBy];
            if (is_array($pole)) {
                $nazwaWBazie = $pole[0];

                if ($this->filterOrderHow == 'DESC') {
                    $sqlSelect .= ' ORDER BY `' . self::escape($nazwaWBazie) . '` DESC';
                } else {
                    $sqlSelect .= ' ORDER BY `' . self::escape($nazwaWBazie) . '` ASC';
                }
            }
        }

        if ((int)$this->limit > 0) {
            $sqlSelect .= ' LIMIT ' . (int)$this->limit . '';
        } elseif ((int)$this->perPage > 0) {
            $sqlSelect .= ' LIMIT ' . (int)$this->perPage . '';
            $sqlSelect .= ' OFFSET ' . (int)($this->perPage * $this->page) . '';
        }

        $this->pageCount = 1;
        if ((int)$this->perPage > 0) {
            $this->recordCount = $db->get_one($sqlCount);
            $this->pageCount   = ceil($this->recordCount / (int)$this->perPage);
        }

        $result = $db->query($sqlSelect);

        $rekordy = array();
        if ($createObjects) {
            foreach ($result as $row) {
                $rekordy[] = $this->buildObject($row);
            }
        } else {
            if (count($primaryKeysNazwyWBazie) > 1) {
                foreach ($result as $row) {
                    $rekordy[] = $row;
                }
            } else {
                foreach ($result as $row) {
                    $rekordy[] = $row[$primaryKeysNazwyWBazie[0]];
                }
            }
        }

        return $rekordy;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    private function getFilterMethod($nazwaPola)
    {
        $allowedMethods = array('=', '!=', '<>', '<', '<=', '>', '>=', 'LIKE');
        if (isset($this->filterMethods[$nazwaPola]) && $this->filterMethods[$nazwaPola] != '') {
            $filterMethod = $this->filterMethods[$nazwaPola];
        } else {
            $filterMethod = '=';
        }
        if (!in_array($filterMethod, $allowedMethods)) {
            throw new Exception('Niedozwolona metoda wyszukiwania: ' . $filterMethod);
        }
        return $filterMethod;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function filterBy($nazwaPola, $wartosc, $metodaPorownania = '')
    {
        $this->filterFields[$nazwaPola]  = $wartosc;
        $this->filterMethods[$nazwaPola] = $metodaPorownania;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function filterOrderBy($nazwaPola, $jak = 'ASC')
    {
        $this->filterOrderBy = $nazwaPola;

        $jak = strtoupper($jak);
        if ($jak == 'DESC') {
            $this->filterOrderHow = 'DESC';
        } else {
            $this->filterOrderHow = 'ASC';
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function filterLimit($limit)
    {
        $this->limit = max(0, (int)$limit);
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function filterPage($page)
    {
        $this->page = max(0, (int)$page - 1);
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function filterPerPage($perPage)
    {
        $this->perPage = max(0, (int)$perPage);
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getRecordCount()
    {
        return $this->recordCount;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getPageCount()
    {
        return $this->pageCount;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function save($o)
    {
        // TODO: wdrozyc wielokrotne primary key

        $db = Core_DB::instancja();

        $pola        = $this->getDescription();
        $polaIdNazwy = $this->getPrimaryKeys();

        $polaId = array();
        if (is_array($polaIdNazwy) && count($polaIdNazwy) > 0) {
            foreach ($polaIdNazwy as $poleIdNazwa) {
                $polaId[$poleIdNazwa] = $o->$poleIdNazwa;
            }
        }

        $recordExists = $this->exists($polaId);

        $rekord = array();

        foreach ($pola as $nazwaWObiekcie => $pole) {
            $nazwaWBazie = $pole[0];
            $typ         = $pole[1];

            switch ($typ) {
                case self::T_INT:
                    if ($o->$nazwaWObiekcie !== null) {
                        $rekord[$nazwaWBazie] = (int)$o->$nazwaWObiekcie;
                    }
                    break;
                case self::T_FLOAT:
                    if ($o->$nazwaWObiekcie !== null) {
                        $rekord[$nazwaWBazie] = $this->toFloat($o->$nazwaWObiekcie);
                    }
                    break;
                case self::T_VARCHAR:
                case self::T_TEXT:
                    $rekord[$nazwaWBazie] = self::escape($o->$nazwaWObiekcie);
                    break;
                case self::T_TEXT_COMPRESSED:
                    $rekord[$nazwaWBazie] = self::escape(gzcompress($o->$nazwaWObiekcie, 9));
                    break;
                case self::T_DATE:
                    if ($o->$nazwaWObiekcie != null) {
                        $rekord[$nazwaWBazie] = self::escape($o->$nazwaWObiekcie);
                    } else {
                        $rekord[$nazwaWBazie] = '0000-00-00';
                    }
                    break;
                case self::T_DATETIME:
                    if ($o->$nazwaWObiekcie != null) {
                        $rekord[$nazwaWBazie] = self::escape($o->$nazwaWObiekcie);
                    } else {
                        $rekord[$nazwaWBazie] = '0000-00-00 00:00:00';
                    }
                    break;
                case self::T_DATETIME_CREATED:
                    if (!$recordExists) {
                        $rekord[$nazwaWBazie] = date('Y-m-d H:i:s');
                    }
                    break;
                case self::T_DATETIME_UPDATED:
                    $rekord[$nazwaWBazie] = date('Y-m-d H:i:s');
                    break;
                case 'zdjecia':

                    break;
                case 'pliki':

                    break;
                default:
                    //throw Exception
                    break;
            }
        }

        if ($recordExists) {
            $warunkiSql = array();

            foreach ($polaId as $poleIdNazwa => $poleIdWartosc) {
                $poleIdNazwaWBazie = $pola[$poleIdNazwa][0];
                $poleIdTyp         = $pola[$poleIdNazwa][1];
                if ($poleIdTyp == self::T_VARCHAR) {
                    $warunkiSql[] = '`' . self::escape($poleIdNazwaWBazie) . '` = "' . self::escape($poleIdWartosc) . '"';
                } else {
                    $warunkiSql[] = '`' . self::escape($poleIdNazwaWBazie) . '` = "' . (int)$poleIdWartosc . '"';
                }
            }

            $db->update(
                $this->getTable(),
                $rekord,
                implode(' AND ', $warunkiSql)
            );
        } else {
            $db->insert(
                $this->getTable(),
                $rekord
            );
            if (count($polaIdNazwy) == 1) {
                $poleIdNazwa     = $polaIdNazwy[0];
                $o->$poleIdNazwa = $db->last_insert_id($this->getTable());
            }

        }
        // TODO: dorobic inserty dla rekordow bez id
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function delete($o)
    {
        $db = Core_DB::instancja();

        $pola = $this->getDescription();

        $poleIdNazwa = $this->getPrimaryKey();
        $poleId      = $pola[$poleIdNazwa];
        if (is_array($poleId)) {
            $poleIdNazwaWBazie = $poleId[0];
            $poleIdWartosc     = $o->$poleIdNazwa;
            $poleIdTyp         = $poleId[1];

            if ($poleIdTyp == self::T_VARCHAR) {
                $db->query('DELETE FROM `' . self::escape($this->getTable()) . '` WHERE `' . self::escape($poleIdNazwaWBazie) . '` = "' . self::escape($poleIdWartosc) . '"');
            } else {
                $db->query('DELETE FROM `' . self::escape($this->getTable()) . '` WHERE `' . self::escape($poleIdNazwaWBazie) . '` = "' . (int)$poleIdWartosc . '"');
            }
        } else {
            // brak pola ID
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function zapiszHistorie($o)
    {

    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    private function toFloat($val)
    {
        if (strpos('' . $val, ',') !== false) {
            return (float)str_replace(',', '.', $val);
        }
        return (float)$val;
    }
}
