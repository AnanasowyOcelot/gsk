<?php
error_reporting(E_ALL);
require_once('../libs/simpletest/autorun.php');
require_once('initCMS.php');

class AllTests extends TestSuite {
	function AllTests() {
		$this->TestSuite('All tests');
		$this->addFile('model/TestProdukt.php');
		//$this->addFile('model/TestProduktImportExcel.php');
		$this->addFile('model/TestTagPermissions.php');
	}
}
