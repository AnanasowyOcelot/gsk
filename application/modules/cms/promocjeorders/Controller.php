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

        //$sql = 'SELECT * FROM `promocje_orders` WHERE';


        $sqlConditions = '';
        if (isset($_POST['dateFrom'])) {
            $dateFrom = date('Y-m-d H:i:s', (strtotime("01/" . $_POST['dateFrom'])));
            $sqlConditions .= ' AND `data_aktualizacji` >= "' . $dateFrom . '"';
        }
        if (isset($_POST['dateTo'])) {
            $dateTo = date('Y-m-d H:i:s', (strtotime("01/" . $_POST['dateTo'])));
            $sqlConditions .= ' AND `data_aktualizacji` <= "' . $dateTo . '"';
        }
        if (isset($_POST['statusId']) && count($_POST['statusId'])) {
            // zeby uodpornic na sql injection trzeba zrobic int z kazdej wartosci tego arraya
            $sqlConditions .= ' AND `statusId` IN ("' . implode('", "', $_POST['statusId']) . '")';
        }
        if (isset($_POST['promotionId']) && count($_POST['promotionId'])) {
            $sqlConditions .= ' AND `promotionId` IN ("' . implode('", "', $_POST['promotionId']) . '")';
        }
        if (isset($_POST['etapId']) && count($_POST['etapId'])) {
        $sqlConditions .= 'AND `stage_id` IN ("' . implode('", "', $_POST['etapId']) . '")';
        }
        $sql = 'SELECT *
            FROM `promocje_orders`
            LEFT JOIN `promocje_orders_items` ON (promocje_orders.id = promocje_orders_items.order_id)
            WHERE
                1 = 1'
                 . $sqlConditions . '
            GROUP BY order_id';



/*
        $dateFromTo = array();
        if (isset($_POST['dateFrom'])) {
            $dateFromTo["from"] = date('Y-m-d H:i:s', (strtotime("01/" . $_POST['dateFrom'])));
            $filterDateForOrderMapper = $orderMapper->findBySql('SELECT * FROM `promocje_orders` WHERE `data_aktualizacji` >= "' . $dateFromTo["from"] . '"');

            foreach ($filterDateForOrderMapper as $order) {
                $orderMapper->filterBy('id', $order->id);
            }

        }
        if (isset($_POST['dateTo'])) {
            $dateFromTo["to"] = date('Y-m-d H:i:s', (strtotime("01/" . $_POST['dateTo'])));
            $filterDateForOrderMapper = $orderMapper->findBySql('SELECT * FROM `promocje_orders` WHERE `data_aktualizacji` <= "' . $dateFromTo["to"] . "");

            foreach ($filterDateForOrderMapper as $order) {
                $orderMapper->filterBy('id', $order->id);
            }
        }*/


        $promocjaMapper = new Model_Promocje_PromocjaMapper;

        $orders = $orderMapper->findBySql($sql);


        $adresMapper = new Model_Mapper_Adres();
        $userMapper = new Model_App_UserMapper;

        $objPHPExcel = new PHPExcel();

        $secondRowNum = 5;

        // WYSRODKOWANIE TEKSTU
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel->getDefaultStyle()->applyFromArray($style);

        for ($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        // USTAWIENIE KOLORU
        function cellColor($cells, $color, $objPHPExcel)
        {
            $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
                ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array('rgb' => $color)
                ));
        }

        foreach (range('A', 'R') as $i) {
            cellColor($i . $secondRowNum, 'C8C8C8', $objPHPExcel);
        }
        foreach (range('A', 'R') as $i) {
            cellColor($i . "4", 'C8C8C8', $objPHPExcel);
        }
        cellColor("O3", 'C8C8C8', $objPHPExcel);

        // INFO NAD ETYKIETAMI
        $objPHPExcel->getActiveSheet()->mergeCells('B1:F1');
        $objPHPExcel->getActiveSheet()->mergeCells('B2:H2');

        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'UWAGA! Estymując ilości, należy podać ilość pakietów a nie gratisów.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'UWAGA! Używając funkcji wklej, należy używać WYŁĄCZNIE FUNKCJI WKLEJ SPECJALNE JAKO TEKST');

        $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $phpColor = new PHPExcel_Style_Color();
        $phpColor->setRGB('FF0000');

        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setColor($phpColor);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setColor($phpColor);

        //ETYKIETA KOD
        $objPHPExcel->getActiveSheet()->SetCellValue('O2', 'KOD PRODUKTU');

        // ETYKIETY PIERWSZY RZĄD
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->mergeCells('A4:D4');
        $objPHPExcel->getActiveSheet()->SetCellValue('A4', 'INFORMACJE GSK');

        $objPHPExcel->getActiveSheet()->mergeCells('E4:N4');
        $objPHPExcel->getActiveSheet()->SetCellValue('E4', 'INFORMACJE O DOSTAWIE');

        // ETYKIETY DRUGI RZĄD
        $titleRow = array(
            array('A', 'L.P.'),
            array('B', 'PH GSK'),
            array('C', 'REGIONALNY'),
            array('D', 'DYSTRYBUTOR'),
            array('E', 'FIRMA'),
            array('F', 'MIASTO'),
            array('G', 'KOD POCZTOWY'),
            array('H', 'ULICA'),
            array('I', 'NUMER LOKALU'),
            array('J', 'OSOBA ODPOWIEDZIALNA - IMIĘ, NAZWISKO'),
            array('K', 'NUMER TELEFONU'),
            array('L', 'KOD POCZTOWY'),
            array('M', 'ULICA'),
            array('N', 'NUMER LOKALU'),
            array('O', 'ESTYMACJA'),
            array('P', 'PAKIET STARTOWY'),
            array('Q', 'POŁOWA AKCJI'),
            array('R', 'KONIEC AKCJI'),
        );
        foreach ($titleRow as $title) {
            $objPHPExcel->getActiveSheet()->SetCellValue($title[0] . $secondRowNum, $title[1]);
        }

        $rowCount = 6;
        $numRow = 1;
        foreach ($orders as $order) {

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $numRow);

            $user = $userMapper->findOneById($order->przedstawicielId);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $user->name);

            $userSup = $userMapper->findOneById($user->supervisor_id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $userSup->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $order->dystrybutorId);

            $adres = $adresMapper->findOneById($order->addressId);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $adres->firma);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $adres->miejscowosc);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $adres->kodPocztowy);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $adres->ulica);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $adres->nrLokalu);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $adres->osobaOdpowiedzialna);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $adres->telefon);

            foreach ($order->items as $item) {
                if ($item['stageId'] == 1) {
                    $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $item['amount']);
                }
                if ($item['stageId'] == 2) {
                    $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $item['amount']);
                }
                if ($item['stageId'] == 3) {
                    $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $item['amount']);
                }
                if ($item['stageId'] == 4) {
                    $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $item['amount']);
                }
            }

            $rowCount++;
            $numRow++;
        }

        $promocja = $promocjaMapper->findOneById($orders[0]->promotionId);
        $objPHPExcel->getActiveSheet()->SetCellValue('O3', $promocja->kod_icoguar);

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('../../www/cms/tmp/promocje.xlsx');

        $response->dodajParametr('downloadLink', Core_Config::get('full_cms_path') . '/tmp/promocje.xlsx');

        return $response;
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
