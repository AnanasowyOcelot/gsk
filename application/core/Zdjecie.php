<?php
class Core_Zdjecie
{
	public static function tworz_miniaturke($zrodlo, $sciezka, $max_szerokosc = '', $max_wysokosc = '', $imgType = '')
	{
		$functions = array(
			IMAGETYPE_GIF  => 'imagecreatefromgif',
			IMAGETYPE_JPEG => 'imagecreatefromjpeg',
			IMAGETYPE_PNG  => 'imagecreatefrompng'
		);

		$info = @getimagesize($zrodlo);

		if ((int)$max_szerokosc != 0 && (int)$max_wysokosc != 0) {
			$typ = $info[2];

			if (!$functions[$info[2]]) {
				return false;
			}

			if (!function_exists($functions[$info[2]])) {
				return false;
			}

			if (!$img = $functions[$info[2]]($zrodlo)) {
				return false;
			}

			$szerokosc_oryg = imagesx($img);
			$wysokosc_oryg  = imagesy($img);
			if ($szerokosc_oryg > $max_szerokosc) {
				$ratio          = (real)($max_szerokosc / $szerokosc_oryg);
				$nowa_szerokosc = round($szerokosc_oryg * $ratio);
				$nowa_wysokosc  = round($wysokosc_oryg * $ratio);
			} else {
				$nowa_szerokosc = $szerokosc_oryg;
				$nowa_wysokosc  = $wysokosc_oryg;
			}
			if ($nowa_wysokosc > $max_wysokosc) {
				$ratio          = (real)($max_wysokosc / $nowa_wysokosc);
				$nowa_szerokosc = round($nowa_szerokosc * $ratio);
				$nowa_wysokosc  = round($nowa_wysokosc * $ratio);
			}
			if (!$nowa_szerokosc) {
				$nowa_szerokosc = $szerokosc_oryg;
			}
			if (!$nowa_wysokosc) {
				$nowa_wysokosc = $wysokosc_oryg;
			}
			if (file_exists($sciezka)) {
				unlink($sciezka);
			}
			if (!$img_pom = imagecreatetruecolor($nowa_szerokosc, $nowa_wysokosc)) {

				return false;
			}

			switch ($typ) {
				case "3":
					// integer representation of the color black (rgb: 0,0,0)
					$background = imagecolorallocate($img_pom, 255, 255, 255);
					// removing the black from the placeholder
					imagecolortransparent($img_pom, $background);

					// turning off alpha blending (to ensure alpha channel information is preserved, rather than removed (blending with the rest of the image in the form of black))
					imagealphablending($img_pom, false);

					// turning on alpha channel information saving (to ensure the full range of transparency is preserved)
					imagesavealpha($img_pom, true);

					break;
				case "2":
				case "1":
					// integer representation of the color black (rgb: 0,0,0)
					$background = imagecolorallocate($img_pom, 255, 255, 255);
					// removing the black from the placeholder
					imagecolortransparent($img_pom, $background);

					break;
			}

			if (!imagecopyresampled($img_pom, $img, 0, 0, 0, 0, $nowa_szerokosc, $nowa_wysokosc, $szerokosc_oryg, $wysokosc_oryg)) {
				return false;
			}

			if($imgType == 'jpg') {
				if (!ImageJPEG($img_pom, $sciezka, 100)) {
					return false;
				}
			} else if($imgType == 'png') {
				if (!ImagePNG($img_pom, $sciezka, 0)) {
					return false;
				}
			} else {
				if ($typ == 2) {
					if (!ImageJPEG($img_pom, $sciezka, 100)) {
						return false;
					}
				} else if ($typ == 1) {
					if (!ImageGIF($img_pom, $sciezka)) {
						return false;
					}
				} else if ($typ == 3) {
					if (!ImagePNG($img_pom, $sciezka, 0)) {
						return false;
					}
				}
			}
			ImageDestroy($img);
			ImageDestroy($img_pom);
			return true;
		} else {
			if (Core_Zdjecie::kopiuj_zdjecie($zrodlo, $sciezka)) {
				return true;
			} else {
				return false;
			}
		}
	}


