<?php

class Core_Narzedzia
{
    public static function drukuj($object, $do_zmiennej = 0)
    {
        if ($do_zmiennej == 0) {
            echo "<pre>";
            print_r($object);
            echo "</pre>";
        } else {
            $html = "<pre>";
            $html .= print_r($object, true);
            $html .= "</pre>";
            return $html;
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function wyswietlPorcjowanie($ilosc_rekordow, $ilosc_wynikow_na_strone, $strona, $link, $dodatkowe_parametry = '')
    {

        $pozycjeHTML = '';
        if ($ilosc_rekordow > $ilosc_wynikow_na_strone) {

            $ostatnia_strona = ceil($ilosc_rekordow / $ilosc_wynikow_na_strone);

            if ($strona > $ostatnia_strona) {
                $strona = $ostatnia_strona;
            } else if ($strona == '' || $strona < 1) {
                $strona = 1;
            }

            $koncowy_element = $ilosc_rekordow;
            $pstrona = $strona - 5;
            $nstrona = $strona + 5;
            $strona_offset = ($strona - 1) * $ilosc_wynikow_na_strone;

            //$link = $dodatkowe_parametry;

            if ($strona > 4) {
                $start = $strona - 3;
                $stop = $strona + 3;
            } else {
                $start = $strona - 1;
                $stop = $strona + 5;
            }

            //	echo $strona;

            if ($stop > $ostatnia_strona) {
                $stop = $ostatnia_strona;
            }

            if ($stop - $start < 10) {
                $start = $stop - 6;
            }

            if ($start < 1) {
                $start = 1;
            }

            $i = -1;

            if ($pstrona > 0) {
                $i++;
                $pozycje[$i]['link'] = $link;
                $pozycje[$i]['strona'] = $pstrona;
                $pozycje[$i]['tekst'] = '<';
                $pozycje[$i]['aktywny'] = 0;
            }

            for ($n = $start; $n <= $stop; $n++) {
                $i++;
                if ($n == $strona) {
                    $pozycje[$i]['link'] = $link;
                    $pozycje[$i]['strona'] = $n;
                    $pozycje[$i]['tekst'] = $n;
                    $pozycje[$i]['aktywny'] = 1;
                } else {
                    $pozycje[$i]['link'] = $link;
                    $pozycje[$i]['strona'] = $n;
                    $pozycje[$i]['tekst'] = $n;
                    $pozycje[$i]['aktywny'] = 0;

                }
            }

            if ($ostatnia_strona != 0) {
                $i++;
                $pozycje[$i]['link'] = $link;
                $pozycje[$i]['strona'] = $ostatnia_strona;
                $pozycje[$i]['tekst'] = " z ";
                $pozycje[$i]['aktywny'] = 0;
                $i++;

                if ($strona != $ostatnia_strona) {
                    $pozycje[$i]['link'] = $link;
                    $pozycje[$i]['strona'] = $ostatnia_strona;
                    $pozycje[$i]['tekst'] = $ostatnia_strona;
                    $pozycje[$i]['aktywny'] = 0;
                } else {
                    $pozycje[$i]['link'] = $link;
                    $pozycje[$i]['strona'] = $ostatnia_strona;
                    $pozycje[$i]['tekst'] = $ostatnia_strona;
                    $pozycje[$i]['aktywny'] = 0;
                }
            }

            if ($strona * $ilosc_wynikow_na_strone < $koncowy_element) {
                $i++;
                $pozycje[$i]['link'] = $link;
                $pozycje[$i]['strona'] = $nstrona;
                $pozycje[$i]['tekst'] = '>';
                $pozycje[$i]['aktywny'] = 0;
            }


            if ($dodatkowe_parametry != '') {
                $dodatkowe_parametry = '/' . $dodatkowe_parametry;
            }

            foreach ($pozycje as $k => $v) {

                //						if($v['szablon'] == 'porcjowanie_pozycja_poprzednia') $pozycjaHTMLpoprzednia = preg_replace($pattern, $replacement, $szablon->html);
                //						else if($v['szablon'] == 'porcjowanie_pozycja_nastepna') $pozycjaHTMLnastepna = preg_replace($pattern, $replacement, $szablon->html);
                //						else $pozycjeHTML .= ' '.preg_replace($pattern, $replacement, $szablon->html).' ';


                if ($v['aktywny'] == 1) {
                    $pozycjeHTML .= '<a href="' . $v['link'] . '/' . $v['strona'] . $dodatkowe_parametry . '">[ ' . $v['tekst'] . ' ]</a>&nbsp;&nbsp;';
                } else {
                    $pozycjeHTML .= '<a href="' . $v['link'] . '/' . $v['strona'] . $dodatkowe_parametry . '">' . $v['tekst'] . '</a>&nbsp;&nbsp;';
                }

            }
        }

        return $pozycjeHTML;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function wyswietlListePodstron($id, $jezyk, $wciecie, $zaznaczona, $podstrona_id = '')
    {
        $podstrony = new Model_Podstrona();
        $podstrony->filtr_sortuj_po = 'po.podstrona_miejsce ASC, po.podstrona_nazwa';
        $podstrony->filtr_sortuj_jak = 'ASC';
        $podstrony->filtr_id_nadrzedna = '' . $id . '';
        $podstrony->filtr_jezyk_id = $jezyk;
        $podstrony->filtrujPodstrony();

        $html = '';

        foreach ($podstrony->rekordy as $p_id) {
            if ($podstrona_id != $p_id) {
                $p = new Model_Podstrona($p_id);

                $html .= '<option value="' . $p->id . '"';
                if (is_array($zaznaczona)) {
                    if (array_search($p->id, $zaznaczona) !== false) $html .= ' selected="selected"';
                } else {
                    if ($p->id == $zaznaczona) $html .= ' selected="selected"';
                }
                $html .= '>';
                for ($i = 0; $i < $wciecie; $i++) $html .= '&nbsp;&nbsp;&nbsp;';
                if ($wciecie != 0) $html .= '-&nbsp;';
                $html .= '' . $p->nazwa[$jezyk] . '</option>';
                $wciecie++;
                $html .= Core_Narzedzia::wyswietlListePodstron($p->id, $jezyk, $wciecie, $zaznaczona, $podstrona_id);
                $wciecie--;
            }
        }
        return $html;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function wyswietlListeKategoriMenu($jezyk, $zaznaczona)
    {
        $kategorieMenu = new Model_Menukategoria();
        $kategorieMenu->filtr_sortuj_po = 'nazwa';
        $kategorieMenu->filtr_sortuj_jak = 'ASC';
        $kategorieMenu->filtr_jezyk_id = $jezyk;
        $kategorieMenu->filtrujRekordy();

        $html = '';

        foreach ($kategorieMenu->rekordy as $p_id) {
            $p = new Model_Menukategoria($p_id);

            $html .= '<option value="' . $p->id . '"';
            if (is_array($zaznaczona)) {
                if (array_search($p->id, $zaznaczona) !== false) $html .= ' selected="selected"';
            } else {
                if ($p->id == $zaznaczona) $html .= ' selected="selected"';
            }
            $html .= '>';
            $html .= '' . $p->nazwa[$jezyk] . '</option>';
        }
        return $html;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function wyswietlListeModulow($zaznaczona)
    {
        $db = Core_DB::instancja();

        $sql_modul = " SELECT * FROM cms_moduly  ORDER BY modul_nazwa ASC";
        $lista = $db->query($sql_modul);

        $html = '';

        $html .= '<option value="">brak</option>';
        foreach ($lista as $id => $dane) {
            $html .= '<option value="' . $dane['modul_id'] . '"';

            if ($dane['modul_id'] == $zaznaczona) $html .= ' selected="selected"';
            $html .= '>';
            $html .= '' . $dane['modul_nazwa'] . '</option>';
        }

        return $html;


    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public static function usunZnakiNiedozwolone($string)
    {
        $string = trim($string);
        $signs = array(
            ' ' => '-',
            'ą' => 'a',
            'Ą' => 'A',
            'ę' => 'e',
            'Ę' => 'E',
            'ć' => 'c',
            'Ć' => 'C',
            'ó' => 'o',
            'Ó' => 'O',
            'ł' => 'l',
            'Ł' => 'L',
            'ś' => 's',
            'Ś' => 's',
            'Ź' => 'S',
            'ń' => 'n',
            'Ń' => 'N',
            'ż' => 'z',
            'Ż' => 'Z',
            'ź' => 'z',
            'Ź' => 'Z',
            '!' => '',
            '@' => '',
            '#' => '',
            '$' => '',
            '%' => '',
            '^' => '',
            '&' => '',
            '*' => '',
            '(' => '',
            ')' => '',
            '+' => '',
            '=' => '',
            '\\' => '',
            '|' => '',
            '/' => '',
            '.' => '',
            ',' => '',
            '{' => '',
            '}' => '',
            '[' => '',
            ']' => '',
            ';' => '',
            ':' => '',
            '\'' => '',
            '"' => '',
            '<' => '',
            '>' => '',
            '?' => '',
            '~' => '',
            '`' => '',
        );

        foreach ($signs as $key => $value) {
            $string = str_replace($key, $value, $string);
        }

        $string = preg_replace('/[\-]+/', '-', $string);

        return $string;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public static function usunZnakiNiedozwolonePliki($string)
    {
        $string = trim($string);
        $signs = array(
            ' ' => '_',
            'ą' => 'a',
            'Ą' => 'A',
            'ę' => 'e',
            'Ę' => 'E',
            'ć' => 'c',
            'Ć' => 'C',
            'ó' => 'o',
            'Ó' => 'O',
            'ł' => 'l',
            'Ł' => 'L',
            'ś' => 's',
            'Ś' => 's',
            'Ź' => 'S',
            'ń' => 'n',
            'Ń' => 'N',
            'ż' => 'z',
            'Ż' => 'Z',
            'ź' => 'z',
            'Ź' => 'Z',
            '!' => '',
            '@' => '',
            '#' => '',
            '$' => '',
            '%' => '',
            '^' => '',
            '&' => '',
            '*' => '',
            '(' => '',
            ')' => '',
            '+' => '',
            '=' => '',
            '\\' => '',
            '|' => '',
            '/' => '',
            ',' => '',
            '{' => '',
            '}' => '',
            '[' => '',
            ']' => '',
            ';' => '',
            ':' => '',
            '\'' => '',
            '"' => '',
            '<' => '',
            '>' => '',
            '?' => '',
            '~' => '',
            '`' => '',
        );

        foreach ($signs as $key => $value) {
            $string = str_replace($key, $value, $string);
        }

        $string = preg_replace('/[\-]+/', '-', $string);

        return $string;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    function emailValidation($email)
    {
        $result = TRUE;

        if (!preg_match("/^([a-zA-Z0-9])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/", $email)) {
            $result = FALSE;
        }
        return $result;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function validateWartosc($typ, $wartosc)
    {
        $zwrot = false;
        $typ = strtoupper($typ);
        if (strlen(trim($wartosc)) > 0) {
            switch ($typ) {

                case 'S':
                    //string
                    if (strlen(trim($wartosc)) == 0) {
                        $zwrot = true;
                    }
                    break;
                case 'I':
                    if (!is_numeric($wartosc)) {
                        $zwrot = true;
                    }
                    break;
                case 'F':
                    if (!is_float($wartosc)) {
                        $zwrot = true;
                    }
                    break;
                case 'E':
                    if (!$this->emailValidation($wartosc)) {
                        $zwrot = true;
                    }
                    break;
            }
        } else {
            $zwrot = true;
        }

        return $zwrot;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public static function validate($dane, $wymagane)
    {
        $errors = array();

        $a_wymagane = explode(",", $wymagane);

        $a_validate = array();
        foreach ($a_wymagane as $index => $pole) {
            $tmp = explode("#", $pole);

            if (count($tmp) > 0) {
                $a_validate[$tmp[0]] = $tmp[1];
            }
        }
        if (count($a_validate) > 0) {
            foreach ($dane as $nazwa_pola => $wartosc) {
                if (array_key_exists($nazwa_pola, $a_validate)) {
                    $typ_pola = $a_validate[$nazwa_pola];

                    if (is_array($dane[$nazwa_pola])) {
                        foreach ($dane[$nazwa_pola] as $jezykId => $wartosc) {
                            if (Core_Narzedzia::validateWartosc($typ_pola, $wartosc)) {
                                $errors[$nazwa_pola][$jezykId] = ' error ';
                            }
                        }
                    } else {
                        $wartosc = $dane[$nazwa_pola];
                        if (Core_Narzedzia::validateWartosc($typ_pola, $wartosc)) {
                            $errors[$nazwa_pola] = ' error ';
                        }
                    }
                }
            }
        }
        return $errors;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function wyslijWiadomoscEmail($to, $subject, $message, $from_email = '', $from_name = '', $attachments = array())
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->IsSMTP();
        $mail->Host = Core_Config::get("SMTP_EMAIL_HOST");
        $mail->SMTPAuth = true;
        $mail->Username = Core_Config::get("SMTP_EMAIL_USER");
        $mail->Password = Core_Config::get("SMTP_EMAIL_PASS");
        $mail->AddReplyTo($from_email, $from_name);

        $mail->From = Core_Config::get("SMTP_EMAIL");
        $mail->FromName = Core_Config::get("SMTP_EMAIL_NAME");

        if (is_array($attachments) && count($attachments) > 0) {
            foreach ($attachments as $attachment_source => $attachment_name) {
                $mail->AddAttachment($attachment_source, $attachment_name);
            }
        }

        if (is_array($to)) {
            foreach ($to as $email)
                $mail->AddAddress(trim($email));
        } else {
            $mail->AddAddress(trim($to));
        }
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        if (!$mail->Send()) {
            return false;
        }
        $mail->ClearAddresses();
        return true;
    }

    public static function makeDirIfNotExists($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}
