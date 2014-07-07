<?php
error_reporting(E_ALL);
ob_start();
require_once('../../application/core/Config.php');
Core_Config::loadIni('../../application/configs/app.ini');
Core_Config::loadIni('../../application/configs/appPage.ini');

require_once(Core_Config::get('libs_path') . 'adodb5/adodb-exceptions.inc.php');
require_once(Core_Config::get('libs_path') . 'adodb5/adodb.inc.php');

function autoloader($s_className)
{
    $a_tmp = explode('_', $s_className);
    $ilosc = count($a_tmp);
    $s_sciezka = '';
    for ($i = 0; $i < $ilosc - 1; $i++) {
        $s_sciezka .= strtolower($a_tmp[$i]) . '/';
    }
    $s_className = $a_tmp[$ilosc - 1];

    $s_sciezkaPliku = Core_Config::get('application_path') . $s_sciezka . $s_className . '.php';
    if (file_exists($s_sciezkaPliku)) {
        require_once $s_sciezkaPliku;
    } else {
        $s_sciezkaPliku = Core_Config::get('modules_path') . $s_sciezka . $s_className . '.php';
        if (file_exists($s_sciezkaPliku)) {
            require_once $s_sciezkaPliku;
        }
    }
}

spl_autoload_register('autoloader');

$code = $_GET['c'];
$action = $_GET['a'];

if ($action == 'download') {
    $imgPath = Core_Config::get('images_path') . 'produkt/' . Model_ZdjecieKod::getPathFromCode($code);
    $zdjecie = new Core_Zdjecie();
    //$image = $zdjecie->imageCreateFromFile($imgPath);
    $image = file_get_contents($imgPath);

    ob_end_clean();
    header("Content-type: image/png");
    header('Content-Disposition: attachment; filename="' . basename($imgPath) . '"');
    //imagepng($image);
    echo $image;
} else {
    ob_end_clean();
    $imgUrl = Core_Config::get('www_url') . 'www/page/img.php?a=download&c=' . $code;


    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>GSK</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta http-equiv="Pragma" content="no-cache"/>
        <meta http-equiv="Cache-Control" content="no-cache"/>
        <meta http-equiv="Expires" content="Sat, 01 Dec 2001 00:00:00 GMT"/>
    </head>
    <body>

    <a href="<?php echo $imgUrl; ?>">pobierz zdjęcie</a>

    <div style="margin: 15px 0 0 0;">
        <label>Adres do skopiowania:
            <input onClick="this.select();" style="width: 400px;" type="text" value="<?php echo $imgUrl; ?>"/>
        </label>
    </div>

    </body>
    </html>
<?php
}
