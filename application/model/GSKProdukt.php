<?php

// TODO: nazwa klasy do zmiany
class Model_GSKProdukt
{
    public static function generujJSON()
    {
        $json = '
categoryType = {
    VIEW_PRODUCTS: "' . Model_KategoriaProdukt::VIEW_PRODUCTS . '",
    VIEW_TILES: "' . Model_KategoriaProdukt::VIEW_TILES . '"
};
		';
        $json .= '
productsData = [];
		';

        $katMapper = new Model_KategoriaProdukt();
        $katMapper->filtr_jezyk_id = 1;
        $katMapper->filtr_aktywna = 1;
        $katMapper->filtr_id_nadrzedna = 0;
        $katMapper->filtr_sortuj_po = 'miejsce';
        $katMapper->filtr_sortuj_jak = 'ASC';
        $katMapper->filtruj();

        foreach ($katMapper->rekordy as $katId) {
            $kat = new Model_KategoriaProdukt($katId);

            $json .= '
productsData.push({
    categoryInfo: {
        id: "' . $kat->id . '",
        parentId: "' . $kat->id_nadrzedna . '",
        viewType: "' . $kat->view_type . '",
        name: "' . $kat->nazwa[1] . '",
        textColor: "' . $kat->kolor_tekst . '",
        backgroundColor: "' . $kat->kolor_tlo . '"
    },
    products: [
        ' . self::getJsonForProducts($katId) . '
    ],
    subCategories: [
		' . self::getJsonForSubcategories($katId) . '
    ]
});
		';
        }

        $dir = Core_Config::get('images_path') . 'produkt/';
        $file = $dir . 'products.js';

        file_put_contents($file, $json);
    }

    private static function getJsonForSubcategories($katNadrzednaId)
    {
        $katMapper = new Model_KategoriaProdukt();
        $katMapper->filtr_jezyk_id = 1;
        $katMapper->filtr_aktywna = 1;
        $katMapper->filtr_id_nadrzedna = $katNadrzednaId;
        $katMapper->filtr_sortuj_po = 'miejsce';
        $katMapper->filtr_sortuj_jak = 'ASC';
        $katMapper->filtruj();

        $jsonArray = array();

        foreach ($katMapper->rekordy as $katId) {
            $kat = new Model_KategoriaProdukt($katId);

            $jsonArray[] = '
			{
                categoryInfo: {
                    id: "' . $kat->id . '",
                    parentId: "' . $kat->id_nadrzedna . '",
                    viewType: "' . $kat->view_type . '",
                    name: "' . $kat->nazwa[1] . '",
                    textColor: "' . $kat->kolor_tekst . '",
                    backgroundColor: "' . $kat->kolor_tlo . '"
                },
				id: "' . $kat->id . '",
				category: "' . $kat->nazwa[1] . '",
				products: [
					' . self::getJsonForProducts($katId) . '
				],
                subCategories: [
                    ' . self::getJsonForSubcategories($katId) . '
                ]
			}
			';
        }

        $json = implode(', ', $jsonArray);

        return $json;
    }

    private static function getJsonForProducts($katId)
    {
        $produktMapper = new Model_Produkt();
        $produktMapper->filtr_jezyk_id = 1;
        $produktMapper->filtr_aktywny = 1;
        $produktMapper->filtr_kategoria_id = $katId;
        $produktMapper->filtr_sortuj_po = 'miejsce';
        $produktMapper->filtr_sortuj_jak = 'ASC';
        $produktMapper->filtrujRekordy();

        $jsonArray = array();

        foreach ($produktMapper->rekordy as $produktId) {
            $produkt = new Model_Produkt($produktId);

            $imageBig = 'z1/2/' . $produkt->zdjecie_1;
            $imageSmall = 'z1/1/' . $produkt->zdjecie_1;
            $template = '';
            if ($produkt->typ == 2) {
                $template = 'promocje';
                $imageBig = 'z1/4/' . $produkt->zdjecie_1;
                $imageSmall = 'z1/3/' . $produkt->zdjecie_1;
            }

            $biggestImagePath = 'z1/4/' . $produkt->zdjecie_1;
            $kodZdjecia = Model_ZdjecieKod::getPictureCode($biggestImagePath);
            $urlDuzegoZdjecia = '';
            if(file_exists(Core_Config::get('images_path') . 'produkt/' . $biggestImagePath)) {
                $urlDuzegoZdjecia = Core_Config::get('www_url') . 'www/page/img.php?c=' . $kodZdjecia;
            }

            $dir = Core_Config::get('images_path') . 'produkt/';
            $imageBigSize = filesize($dir . $imageBig);
            $imageSmallSize = filesize($dir . $imageSmall);

            $opis = $produkt->opis[1];
            $opis = str_replace("
", ' ', $opis);

            $jsonArray[] = '
			{
				id: "' . $produkt->id . '",
				template: "' . $template . '",
				name: "' . $produkt->nazwa[1] . '",
				image: "' . $imageBig . '",
				imageSize: "' . $imageBigSize . '",
				imageSmall: "' . $imageSmall . '",
				imageSmallSize: "' . $imageSmallSize . '",
				imageUrl: "' . $urlDuzegoZdjecia . '",
				barcode: "",
				description: "<b>EAN produktu</b>: ' . $produkt->ean . '<br />" +
					"<b>Liczba sztuk w opakowaniu</b>: ' . $produkt->sztuk_w_opakowaniu . '<br />" +
					"<b>EAN opakowania</b>: ' . $produkt->ean_opakowania . '<br />" +
					"<b>PKWIU</b>: ' . $produkt->pkwiu . '",
				description2: "' . $opis . '"
			}
			';
        }

        $json = implode(', ', $jsonArray);

        return $json;
    }
}

