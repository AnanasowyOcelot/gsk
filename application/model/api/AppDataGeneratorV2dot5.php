<?php

class Model_Api_AppDataGeneratorV2dot5
{
    public static function generujJson()
    {
        $res = array();

        $res['apiVersion'] = '2.5';

        $res['categoryType'] = array(
            'VIEW_PRODUCTS' => Model_KategoriaProdukt::VIEW_PRODUCTS,
            'VIEW_TILES' => Model_KategoriaProdukt::VIEW_TILES
        );

        $res['productsData'] = self::getSubcategories(0);

        $json = json_encode($res);

        return $json;
    }

    private static function getSubcategories($katNadrzednaId)
    {
        $katMapper = new Model_KategoriaProdukt();
        $katMapper->filtr_jezyk_id = 1;
        $katMapper->filtr_aktywna = 1;
        $katMapper->filtr_id_nadrzedna = $katNadrzednaId;
        $katMapper->filtr_sortuj_po = 'miejsce';
        $katMapper->filtr_sortuj_jak = 'ASC';
        $katMapper->filtruj();

        $res = array();
        foreach ($katMapper->rekordy as $katId) {
            $kat = new Model_KategoriaProdukt($katId);
            $res[] = array(
                'categoryInfo' => array(
                    'id' => $kat->id,
                    'parentId' => $kat->id_nadrzedna,
                    'viewType' => $kat->view_type,
                    'name' => $kat->nazwa[1],
                    'textColor' => $kat->kolor_tekst,
                    'backgroundColor' => $kat->kolor_tlo
                ),
                'products' => self::getProducts($katId),
                'pdfs' => self::getPdfs($katId),
                'subCategories' => self::getSubcategories($katId)
            );
        }
        return $res;
    }

    private static function getProducts($katId)
    {
        $produktMapper = new Model_Produkt();
        $produktMapper->filtr_jezyk_id = 1;
        $produktMapper->filtr_aktywny = 1;
        $produktMapper->filtr_kategoria_id = $katId;
        $produktMapper->filtr_sortuj_po = 'miejsce';
        $produktMapper->filtr_sortuj_jak = 'ASC';
        $produktMapper->filtrujRekordy();

        $res = array();
        foreach ($produktMapper->rekordy as $produktId) {
            $produkt = new Model_Produkt($produktId);

            $imageBig = 'z1/2/' . $produkt->zdjecie_1;
            $imageSmall = 'z1/1/' . $produkt->zdjecie_1;
            $template = '';
            if ($produkt->typ == 2) {
                $template = 'promocje';
                $imageBig = 'z1/4/' . $produkt->zdjecie_1;
                $imageSmall = 'z1/3/' . $produkt->zdjecie_1;
            }

            $biggestImagePath = 'z1/4/' . $produkt->zdjecie_1;
            $kodZdjecia = Model_ZdjecieKod::getPictureCode($biggestImagePath);
            $urlDuzegoZdjecia = '';
            if (file_exists(Core_Config::get('images_path') . 'produkt/' . $biggestImagePath)) {
                $urlDuzegoZdjecia = Core_Config::get('www_url') . 'www/page/img.php?c=' . $kodZdjecia;
            }

            $dir = Core_Config::get('images_path') . 'produkt/';
            $imageBigSize = filesize($dir . $imageBig);
            $imageSmallSize = filesize($dir . $imageSmall);

            $opis = $produkt->opis[1];
            $opis = str_replace("
", ' ', $opis);

            $res[] = array(
                'id' => $produkt->id,
                'template' => $template,
                'name' => $produkt->nazwa[1],
                'serverDir' => 'images/produkt/',
                'image' => $imageBig,
                'imageSize' => $imageBigSize,
                'imageSmall' => $imageSmall,
                'imageSmallSize' => $imageSmallSize,
                'imageUrl' => $urlDuzegoZdjecia,
                'barcode' => '',
                'description' => '<b>EAN produktu</b>: ' . $produkt->ean . '<br />'
					.'<b>Liczba sztuk w opakowaniu</b>: ' . $produkt->sztuk_w_opakowaniu . '<br />'
					.'<b>EAN opakowania</b>: ' . $produkt->ean_opakowania . '<br />'
					.'<b>PKWIU</b>: ' . $produkt->pkwiu . '',
                'description2' => $opis
            );
        }
        return $res;
    }

    private static function getPdfs($katId)
    {
        $res = array();
        $pdfMapper = new Model_Mapper_Pdf();
        $pdfMapper->filterBy('kategoria_id', $katId);
        $pdfs = $pdfMapper->find();
        foreach ($pdfs as $pdf) {
            $res[] = self::getPdf($pdf);
        }
        return $res;
    }

    private static function getPdf(Model_DataObject_Pdf $pdf)
    {
        $res = array(
            'id' => $pdf->id,
            'name' => $pdf->nazwa,
            'serverDir' => 'images/pdf/',
            'files' => self::getPdfImagesFiles($pdf)
        );
        return $res;
    }

    public static function getPdfImagesFiles(Model_DataObject_Pdf $pdf)
    {
        $files = array();
        for ($i = 0; $i < $pdf->liczba_stron; $i++) {
            $file = Core_Config::get('images_path') . 'pdf/' . $pdf->id . '/pages/' . ($i + 1) . '.png';
            $files[] = array(
                'path' => $pdf->id . '/pages/' . ($i + 1) . '.png',
                'size' => filesize($file)
            );
        }
        return $files;
    }

}

