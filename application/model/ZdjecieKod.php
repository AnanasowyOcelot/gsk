<?php

class Model_ZdjecieKod
{
    public static function getPictureCode($path)
    {
        $db = Core_DB::instancja();
        $sqlSelect = 'SELECT
                code
            FROM
                zdjecia_kody
            WHERE
                path = "' . mysql_real_escape_string($path) . '"
            ';
        $resultSelect = $db->query($sqlSelect);
        $rows = $resultSelect->GetArray();
        if (count($rows) == 0) {
            $code = self::generateUniquePictureCode();
            self::saveCode($code, $path);
        } else {
            $code = $rows[0]['code'];
        }
        return $code;
    }

    private static function saveCode($code, $path)
    {
        $db = Core_DB::instancja();
        $sqlSelect = 'INSERT INTO
                zdjecia_kody
            SET
                path = "' . mysql_real_escape_string($path) . '",
                code = "' . mysql_real_escape_string($code) . '"
            ';
        $db->query($sqlSelect);
    }

    private static function generateUniquePictureCode()
    {
        $code = md5(uniqid(rand(), true));
        if(self::getPathFromCode($code) !== null) {
            $code = self::generateUniquePictureCode();
        }
        return $code;
    }

    public static function getPathFromCode($code)
    {
        $db = Core_DB::instancja();
        $sqlSelect = 'SELECT
                path
            FROM
                zdjecia_kody
            WHERE
                code = "' . mysql_real_escape_string($code) . '"
            ';
        $resultSelect = $db->query($sqlSelect);
        $rows = $resultSelect->GetArray();
        if (count($rows) == 0) {
            $path = null;
        } else {
            $path = $rows[0]['path'];
        }

        return $path;
    }
}
