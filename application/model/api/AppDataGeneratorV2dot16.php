<?php

class Model_Api_AppDataGeneratorV2dot16
{
    public static function generujJson($postData)
    {
        $res = array();

        $res['apiVersion'] = '2.16';

        $user = null;
        if (isset($postData['login']) && isset($postData['password'])) {
            if ($postData['login'] != '' && $postData['password'] != '') {
                $user = self::getUser($postData['login'], $postData['password']);
            }
        }
        if ($user !== null) {
            $res['status'] = 'OK';
            $res['errorMessage'] = '';

            $logEntry = new Model_App_Log();
            $logEntry->activity = 'aktualizacja';
            $logEntry->userId = $user->id;
            $logEntry->apiVersion = $res['apiVersion'];
            $logEntry->appVersion = $postData['appVersion'];
            $mapperLog = new Model_App_LogMapper();
            $mapperLog->save($logEntry);

            if (isset($postData['promotions'])) {
                if (isset($postData['promotions']['orders'])) {
                    $ordersData = $postData['promotions']['orders'];
                    $ordermapper = new Model_Promocje_OrderMapper();
                    foreach ($ordersData as $orderData) {
                        if ((int)$orderData['remote_id'] == 0) {
                            $order = new Model_Promocje_OrderEntity();
                            $order->id = (int)$orderData['remote_id'];
                            $order->przedstawicielId = $user->id;
                            $order->statusId = $orderData['status_id'];
                            $order->promocjaId = $orderData['promocja_id'];
                            $order->addressId = $orderData['address_id'];
                            if (isset($orderData['items'])) {
                                $order->items = $orderData['items'];
                            }
                            $ordermapper->save($order);
                        }
                    }
                }
            }
        } else {
            $res['status'] = 'ERROR';
            $res['errorMessage'] = 'Nieprawidłowy login, hasło, użytkownik nieaktywny lub błąd synchronizacji.';
        }

        $res['postData'] = $postData;

        $res['categoryType'] = array(
            'VIEW_PRODUCTS' => Model_KategoriaProdukt::VIEW_PRODUCTS,
            'VIEW_TILES' => Model_KategoriaProdukt::VIEW_TILES,
            'MODULE_PROMOTIONS' => Model_KategoriaProdukt::MODULE_PROMOTIONS
        );

        if ($res['status'] == 'OK') {
            $res['promotions'] = self::getPromotionsData($user);
            $res['categories'] = self::getAllCategories();
            $res['products'] = self::getAllProducts();

            $res['productsData'] = self::getSubcategories(0);
        } else {
            $res['promotions'] = array();
            $res['categories'] = array();
            $res['products'] = array();

            $res['productsData'] = array();
        }


        $newCat = array(
            'categoryInfo' => array(
                'id' => 99999,
                'parentId' => 132,
                'viewType' => Model_KategoriaProdukt::MODULE_PROMOTIONS,
                'name' => 'Gadżety',
                'textColor' => '#ffffff',
                'backgroundColor' => '#000000'
            )
        );
        $res['productsData'][1]['subCategories'][] = $newCat;

        $json = json_encode($res);

        return $json;
    }

    /**
     * @param $email
     * @param $password
     * @return null|Model_App_UserEntity
     */
    private static function getUser($email, $password)
    {
        $mapper = new Model_App_UserMapper();
        $mapper->filterBy('email', $email);
        $mapper->filterBy('password', $password);
        $mapper->filterBy('active', 1);
        return $mapper->findOne();
    }

    private static function getPromotionsData(Model_App_UserEntity $user = null)
    {
        $res = array();

        $mapper = new Model_Promocje_PromocjaMapper();
        $mapper->filterBy('aktywna', 1);
        $promocje = $mapper->find();
        $res['promotions'] = array();
        foreach ($promocje as $promocja) {
            $res['promotions'][] = array(
                'id' => $promocja->id,
                'nazwa' => $promocja->nazwa,
                'etapy' => $promocja->getEtapy()
            );
        }

        $mapper = new Model_Promocje_OrderMapper();
        $mapper->filterBy('przedstawicielId', (int)$user->id);
        $orders = $mapper->find();
        $res['orders'] = array();
        foreach ($orders as $order) {
            $res['orders'][] = array(
                'id' => $order->id,
                'statusId' => $order->statusId,
                'addressId' => $order->addressId,
                'promocjaId' => $order->promocjaId,
                'przedstawicielId' => $order->przedstawicielId,
                'dystrybutorId' => $order->dystrybutorId
            );
        }

        $mapper = new Model_Promocje_OrderStatusMapper();
        $statuses = $mapper->getAll();
        $res['orders_statuses'] = array();
        foreach ($statuses as $status) {
            $res['orders_statuses'][] = array(
                'id' => $status->id,
                'nazwa' => $status->nazwa
            );
        }

        $mapper = new Model_Promocje_AdresMapper();
        $res['addresses'] = $mapper->find();

        return $res;
    }

