<?php

class TestProduktImportExcel extends UnitTestCase
{
	const NUMER_WIERSZA_Z_NAZWAMI_KOLUMN = 3;

	private $importedFilePath = null;

	/**
	 * @var Model_ProduktImportExcel
	 */
	private $importer;

	public function setUp()
	{
		$this->importedFilePath = Core_Config::get('server_path') . 'testdata/Asortyment GSK OHC opisy marketingowe listopad 2013.xlsx';
		$this->importer         = new Model_ProduktImportExcel($this->importedFilePath);
	}

	function tearDown() {

	}

	public function testDataFileExists()
	{
		$this->assertTrue(file_exists($this->importedFilePath));
	}

	public function testFilePathSet()
	{
		$this->assertEqual($this->importedFilePath, $this->importer->getFilePath());
	}

	public function testPHPExcelCreated()
	{
		$this->assertTrue($this->importer->getPHPExcel() instanceof PHPExcel);
	}

	public function testReadDescriptionRow()
	{
		$rowTestData = array(
			'KOD KRESKOWY SZTUKI',
			'NAZWA PRODUKTU',
			'OPIS MARKETINGOWY',
			'ZDJĘCIE (EAN W NAZWIE)'
		);

		$row = $this->importer->getRow(self::NUMER_WIERSZA_Z_NAZWAMI_KOLUMN);
		$this->assertEqual($row, $rowTestData);
	}

	public function testGetColumnNumbers()
	{
		$testData = array(
			'KOD KRESKOWY SZTUKI'    => 0,
			'NAZWA PRODUKTU'         => 1,
			'OPIS MARKETINGOWY'      => 2,
			'ZDJĘCIE (EAN W NAZWIE)' => 3
		);

		$colNumbers = $this->importer->getColumnNumbers(self::NUMER_WIERSZA_Z_NAZWAMI_KOLUMN);
		$this->assertEqual($colNumbers, $testData);
	}

	public function testGetProductFromRow()
	{
		$jezykId = 1;
		$testRow = array(
			'5908311862360',
			'Pasta Aquafresh Fresh&Minty 50ml',
			'Pasta Aquafresh Fresh&Minty 50ml ma specjalnie opracowaną formułę, tak, aby zapewnić zdrowe dziąsła, mocne zęby i świeży oddech. Specjalna formuła chroni szkliwo i zapobiega utracie minerałów. Wysoka zawartość fluorku (1450 ppm) chroni przed próchnicą. ',
			'tak'
		);
		$produkt = new Model_Produkt();

		$product = $this->importer->fillProductFromRow($produkt, $testRow);

		$this->assertTrue($product instanceof Model_Produkt);
		$this->assertEqual($product->ean, $testRow[0]);
		$this->assertEqual($product->nazwa[$jezykId], $testRow[1]);
		$this->assertEqual($product->opis[$jezykId], $testRow[2]);
	}

	public function testImportProducts()
	{
		$this->assertEqual(0, $this->importer->getNumberOfImportedProducts());
		$this->importer->importProducts(false);
		$this->assertEqual(59, $this->importer->getNumberOfImportedProducts());
	}
}
