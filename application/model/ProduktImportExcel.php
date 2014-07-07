<?php

class Model_ProduktImportExcel
{
	const NUMER_WIERSZA_Z_NAZWAMI_KOLUMN = 3;

	private $filePath = '';
	private $PHPExcel;
	private $numberOfProductsImported = 0;
	private $numberOfProductsCreated = 0;
	private $numberOfProductsUpdated = 0;
	private $jezykId = 1;

	/**
	 * @var array
	 */
	private $columnNumbers;

	public function __construct($filePath = '')
	{
		$this->filePath      = $filePath;
		$this->PHPExcel      = PHPExcel_IOFactory::load($this->filePath);
		$this->columnNumbers = $this->getColumnNumbers(self::NUMER_WIERSZA_Z_NAZWAMI_KOLUMN);
	}

	public function getFilePath()
	{
		return $this->filePath;
	}

	public function getPHPExcel()
	{
		return $this->PHPExcel;
	}

	public function getRow($rowNum)
	{
		$result      = array();
		$rowIterator = $this->PHPExcel->getActiveSheet()->getRowIterator($rowNum);
		foreach ($rowIterator as $rowObject) {
			$result = $this->getRowFromCellIterator($rowObject->getCellIterator());
			break;
		}
		return $result;
	}

	private function getRowFromCellIterator(PHPExcel_Worksheet_CellIterator $cellIterator)
	{
        $cellIterator->setIterateOnlyExistingCells(false);
		$row = array();
		foreach ($cellIterator as $cellObject) {
			$row[] = $cellObject->getValue();
		}
		return $row;
	}

	public function getColumnNumbers($rowNum)
	{
		$row    = $this->getRow($rowNum);
		$result = array();
		foreach ($row as $number => $name) {
			$result[$name] = $number;
		}
		return $result;
	}

	/**
	 * @param Model_Produkt $produkt
	 * @param $row
	 * @return Model_Produkt
	 */
	public function fillProductFromRow(Model_Produkt $produkt, $row)
	{
		$produkt->ean                   = $row[$this->columnNumbers['KOD KRESKOWY SZTUKI']];
		$produkt->nazwa[$this->jezykId] = $row[$this->columnNumbers['NAZWA PRODUKTU']];
		$produkt->opis[$this->jezykId]  = $row[$this->columnNumbers['OPIS MARKETINGOWY']];
		return $produkt;
	}

	/**
	 * @param bool $czyZapis
	 */
	public function importProducts($czyZapis = true)
	{
		$this->numberOfProductsImported = 0;
		$this->numberOfProductsCreated  = 0;
		$this->numberOfProductsUpdated  = 0;

		$startingRow = self::NUMER_WIERSZA_Z_NAZWAMI_KOLUMN + 1;
		$rowIterator = $this->PHPExcel->getActiveSheet()->getRowIterator($startingRow);
		foreach ($rowIterator as $rowObject) {
			$row = $this->getRowFromCellIterator($rowObject->getCellIterator());

			$ean = $row[$this->columnNumbers['KOD KRESKOWY SZTUKI']];
			if ($ean != '') {
				$produkt = new Model_Produkt();
				$produkt->pobierzPrzezEan($ean);
				if ($produkt->id > 0) {
					$produkt = $this->fillProductFromRow($produkt, $row);
					if ($czyZapis) {
						$produkt->zapisz();
					}
					$this->numberOfProductsUpdated++;
				}
				$this->numberOfProductsImported++;
			}
		}
	}

	public function getNumberOfImportedProducts()
	{
		return $this->numberOfProductsImported;
	}

	public function getNumberOfUpdatedProducts()
	{
		return $this->numberOfProductsUpdated;
	}

	public function getNumberOfCreatedProducts()
	{
		return $this->numberOfProductsCreated;
	}
}
