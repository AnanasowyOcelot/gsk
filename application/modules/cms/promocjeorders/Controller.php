<?php

class promocjeorders_Controller extends Core_CMS_Module_Controller
{
	public function __construct()
	{
		$modul           = 'promocjeorders';
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
        foreach($promocje as $promocja) {
            $nazwyPromocji[$promocja->id] = $promocja->nazwa;
        }
        $response->dodajParametr('promocjeNazwy', $nazwyPromocji);

        $mapperUsers = new Model_App_UserMapper();
        $users = $mapperUsers->find();
        $userNames = array();
        foreach($users as $user) {
            $userNames[$user->id] = $user->name;
        }
        $response->dodajParametr('userNames', $userNames);

        return $response;
    }
}
