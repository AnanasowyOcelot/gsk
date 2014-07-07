<?php

class Core_CMS_Module_Controller extends Core_ModuleController
{
    /**
     * @var string
     */
    private $mapperClassName = '';

    private $record = null;

    /**
     * @var array
     */
    private $moduleConfig = array();

    /**
     * @var array
     */
    private $komunikaty = array();

    //================================================================================
    /**
     * @param $modul
     * @param $mapperClassName
     * @param array $moduleConfig
     */
    public function __construct($modul, $mapperClassName, array $moduleConfig = null)
    {
        $this->moduleConfig = array(
            'liczbaNaStrone' => 30,
            'index.sortowanie.kolumna' => 'id',
            'index.sortowanie.typ' => 'asc'
        );
        $this->moduleConfig = array_merge($this->moduleConfig, $moduleConfig);

        $this->modul = $modul;
        $this->setMapperClass($mapperClassName);
        parent::__construct();
    }

    //================================================================================
    /**
     * @param string $className
     * @throws Exception
     */
    public function setMapperClass($className)
    {
        if (is_subclass_of($className, 'Core_Mapper')) {
            $this->mapperClassName = $className;
        } else {
            throw new Exception('Brak ustawionej mapperClassName w kontrolerze.');
        }
    }

    //================================================================================
    /**
     * @return Core_Mapper
     */
    public function getNewMapper()
    {
        return new $this->mapperClassName();
    }

    //================================================================================
    /**
     * @param string $komunikat
     * @param string $typKomunikatu
     */
    public function dodajKomunikat($komunikat, $typKomunikatu = 'ok')
    {
        $this->komunikaty[] = array($typKomunikatu, $komunikat);
    }

    //================================================================================
    /**
     * @return array
     */
    public function getKomunikaty()
    {
        return $this->komunikaty;
    }

    //================================================================================
    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    protected function obslugaFormularza(Core_Request $request)
    {
        $rekord = $this->getRecord($request);

        $mapper         = $this->getNewMapper();
        $primaryKeyName = $mapper->getPrimaryKey();
        $rekordId       = $rekord->$primaryKeyName;

        $response = new Core_Response();
        $response->setModuleTemplate("form");

        $komunikaty = array();

        $errors   = array();
        $a_rekord = $request->getParametr('r');
        if (is_array($a_rekord)) {
            $errors = Core_Narzedzia::validate($a_rekord, $request->getParametr('wymagane'));

            if (count($errors) == 0) {
                $rekord = $mapper->fromArray($a_rekord, $rekord);
				$rekord = $this->handleRecordBeforeSave($request, $rekord);
				if($this->validateRecordBeforeSave($rekord)) {
					$mapper->save($rekord);
					$rekordId = $rekord->$primaryKeyName;
					Model_Historia::zapiszRekord($rekord, $rekordId, $this->modul, 'zapis', $_SESSION['cmsAdminId']);
				}

                $this->setTemplate('komunikat');
                $komunikaty[] = array('ok', 'Rekord został zapisany.');
                $response->setModuleTemplate("info");
            } else {
                $komunikaty[] = array('error', 'Proszę wypełnić wymagane pola');
            }
        }
        $response->dodajParametr('rekordId', $rekordId);
        $response->dodajParametr('errors', $errors);

        $formView = new Core_CMS_Module_ViewForm($this->getForm($rekord));
        $response->dodajParametr('form', $formView);

        $klucz = $request->getParametr('klucz');
        if (isset($klucz) && $klucz != '') {
            $rekord       = Model_Historia::pobierzRekord($klucz);
            $komunikaty[] = array('warning', 'Przywracanie wersji archiwalnej');
            $response->dodajParametr('historiaOpen', '1');
        }

        $rekord = $this->handleRecordBeforeSetToForm($request, $rekord);

        $response->dodajParametr('historia_html', $this->getHistoriaHtml($rekordId, $klucz));
        $response->dodajParametr('r', $rekord);
        $response->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $response->dodajParametr('linkParams', $this->getLinkParams($request, $primaryKeyName));
        $response->dodajParametr('primaryKeyName', $primaryKeyName);
        $response->dodajParametr('link_form', Core_Config::get('cms_dir') . '/' . $this->modul . '/edytuj/');
        $response->dodajParametr('komunikaty', $komunikaty);
        $response->dodajParametr('uprawnienia', $request->getUprawnieniaModul($this->modul));

        $response = $this->handleResponse($response, $rekord);

        return $response;
    }

	/**
	 * @param $rekord
	 * @return bool
	 */
	protected function validateRecordBeforeSave($rekord)
    {
        return true;
    }

