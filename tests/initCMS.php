<?php

error_reporting(E_ALL);

ob_start();

$appDir = '../application/';

require_once($appDir . 'core/Config.php');
Core_Config::loadIni($appDir . 'configs/appTest.ini');
Core_Config::loadIni($appDir . 'configs/appCms.ini');

require_once(Core_Config::get('libs_path') . 'smarty/libs/Smarty.class.php');
require_once(Core_Config::get('libs_path') . 'adodb5/adodb-exceptions.inc.php');
require_once(Core_Config::get('libs_path') . 'adodb5/adodb.inc.php');
require_once(Core_Config::get('libs_path') . 'PHPExcel_1.7.9/PHPExcel.php');
require_once(Core_Config::get('libs_path') . 'ckeditor/ckeditor.php');

session_start();

function autoloader($s_className)
{
	$a_tmp     = explode('_', $s_className);
	$ilosc     = count($a_tmp);
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
