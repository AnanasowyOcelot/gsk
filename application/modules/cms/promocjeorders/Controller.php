<?php

class promocjeorders_Controller extends Core_CMS_Module_Controller
{
    public function __construct()
    {
        $modul = 'promocjeorders';
        $mapperClassName = 'Model_Promocje_OrderMapper';
        parent::__construct($modul, $mapperClassName, array(
            'liczbaNaStrone' => 50,
            'index.sortowanie.kolumna' => 'id',
            'index.sortowanie.typ' => 'desc'
        ));
    }

    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    protected function obslugaFormularza(Core_Request $request)
    {
        $response = parent::obslugaFormularza($request);

        $userMapper = new Model_App_UserMapper();
        $response->dodajParametr('przedstawiciele', $userMapper->find());

        $response->dodajParametr('statusy', Model_Promocje_OrderStatusMapper::getAll());
        $response->dodajParametr('statusButtons', array(
            array(
                'id' => Model_Promocje_OrderStatusMapper::STATUS_POTWIERDZONE,
                'name' => 'potwierdź'
            ),
            array(
                'id' => Model_Promocje_OrderStatusMapper::STATUS_DO_POPRAWY,
                'name' => 'do poprawy'
            ),
            array(
                'id' => Model_Promocje_OrderStatusMapper::STATUS_ODRZUCONE,
                'name' => 'odrzuć'
            )
        ));

        return $response;
    }

    /**
     * @param Core_Response $response
     * @param Model_Promocje_OrderEntity $rekord
     * @return Core_Response
     */
    protected function handleResponse(Core_Response $response, Model_Promocje_OrderEntity $rekord)
    {
        $response = parent::handleResponse($response, $rekord);

        $promocjeMapper = new Model_Promocje_PromocjaMapper();
        $promocja = $promocjeMapper->findOneById($rekord->promotionId);

        $response->dodajParametr('etapy', $promocja->getEtapy());
        $response->dodajParametr('promotionName', $promocja->nazwa);

        return $response;
    }

    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    public function indexAction(Core_Request $request)
    {
        $response = parent::indexAction($request);

        $response->dodajParametr('statusy', Model_Promocje_OrderStatusMapper::getAll());

        $mapperPromocje = new Model_Promocje_PromocjaMapper();
        $promocje = $mapperPromocje->find();
        $nazwyPromocji = array();
        foreach ($promocje as $promocja) {
            $nazwyPromocji[$promocja->id] = $promocja->nazwa;
        }
        $response->dodajParametr('promocjeNazwy', $nazwyPromocji);

        $mapperUsers = new Model_App_UserMapper();
        $users = $mapperUsers->find();
        $userNames = array();
        foreach ($users as $user) {
            $userNames[$user->id] = $user->name;
        }
        $response->dodajParametr('userNames', $userNames);

        return $response;
    }


    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    public function exportAction(Core_Request $request)
    {
        $response = new Core_Response();
        $response->setModuleTemplate("export");
        $orderMapper = new Model_Promocje_OrderMapper();


        $sqlConditions = '';
        if (!empty($_POST['dateFrom']) && count($_POST['dateFrom'])) {
            $dateFrom = date('Y-m-d H:i:s', (strtotime("01/" . $_POST['dateFrom'])));
            $sqlConditions .= ' AND `data_aktualizacji` >= "' . $dateFrom . '"';
        }
        if (!empty($_POST['dateTo']) && count($_POST['dateTo'])) {
            $dateTo = date('Y-m-d H:i:s', (strtotime("01/" . $_POST['dateTo'])));
            $sqlConditions .= ' AND `data_aktualizacji` <= "' . $dateTo . '"';
        }
        if (!empty($_POST['statusId']) && count($_POST['statusId'])) {
            $sqlConditions .= ' AND `status_id` IN ("' . implode('", "', $_POST['statusId']) . '")';
        }
        if (!empty($_POST['promotionId']) && count($_POST['promotionId'])) {
            $sqlConditions .= ' AND `promocja_id` IN ("' . implode('", "', $_POST['promotionId']) . '")';
        }
        if (!empty($_POST['etapId']) && count($_POST['etapId'])) {
            $sqlConditions .= ' AND `stage_id` IN ("' . implode('", "', $_POST['etapId']) . '")';
        }
        $sql = 'SELECT *
            FROM `promocje_orders`
            LEFT JOIN `promocje_orders_items` ON (promocje_orders.id = promocje_orders_items.order_id)
            WHERE
                1 = 1'
            . $sqlConditions . '
            GROUP BY id';


        $promocjaMapper = new Model_Promocje_PromocjaMapper;

        $orders = $orderMapper->findBySql($sql);


        $adresMapper = new Model_Promocje_AdresMapper();
        $userMapper = new Model_App_UserMapper();

        $objPHPExcel = new PHPExcel();

        $promotionIdsArray = array();
        foreach ($orders as $order) {
            $promotionIdsArray[$order->promotionId] = $order->promotionId;
        }

        $sheetIndex = 0;
        $objPHPExcel->removeSheetByIndex(0);
        foreach ($promotionIdsArray as $promotion) {

            $objPHPExcel->createSheet(NULL, $sheetIndex);
            $objPHPExcel->setActiveSheetIndex($sheetIndex);

            $sheet = $objPHPExcel->getActiveSheet();

            $promocja = $promocjaMapper->findOneById($promotion);
            $sheet->setTitle($promocja->nazwa);

            $secondRowNum = 5;

            // WYSRODKOWANIE TEKSTU
            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );

            $objPHPExcel->getDefaultStyle()->applyFromArray($style);

            for ($col = 'A'; $col !== 'S'; $col++) {
                $sheet
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
            }



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

            $sheet->mergeCells('E4:N4');
            $sheet->SetCellValue('E4', 'INFORMACJE O DOSTAWIE');

            // ETYKIETY DRUGI RZĄD
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
                if (in_array($stage->id, $stageIdsArray)) {
                    $stageColNums[$stage->id] = $stageColIndex;
                    $titleRow[] = strtoupper($stage->nazwa);
                    $stageColIndex++;
                }
            }


