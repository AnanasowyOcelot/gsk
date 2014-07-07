<?php

class Model_Api_AppDataGeneratorV2dot26
{
    public static function generujJson($postData)
    {
        ob_start();

        $res = array();

        $res['apiVersion'] = '2.26';
        $res['okMessage'] = 'Aktualizacja zakończona pomyślnie.';

        $user = null;
        $res['user'] = null;
        if (isset($postData['login']) && isset($postData['password'])) {
            if ($postData['login'] != '' && $postData['password'] != '') {
                $user = self::getUser($postData['login'], $postData['password']);
            }
        }
        if ($user !== null) {
            $res['status'] = 'OK';
            $res['errorMessage'] = '';
            $res['user'] = array(
                'id' => $user->id,
                'name' => $user->name,
                'supervisor_id' => $user->supervisor_id
            );

            if (isset($postData['newPassword'])) {
                if (trim($postData['newPassword']) != '') {
                    $user->password = $postData['newPassword'];
                    $mapper = new Model_App_UserMapper();
                    $mapper->save($user);

                    $res['okMessage'] .= '<br /><br />Zmiana hasła zakończona pomyślnie.';
                }
            }

            $logEntry = new Model_App_Log();
            $logEntry->activity = 'aktualizacja';
            $logEntry->userId = $user->id;
            $logEntry->apiVersion = $res['apiVersion'];
            $logEntry->appVersion = $postData['appVersion'];
            $mapperLog = new Model_App_LogMapper();
            $mapperLog->save($logEntry);

            if (isset($postData['promotions'])) {
                $addressIds = array();
                if (isset($postData['promotions']['addresses'])) {
                    $addressesData = $postData['promotions']['addresses'];
                    $addressMapper = new Model_Mapper_Adres();
                    foreach ($addressesData as $addressData) {
                        if (isset($addressData['remoteId']) && (int)$addressData['remoteId'] == 0) {
                            $address = new Model_DataObject_Adres();
                            $address = $addressMapper->fromArray($addressData, $address);
                            $address->id = 0;
                            $address->aktywny = 1;
                            $addressMapper->save($address);

                            $addressIds[$addressData['id']] = $address->id;
                        }
                    }
                }

                if (isset($postData['promotions']['orders'])) {
                    $ordersData = $postData['promotions']['orders'];
                    foreach ($ordersData as $orderData) {
                        if (isset($orderData['remoteId'])) {
                            $addressId = $orderData['addressRemoteId'];
                            if ((int)$addressId == 0) {
                                if (isset($addressIds[$orderData['addressId']])) {
                                    $addressId = $addressIds[$orderData['addressId']];
                                }
                            }

                            $order = null;
                            $orderMapper = new Model_Promocje_OrderMapper();
                            if ((int)$orderData['remoteId'] == 0) {
                                $orderMapper->filterBy('clientAppId', $orderData['clientAppId']);
                                $orderMapper->filterBy('przedstawicielId', $orderData['przedstawicielId']);
                                $order = $orderMapper->findOne();
                                if($order === null) {
                                    $order = new Model_Promocje_OrderEntity();
                                }
                            } else {
                                $order = $orderMapper->findOneById((int)$orderData['remoteId']);
                            }

                            if($order !== null) {
                                $order->clientAppId = $orderData['clientAppId'];
                                $order->przedstawicielId = $user->id;

                                if($orderData['statusId'] == Model_Promocje_OrderStatusMapper::STATUS_POTWIERDZONE) {
                                    $order->statusId = Model_Promocje_OrderStatusMapper::STATUS_CZEKA_NA_ZATWIERDZENIE;
                                    $supervisor = $user->getSupervisor();
                                    if($supervisor->id > 0) {
                                        $order->nextEditorId = $supervisor->id;
                                        $order->nextEditorName = $supervisor->name;
                                    } else {
                                        $order->nextEditorId = 0;
                                        $order->nextEditorName = 'administrator';
                                    }
                                } elseif($orderData['statusId'] == Model_Promocje_OrderStatusMapper::STATUS_DO_POPRAWY) {
                                    $order->statusId = Model_Promocje_OrderStatusMapper::STATUS_CZEKA_NA_ZATWIERDZENIE;

                                    $userMapper = new Model_App_UserMapper();
                                    $u = $userMapper->findOneById($orderData['przedstawicielId']);
                                    $order->nextEditorId = $u->id;
                                    $order->nextEditorName = $u->name;
                                } elseif($orderData['statusId'] == Model_Promocje_OrderStatusMapper::STATUS_ODRZUCONE) {
                                    $order->statusId = Model_Promocje_OrderStatusMapper::STATUS_ODRZUCONE;

                                    $order->nextEditorId = 0;
                                    $order->nextEditorName = '';
                                }

                                $order->promotionId = $orderData['promotionId'];
                                $order->addressId = $addressId;
                                if (isset($orderData['items'])) {
                                    $order->items = $orderData['items'];
                                }
                                $orderMapper->save($order);
                            }
                        }
                    }
                }
            }
        } else {
            $res['status'] = 'ERROR';
            $res['errorMessage'] = 'Nieprawidłowy login, hasło, użytkownik nieaktywny lub błąd synchronizacji.';
        }

        $res['asortymentSieci'] = self::getAsortymentSieci();

        $res['categoryType'] = array(
            'VIEW_PRODUCTS' => Model_KategoriaProdukt::VIEW_PRODUCTS,
            'VIEW_TILES' => Model_KategoriaProdukt::VIEW_TILES,
            'MODULE_PROMOTIONS' => Model_KategoriaProdukt::MODULE_PROMOTIONS,
            'MODULE_ASORTYMENT_SIECI' => Model_KategoriaProdukt::MODULE_ASORTYMENT_SIECI
        );

        if ($res['status'] == 'OK') {
            $res['promotions'] = self::getPromotionsData($user);
            $res['categories'] = self::getAllCategories();
            $res['products'] = self::getAllProducts();

            $res['pictures'] = self::getPictures();

            $res['productsData'] = self::getSubcategories(0, $user);
        } else {
            $res['promotions'] = array();
            $res['categories'] = array();
            $res['products'] = array();
            $res['pictures'] = array();

            $res['productsData'] = array();
        }

        $debug = ob_get_contents();
        ob_end_clean();
        $res['debug'] = $debug;

        // json_encode has length limit
        $jsonParts = array();
        foreach ($res as $key => $val) {
            if($key == 'asortymentSieci') {
                $jsonParts[$key] = static::encodeJson($val);
            } else {
                $jsonParts[$key] = json_encode($val);
            }
        }

        $json = static::encodeJson($jsonParts);

        return $json;
    }

