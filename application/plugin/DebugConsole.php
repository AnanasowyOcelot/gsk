<?phpclass Plugin_DebugConsole{    public static function render(array $a_params) {        $html = '';                $html .= '<div id="konsola" style="display: none;">';                $html .= '<div class="okienko1">';        if(isset($a_params['komunikaty'])) {            $html .= '<div>'.$a_params['komunikaty'].'</div>';        }                $html .= '</div>';                $html .= '<div class="okienko2">';        if(isset($a_params['modul'])) {            $html .= 'modul:'.$a_params['modul'].'<br />akcja:'.$a_params['akcja'].'';        }        if(isset($a_params['parametry'])) {        $html .= '<hr />parametry GET:<pre>'.print_r($a_params['parametry'], 1).'</pre>';        }        $html .= '</div>';        $html .= '</div>';                return $html;    }}