            $colIndex = 'A';
            foreach ($titleRow as $colTitle) {
                $sheet->SetCellValue($colIndex . $secondRowNum, $colTitle);
                $colIndex++;
            }

            $rowCount = 6;
            $numRow = 1;

            foreach ($orders as $order) {

                if ($order->promotionId == $promotion) {

                    $user = $userMapper->findOneById($order->przedstawicielId);
                    $userSup = $userMapper->findOneById($user->supervisor_id);
                    if (!empty($userSup)) {
                        $userSupName = $userSup->name;
                    } else {
                        $userSupName = '';
                    }

                    $adres = $adresMapper->findOneById($order->addressId);


                    $cellValues = [
                        $numRow,
                        $user->name,
                        $userSupName,
                        $order->dystrybutorId,
                        $adres->firma,
                        $adres->miejscowosc,
                        $adres->kodPocztowy,
                        $adres->ulica,
                        $adres->nrLokalu,
                        $adres->osobaOdpowiedzialna,
                        $adres->telefon
                    ];

                    $colIndex = 'A';
                    foreach ($cellValues as $cellValue) {
                        $sheet->SetCellValue($colIndex . $rowCount, $cellValue);
                        $colIndex++;
                    }

                    foreach ($order->items as $item) {
                        Core:
                        $sheet->SetCellValue(
                            $stageColNums[$item['stageId']] . $rowCount,
                            $item['amount']
                        );
                    }

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


            $sheet->SetCellValue('L3', $promocja->kod_icoguar);


            $sheetIndex++;
        }

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('../../www/cms/tmp/promocje.xlsx');

        $response->dodajParametr('downloadLink', Core_Config::get('full_cms_path') . '/tmp/promocje.xlsx');

        return $response;
    }

    // USTAWIENIE KOLORU
    public function cellColor($cells, $color, $sheet)
    {
        $sheet->getStyle($cells)->getFill()
            ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => $color)
            ));
    }

    public function exportFormAction(Core_Request $request)
    {

        $response = new Core_Response();
        $response->setModuleTemplate("exportForm");

        $promocjaMapper = new Model_Promocje_PromocjaMapper();
        $promocje = $promocjaMapper->find();
        $promocjeArr = array();
        foreach ($promocje as $promocja) {
            $promocjeArr[$promocja->id] = $promocja->nazwa;
        }


        $orderStatusMapper = new Model_Promocje_OrderStatusMapper();
        $statusy = $orderStatusMapper->getAll();
        $statusyArr = array();
        foreach ($statusy as $status) {
            $statusyArr[$status->id] = $status->nazwa;
        }

        $etapMapper = new Model_Promocje_EtapMapper();
        $etapy = $etapMapper->find();
        $etapyArr = array();
        foreach ($etapy as $etap) {
            $etapyArr[$etap->id] = $etap->nazwa;
        }

        $response->dodajParametr("promocje", $promocjeArr);
        $response->dodajParametr("statusy", $statusyArr);
        $response->dodajParametr("etapy", $etapyArr);
        $response->dodajParametr("link", Core_Config::get('cms_dir') . '/' . $this->modul . '/');


        return $response;

    }

}
