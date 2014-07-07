<?php

class promocje_Controller extends Core_CMS_Module_Controller
{
    public function __construct()
    {
        $modul = 'promocje';
        $mapperClassName = 'Model_Promocje_PromocjaMapper';
        parent::__construct($modul, $mapperClassName, array(
            'liczbaNaStrone' => 20
        ));
    }

    protected function handleRecordBeforeSetToForm(Core_Request $o_requestIn, Model_Promocje_PromocjaEntity $rekord)
    {
        if ($rekord->data != '') {
            $time = strtotime($rekord->data);
        } else {
            $time = time();
        }
        $rekord->formularz_data = date('m', $time) . '/' . date('Y', $time);

        return $rekord;
    }

    protected function handleRecordBeforeSave(Core_Request $o_requestIn, Model_Promocje_PromocjaEntity $rekord)
    {
        $a_rekord = $o_requestIn->getParametr('r');

        // data:
        $miesiacRok = explode('/', $a_rekord['formularz_data']);
        $miesiac = $miesiacRok[0];
        $rok = $miesiacRok[1];
        $rekord->data = date('Y-m-d', strtotime($rok . '-' . $miesiac . '-01'));

        // etapy:
        $etapy = $a_rekord['etapy'];
        $rekord->clearEtapy();
        foreach($etapy as $etapId => $czyWlaczony) {
            if($czyWlaczony) {
                $rekord->addEtapById($etapId);
            }
        }

        return $rekord;

    }

    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    protected function obslugaFormularza(Core_Request $request)
    {
        $response = parent::obslugaFormularza($request);

        // etapy:
        $mapperEtap = new Model_Promocje_EtapMapper();
        $response->dodajParametr('etapy', $mapperEtap->find());

        return $response;
    }
}
