<?php

class Model_ProceduryPromocyjne_ImportExcelForProceduryPromocyjne
{
    const COLUMN_NAMES_ROW_NUMBER = 3;

    private $filePath = '';
    private $PHPExcel;
    private $documentName = null;

    public function __construct($filePath = '')
    {
        $this->filePath = $filePath;
        $this->PHPExcel = PHPExcel_IOFactory::load($this->filePath);
    }

    /**
     * @param bool $czyZapis
     * @return string
     */
    public function import($czyZapis = true)
    {
        $dokumet = new Model_ProceduryPromocyjne_DokumentEntity();

        if ($this->documentName === null) {
            $dokumet->nazwa = $this->filePath;
        } else {
            $dokumet->nazwa = $this->documentName;
        }
        $dokumnetMapper = new Model_ProceduryPromocyjne_DokumentMapper();

        // ZAPISYWANIE DOKUMENTU
        $dokumnetMapper->save($dokumet);

        try {
            $i = 1;
            if ($i < $this->PHPExcel->getSheetCount()) {
                while ($this->PHPExcel->setActiveSheetIndex($i)) {

                    $objWorksheet = $this->PHPExcel->getActiveSheet();

                    $klientMapper = new Model_ProceduryPromocyjne_KlientMapper();
                    $klientEntity = new Model_ProceduryPromocyjne_KlientEntity();

                    $klientEntity->dokument_id = $dokumet->id;
                    $klientEntity->nazwa = $objWorksheet->getTitle();

                    // ZAPISYWANIE KLIENTA
                    $klientMapper->save($klientEntity);

                    $startingRow = 1;
                    $rowIterator = $this->PHPExcel->getActiveSheet()->getRowIterator($startingRow);

                    $table = array();
                    foreach ($rowIterator as $rowObject) {
                        $row = array();
//                echo 'row -------- <br/>';
//                $num = 0;
                        $cellIterator = $rowObject->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        foreach ($cellIterator as $cellNum) {

                            $cellValue = $this->getCFM($cellNum->getCoordinate(), $this->PHPExcel);
                            $row[] = $cellValue;

//                    echo $cellValue . ' ';
//                    $num++;
                        }
                        $table[] = $row;
                    }
                    $promocjaMapper = new Model_ProceduryPromocyjne_PromocjaMapper();

                    $colNumbers = array();
                    for ($cellNum = 1; $cellNum < count($table[1]); $cellNum++) {
                        if (isset($table[1][$cellNum])) {
                            $cellVal = $table[1][$cellNum];
                            $cellVal = str_replace("\n", ' ', $cellVal);
                            $colNumbers[$cellVal] = $cellNum;
                        }
                    }


                    for ($row = 2; $row < count($table); $row++) {
                        $promocjaEntity = new Model_ProceduryPromocyjne_PromocjaEntity();

                        $promocjaEntity->klient_id = $klientEntity->id;

                        $promocjaEntity->subbrand = $table[$row][$colNumbers['Subbrand']];
                        $promocjaEntity->produkt = $table[$row][$colNumbers['Produkt']];
                        $promocjaEntity->termin = $table[$row][$colNumbers['Termin promocji']];
                        $promocjaEntity->termin_rabatu_OD = $table[$row][$colNumbers['Termin rabatu OD (dwuklik - kalendarz)']];

                        if ($table[$row][$colNumbers['Gazetka Tak / Nie']] == "TAK") {
                            $promocjaEntity->gazetka = 1;
                        } elseif ($table[$row][$colNumbers['Gazetka Tak / Nie']] == "NIE") {
                            $promocjaEntity->gazetka = 0;
                        }

                        $promocjaEntity->cena_rekomendowana = $table[$row][$colNumbers['Cena rekomendowana']];
                        $promocjaEntity->forma_promocji = $table[$row][$colNumbers['Forma promocji (końcówka, stojak, promocja cenowa, inna-jaka)']];
                        $promocjaEntity->dodatkowa_lokalizacja = $table[$row][$colNumbers['Dodatkowa lokalizacja (rodzaj) lista']];
                        $promocjaEntity->ilosc_dodatkowych_lokalizacji = $table[$row][$colNumbers['Ilość dodatkowych lokalizacji']];
                        $promocjaEntity->uwagi = $table[$row][$colNumbers['Uwagi']];
                        $promocjaEntity->EAN = $table[$row][$colNumbers['EAN']];

                        $produktId = Model_Produkt::findOneByEan($promocjaEntity->EAN)->id;
                        if ((int)$produktId > 0) {
                            $promocjaEntity->produkt_id = $produktId;
                        }

                        $promocjaMapper->save($promocjaEntity);
                    }


                    $i++;

                }
            }
        } catch (Exception $e) {
            // nic
        }


        return $table;
    }

    public function getCFM($cellAddress, $objPHPExcel)
    {
        $foundInRange = false;
        foreach ($objPHPExcel->getActiveSheet()->getMergeCells() as $range) {
            if ($objPHPExcel->getActiveSheet()->getCell($cellAddress)->isInRange($range)) {
                $rangeDetails = PHPExcel_Cell::splitRange($range);
                $result = $objPHPExcel->getActiveSheet()->getCell($rangeDetails[0][0])->getValue();
                $foundInRange = true;
                break;
            }
        }
        if (!$foundInRange) {
            $result = $objPHPExcel->getActiveSheet()->getCell($cellAddress)->getValue();
        }
        return $result;
    }

    public function setDocumentName($name)
    {
        $this->documentName = $name;
    }
}
