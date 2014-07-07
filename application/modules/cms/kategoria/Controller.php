<?php
class kategoria_Controller extends Core_ModuleController
{
    public function __construct()
    {
        $this->modul = 'kategoria';
        parent::__construct();
    }

    //================================================================================
    /**
     * @param Core_Request $o_requestIn
     * @return Core_Response
     */
    private function obslugaFormularza(Core_Request $o_requestIn)
    {
        $r                    = new Model_KategoriaProdukt((int)$o_requestIn->getParametr('id'));
        $response = new Core_Response();
        $response->setModuleTemplate("form");

        $komunikaty = array();
        $a_rekord   = $o_requestIn->getParametr('r');
        $a_produkty = $o_requestIn->getParametr('produkty');
        if (is_array($a_rekord)) {
            $r->fromArray($a_rekord);
            $komunikaty = $r->validate();
            if (count($komunikaty) == 0) {
                $r->zapisz();

                Model_KategoriaProdukt::zapiszUprawnieniaDlaGrup($r->id, $a_rekord['grupaUprawnienia']);

                foreach ($a_produkty as $produktId => $a_produkt) {
                    $produkt = new Model_Produkt($produktId);
                    foreach ($produkt->miejsce as $jId => $miejsce) {
                        $produkt->miejsce[$jId] = $a_produkt['miejsce'];
                    }
                    //Core_Narzedzia::drukuj('miejsce');
                    //Core_Narzedzia::drukuj($a_produkt['miejsce']);
                    //Core_Narzedzia::drukuj($produkt);
                    $produkt->zapisz();
                }

                $this->setTemplate('komunikat');
                $komunikaty[] = array('ok', 'Rekord został zapisany.');
                $response->setModuleTemplate("info");
            }
        } else {

            $a_jezyki     = Model_Jezyk::pobierzWszystkie();
            $a_pole_tresc = array();
            foreach ($a_jezyki as $idJezyka => $skrotJezyka) {
                $CKEditor                                 = new CKEditor();
                $CKEditor->config['width']                = 537;
                $CKEditor->config['filebrowserBrowseUrl'] = '/www/cms/filemanager/index.html';

                $config                  = array();
                $config['toolbar']       = array(
                    array('Source', '-', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-', 'Subscript', 'Superscript', '-', 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Table'),
                    array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                    array('Image', 'Link', 'Unlink', 'Anchor'),
                    array('TextColor', 'BGColor')
                );
                $a_pole_tresc[$idJezyka] = $CKEditor->editor('r[tresc][' . $idJezyka . ']', $r->tresc[$idJezyka], $config);
            }


            $parentSelect = '<option value="0"> -- brak -- </option>';
            $parentSelect .= kategoria_View::wyswietlListe(0, 1, 0, $r->id_nadrzedna, $r->id);
            $response->dodajParametr('parentSelect', $parentSelect);

            $view_types = array(
                array(
                    'value' => Model_KategoriaProdukt::VIEW_PRODUCTS,
                    'name'  => 'produkty'
                ),
                array(
                    'value' => Model_KategoriaProdukt::VIEW_TILES,
                    'name'  => 'kafelki'
                ),
                array(
                    'value' => Model_KategoriaProdukt::MODULE_ASORTYMENT_SIECI,
                    'name'  => 'asortyment sieci'
                ),
                array(
                    'value' => Model_KategoriaProdukt::MODULE_PROMOTIONS,
                    'name'  => 'promocje'
                )
            );

            if ($r->id > 0) {
                $filtrProdukty                     = new Model_Produkt();
                $filtrProdukty->filtr_kategoria_id = $r->id;
                $filtrProdukty->filtr_sortuj_po    = 'miejsce';
                $filtrProdukty->filtr_sortuj_jak   = 'ASC';
                $filtrProdukty->filtr_jezyk_id     = 1;
                $filtrProdukty->filtrujRekordy();
                $produkty = array();
                foreach ($filtrProdukty->rekordy as $produktId) {
                    $produkty[] = new Model_Produkt($produktId);
                }
                $response->dodajParametr('produkty', $produkty);
            } else {
                $response->dodajParametr('produkty', array());
            }


            $grupyUprawenienia = Model_KategoriaProdukt::pobierzUprawnieniaDlaGrup($r->id);
            $response->dodajParametr('grupyUprawenienia', $grupyUprawenienia);

            $response->dodajPlikJS("kategoria/js/engine.js");
            $response->dodajParametr('link_form', Core_Config::get('cms_dir') . '/' . $this->modul . '/edytuj/');
            $response->dodajParametr('jezyki', $a_jezyki);
            $response->dodajParametr('view_types', $view_types);
            $response->dodajParametr('r', $r);
            $response->dodajParametr('pole_tresc', $a_pole_tresc);
        }
        $response->dodajParametr("link_powrot", $_SESSION['kategoria']['link_powrot']);
        $response->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $response->dodajParametr('komunikaty', $komunikaty);

        $response->dodajParametr('connectedTags', Model_Tag_Service::getTags($r->tagIds));
        $response->dodajParametr('availableTags', Model_Tag_Service::getTagsNotInList($r->tagIds));

        //=======================================================================================


        // TODO: przeniesc gdzies indziej
        Model_GSKProdukt::generujJSON();
        Model_KategoriaProdukt::updateCategoriesPaths();


        return $response;
    }