    private static function encodeJson(array $data) {
        $json = '';
        $json .= '{';
        $partNum = 0;
        foreach ($data as $key => $val) {
            if ($partNum > 0) {
                $json .= ',
';
            }
            $partNum++;
            if(is_array($val)) {
                $json .= '"' . $key . '": ' . json_encode($val);
            } else {
                $json .= '"' . $key . '": ' . $val;
            }
        }
        $json .= '}';
        return $json;
    }

    private static function getPictures()
    {
        $pictures = array();

        $mapperKlient = new Model_AsortymentSieci_KlientMapper();
        $klienci = $mapperKlient->find();
        foreach ($klienci as $klient) {
            $imageBig = 'Model_AsortymentSieci_KlientEntity/p_0/2/' . $klient->id . '.png';
            $imageSmall = 'Model_AsortymentSieci_KlientEntity/p_0/1/' . $klient->id . '.png';

            $dir = Core_Config::get('images_path') . '';
            $imageBigSize = filesize($dir . $imageBig);
            $imageSmallSize = filesize($dir . $imageSmall);

            $pictures[] = array(
                'serverDir' => 'images/',
                'image' => $imageBig,
                'imageSize' => $imageBigSize,
                'imageSmall' => $imageSmall,
                'imageSmallSize' => $imageSmallSize
            );
        }

        return $pictures;
    }

