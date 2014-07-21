<?php

class Core_Response
{
    const CONTENT_TYPE_AJAX = 'ajax';

    protected $content;
    protected $naglowek;
    protected $contentType;
    protected $moduleTemplate;
    protected $moduleTemplateDir;
    protected $layoutTemplate;
    protected $refererURL;
    protected $headers;
    protected $js_include;
    protected $parametry;
    protected $errors;
    protected $podstrona_id;

    public function __construct($content_in = '', $contentType_in = '', array $parametry_in = array(), $moduleTemplate_in = '', $layoutTemplate_in = '', array $headers_in = array())
    {
        $this->content = $content_in;
        $this->contentType = $contentType_in;
        $this->parametry = $parametry_in;
        $this->moduleTemplate = $moduleTemplate_in;
        $this->layoutTemplate = $layoutTemplate_in;
        $this->headers = $headers_in;
        $this->errors = array();
        $this->js_include = array();
    }

    //================================================================================================================
    public function headerList()
    {
        $html_headers = '';
        foreach ($this->getHeaders() as $klucz => $wartosc) {
            if ($klucz != "") {
                $html_headers .= header($klucz . ":" . $wartosc);
            } else {
                $html_headers .= header($wartosc);
            }
        }
        return $html_headers;
    }

    //================================================================================================================
    /**
     * Converts the response object to string containing all headers and the response content.
     *
     * @return string The response with headers and content
     */
    public function headerToString()
    {
        $headers = '';
        foreach ($this->headers as $name => $value) {
            if (is_string($value)) {
                $headers .= $this->buildHeader($name, $value);
            } else {
                foreach ($value as $headerValue) {
                    $headers .= $this->buildHeader($name, $headerValue);
                }
            }
        }
        return $headers;
    }

    //================================================================================================================
    /**
     * Gets a response header.
     *
     * @param string $header The header name
     * @param Boolean $first  Whether to return the first value or all header values
     *
     * @return string|array The first header value if $first is true, an array of values otherwise
     */
    public function getHeader($header, $first = true)
    {
        foreach ($this->headers as $key => $value) {
            if (str_replace('-', '_', strtolower($key)) == str_replace('-', '_', strtolower($header))) {
                if ($first) {
                    return is_array($value) ? (count($value) ? $value[0] : '') : $value;
                }

                return is_array($value) ? $value : array($value);
            }
        }
        return $first ? null : array();
    }

    //================================================================================================================
    protected function buildHeader($name, $value)
    {
        $tmp_header = sprintf("%s: %s\n", $name, $value);

        if ($name == '') {
            $tmp_header = sprintf("%s\n", $value);
        }
        return $tmp_header;
    }

    //================================================================================================================
    public function setTemplateDir($dir_in)
    {
        $this->moduleTemplateDir = $dir_in;
    }

    //================================================================================================================
    public function getPodstronaId()
    {
        return $this->podstrona_id;
    }

    public function setPodstronaId($podstrona_in)
    {
        $this->podstrona_id = $podstrona_in;
    }

    //================================================================================================================
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content_in)
    {
        $this->content = $content_in;
    }

    //================================================================================================================
    public function getRefererURL()
    {
        return $this->refererURL;
    }

    public function setRefererURL($refererURL_in)
    {
        $this->refererURL = $refererURL_in;
    }

    //================================================================================================================
    public function getNaglowek()
    {
        return $this->naglowek;
    }

    public function setNaglowek($naglowek_in)
    {
        $this->naglowek = $naglowek_in;
    }

    //================================================================================================================
    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType($contentType_in)
    {
        $this->contentType = $contentType_in;
    }

    //================================================================================================================
    public function setParametry($parametry_in)
    {
        foreach ($parametry_in as $klucz => $wartosc) {
            $this->parametry[$klucz] = $wartosc;
        }
    }

    public function getParametry()
    {
        return $this->parametry;
    }

    public function getParametr($klucz)
    {
        return $this->parametry[$klucz];
    }

    public function dodajParametr($klucz, $wartosc)
    {
        $this->parametry[$klucz] = $wartosc;
    }

    //================================================================================================================
    public function setPlikiJS($pliki_in)
    {
        $this->js_include = $pliki_in;
    }

    public function getPlikiJS()
    {
        return $this->js_include;
    }

    public function dodajPlikJS($sciezka)
    {
        $this->js_include[] = $sciezka;
    }

    //================================================================================================================
    public function getModuleTemplate()
    {
        return $this->moduleTemplate;
    }

    public function setModuleTemplate($moduleTemplate_in)
    {
        $this->moduleTemplate = $moduleTemplate_in;
    }

    //================================================================================================================
    public function getLayoutTemplate()
    {
        return $this->layoutTemplate;
    }


    public function setLayoutTemplate($layoutTemplate_in)
    {
        $this->layoutTemplate = $layoutTemplate_in;
    }

    //================================================================================================================
    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors($a_errors_in)
    {
        $this->errors = $a_errors_in;
    }

    public function addError($error_in)
    {
        $this->errors[] = $error_in;
    }

    //================================================================================================================
    public function getHeaders()
    {
        return $this->headers;
    }


    public function setHeaders($headers_in)
    {
        $this->headers = $headers_in;
    }

    public function setHeaderParametr($klucz, $wartosc)
    {
        $this->headers[$klucz] = $wartosc;
    }

    //================================================================================================================


    function getSmartyVars($string)
    {
        // regexp
        $fullPattern = '`{[^\$]*\$([a-zA-Z0-9]+)[^\}]*}`';
        $separateVars = '`[^\$]*\$([a-zA-Z0-9]+)`';

        $smartyVars = array();
        // We start by extracting all the {} with var embedded
        if (!preg_match_all($fullPattern, $string, $results)) {
            return $smartyVars;
        }
        // Then we extract all smarty variables
        foreach ($results[0] AS $result) {
            if (preg_match_all($separateVars, $result, $matches)) {
                $smartyVars = array_merge($smartyVars, $matches[1]);
            }
        }
        return array_unique($smartyVars);
    }

    public function render()
    {

        switch ($this->contentType) {
            case 'ajax':
                $content = $this->content;
                break;
            case 'plik':
                //header("Content-Disposition: attachment; filename=".$file);
                //header("Content-Type: application/octet-stream");
                //header("Content-Length: ".$len);
                //header("Pragma: no-cache");
                //header("Expires: 0");
                //
                //$fp = fopen($file,"r");
                //$layout =  fread($fp, $len);
                //fclose($fp);
                break;
            default:
                $o_sm = new Smarty();
                $o_sm->compile_dir = Core_Config::get('server_path') . 'tmp/';
                $o_sm->compile_check = true;

                $this->dodajParametr('tresc', $this->content);
                $this->dodajParametr('tytul', $this->naglowek);

                $this->dodajParametr('img_path', '/www/' . Core_Config::get('cms_dir') . '/img/');

                foreach ($this->parametry as $key => $value) {
                    $o_sm->assign($key, $value);
                }

                if ($this->moduleTemplate != "") {
                    $content = $o_sm->fetch($this->moduleTemplateDir . $this->moduleTemplate . '.tpl');
                } else {
                    $content = $this->content;
                }
                break;
        }

        return $content;
    }
}