	public function kopiuj_zdjecie($zrodlo, $sciezka)
	{
		chmod($zrodlo, 0777);
		if (file_exists($zrodlo)) {
			if (file_exists($sciezka)) {
				if (!unlink($sciezka)) {
					//$this->errors[] = 'Błąd podczas usunąć pliku.';
					return false;
				}
			}
			if (!copy($zrodlo, $sciezka)) {
				//$this->errors[] = 'Nie można skopiować pliku.';
				return false;
			}
		} else {
			return false;
		}
		return true;
	}

	function imageCreateFromFile($path)
	{
		$info = @getimagesize($path);


		if (!$info) {
			return false;
		}
		$functions = array(
			IMAGETYPE_GIF  => 'imagecreatefromgif',
			IMAGETYPE_JPEG => 'imagecreatefromjpeg',
			IMAGETYPE_PNG  => 'imagecreatefrompng',
			IMAGETYPE_WBMP => 'imagecreatefromwbmp',
			IMAGETYPE_XBM  => 'imagecreatefromwxbm',
		);
		if (!$functions[$info[2]]) {
			return false;
		}
		if (!function_exists($functions[$info[2]])) {
			return false;
		}
		return $functions[$info[2]]($path);
	}

	public static function skaluj_do_wymiarow($obrazek, $szerokosc_max, $wysokosc_max)
	{
		$szerokosc_oryg = imagesx($obrazek);
		$wysokosc_oryg  = imagesy($obrazek);
		if ($szerokosc_oryg > $szerokosc_max) {
			$ratio          = (real)($szerokosc_max / $szerokosc_oryg);
			$nowa_szerokosc = $szerokosc_max;
			$nowa_wysokosc  = round($wysokosc_oryg * $ratio);
		} else {
			$nowa_szerokosc = $szerokosc_oryg;
			$nowa_wysokosc  = $wysokosc_oryg;
		}
		if ($nowa_wysokosc > $wysokosc_max) {
			$ratio          = (real)($wysokosc_max / $nowa_wysokosc);
			$nowa_szerokosc = round($nowa_szerokosc * $ratio);
			$nowa_wysokosc  = $wysokosc_max;
		}
		$obrazek_tmp = imagecreatetruecolor($nowa_szerokosc, $nowa_wysokosc);
		imagecopyresampled($obrazek_tmp, $obrazek, 0, 0, 0, 0, $nowa_szerokosc, $nowa_wysokosc, $szerokosc_oryg, $wysokosc_oryg);
		imagedestroy($obrazek);
		return $obrazek_tmp;
	}

	public static function skaluj_do_rozmiaru_pliku($obrazek, $wielkosc_max)
	{
		imagejpeg($obrazek, WWW_SERVER_PATH . 'img/allegro_aukcje/' . 'temp.jpg', 75);
		$obrazek = file_get_contents(WWW_SERVER_PATH . 'img/allegro_aukcje/' . 'temp.jpg');
		unlink(WWW_SERVER_PATH . 'img/allegro_aukcje/' . 'temp.jpg');
		// tutaj uzywamy Base64 recznie, ale nigdzie indziej!
		while (strlen(base64_encode($obrazek)) > $wielkosc_max) {
			$temp    = imagecreatefromstring($obrazek);
			$x       = ceil(0.9 * imagesx($temp));
			$y       = ceil(0.9 * imagesy($temp));
			$obrazek = imagecreatetruecolor($x, $y);
			imagecopyresized($obrazek, $temp, 0, 0, 0, 0, $x, $y, imagesx($temp), imagesy($temp));
			imagejpeg($obrazek, 'temp.jpg', 75);
			$obrazek = file_get_contents('temp.jpg');
			unlink('temp.jpg');
		}
		return $obrazek;
	}

