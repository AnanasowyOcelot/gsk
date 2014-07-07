<?php

class Model_Mapper_Pdf extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'pdf';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_DataObject_Pdf';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function getPrimaryKey()
    {
        return 'id';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'kategoria_id' => array('kategoria_id', Core_Mapper::T_INT),
            'nazwa' => array('nazwa', Core_Mapper::T_VARCHAR),
            'active' => array('active', Core_Mapper::T_INT),
            'liczba_stron' => array('liczba_stron', Core_Mapper::T_INT),
            'data_utworzenia' => array('data_utworzenia', Core_Mapper::T_DATETIME),
            'data_aktualizacji' => array('data_aktualizacji', Core_Mapper::T_DATETIME)
        );
    }

    public function save(Model_DataObject_Pdf $o)
    {
        if ($o->id == 0) {
            parent::save($o);
        }

        $files = $o->_files;
        if (count($files) > 0) {
            foreach ($files as $nazwa => $dane) {
                if ($dane['tmp_name'] != "") {
                    $liczbaStron = 0;
                    $tmpPath = $dane['tmp_name'];

                    $dir = Core_Config::get('images_path') . 'pdf/' . $o->id;
                    $fileDir = $dir . '/file';
                    $pagesDir = $dir . '/pages';
                    $thumbDir = $dir . '/thumbs';

                    Core_Narzedzia::makeDirIfNotExists($dir);
                    Core_Narzedzia::makeDirIfNotExists($fileDir);
                    Core_Narzedzia::makeDirIfNotExists($pagesDir);
                    Core_Narzedzia::makeDirIfNotExists($thumbDir);

                    $pdfFilePath = $fileDir . '/file.pdf';
                    move_uploaded_file($tmpPath, $pdfFilePath);

                    $fp_pdf = fopen($pdfFilePath, 'rb');
                    $img = new imagick();
                    $img->setResolution(200, 200);
                    $img->readImageFile($fp_pdf);
                    $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
                    $params = $img->identifyImage();
                    $img->setPage($params['geometry']['width'], $params['geometry']['height'], 0, 0);
                    $img->setImageFormat("png");
                    $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
                    $liczbaStron = $img->getNumberImages();
                    $count = $liczbaStron;
                    for ($x = 1; $x <= $liczbaStron; $x++) {
                        $path = $pagesDir . '/' . $count . '.png';
                        $img->writeImage($path);
                        $img->previousImage();
                        $count--;
                    }
                    $o->liczba_stron = $liczbaStron;

                    $thumbSize = array(
                        'szerokosc' => '110',
                        'wysokosc' => '90'
                    );
                    $thumbPath = $thumbDir . '/1.png';
                    $firstPagePath = $pagesDir . '/1.png';
                    Core_Zdjecie::tworz_miniaturke(
                        $firstPagePath,
                        $thumbPath,
                        $thumbSize['szerokosc'],
                        $thumbSize['wysokosc']
                    );
                }
            }
        }

        parent::save($o);
    }
}