    //================================================================================
    function indexAction(Core_Request $o_requestIn)
    {
        $response = new Core_Response();
        $response->setModuleTemplate("lista");
        $this->akcja = "index";
        $komunikaty  = array();

        //************************************************************/
        $a_listaId = $o_requestIn->getParametr('id_zaznaczone');
        if (is_array($a_listaId)) {
            foreach ($a_listaId as $id) {
                Model_KategoriaProdukt::usun($id);
                $komunikaty[] = array('ok', 'Rekord ' . $id . ' został usunięty.');
            }
        }
        //************************************************************/

        $na_strone    = 30;
        $jezyk_id     = 1;
        $sort_kolumna = '';
        $sort_typ     = '';
        $parametry    = array();
        if ($o_requestIn->getParametr('col') == '') {
            $sort_kolumna = "pathAndKolejnosc";
        } else {
            $sort_kolumna     = $o_requestIn->getParametr('col');
            $parametry['col'] = $sort_kolumna;
        }
        if ($o_requestIn->getParametr('typ') == '') {
            $sort_typ = "asc";
        } else {
            $sort_typ         = $o_requestIn->getParametr('typ');
            $parametry['typ'] = $sort_typ;
        }
        if ($o_requestIn->getParametr('s') == '') {
            $strona = 1;
        } else {
            $strona = $o_requestIn->getParametr('s'); /*$parametry['s'] = $strona;*/
        }

        $filtr                      = new Model_KategoriaProdukt();
        $filtr->filtr_strona        = $strona;
        $filtr->filtr_sortuj_po     = $sort_kolumna;
        $filtr->filtr_sortuj_jak    = $sort_typ;
        $filtr->filtr_ilosc_wynikow = $na_strone;
        $filtr->filtr_jezyk_id      = $jezyk_id;
        $parametry_szukaj           = array(); // link + parametry szukaj do sortowania po kolumnach
        if ((int)$o_requestIn->getParametr('id') > 0) {
            $parametry_szukaj['id'] = $filtr->filtr_id = $o_requestIn->getParametr('id');
        }
        if ($o_requestIn->getParametr('nazwa') != "") {
            $parametry_szukaj['nazwa'] = $filtr->filtr_nazwa = $o_requestIn->getParametr('nazwa');
        }
        $filtr->filtruj();
        //************************************************************/
        $a_tmp = array();
        if (count($parametry_szukaj) > 0) {
            foreach ($parametry_szukaj as $nazawa => $wartosc) {
                $a_tmp[] = $nazawa . ':' . $wartosc;
            }
        }
        $v_parametry_link = implode(",", $a_tmp);
        //************************************************************/

        $a_parametry = array_merge($parametry, $parametry_szukaj);
        //************************************************************/
        $a_powrot    = array();
        $a_powrot [] = "s:" . $strona;
        if (count($a_parametry) > 0) {
            foreach ($a_parametry as $nazawa => $wartosc) {
                $a_powrot [] = $nazawa . ':' . $wartosc;
            }
        }
        $v_parametry_powrot = implode(",", $a_powrot);
        //************************************************************/
        $link          = Core_Config::get('cms_dir') . '/' . $this->modul . '/index/';
        $o_porcjowarka = new Plugin_Porcjowarka($filtr->ilosc_rekordow, $na_strone, $link, $a_parametry);
        $porcjowarka   = $o_porcjowarka->buduj($strona);
        //************************************************************/
        $rekordy = array();
        if (count($filtr->rekordy) > 0) {
            foreach ($filtr->rekordy as $kategoria_id) {
                $o = new Model_KategoriaProdukt($kategoria_id);
                if ((int)$o->id > 0) {
                    $html = '';
                    foreach ($o->nadrzedne as $num => $n) {
                        $rr = new Model_KategoriaProdukt($n);
                        $html .= '<span style="verticalaq-align:middle; top:0px;"><b>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp; </b></span>';
                        $html .= '<span style="vertical-align:middle;">';
                        if ($num == count($o->nadrzedne) - 1) {
                            $html .= '<b>' . $rr->nazwa[$jezyk_id] . '</b>';
                        } else {
                            $html .= $rr->nazwa[$jezyk_id];
                        }
                        $html .= '</span>';
                    }
                    $o->nazwa_pelna_sciezka[$jezyk_id] = $html;

                    $mapperProdukt                     = new Model_Produkt();
                    $mapperProdukt->filtr_kategoria_id = $kategoria_id;
                    $mapperProdukt->filtr_jezyk_id     = 1;
                    $mapperProdukt->filtrujRekordy();
                    $o->liczba_produktow = $mapperProdukt->ilosc_rekordow;

                    $kolor = $o->kolor_tlo;
                    if (preg_match_all('/([a-fA-F0-9]){3}(([a-fA-F0-9]){3})?\b/', $kolor, $out) > 0) {
                        $o->kolor = '#' . $kolor;
                    } else {
                        $o->kolor = $kolor;
                    }

                    $rekordy[] = $o;
                }
            }
        }

		$tagMapper = new Model_Tag_TagMapper();
		$tags = $tagMapper->find();
		$aTags = array();
		foreach($tags as $tag) {
			$aTags[$tag->id] = $tag;
		}
		$response->dodajParametr('aTags', $aTags);

        $_SESSION['kategoria']['link_powrot'] = $v_parametry_powrot;

        $response->dodajParametr("lista", $rekordy);
        $response->dodajParametr("link_parametry", $v_parametry_link);
        $response->dodajParametr('komunikaty', $komunikaty);
        $response->dodajParametr("parametry", $parametry_szukaj);
        $response->dodajParametr("jezyk_id", $jezyk_id);
        $response->dodajParametr("modul", $this->modul);
        $response->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $response->dodajParametr('porcjowarka', $porcjowarka);
        $response->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul('kategoria'));