	function dodaj_ramke($obrazek, $ramka_grubosc, $kolor_rgb)
	{
		$szerokosc    = imagesx($obrazek);
		$wysokosc     = imagesy($obrazek);
		$obrazek_nowy = imagecreatetruecolor($szerokosc + 2 * $ramka_grubosc, $wysokosc + 2 * $ramka_grubosc);
		$kolor        = imagecolorallocate($obrazek_nowy, $kolor_rgb[0], $kolor_rgb[1], $kolor_rgb[2]);
		imagefill($obrazek_nowy, 0, 0, $kolor);
		imagecopy($obrazek_nowy, $obrazek, $ramka_grubosc, $ramka_grubosc, 0, 0, $szerokosc, $wysokosc);
		$sciezka = WWW_SERVER_PATH . 'edycja/tmp/allegro_tmp.jpg';
		ImageJPEG($obrazek_nowy, $sciezka, 90);
		imagedestroy($obrazek);
		return $obrazek_nowy;
	}

	function dodaj_watermark($obrazek, $watermark)
	{
		//$watermark = @imagecreatefrompng(WWW_SERVER_PATH.$WatermarkFile)
		imageAlphaBlending($watermark, false);
		imageSaveAlpha($watermark, true);
		$obrazek_nowy    = $obrazek;
		$imageWidth      = imageSX($obrazek_nowy);
		$imageHeight     = imageSY($obrazek_nowy);
		$watermarkWidth  = imageSX($watermark);
		$watermarkHeight = imageSY($watermark);
		$coordinate_X    = ($imageWidth - $watermarkWidth) / 2;
		$coordinate_Y    = ($imageHeight - $watermarkHeight) / 2;
		imagecopy($obrazek_nowy, $watermark, $coordinate_X, $coordinate_Y, 0, 0, $watermarkWidth, $watermarkHeight);
		return $obrazek_nowy;
	}

	function doklej_obrazek_z_lewej($obrazek, $obrazek_doklejany)
	{
		$obrazekWidth    = imageSX($obrazek);
		$obrazekHeight   = imageSY($obrazek);
		$doklejanyWidth  = imageSX($obrazek_doklejany);
		$doklejanyHeight = imageSY($obrazek_doklejany);
		$imageWidth      = imageSX($obrazek) + imageSX($obrazek_doklejany);
		$imageHeight     = max(imageSY($obrazek), imageSY($obrazek_doklejany));
		$obrazek_nowy    = imagecreatetruecolor($imageWidth, $imageHeight);
		$kolor           = imagecolorallocate($obrazek_nowy, 255, 255, 255);
		imagefill($obrazek_nowy, 0, 0, $kolor);
		$coordinate_X = $doklejanyWidth;
		$coordinate_Y = ($imageHeight - $obrazekHeight) / 2;
		imagecopy($obrazek_nowy, $obrazek, $coordinate_X, $coordinate_Y, 0, 0, $obrazekWidth, $obrazekHeight);
		$coordinate_X = 0;
		$coordinate_Y = ($imageHeight - $doklejanyHeight) / 2;
		imagecopy($obrazek_nowy, $obrazek_doklejany, $coordinate_X, $coordinate_Y, 0, 0, $doklejanyWidth, $doklejanyHeight);
		imagedestroy($obrazek_doklejany);
		return $obrazek_nowy;
	}

