<?php
class produkt_Controller extends Core_ModuleController
{
    public function __construct()
    {
        $this->modul = 'produkt';
        parent::__construct();
    }

    //================================================================================
    /**
     * @param Core_Request $o_requestIn
     * @return Core_Response
     */
    private function obslugaFormularza(Core_Request $o_requestIn)
    {
        $response = new Core_Response();
        $r = new Model_Produkt((int)$o_requestIn->getParametr('id'));
        $a_rekord = $o_requestIn->getParametr('r');

        $uprawnienie = true;
        if ($r->kategoria_id != 0) {
            $uprawnienie = Model_KategoriaProdukt::pobierzUprawnienieDlaAdministratora($r->kategoria_id, $_SESSION['cmsAdminId']);
        }
        if ($uprawnienie && $a_rekord['kategoria_id'] != 0) {
            $uprawnienie = Model_KategoriaProdukt::pobierzUprawnienieDlaAdministratora($a_rekord['kategoria_id'], $_SESSION['cmsAdminId']);
        }

        if ($uprawnienie) {
            $response->setModuleTemplate("form");
            $komunikaty = array();

            if (is_array($a_rekord)) {
                $r->fromArray($a_rekord);
                $r->setFiles($o_requestIn->getPliki());

                $errors = Core_Narzedzia::validate($o_requestIn->getParametr('r'), $o_requestIn->getParametr('wymagane'));
                if (count($errors) == 0) {
                    $r->zapisz();
                    $this->setTemplate('komunikat');
                    $komunikaty[] = array('ok', 'Rekord został zapisany.');
                    $response->dodajParametr('rekord_id', $r->id);
                    $response->setModuleTemplate("info");
                    Model_Historia::zapiszRekord($r, $r->id, $this->modul, 'zapis', 1);
                } else {
                    $response->dodajParametr('errors', $errors);
                    $komunikaty[] = array('error', 'Proszę wypełnić wymagane pola');
                }
            }

            $a_jezyki = Model_Jezyk::pobierzWszystkie();
            $a_pole_opis = array();
            foreach ($a_jezyki as $idJezyka => $skrotJezyka) {
                $CKEditor = new CKEditor();
                $CKEditor->config['width'] = 537;
                $CKEditor->config['filebrowserBrowseUrl'] = '/www/cms/filemanager/index.html';

                $config = array();
                $config['toolbar'] = array(
                    array('Source', '-', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-', 'Subscript', 'Superscript', '-', 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Table'),
                    array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                    array('Image', 'Link', 'Unlink', 'Anchor'),
                    array('TextColor', 'BGColor')
                );

                $a_pole_opis[$idJezyka] = $CKEditor->editor('r[opis][' . $idJezyka . ']', $r->opis[$idJezyka], $config);
            }

            $klucz = $o_requestIn->getParametr('klucz');

            if (isset($klucz) && $klucz != '') {
                $r = Model_Historia::pobierzRekord($klucz);
                $komunikaty[] = array('warning', 'Przywrócenie wersji archiwalnej');
                $response->dodajParametr('historiaOpen', '1');
            }

            $kategoriaSelect = '<option value="0"> -- brak -- </option>';
            $kategoriaSelect .= produkt_View::wyswietlListeKategorii(0, 1, 0, $r->kategoria_id);
            $response->dodajParametr('kategoriaSelect', $kategoriaSelect);

            $tagMapper = new Model_Tag_TagMapper();
            $tags = $tagMapper->find();
            $response->dodajParametr('tags', $tags);

            $v_historia = new historia_View();
            $v_historia = $v_historia->historiaObiektow($r->id, $this->modul, $this->modul, $klucz);
            $response->dodajParametr('historia_html', $v_historia);

            $response->dodajParametr('connectedTags', Model_Tag_Service::getTags($r->tagIds));
            $response->dodajParametr('availableTags', Model_Tag_Service::getTagsNotInList($r->tagIds));

            //================================================================================

            $a_jezyki = Model_Jezyk::pobierzWszystkie();

            $response->dodajParametr('jezyki', $a_jezyki);
            $response->dodajParametr('r', $r);
            $response->dodajParametr('pole_opis', $a_pole_opis);
            $response->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
            $response->dodajParametr('link_form', Core_Config::get('cms_dir') . '/' . $this->modul . '/edytuj/');
            $response->dodajParametr('komunikaty', $komunikaty);
            $response->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));

        } else {
            $komunikaty = array();
            $komunikaty[] = array('error', "Brak uprawnień do edycji towaru w tej kategorii");
            $response->dodajParametr('komunikaty', $komunikaty);
        }

        return $response;
    }

    //================================================================================
    function indexAction(Core_Request $o_requestIn)
    {
        $response = new Core_Response();
        $response->setModuleTemplate("lista");
        $na_strone = 30;
        $jezyk_id = 1;

        $komunikaty = array();
        $a_listaId = $o_requestIn->getParametr('id');
        if (is_array($a_listaId)) {
            foreach ($a_listaId as $id) {
                $rekord = new Model_Produkt();
                $rekord->usun($id);
                $komunikaty[] = array('ok', 'Rekord ' . $id . ' został usunięty.');
            }
        }
        /********************* POBIERANIE ************************************/
        $parametry = array();
        if ($o_requestIn->getParametr('col') == '') {
            $sort_kolumna = "id";
        } else {
            $sort_kolumna = $o_requestIn->getParametr('col');
            $parametry['col'] = $sort_kolumna;
        }
        if ($o_requestIn->getParametr('typ') == '') {
            $sort_typ = "desc";
        } else {
            $sort_typ = $o_requestIn->getParametr('typ');
            $parametry['typ'] = $sort_typ;
        }
        if ($o_requestIn->getParametr('s') == '') {
            $strona = 1;
        } else {
            $strona = $o_requestIn->getParametr('s');
        }
        $filtr_modul = new Model_Produkt();
        $filtr_modul->filtr_strona = $strona;
        $filtr_modul->filtr_sortuj_po = $sort_kolumna;
        $filtr_modul->filtr_sortuj_jak = $sort_typ;
        $filtr_modul->filtr_ilosc_wynikow = $na_strone;
        $filtr_modul->filtr_jezyk_id = $jezyk_id;
        $parametry_szukaj = array(); // link + parametry szukaj do sortowania po kolumnach
        if ($o_requestIn->getParametr('s_nazwa') != "") {
            $parametry_szukaj['s_nazwa'] = $filtr_modul->filtr_nazwa = $o_requestIn->getParametr('s_nazwa');
        }
        if ($o_requestIn->getParametr('s_kategoria') != "") {
            $parametry_szukaj['s_kategoria'] = $filtr_modul->filtr_kategoria_id = $o_requestIn->getParametr('s_kategoria');
        }

        $filtr_modul->filtrujRekordy();

        $rekordy = array();
        if (count($filtr_modul->rekordy) > 0) {
            foreach ($filtr_modul->rekordy as $element_id) {
                $o = new Model_Produkt($element_id);

                $biggestImagePath = 'z1/4/' . $o->zdjecie_1;
                if (file_exists(Core_Config::get('images_path') . 'produkt/' . $biggestImagePath)) {
                    $o->czy_duze_zdjecie = true;
                }

                if ((int)$o->id > 0) {
                    $rekordy[] = $o;
                }
            }
        }

        $a_parametry = array_merge($parametry, $parametry_szukaj);
        //************************************************************/
        $a_powrot = array();
        $a_powrot [] = "s:" . $strona;
        if (count($a_parametry) > 0) {
            foreach ($a_parametry as $nazawa => $wartosc) {
                $a_powrot [] = $nazawa . ':' . $wartosc;
            }
        }
        $v_parametry_powrot = implode(",", $a_powrot);
        //************************************************************/
        $link = Core_Config::get('cms_dir') . '/' . $this->modul . '/index/';
        $o_porcjowarka = new Plugin_Porcjowarka($filtr_modul->ilosc_rekordow, $na_strone, $link, $a_parametry);
        $porcjowarka = $o_porcjowarka->buduj($strona);
        //************************************************************/

        $tagMapper = new Model_Tag_TagMapper();
        $tags = $tagMapper->find();
        $aTags = array();
        foreach ($tags as $tag) {
            $aTags[$tag->id] = $tag;
        }
        $response->dodajParametr('aTags', $aTags);

        $kategoriaSelect = '<option value="">--- wszystkie kategorie ---</option>';
        $kategoriaSelect .= produkt_View::wyswietlListeKategorii(0, 1, 0, $o_requestIn->getParametr('s_kategoria'));
        $response->dodajParametr('kategoriaSelect', $kategoriaSelect);
        $response->dodajParametr('modul', $this->modul);

        $_SESSION['podstrona']['link_powrot'] = $v_parametry_powrot;
        $response->dodajParametr("lista", $rekordy);
        $response->dodajParametr("parametry", $a_parametry);
        $response->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $response->dodajParametr('porcjowarka', $porcjowarka);
        $response->dodajParametr('komunikaty', $komunikaty);
        $response->dodajParametr("jezyk_id", $jezyk_id);
        $response->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
        return $response;
    }

    //================================================================================
    function dodajAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('form_nazwa', "dodaj");
        $o_indexResponse->dodajParametr('button_del', "0");
        return $o_indexResponse;
    }

    //============================================================================
    function przywrocAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('form_nazwa', "edycja");
        $o_indexResponse->dodajParametr('button_del', "1");
        $o_indexResponse->dodajParametr('formToken', 0);
        return $o_indexResponse;
    }

    //============================================================================
    function edytujAction(Core_Request $o_requestIn)
    {

        $dokumentMapper = new Model_ProceduryPromocyjne_DokumentMapper();
        $dokumentId = $dokumentMapper->findNewestFileId();

        $klientMapper = new Model_ProceduryPromocyjne_KlientMapper();
        $klientMapper->filterBy('dokument_id', $dokumentId);

        $table = procedurypromocyjnepliki_Controller::createPromocjeTable($klientMapper, $o_requestIn->getParametr('id'));

        $o_indexResponse = $this->obslugaFormularza($o_requestIn);
        $o_indexResponse->dodajParametr('klienciPromocjeLista', $table);
        $o_indexResponse->dodajParametr('button_del', "1");
        $o_indexResponse->dodajParametr('form_nazwa', "edycja");
        return $o_indexResponse;
    }

    //============================================================================
    function usunAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setModuleTemplate('usun');
        $idRekordu = (int)$o_requestIn->getParametr('id');

        $obj = new Model_Produkt();
        $komunikaty = $obj->usun($idRekordu);
        $o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $o_indexResponse->dodajParametr('link_form', '/' . $this->modul . '/dodaj/');
        $o_indexResponse->dodajParametr('komunikaty', $komunikaty);
        return $o_indexResponse;
    }
}