    /**
     * @param Core_Request $o_requestIn
     * @param $rekord
     * @return mixed
     */
    protected function handleRecordBeforeSave(Core_Request $o_requestIn, $rekord)
    {
        return $rekord;
    }

    /**
     * @param Core_Request $o_requestIn
     * @param $rekord
     * @return mixed
     */
    protected function handleRecordBeforeSetToForm(Core_Request $o_requestIn, $rekord)
    {
        return $rekord;
    }

    /**
     * @param Core_Response $response
     * @param $rekord
     * @return Core_Response
     */
    protected function handleResponse(Core_Response $response, $rekord)
    {
        return $response;
    }

    /**
     * @param Core_Request $o_requestIn
     * @param string $primaryKeyName
     * @return string
     */
    protected function getLinkParams(Core_Request $o_requestIn, $primaryKeyName)
    {
        $a_powrot    = array();
        $a_parametry = $o_requestIn->getParametryGet();
        unset($a_parametry[$primaryKeyName]);
        foreach ($a_parametry as $nazawa => $wartosc) {
            $a_powrot[] = $nazawa . ':' . $wartosc;
        }
        $v_parametry_powrot = implode(",", $a_powrot);
        if ($v_parametry_powrot != '') {
            $v_parametry_powrot .= ',';
        }
        return $v_parametry_powrot;
    }

    /**
     * @param Core_Request $o_requestIn
     * @return null|object
     * @throws Exception
     */
    protected function getRecord(Core_Request $o_requestIn = null)
    {
        if ($o_requestIn === null) {
            if (!$this->record) {
                throw new Exception('Rekord nie został utworzony.');
            }
        } else {
            $mapper         = $this->getNewMapper();
            $primaryKeyName = $mapper->getPrimaryKey();
            if ($o_requestIn->getParametr($primaryKeyName) != '') {
                $this->record = $mapper->findOneById($o_requestIn->getParametr($primaryKeyName));
            } else {
                $this->record = $mapper->getNew();
            }
        }
        return $this->record;
    }

    /**
     * @param $rekord
     * @return mixed
     */
    protected function getRecordPrimaryKeyValue($rekord)
    {
        //$mapper = $this->getNewMapper();
        $primaryKeyName = $this->getNewMapper()->getPrimaryKey();
        return $rekord->$primaryKeyName;
    }

    /**
     * @param $rekordId
     * @param $klucz
     * @return bool|mixed|string
     */
    protected function getHistoriaHtml($rekordId, $klucz)
    {
        $v_historia = new historia_View();
        return $v_historia->historiaObiektow($rekordId, $this->modul, $this->modul, $klucz);
    }

    //================================================================================
    /**
     * @param null $rekord
     * @return Core_Form_Form
     */
    protected function getForm($rekord = null)
    {
        $mapper = $this->getNewMapper();

        $form = new Core_Form_Form();

        $pola = $mapper->getDescription();
        foreach ($pola as $poleNazwa => $poleOpis) {
            $poleTyp   = $poleOpis[1];
            $poleLabel = $this->getLabel($poleNazwa);

            if ($poleTyp == Core_Mapper::T_VARCHAR) {
                $field = new Core_Form_FieldText();
            } elseif ($poleTyp == Core_Mapper::T_TEXT) {
                $field = new Core_Form_FieldTextarea();
            } else {
                $field = new Core_Form_FieldText();
            }
            $field->name  = 'r[' . $poleNazwa . ']';
            $field->value = $rekord->$poleNazwa;
            $field->label = $poleLabel;
            $form->addField($field);
        }

        return $form;
    }

    //================================================================================
    /**
     * @param string $name
     * @return string
     */
    private function getLabel($name)
    {
        $name       = str_replace('_', ' ', $name);
        $nameChunks = preg_split('/(?=[A-Z])/', $name);
        $nameChunks = array_filter($nameChunks);
        $label      = implode(' ', $nameChunks);
        $label      = ucfirst($label);
        return $label;
    }

    //================================================================================
    /**
     * @param array $a_listaId
     * @return Core_Response
     */
    protected function obslugaCheckboxowListy($a_listaId)
    {
        if (is_array($a_listaId)) {
            $mapper = $this->getNewMapper();

            foreach ($a_listaId as $id) {
                $rekord = $mapper->findOneById($id);
                $mapper->delete($rekord);
                $this->dodajKomunikat('Rekord ' . $id . ' został usunięty.', 'ok');
            }
        }
    }