	function tworz_miniaturke_kadrujac_zdjecie2($zrodlo, $sciezka, $max_szerokosc = '', $max_wysokosc = '')
	{
		$zwroc = true;
		if ((int)$max_szerokosc != 0 && (int)$max_wysokosc != 0) {
			if (!$img = ImageCreateFromJPEG($zrodlo)) {
				$zwroc = false;
			}
			$szerokosc_oryg = imagesx($img);
			$wysokosc_oryg  = imagesy($img);

			$ratio1          = (real)($max_wysokosc / $wysokosc_oryg);
			$ratio2          = (real)($max_szerokosc / $szerokosc_oryg);
			$nowa_szerokosc1 = round($szerokosc_oryg * $ratio1);
			$nowa_wysokosc1  = round($wysokosc_oryg * $ratio1);
			$nowa_szerokosc2 = round($szerokosc_oryg * $ratio2);
			$nowa_wysokosc2  = round($wysokosc_oryg * $ratio2);

			if ($nowa_szerokosc1 >= $max_szerokosc && $nowa_wysokosc1 >= $max_wysokosc) {
				$ratio          = $ratio1;
				$nowa_szerokosc = $nowa_szerokosc1;
				$nowa_wysokosc  = $nowa_wysokosc1;
			} else if ($nowa_szerokosc2 >= $max_szerokosc && $nowa_wysokosc2 >= $max_wysokosc) {
				$ratio          = $ratio2;
				$nowa_szerokosc = $nowa_szerokosc2;
				$nowa_wysokosc  = $nowa_wysokosc2;
			} else {
				echo 'nie dziala';
			}

			/* 
			if($szerokosc_oryg > $wysokosc_oryg)
			{
				$ratio = (real)($max_wysokosc / $wysokosc_oryg);	
				$nowa_szerokosc = round($szerokosc_oryg * $ratio);
				$nowa_wysokosc = round($wysokosc_oryg * $ratio);
			}
			else
			{
				$ratio = (real)($max_szerokosc / $szerokosc_oryg);
				$nowa_szerokosc = round($szerokosc_oryg * $ratio);
				$nowa_wysokosc = round($wysokosc_oryg * $ratio);
			}
			*/

			$sze_start = 0;
			if ($nowa_szerokosc > $max_szerokosc) {
				$sze_start = round(($nowa_szerokosc - $max_szerokosc) / 2);
			}

			$wys_start = 0;
			if ($nowa_wysokosc > $max_wysokosc) {
				$wys_start = round(($nowa_wysokosc - $max_wysokosc) / 2);
			}
			if (file_exists($sciezka)) {
				unlink($sciezka);
			}
			if (!$img_pom = imagecreatetruecolor($nowa_szerokosc, $nowa_wysokosc)) {
				$zwroc = false;
			}
			if (!imagecopyresampled($img_pom, $img, 0, 0, 0, 0, $nowa_szerokosc, $nowa_wysokosc, $szerokosc_oryg, $wysokosc_oryg)) {
				$zwroc = false;
			}

			if (!ImageJPEG($img_pom, $sciezka, 90)) {
				$zwroc = false;
			}
			ImageDestroy($img);
			ImageDestroy($img_pom);

			if (!$img = ImageCreateFromJPEG($sciezka)) {
				$zwroc = false;
			}

			if (!$img_pom = imagecreatetruecolor($max_szerokosc, $max_wysokosc)) {
				$zwroc = false;
			}

			if (!imagecopy($img_pom, $img, 0, 0, 0 + $sze_start, 0 + $wys_start, $max_szerokosc, $max_wysokosc)) {
				$zwroc = false;
			}
			if (!ImageJPEG($img_pom, $sciezka, 90)) {
				$zwroc = false;
			}
			ImageDestroy($img);
			ImageDestroy($img_pom);
			return $zwroc;
		} else {
			if ($this->kopiuj_zdjecie($zrodlo, $sciezka)) {
				return true;
			} else {
				return false;
			}
		}
	}