    private static function getAsortymentSieci()
    {
        $asortymentSieci = array();
        $mapperKlient = new Model_AsortymentSieci_KlientMapper();
        $mapperAsortymentProdukt = new Model_AsortymentSieci_ProduktMapper();
        $klienci = $mapperKlient->find();
        foreach ($klienci as $klient) {
            $values = $mapperKlient->getValues($klient->id);
            $produkty = array();
            foreach ($values as $value) {
                $asortymentProdukt = $mapperAsortymentProdukt->findOneById($value['produkt_id']);
                if((int)$asortymentProdukt->produktId == 0) {
                    $produkt = Model_Produkt::findOneByEan($asortymentProdukt->ean);
                } else {
                    $produkt = new Model_Produkt($asortymentProdukt->produktId);
                }
                $asortymentProdukt->imageSmall = 'z1/1/' . $produkt->zdjecie_1;
                //$produkty[] = $asortymentProdukt;
                $produkty[] = array(
                    'produktId' => $asortymentProdukt->produktId,
                    'nazwaSku' => $asortymentProdukt->nazwaSku,
                    'imageSmall' => $asortymentProdukt->imageSmall
                );
            }
            $asortymentSieci[] = array(
                'klientNazwa' => $klient->nazwa,
                'logoPath' => 'Model_AsortymentSieci_KlientEntity/p_0/1/' . $klient->id . '.png',
                'produkty' => $produkty
            );
        }
        return $asortymentSieci;
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
        /*$mapper->filterBy('przedstawicielId', (int)$user->id);
        $orders = $mapper->find();
        $res['orders'] = array();
        foreach ($orders as $order) {
            $res['orders'][] = $order;
        }*/
        $res['orders'] = $mapper->findAllForUser($user);

        $mapper = new Model_Promocje_OrderStatusMapper();
        $statuses = $mapper->getAll();
        $res['orders_statuses'] = array();
        foreach ($statuses as $status) {
            $res['orders_statuses'][] = array(
                'id' => $status->id,
                'nazwa' => $status->nazwa
            );
        }

        $mapper = new Model_Mapper_Adres();
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

            $nameSafeCharacters = preg_replace(
                '/[^A-Za-z0-9ąĄćĆęĘłŁńŃóÓśŚżŻźŹ& _ .-]/',
                '',
                strip_tags($produkt->nazwa[1])
            );

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
    private static function getSubcategories($katNadrzednaId, $user)
    {
        $katMapper = new Model_KategoriaProdukt();
        $katMapper->filtr_jezyk_id = 1;
        $katMapper->filtr_aktywna = 1;
        $katMapper->filtr_id_nadrzedna = $katNadrzednaId;
        $katMapper->filtr_sortuj_po = 'miejsce';
        $katMapper->filtr_sortuj_jak = 'ASC';
        $katMapper->filtruj();

        $categories = array();
        foreach ($katMapper->rekordy as $katId) {
            $categories[] = new Model_KategoriaProdukt($katId);
        }

        $filteredCategories = Model_Tag_PermissionsService::filter($categories, $user);

        $res = array();
        foreach ($filteredCategories as $kat) {
            $res[] = array(
                'categoryInfo' => array(
                    'id' => $kat->id,
                    'parentId' => $kat->id_nadrzedna,
                    'viewType' => $kat->view_type,
                    'name' => $kat->nazwa[1],
                    'textColor' => $kat->kolor_tekst,
                    'backgroundColor' => $kat->kolor_tlo
                ),
                'products' => self::getProducts($kat->id, $user),
                'pdfs' => self::getPdfs($kat->id, $user),
                'subCategories' => self::getSubcategories($kat->id, $user)
            );
        }
        return $res;
    }

    // TODO: deprecated
    private static function getProducts($katId, $user)
    {
        $categoryFullPathName = Model_KategoriaProdukt::getCategoryFullPathName($katId);

        $produktMapper = new Model_Produkt();
        $produktMapper->filtr_jezyk_id = 1;
        $produktMapper->filtr_aktywny = 1;
        $produktMapper->filtr_kategoria_id = $katId;
        $produktMapper->filtr_sortuj_po = 'miejsce';
        $produktMapper->filtr_sortuj_jak = 'ASC';
        $produktMapper->filtrujRekordy();

        $products = array();
        foreach ($produktMapper->rekordy as $produktId) {
            $products[] = new Model_Produkt($produktId);
        }

        $filteredProducts = Model_Tag_PermissionsService::filter($products, $user);

        $res = array();
        foreach ($filteredProducts as $produkt) {
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

    private static function getPdfs($katId, $user)
    {
        $categoryFullPathName = Model_KategoriaProdukt::getCategoryFullPathName($katId);

        $res = array();
        $pdfMapper = new Model_Mapper_Pdf();
        $pdfMapper->filterBy('kategoria_id', $katId);
        $pdfMapper->filterBy('active', 1);
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