    //================================================================================
    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    public function indexAction(Core_Request $request)
    {
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setModuleTemplate("lista");

        $mapper         = $this->getNewMapper();
        $primaryKeyName = $mapper->getPrimaryKey();

        //************************************************************/
        $a_listaId = $request->getParametr($primaryKeyName);
        $this->obslugaCheckboxowListy($a_listaId);

        /********************* POBIERANIE ************************************/

        $na_strone = $this->moduleConfig['liczbaNaStrone'];
        $jezyk_id  = 1;

        $parametry = array();

        $sort_kolumna = $this->moduleConfig['index.sortowanie.kolumna'];
        if ($request->getParametr('col') != '') {
            $sort_kolumna     = $request->getParametr('col');
            $parametry['col'] = $sort_kolumna; // TODO: czy to potrzebne?
        }

        $sort_typ = $this->moduleConfig['index.sortowanie.typ'];
        if ($request->getParametr('typ') != '') {
            $sort_typ         = $request->getParametr('typ');
            $parametry['typ'] = $sort_typ; // TODO: czy to potrzebne?
        }

        $strona = 1;
        if ($request->getParametr('s') != '') {
            $strona = $request->getParametr('s');
        }

        $mapper->filterPage($strona);
        $mapper->filterPerPage($na_strone);
        $mapper->filterOrderBy($sort_kolumna, $sort_typ);


        // TODO: wyszukiwanie
        $parametry_szukaj = array(); // link + parametry szukaj do sortowania po kolumnach
        if ((int)$request->getParametr($primaryKeyName) > 0) {
            $parametry_szukaj[$primaryKeyName] = $request->getParametr($primaryKeyName);
        }
        if ($request->getParametr('login') != "") {
            $parametry_szukaj['login'] = $request->getParametr('login');
        }

        $rekordy = $mapper->find();


        $a_parametry = array_merge($parametry, $parametry_szukaj);
        //************************************************************/
        $a_powrot   = array();
        $a_powrot[] = "s:" . $strona;
        if (count($a_parametry) > 0) {
            foreach ($a_parametry as $nazawa => $wartosc) {
                $a_powrot [] = $nazawa . ':' . $wartosc;
            }
        }
        $v_parametry_powrot = implode(",", $a_powrot);
        if ($v_parametry_powrot != '') {
            $v_parametry_powrot .= ',';
        }
        //************************************************************/
        $link          = Core_Config::get('cms_dir') . '/' . $this->modul . '/index/';
        $o_porcjowarka = new Plugin_Porcjowarka($mapper->getRecordCount(), $na_strone, $link, $a_parametry);
        $porcjowarka   = $o_porcjowarka->buduj($strona);
        //************************************************************/

        //$_SESSION['podstrona']['link_powrot'] = $v_parametry_powrot;
        $o_indexResponse->dodajParametr('lista', $rekordy);
        $o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $o_indexResponse->dodajParametr('linkParams', $v_parametry_powrot);
        $o_indexResponse->dodajParametr('primaryKeyName', $primaryKeyName);
        $o_indexResponse->dodajParametr('porcjowarka', $porcjowarka);
        $o_indexResponse->dodajParametr('komunikaty', $this->getKomunikaty());
        $o_indexResponse->dodajParametr('jezyk_id', $jezyk_id);
        $o_indexResponse->dodajParametr('uprawnienia', $request->getUprawnieniaModul($this->modul));
        return $o_indexResponse;
    }

    //================================================================================
    /**
     * @param Core_Request $o_requestIn
     * @return Core_Response
     */
    public function dodajAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('form_nazwa', "dodaj");
        $o_indexResponse->dodajParametr('button_del', "0");
        return $o_indexResponse;
    }

    //============================================================================
    /**
     * @param Core_Request $o_requestIn
     * @return Core_Response
     */
    public function przywrocAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('form_nazwa', "edycja");
        $o_indexResponse->dodajParametr('button_del', "1");
        $o_indexResponse->dodajParametr('formToken', 0);
        return $o_indexResponse;
    }

    //============================================================================
    /**
     * @param Core_Request $o_requestIn
     * @return Core_Response
     */
    public function edytujAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('button_del', "1");
        $o_indexResponse->dodajParametr('form_nazwa', "edycja");
        return $o_indexResponse;
    }

    //============================================================================
    /**
     * @param Core_Request $o_requestIn
     * @return Core_Response
     */
    public function usunAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setModuleTemplate('usun');

        $mapper         = $this->getNewMapper();
        $primaryKeyName = $mapper->getPrimaryKey();

        $komunikaty = array();
        $idRekordu  = $o_requestIn->getParametr($primaryKeyName);

        $obj = $mapper->findOneById($idRekordu);
        $mapper->delete($obj);
        // TODO: komunikaty

        // TODO: link "powrót"

        $o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $o_indexResponse->dodajParametr('link_form', '/' . $this->modul . '/dodaj/');
        $o_indexResponse->dodajParametr('komunikaty', $komunikaty);
        return $o_indexResponse;
    }
}