	function tworz_miniaturke_kadrujac_zdjecie($zrodlo, $sciezka, $max_szerokosc = '', $max_wysokosc = '')
	{
		$zwroc = true;
		if ((int)$max_szerokosc != 0 && (int)$max_wysokosc != 0) {
			//if(!$img = ImageCreateFromJPEG($zrodlo))
			if (!$img = Core_Zdjecie::imageCreateFromFile($zrodlo)) {
				$zwroc = false;
			}
			$szerokosc_oryg = imagesx($img);
			$wysokosc_oryg  = imagesy($img);

			$ratio1          = (real)($max_wysokosc / $wysokosc_oryg);
			$ratio2          = (real)($max_szerokosc / $szerokosc_oryg);
			$nowa_szerokosc1 = round($szerokosc_oryg * $ratio1);
			$nowa_wysokosc1  = round($wysokosc_oryg * $ratio1);
			$nowa_szerokosc2 = round($szerokosc_oryg * $ratio2);
			$nowa_wysokosc2  = round($wysokosc_oryg * $ratio2);

			if ($nowa_szerokosc1 >= $max_szerokosc && $nowa_wysokosc1 >= $max_wysokosc) {
				$ratio          = $ratio1;
				$nowa_szerokosc = $nowa_szerokosc1;
				$nowa_wysokosc  = $nowa_wysokosc1;
			} else if ($nowa_szerokosc2 >= $max_szerokosc && $nowa_wysokosc2 >= $max_wysokosc) {
				$ratio          = $ratio2;
				$nowa_szerokosc = $nowa_szerokosc2;
				$nowa_wysokosc  = $nowa_wysokosc2;
			} else {
				echo 'nie dziala';
			}

			/* 
			if($szerokosc_oryg > $wysokosc_oryg)
			{
				$ratio = (real)($max_wysokosc / $wysokosc_oryg);	
				$nowa_szerokosc = round($szerokosc_oryg * $ratio);
				$nowa_wysokosc = round($wysokosc_oryg * $ratio);
			}
			else
			{
				$ratio = (real)($max_szerokosc / $szerokosc_oryg);
				$nowa_szerokosc = round($szerokosc_oryg * $ratio);
				$nowa_wysokosc = round($wysokosc_oryg * $ratio);
			}
			*/

			$sze_start = 0;
			if ($nowa_szerokosc > $max_szerokosc) {
				$sze_start = round(($nowa_szerokosc - $max_szerokosc) / 2);
			}

			$wys_start = 0;
			if ($nowa_wysokosc > $max_wysokosc) {
				$wys_start = round(($nowa_wysokosc - $max_wysokosc) / 2);
			}
			if (file_exists($sciezka)) {
				unlink($sciezka);
			}
			if (!$img_pom = imagecreatetruecolor($nowa_szerokosc, $nowa_wysokosc)) {
				$zwroc = false;
			}
			if (!imagecopyresampled($img_pom, $img, 0, 0, 0, 0, $nowa_szerokosc, $nowa_wysokosc, $szerokosc_oryg, $wysokosc_oryg)) {
				$zwroc = false;
			}

			if (!ImageJPEG($img_pom, $sciezka, 90)) {
				$zwroc = false;
			}
			ImageDestroy($img);
			ImageDestroy($img_pom);

			//if(!$img = ImageCreateFromJPEG($sciezka))
			if (!$img = Core_Zdjecie::imageCreateFromFile($sciezka)) {
				$zwroc = false;
			}

			if (!$img_pom = imagecreatetruecolor($max_szerokosc, $max_wysokosc)) {
				$zwroc = false;
			}

			if (!imagecopy($img_pom, $img, 0, 0, 0 + $sze_start, 0 + $wys_start, $max_szerokosc, $max_wysokosc)) {
				$zwroc = false;
			}

			/*		
			if(!ImageJPEG($img_pom,$sciezka,90))
			{
				$zwroc = false;
			}
			*/

			//==============================================
			$info = @getimagesize($sciezka);
			$typ  = $info[2];

			if ($typ == 2) {
				if (!ImageJPEG($img_pom, $sciezka, 75)) {
					$zwroc = false;
				}
			} else if ($typ == 1) {
				if (!ImageGIF($img_pom, $sciezka)) {
					$zwroc = false;
				}
			} else if ($typ == 3) {
				if (!ImagePNG($img_pom, $sciezka, 9)) {
					$zwroc = false;
				}
			}
			//==============================================
			ImageDestroy($img);
			ImageDestroy($img_pom);
			return $zwroc;
		} else {
			if ($this->kopiuj_zdjecie($zrodlo, $sciezka)) {
				return true;
			} else {
				return false;
			}
		}
	}

