<?php

class Model_Promocje_ExportExcel
{
    /**
     * @var Model_Promocje_PromocjaMapper
     */
    protected $promocjaMapper;

    /**
     * @var Model_Promocje_AdresMapper
     */
    protected $adresMapper;

    /**
     * @var Model_App_UserMapper
     */
    protected $userMapper;

    /**
     * @var Model_Promocje_DystrybutorMapper
     */
    protected $dystrybutorMapper;

    public function __construct()
    {
        $this->promocjaMapper = new Model_Promocje_PromocjaMapper();
        $this->adresMapper = new Model_Promocje_AdresMapper();
        $this->userMapper = new Model_App_UserMapper();
        $this->dystrybutorMapper = new Model_Promocje_DystrybutorMapper();
    }

    /**
     * @param array $orders
     * @param array $wybraneEtapy
     */
    public function generateExcelFile(array $orders, array $wybraneEtapy)
    {
        $objPHPExcel = new PHPExcel();

        if (!empty($orders)) {
            $promotionIdsArray = array();

            foreach ($orders as $order) {
                $promotionIdsArray[$order->promotionId] = $order->promotionId;
            }

            $sheetIndex = 0;
            $objPHPExcel->removeSheetByIndex(0);
            foreach ($promotionIdsArray as $promotionId) {
                // PREPARE SHEET
                $objPHPExcel->createSheet(NULL, $sheetIndex);
                $objPHPExcel->setActiveSheetIndex($sheetIndex);
                $sheet = $objPHPExcel->getActiveSheet();
                $promocja = $this->promocjaMapper->findOneById($promotionId);
                $sheet->setTitle($promocja->nazwa);

                // PREPARE CELLS
                $this->centerAllCells($objPHPExcel);
                $this->setAutosize($sheet);

                $secondRowNum = 5;

                // WRITE TITLE ROWS
                $titleRow = $this->createTitleRows($sheet, $objPHPExcel);
                list($stageColNums, $titleRow) = $this->createStagesInTitleRow($orders, $wybraneEtapy, $titleRow);
                $this->writeTitleRow($titleRow, $sheet, $secondRowNum);
                $sheet->SetCellValue('L3', $promocja->kod_icoguar);

                // WRITE ORDER ROWS
                $rowCount = 6;
                $numRow = 1;
                foreach ($orders as $order) {
                    if ($order->promotionId == $promotionId) {
                        $this->setOrderRow(
                            $sheet,
                            $order,
                            $wybraneEtapy,
                            $stageColNums,
                            $numRow,
                            $rowCount
                        );
                        $rowCount++;
                        $numRow++;
                    }
                    $lastUsedCol = $sheet->getHighestDataColumn();
                    foreach (range('A', $lastUsedCol) as $i) {
                        $this->cellColor($i . $secondRowNum, 'C8C8C8', $sheet);
                    }
                    foreach (range('A', $lastUsedCol) as $i) {
                        $this->cellColor($i . "4", 'C8C8C8', $sheet);
                    }
                }

                $sheetIndex++;
            }

        } else {
            $sheet = $objPHPExcel->getActiveSheet();

            $this->centerAllCells($objPHPExcel);
            $this->setAutosize($sheet);

            $titleRow = $this->createTitleRows($sheet, $objPHPExcel);

            $secondRowNum = 5;
            $colIndex = 'A';
            foreach ($titleRow as $colTitle) {
                $sheet->SetCellValue($colIndex . $secondRowNum, $colTitle);
                $colIndex++;
            }
            $lastUsedCol = $sheet->getHighestDataColumn();
            foreach (range('A', $lastUsedCol) as $i) {
                $this->cellColor($i . $secondRowNum, 'C8C8C8', $sheet);
            }
            foreach (range('A', $lastUsedCol) as $i) {
                $this->cellColor($i . "4", 'C8C8C8', $sheet);
            }
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(Core_Config::get('server_path') . 'www/cms/tmp/promocje.xlsx');
    }

    protected function cellColor($cells, $color, PHPExcel_Worksheet $sheet)
    {
        $sheet->getStyle($cells)->getFill()
            ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => $color)
            ));
    }

    /**
     * @param $sheet
     * @param $order
     * @param array $wybraneEtapy
     * @param $stageColNums
     * @param $numRow
     * @param $rowCount
     * @return array
     */
    private function setOrderRow($sheet, $order, array $wybraneEtapy, $stageColNums, $numRow, $rowCount)
    {
        $user = $this->userMapper->findOneById($order->przedstawicielId);
        $userSup = $this->userMapper->findOneById($user->supervisor_id);
        if (!empty($userSup)) {
            $userSupName = $userSup->name;
        } else {
            $userSupName = '';
        }

        $adres = $this->adresMapper->findOneById($order->addressId);

        $cellValues = array(
            $numRow,
            $user->name,
            $userSupName,
            $this->dystrybutorMapper->findOneById($order->dystrybutorId)->name,
            $adres->firma,
            $adres->miejscowosc,
            $adres->kodPocztowy,
            $adres->ulica,
            $adres->nrLokalu,
            $adres->osobaOdpowiedzialna,
            $adres->telefon
        );

        $colIndex = 'A';
        foreach ($cellValues as $cellValue) {
            $sheet->SetCellValue($colIndex . $rowCount, $cellValue);
            $colIndex++;
        }

        foreach ($order->items as $item) {
            if (count($wybraneEtapy) == 0 || in_array($item['stageId'], $wybraneEtapy)) {
                $sheet->SetCellValue(
                    $stageColNums[$item['stageId']] . $rowCount,
                    $item['amount']
                );
            }
        }
    }

    /**
     * @return array
     */
    private function getTitleRow()
    {
        $titleRow = array(
            'L.P.',
            'PH GSK',
            'REGIONALNY',
            'DYSTRYBUTOR',
            'FIRMA',
            'MIASTO',
            'KOD POCZTOWY',
            'ULICA',
            'NUMER LOKALU',
            'OSOBA ODPOWIEDZIALNA - IMIĘ, NAZWISKO',
            'NUMER TELEFONU'
        );
        return $titleRow;
    }

    /**
     * @param $objPHPExcel
     */
    private function centerAllCells($objPHPExcel)
    {
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $objPHPExcel->getDefaultStyle()->applyFromArray($style);
    }

    /**
     * @param $sheet
     */
    private function setAutosize($sheet)
    {
        for ($col = 'A'; $col !== 'S'; $col++) {
            $sheet
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }
    }

    /**
     * @param array $orders
     * @param array $wybraneEtapy
     * @param $titleRow
     * @return array
     */
    private function createStagesInTitleRow(array $orders, array $wybraneEtapy, $titleRow)
    {
        $stageMapper = new Model_Promocje_EtapMapper();
        $stageMapper->filterOrderBy('kolejnosc');
        $stages = $stageMapper->find();

        $stageIdsArray = array();
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $stageIdsArray[$item['stageId']] = $item['stageId'];
            };
        }

        $stageColIndex = chr(ord('A') + count($titleRow));
        $stageColNums = array();
        foreach ($stages as $stage) {
            if (count($wybraneEtapy) == 0 || in_array($stage->id, $wybraneEtapy)) {
                $stageColNums[$stage->id] = $stageColIndex;
                $titleRow[] = strtoupper($stage->nazwa);
                $stageColIndex++;
            }
        }
        return array($stageColNums, $titleRow);
    }

    /**
     * @param $sheet
     * @param $objPHPExcel
     * @return array
     */
    private function createTitleRows($sheet, $objPHPExcel)
    {
        $this->cellColor("L3", 'C8C8C8', $sheet);

        // INFO NAD ETYKIETAMI
        $sheet->mergeCells('B1:F1');
        $sheet->mergeCells('B2:H2');

        $sheet->SetCellValue('B1', 'UWAGA! Estymując ilości, należy podać ilość pakietów a nie gratisów.');
        $sheet->SetCellValue('B2', 'UWAGA! Używając funkcji wklej, należy używać WYŁĄCZNIE FUNKCJI WKLEJ SPECJALNE JAKO TEKST');

        $sheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('FF0000');

        $sheet->getStyle('B1')->getFont()->setColor($phpColor);
        $sheet->getStyle('B2')->getFont()->setColor($phpColor);

        //ETYKIETA KOD
        $sheet->SetCellValue('L2', 'KOD PRODUKTU');

        // ETYKIETY PIERWSZY RZĄD
        $objPHPExcel->setActiveSheetIndex(0);

        $sheet->mergeCells('A4:D4');
        $sheet->SetCellValue('A4', 'INFORMACJE GSK');

        $lastUsedCol = $sheet->getHighestDataColumn();

        $sheet->mergeCells("E4:".$lastUsedCol."4");
        $sheet->SetCellValue('E4', 'INFORMACJE O DOSTAWIE');

        // ETYKIETY DRUGI RZĄD
        $titleRow = $this->getTitleRow();
        return $titleRow;
    }

    /**
     * @param $titleRow
     * @param $sheet
     * @param $secondRowNum
     */
    private function writeTitleRow($titleRow, $sheet, $secondRowNum)
    {
        $colIndex = 'A';
        foreach ($titleRow as $colTitle) {
            $sheet->SetCellValue($colIndex . $secondRowNum, $colTitle);
            $colIndex++;
        }
    }

}