    private static function getAllCategories()
    {
        $katMapper = new Model_KategoriaProdukt();
        $katMapper->filtr_jezyk_id = 1;
        $katMapper->filtr_aktywna = 1;
        $katMapper->filtr_sortuj_po = 'miejsce';
        $katMapper->filtr_sortuj_jak = 'ASC';
        $katMapper->filtruj();

        $res = array();
        foreach ($katMapper->rekordy as $katId) {
            $kat = new Model_KategoriaProdukt($katId);
            $res[] = array(
                'id' => (int)$kat->id,
                'parentId' => (int)$kat->id_nadrzedna,
                'viewType' => $kat->view_type,
                'name' => $kat->nazwa[1],
                'textColor' => $kat->kolor_tekst,
                'backgroundColor' => $kat->kolor_tlo
            );
        }
        return $res;
    }

    private static function getAllProducts()
    {
        $produktMapper = new Model_Produkt();
        $produktMapper->filtr_jezyk_id = 1;
        $produktMapper->filtr_aktywny = 1;
        $produktMapper->filtr_sortuj_po = 'miejsce';
        $produktMapper->filtr_sortuj_jak = 'ASC';
        $produktMapper->filtrujRekordy();

        $res = array();
        foreach ($produktMapper->rekordy as $produktId) {
            $produkt = new Model_Produkt($produktId);

            $categoryFullPathName = Model_KategoriaProdukt::getCategoryFullPathName($produkt->kategoria_id);

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

            $nameSafeCharacters = preg_replace('/[^A-Za-z0-9ąĄćĆęĘłŁńŃóÓśŚżŻźŹ& _ .-]/', '', strip_tags($produkt->nazwa[1]));

            $res[] = array(
                'id' => $produkt->id,
                'template' => $template,
                'name' => $produkt->nazwa[1],
                'nameSafeCharacters' => $nameSafeCharacters,
                'serverDir' => 'images/produkt/',
                'image' => $imageBig,
                'imageSize' => $imageBigSize,
                'imageSmall' => $imageSmall,
                'imageSmallSize' => $imageSmallSize,
                'imageUrl' => $urlDuzegoZdjecia,
                'categoryFullPathName' => $categoryFullPathName,
                'barcode' => '',
                'description' => self::getProductDescription($produkt),
                'description2' => $opis
            );
        }
        return $res;
    }

    // TODO: deprecated
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

    // TODO: deprecated
    private static function getProducts($katId)
    {
        $categoryFullPathName = Model_KategoriaProdukt::getCategoryFullPathName($katId);

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

            $nameSafeCharacters = preg_replace('/[^A-Za-z0-9ąĄćĆęĘłŁńŃóÓśŚżŻźŹ& _ .-]/', '', strip_tags($produkt->nazwa[1]));

            $res[] = array(
                'id' => $produkt->id,
                'template' => $template,
                'name' => $produkt->nazwa[1],
                'nameSafeCharacters' => $nameSafeCharacters,
                'serverDir' => 'images/produkt/',
                'image' => $imageBig,
                'imageSize' => $imageBigSize,
                'imageSmall' => $imageSmall,
                'imageSmallSize' => $imageSmallSize,
                'imageUrl' => $urlDuzegoZdjecia,
                'categoryFullPathName' => $categoryFullPathName,
                'barcode' => '',
                'description' => self::getProductDescription($produkt),
                'description2' => $opis
            );
        }
        return $res;
    }

    private static function getProductDescription(Model_Produkt $produkt)
    {
        $desc = '';
        if (trim($produkt->ean) != '') {
            $desc .= '<b>EAN produktu</b>: ' . $produkt->ean . '<br />';
        }
        if (trim($produkt->sztuk_w_opakowaniu) != '') {
            $desc .= '<b>Liczba sztuk w opakowaniu</b>: ' . $produkt->sztuk_w_opakowaniu . '<br />';
        }
        if (trim($produkt->ean_opakowania) != '') {
            $desc .= '<b>EAN opakowania</b>: ' . $produkt->ean_opakowania . '<br />';
        }
        if (trim($produkt->pkwiu) != '') {
            $desc .= '<b>PKWIU</b>: ' . $produkt->pkwiu . '<br />';
        }
        foreach ($produkt->atrybuty as $atrybut) {
            $desc .= '<b>' . $atrybut->nazwa . '</b>: ' . $atrybut->wartosc . '<br />';
        }
        return $desc;
    }

    private static function getPdfs($katId)
    {
        $categoryFullPathName = Model_KategoriaProdukt::getCategoryFullPathName($katId);

        $res = array();
        $pdfMapper = new Model_Mapper_Pdf();
        $pdfMapper->filterBy('kategoria_id', $katId);
        $pdfs = $pdfMapper->find();
        foreach ($pdfs as $pdf) {
            $res[] = self::getPdf($pdf, $categoryFullPathName);
        }
        return $res;
    }

    private static function getPdf(Model_DataObject_Pdf $pdf, $categoryFullPathName)
    {
        $thumb = self::getPdfThumb($pdf);
        $res = array(
            'id' => $pdf->id,
            'name' => $pdf->nazwa,
            'serverDir' => 'images/pdf/',
            'categoryFullPathName' => $categoryFullPathName,
            'thumb' => $thumb,
            'thumbSize' => filesize(Core_Config::get('images_path') . 'pdf/' . $thumb),
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

    private static function getPdfThumb(Model_DataObject_Pdf $pdf)
    {
        return $pdf->id . '/thumbs/1.png';
    }

}