	function tworz_miniaturke_kadrujac_zdjecie3($zrodlo, $sciezka, $max_szerokosc = '', $max_wysokosc = '')
	{
		$zwroc     = true;
		$functions = array(
			IMAGETYPE_GIF  => 'imagecreatefromgif',
			IMAGETYPE_JPEG => 'imagecreatefromjpeg',
			IMAGETYPE_PNG  => 'imagecreatefrompng'
		);


		if ((int)$max_szerokosc != 0 && (int)$max_wysokosc != 0) {
			$img = $this->imageCreateFromFile($zrodlo);

			$szerokosc_oryg = imagesx($img);
			$wysokosc_oryg  = imagesy($img);

			$ratio1          = (real)($max_wysokosc / $wysokosc_oryg);
			$ratio2          = (real)($max_szerokosc / $szerokosc_oryg);
			$nowa_szerokosc1 = round($szerokosc_oryg * $ratio1);
			$nowa_wysokosc1  = round($wysokosc_oryg * $ratio1);
			$nowa_szerokosc2 = round($szerokosc_oryg * $ratio2);
			$nowa_wysokosc2  = round($wysokosc_oryg * $ratio2);

			if ($nowa_szerokosc1 >= $max_szerokosc && $nowa_wysokosc1 >= $max_wysokosc) {
				$ratio          = $ratio1;
				$nowa_szerokosc = $nowa_szerokosc1;
				$nowa_wysokosc  = $nowa_wysokosc1;
			} else if ($nowa_szerokosc2 >= $max_szerokosc && $nowa_wysokosc2 >= $max_wysokosc) {
				$ratio          = $ratio2;
				$nowa_szerokosc = $nowa_szerokosc2;
				$nowa_wysokosc  = $nowa_wysokosc2;
			} else {
				echo 'nie dziala';
			}


			$sze_start = 0;
			if ($nowa_szerokosc > $max_szerokosc) {
				$sze_start = round(($nowa_szerokosc - $max_szerokosc) / 2);
			}

			$wys_start = 0;
			if ($nowa_wysokosc > $max_wysokosc) {
				$wys_start = round(($nowa_wysokosc - $max_wysokosc) / 2);
			}
			if (file_exists($sciezka)) {
				unlink($sciezka);
			}
			if (!$img_pom = imagecreatetruecolor($nowa_szerokosc, $nowa_wysokosc)) {
				$zwroc = false;
			}
			if (!imagecopyresampled($img_pom, $img, 0, 0, 0, 0, $nowa_szerokosc, $nowa_wysokosc, $szerokosc_oryg, $wysokosc_oryg)) {
				$zwroc = false;
			}
/*
			if ($typ == 2) {
				if (!ImageJPEG($img_pom, $sciezka, 75)) {
					$zwroc = false;
				}
			} else if ($typ == 1) {
				if (!ImageGIF($img_pom, $sciezka)) {
					$zwroc = false;
				}
			} else if ($typ == 3) {
				if (!ImagePNG($img_pom, $sciezka, 9)) {
					$zwroc = false;
				}
			}
*/
			ImageDestroy($img);
			ImageDestroy($img_pom);

			$img = $this->imageCreateFromFile($zrodlo);

			if (!$img_pom = imagecreatetruecolor($max_szerokosc, $max_wysokosc)) {
				$zwroc = false;
			}

			if (!imagecopy($img_pom, $img, 0, 0, 0 + $sze_start, 0 + $wys_start, $max_szerokosc, $max_wysokosc)) {
				$zwroc = false;
			}
/*
			if ($typ == 2) {
				if (!ImageJPEG($img_pom, $sciezka, 75)) {
					$zwroc = false;
				}
			} else if ($typ == 1) {
				if (!ImageGIF($img_pom, $sciezka)) {
					$zwroc = false;
				}
			} else if ($typ == 3) {
				if (!ImagePNG($img_pom, $sciezka, 9)) {
					$zwroc = false;
				}
			}*/
			ImageDestroy($img);
			ImageDestroy($img_pom);
			return $zwroc;
		} else {
			if ($this->kopiuj_zdjecie($zrodlo, $sciezka)) {
				return true;
			} else {
				return false;
			}
		}
	}

