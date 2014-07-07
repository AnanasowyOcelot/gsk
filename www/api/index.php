<?php
error_reporting(E_ALL);

echo 'aaaaaaa';
exit();

/********************************************************************************************/
/****************************** DEFINICJE KLAS ******************************************/
/********************************************************************************************/
require_once('../../application/core/Config.php');
Core_Config::loadIni('../../application/configs/app.ini');
Core_Config::loadIni('../../application/configs/appPage.ini');

require_once(Core_Config::get('libs_path') . 'smarty/libs/Smarty.class.php');
require_once(Core_Config::get('libs_path') . 'adodb5/adodb-exceptions.inc.php');
require_once(Core_Config::get('libs_path') . 'adodb5/adodb.inc.php');
require_once(Core_Config::get('libs_path') . 'phpmailer/class.phpmailer.php');

/*************************************************************************************************/
/************************************ AUTOLOADER  *******************************************/
/*************************************************************************************************/
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

$o_jezyk = new Model_Jezyk(1);
if ((int)$o_jezyk->id > 0) {
    $routeMatch['jezyk_id'] = $o_jezyk->id;
    Core_Config::set("jezyk_id", $o_jezyk->id);
    Core_Config::set("jezyk_skrot", $o_jezyk->skrot);
} else {
    $routeMatch['jezyk_id'] = 1;
    Core_Config::set("jezyk_id", 1);
    Core_Config::set("jezyk_skrot", 'pl');
}

$version = '1.0';
if (isset($_GET['v'])) {
    $version = $_GET['v'];
}

$json = '';
switch ($version) {
    case '2.28':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot28::generujJson($_POST);
        break;
    case '2.27':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot27::generujJson($_POST);
        break;
    case '2.26':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot26::generujJson($_POST);
        break;
    case '2.25':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot25::generujJson($_POST);
        break;
    case '2.24':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot24::generujJson($_POST);
        break;
    case '2.23':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot23::generujJson($_POST);
        break;
    case '2.22':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot22::generujJson($_POST);
        break;
    case '2.21':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot21::generujJson($_POST);
        break;
    case '2.20':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot20::generujJson($_POST);
        break;
    case '2.19':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot19::generujJson($_POST);
        break;
    case '2.18':
    case '2.17':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot17::generujJson($_POST);
        break;
    case '2.16':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot16::generujJson($_POST);
        break;
    case '2.15':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot15::generujJson($_POST);
        break;
    case '2.14':
    case '2.13':
    case '2.12':
    case '2.11':
    case '2.10':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot10::generujJson();
        break;
    case '2.9':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot9::generujJson();
        break;
    case '2.8':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot8::generujJson();
        break;
    case '2.7':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot7::generujJson();
        break;
    case '2.6':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot6::generujJson();
        break;
    case '2.5':
        $json = 'appData = ' . Model_Api_AppDataGeneratorV2dot5::generujJson();
        break;
    default:
        $json = Model_Api_AppDataGenerator::generujJson();
        break;
}

echo $json;
