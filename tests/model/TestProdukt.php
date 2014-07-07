<?php

class TestProdukt extends UnitTestCase
{
	public function testFromArray()
	{
		$arrayData = array(
			'id'                 => 23,
			'kategoria_id'       => 124,
			'nazwa'              => array(
				'1' => 'ąęśćżź!@#$%^&*()',
				'2' => 'qwertyuiop'
			),
			'nazwa_dluga'        => array(
				'1' => 'Lorem ipsum dolor sit amet enim.',
				'2' => 'tiam ullamcorper. Suspendisse a pellentesque dui, non felis.'
			),
			'ean'                => '987654321',
			'ean_opakowania'     => '123456789',
			'sztuk_w_opakowaniu' => 13,
			'pkwiu'              => '132-456-789',
			'cena_szt'           => 56.78,
			'cena_op'            => 67.89,
			'typ'                => 2,
			'opis'               => array(
				'1' => '<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper.</p> Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula.',
				'2' => 'Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula.',
			),
			'miejsce'            => array(
				'1' => 10,
				'2' => 10
			),
			'aktywny'            => array(
				'1' => 1,
				'2' => 1
			)
		);

		$produkt = new Model_Produkt();
		$produkt->fromArray($arrayData);

		$this->assertEqual($produkt->id, $arrayData['id']);
		$this->assertEqual($produkt->kategoria_id, $arrayData['kategoria_id']);
		$this->assertEqual($produkt->nazwa, $arrayData['nazwa']);
		$this->assertEqual($produkt->nazwa_dluga, $arrayData['nazwa_dluga']);
		$this->assertEqual($produkt->ean, $arrayData['ean']);
		$this->assertEqual($produkt->ean_opakowania, $arrayData['ean_opakowania']);
		$this->assertEqual($produkt->sztuk_w_opakowaniu, $arrayData['sztuk_w_opakowaniu']);
		$this->assertEqual($produkt->pkwiu, $arrayData['pkwiu']);
		$this->assertEqual($produkt->cena_szt, $arrayData['cena_szt']);
		$this->assertEqual($produkt->cena_op, $arrayData['cena_op']);
		$this->assertEqual($produkt->typ, $arrayData['typ']);
		$this->assertEqual($produkt->opis, $arrayData['opis']);
		$this->assertEqual($produkt->miejsce, $arrayData['miejsce']);
		$this->assertEqual($produkt->aktywny, $arrayData['aktywny']);
	}
}
