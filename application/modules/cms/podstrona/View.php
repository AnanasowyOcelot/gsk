<?php
class podstrona_View extends Core_View
{
	//============================================================================
	function listaPodstronaElementy($a_wybrane = array())
	{

		$o_elementy = new Model_Element();
		$a_elementy = $o_elementy->pobierzElementy();


		Core_Narzedzia::drukuj($a_elementy);

		$html = '<div style="widht:480px; padding:0px 0px 0px 0px; position:relative; overflow:hidden;">';
		foreach ($a_elementy as $klucz => $nazwa) {
			$html .= '<div style="width:157px; padding:5px; float:left; border:1px solid #999; height:25px; margin:2px; background-color:#abd8ef;"><input type="checkbox" value="' . $klucz . '">' . $nazwa . '</div>';
		}
		$html .= '</div>';

		return $html;
	}

	//============================================================================
	public static function listaElementySelect()
	{

		$o_elementy = new Model_Element();

		$a_elementy = $o_elementy->pobierzElementy();

		$html = '<select name="element_id" id="element_id" style="width:200px;">';
		foreach ($a_elementy as $klucz => $dane) {
			$html .= '<option value="' . $klucz . '" attr_nazwa="' . $dane['nazwa'] . '" attr_id="' . $dane['id'] . '">' . $dane['nazwa'] . '</option>';
		}
		$html .= '</select>';

		return $html;
	}

	//============================================================================
	public static function listaElementy($podstrona)
	{
		$html = '';

		$licznik_elementow = 0;
		if (is_array($podstrona->elementy_podstrona) && count($podstrona->elementy_podstrona) > 0) {
			foreach ($podstrona->elementy_podstrona as $tpl_name => $dane_elementu) {
				$html .= '<div id="element_' . $licznik_elementow . '"  style="width:520px; height:20px; border:1px solid #000; padding:5px 0px; margin:3px 2px; background-color:#abd8ef;">
						<input type="hidden" name="r[elementy_podstrona][' . $licznik_elementow . '][element_id]" value="' . $dane_elementu['element_id'] . '">
						<input type="hidden" name="r[elementy_podstrona][' . $licznik_elementow . '][element_parametr]" value="' . $dane_elementu['parametr'] . '">
						<input type="hidden" name="r[elementy_podstrona][' . $licznik_elementow . '][element_tpl_nazwa]" value="' . $tpl_name . '">
						<span style="width:200px; display:inline-block; border-right:1px solid #999;">&nbsp;' . $dane_elementu['element_nazwa'] . '</span>
						<span style="width:140px; display:inline-block; border-right:1px solid #999;">&nbsp;' . $dane_elementu['parametr'] . '</span>
						<span style="width:135px; display:inline-block; border-right:1px solid #999;">&nbsp;' . $tpl_name . '</span>
						<a href="javascript:;" onClick="removeElementStrona(' . $licznik_elementow . ')"><img src="/www/cms/img/remove.png" style="vertical-align:middle;"></a>
					    </div>';

				$licznik_elementow++;
			}
		}

		$html .= '<input type="hidden" name="licznik_elementow" id="licznik_elementow" value="' . $licznik_elementow . '" >';

		return $html;
	}
}
