<?php

class Model_Promocje_PromocjaEntity
{
    public $id = 0;
    public $nazwa = '';
    public $kod_icoguar = '';
    public $cena_zakupu_nagrody = 0.00;
    public $aktywna = 0;
    public $data = '';
    public $dataUtworzenia = '';
    public $dataAktualizacji = '';

    private $etapy = array();

    public function addEtap(Model_Promocje_EtapEntity $etap) {
        if(!$this->hasEtap($etap->id)) {
            $this->etapy[] = $etap;
        }
    }

    public function addEtapById($etapId) {
        $mapperEtap = new Model_Promocje_EtapMapper();
        $this->addEtap($mapperEtap->findOneById($etapId));
    }

    public function hasEtap($etapId) {
        foreach ($this->etapy as $etap) {
            if($etap->id == $etapId) {
                return true;
            }
        }
        return false;
    }

    public function getEtapy() {
        return $this->etapy;
    }

    public function clearEtapy() {
        $this->etapy = array();
    }
}
