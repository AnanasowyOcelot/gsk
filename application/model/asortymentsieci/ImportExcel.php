<?php

class Model_AsortymentSieci_ImportExcel
{
    const NUMER_WIERSZA_Z_NAZWAMI_KOLUMN   = 8;
    const NUMER_WIERSZA_Z_NAZWAMI_KLIENTOW = 2;

    private $filePath = '';
    private $PHPExcel;
    private $numberOfProductsImported = 0;
    private $numberOfProductsCreated = 0;
    private $numberOfProductsUpdated = 0;
    private $jezykId = 1;

    /**
     * @var array
     */
    private $columnNumbers;

    /**
     * @var array
     */
    private $clientsColumnNumbers;

    public function __construct($filePath = '')
    {
        $this->filePath             = $filePath;
        $this->PHPExcel             = PHPExcel_IOFactory::load($this->filePath);
        $this->columnNumbers        = $this->getColumnNumbers(self::NUMER_WIERSZA_Z_NAZWAMI_KOLUMN);
        $this->clientsColumnNumbers = $this->getColumnNumbers(self::NUMER_WIERSZA_Z_NAZWAMI_KLIENTOW);
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getPHPExcel()
    {
        return $this->PHPExcel;
    }

    public function getRow($rowNum)
    {
        $result      = array();
        $rowIterator = $this->PHPExcel->getActiveSheet()->getRowIterator($rowNum);
        foreach ($rowIterator as $rowObject) {
            $result = $this->getRowFromCellIterator($rowObject->getCellIterator());
            break;
        }
        return $result;
    }

    private function getRowFromCellIterator(PHPExcel_Worksheet_CellIterator $cellIterator)
    {
        $cellIterator->setIterateOnlyExistingCells(false);
        $row = array();
        foreach ($cellIterator as $cellObject) {
            $row[] = $cellObject->getValue();
        }
        return $row;
    }

    public function getColumnNumbers($rowNum)
    {
        $row    = $this->getRow($rowNum);
        $result = array();
        foreach ($row as $number => $name) {
            if (trim($name) != '') {
                $result[$name] = $number;
            }
        }
        return $result;
    }

    /**
     * @param Model_Produkt $produkt
     * @param $row
     * @return Model_Produkt
     */
    public function fillProductFromRow(Model_Produkt $produkt, $row)
    {
        $produkt->ean                   = $row[$this->columnNumbers['KOD KRESKOWY SZTUKI']];
        $produkt->nazwa[$this->jezykId] = $row[$this->columnNumbers['NAZWA PRODUKTU']];
        $produkt->opis[$this->jezykId]  = $row[$this->columnNumbers['OPIS MARKETINGOWY']];
        return $produkt;
    }

    /**
     * @param bool $czyZapis
     * @return string
     */
    public function import($czyZapis = true)
    {
        $this->numberOfProductsImported = 0;
        $this->numberOfProductsCreated  = 0;
        $this->numberOfProductsUpdated  = 0;

        $html = '';

        $mapper = new Model_AsortymentSieci_KlientMapper();
        foreach ($this->clientsColumnNumbers as $clientName => $colNumber) {
            $klient = $mapper->findOneByName($clientName);
            if ($klient === null) {
                $klient        = new Model_AsortymentSieci_KlientEntity();
                $klient->nazwa = $clientName;
                $mapper->save($klient);
            }
        }

        $asortymentProduktMapper = new Model_AsortymentSieci_ProduktMapper();

        $asortymentProduktMapper->deleteAll();

        $startingRow = self::NUMER_WIERSZA_Z_NAZWAMI_KOLUMN + 1;
        $rowIterator = $this->PHPExcel->getActiveSheet()->getRowIterator($startingRow);
        foreach ($rowIterator as $rowObject) {
            $row = $this->getRowFromCellIterator($rowObject->getCellIterator());

            $kategoria = trim($row[$this->columnNumbers['KATEGORIA']]);
            $segment   = trim($row[$this->columnNumbers['SEGMENT']]);
            $ean       = trim($row[$this->columnNumbers['EAN']]);
            $nazwaSku  = trim($row[$this->columnNumbers['NAZWA SKU']]);

            if ($ean != '') {
                $values = array();
                foreach ($this->clientsColumnNumbers as $clientName => $colNumber) {
                    //$values[$clientName] = $row[$colNumber];
                    $values[] = array(
                        'klient'  => $clientName,
                        'wartosc' => $row[$colNumber]
                    );
                }

                $p = $this->createProduct($ean, $kategoria, $segment, $nazwaSku, $values);
                $asortymentProduktMapper->save($p);
            }
        }
        return $html;
    }

    /**
     * @param $ean
     * @param $kategoria
     * @param $segment
     * @param $nazwaSku
     * @param $values
     * @return Model_AsortymentSieci_ProduktEntity
     */
    private function createProduct($ean, $kategoria, $segment, $nazwaSku, $values)
    {
        $produkt = Model_Produkt::findOneByEan($ean);

        $p = new Model_AsortymentSieci_ProduktEntity();
        if ((int)$produkt->id > 0) {
            $p->produktId = $produkt->id;
        }
        $p->produktNazwa = $produkt->nazwa[1];
        $p->kategoria    = $kategoria;
        $p->segment      = $segment;
        $p->ean          = $ean;
        $p->nazwaSku     = $nazwaSku;
        $p->values       = $values;
        return $p;
    }


    public function getNumberOfImportedProducts()
    {
        return $this->numberOfProductsImported;
    }

    public function getNumberOfUpdatedProducts()
    {
        return $this->numberOfProductsUpdated;
    }

    public function getNumberOfCreatedProducts()
    {
        return $this->numberOfProductsCreated;
    }
}