	function tworz_miniaturke_w_kwadracie($zrodlo, $sciezka, $max_szerokosc = 200, $max_wysokosc = 200)
	{
		$zwroc = true;
		if (!$img = ImageCreateFromJPEG($zrodlo)) {
			$zwroc = false;
		}
		$szerokosc_oryg = imagesx($img);
		$wysokosc_oryg  = imagesy($img);
		if ($szerokosc_oryg > $max_szerokosc) {
			$ratio          = (real)($max_szerokosc / $szerokosc_oryg);
			$nowa_szerokosc = round($szerokosc_oryg * $ratio);
			$nowa_wysokosc  = round($wysokosc_oryg * $ratio);
		} else {
			$nowa_szerokosc = $szerokosc_oryg;
			$nowa_wysokosc  = $wysokosc_oryg;
		}
		if ($nowa_wysokosc > $max_wysokosc) {
			$ratio          = (real)($max_wysokosc / $nowa_wysokosc);
			$nowa_szerokosc = round($nowa_szerokosc * $ratio);
			$nowa_wysokosc  = round($nowa_wysokosc * $ratio);
		}
		if (!$nowa_szerokosc) {
			$nowa_szerokosc = $szerokosc_oryg;
		}
		if (!$nowa_wysokosc) {
			$nowa_wysokosc = $wysokosc_oryg;
		}
		if (file_exists($sciezka)) {
			unlink($sciezka);
		}
		if (!$img_pom = imagecreatetruecolor($nowa_szerokosc, $nowa_wysokosc)) {
			$zwroc = false;
		}
		if (!imagecopyresampled($img_pom, $img, 0, 0, 0, 0, $nowa_szerokosc, $nowa_wysokosc, $szerokosc_oryg, $wysokosc_oryg)) {
			$zwroc = false;
		}
		if (!ImageJPEG($img_pom, $sciezka, 80)) {
			$zwroc = false;
		}
		ImageDestroy($img);
		ImageDestroy($img_pom);
		if (!$img_pom = ImageCreateTrueColor($max_szerokosc, $max_wysokosc)) {
			$zwroc = false;
		}
		$bialy = ImageColorAllocate($img_pom, 255, 255, 255);
		ImageFill($img_pom, 0, 0, $bialy);
		if (!$img = ImageCreateFromJPEG($sciezka)) {
			$zwroc = false;
		}
		$x = 0;
		$y = 0;
		if ($nowa_szerokosc > $nowa_wysokosc) {
			$y = (real)(($max_wysokosc - $nowa_wysokosc) / 2);
		} else {
			$x = (real)(($max_szerokosc - $nowa_szerokosc) / 2);
		}
		if (!ImageCopyResized($img_pom, $img, $x, $y, 0, 0, $nowa_szerokosc, $nowa_wysokosc, $nowa_szerokosc, $nowa_wysokosc)) {
			$zwroc = false;
		}
		if (!ImageJPEG($img_pom, $sciezka, 90)) {
			$zwroc = false;
		}
		ImageDestroy($img);
		ImageDestroy($img_pom);
		return $zwroc;
	}
}
