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

        $dystrybutorMapper = new Model_Promocje_DystrybutorMapper();
        $response->dodajParametr('dystrybutorzy', $dystrybutorMapper->find());

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

        $orderMapper = new Model_Promocje_OrderMapper();
        $orders = $orderMapper->findBySql($sql);

        $wybraneEtapy = array();
        if (is_array($_POST['etapId'])) {
            $wybraneEtapy = $_POST['etapId'];
        }

        $exporter = new Model_Promocje_ExportExcel();
        $exporter->generateExcelFile($orders, $wybraneEtapy);

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