        return $response;
    }

    //============================================================================
    function dodajAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('button_del', "0");
        $o_indexResponse->dodajParametr('form_nazwa', "dodaj");
        $this->akcja = "dodaj";
        return $o_indexResponse;
    }

    //============================================================================
    function edytujAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('button_del', "1");
        $o_indexResponse->dodajParametr('form_nazwa', "edycja");
        $this->akcja = "edytuj";

        return $o_indexResponse;
    }

    //============================================================================
    function usunAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setModuleTemplate('usun');
        $this->akcja = "usun";
        $komunikaty  = array();
        $idRekordu   = (int)$o_requestIn->getParametr('id');
        if ($idRekordu > 0) {
            Model_KategoriaProdukt::usun($idRekordu);
            $komunikaty[] = array('ok', 'Rekord o id = ' . (int)$idRekordu . ' został usunięty.');
        }
        $o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $o_indexResponse->dodajParametr('link_form', '/' . $this->modul . '/dodaj/');
        $o_indexResponse->dodajParametr('komunikaty', $komunikaty);
        return $o_indexResponse;
    }

    public function updateAllCategoriesPathsAction(Core_Request $o_requestIn = null)
    {
        $res = array(
            'paths' => Model_KategoriaProdukt::updateCategoriesPaths()
        );
        return new Core_Response(json_encode($res), Core_Response::CONTENT_TYPE_AJAX);
    }
}
