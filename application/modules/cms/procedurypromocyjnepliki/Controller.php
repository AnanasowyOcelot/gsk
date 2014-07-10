<?php

class procedurypromocyjnepliki_Controller extends Core_CMS_Module_Controller
{
    public function __construct()
    {
        $modul = 'procedurypromocyjnepliki';
        $mapperClassName = 'Model_ProceduryPromocyjne_DokumentMapper';
        parent::__construct($modul, $mapperClassName, array(
            'liczbaNaStrone' => 20
        ));
    }

    protected function obslugaFormularza(Core_Request $request)
    {
        $response = parent::obslugaFormularza($request);

        $klientMapper = new Model_ProceduryPromocyjne_KlientMapper();

        $rekord2 = $this->getRecord($request);


        $klientMapper->filterBy('dokument_id', $rekord2->id);
        $klients = $klientMapper->find();



        $table = array();
        foreach ($klients as $klient) {
            //$table[] = $klient->nazwa;
            $promocjaMapper = new Model_ProceduryPromocyjne_PromocjaMapper();
            $promocjaMapper->filterBy('klient_id', $klient->id);
            $promocje = $promocjaMapper->find();
            foreach($promocje as $promocja){
                $promocjaArr = array();
                $promocjaArr['subbrand'] = $promocja->subbrand;
                $promocjaArr['produkt'] = $promocja->produkt;
                $promocjaArr['termin'] = $promocja->termin;
                $promocjaArr['termin_rabatu_OD'] = $promocja->termin_rabatu_OD;
                $promocjaArr['gazetka'] = $promocja->gazetka;
                $promocjaArr['cena_rekomendowana'] = $promocja->cena_rekomendowana;
                $promocjaArr['forma_promocji'] = $promocja->forma_promocji;
                $promocjaArr['dodatkowa_lokalizacja'] = $promocja->dodatkowa_lokalizacja;
                $promocjaArr['ilosc_dodatkowych_lokalizacji'] = $promocja->ilosc_dodatkowych_lokalizacji;
                $promocjaArr['uwagi'] = $promocja->uwagi;

                $table[$klient->nazwa][] = $promocjaArr;
            }

        }
        Core_Narzedzia::drukuj($table);
//        exit();


        $response->dodajParametr('klienciPromocjeLista', $table);

        return $response;
    }
}
