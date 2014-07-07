<?php

class Model_DataObject_Pdf
{
    public $id = 0;
    public $kategoria_id = 0;
    public $nazwa = '';
    public $active = 1;
    public $liczba_stron = 0;
    public $data_utworzenia = '';
    public $data_aktualizacji = '';

    public $_files = array();

    public function setFiles($files)
    {
        $this->_files = $files;
    }

    public function getPageImagesPaths()
    {
        $imagesPaths = array();
        $dir = Core_Config::get('images_path') . 'pdf/' . $this->id . '/pages';
        for ($i = 0; $i < $this->liczba_stron; $i++) {
            $imagesPaths[] = $dir . '/' . ($i + 1) . '.png';
        }
        return $imagesPaths;
    }

    public function getFirstPageImagePath()
    {
        $dir = Core_Config::get('images_path') . 'pdf/' . $this->id . '/pages';
        return $dir . '/1.png';
    }
}
