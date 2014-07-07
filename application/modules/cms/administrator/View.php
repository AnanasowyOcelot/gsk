<?php

class administrator_View extends Core_View
{
	//============================================================================
	function selectGrupy($id_wybrana='') {
		$sql = "SELECT 
				grupa_id AS id, 
				grupa_nazwa AS nazwa 
			FROM 
				administratorzy_grupy";
		
//		if((int)$id_wybrana>0)
//		{
//			$sql .= " WHERE grupa_id=".(int)$id_wybrana;
//		}
		
		$grupySelect = $this->db->Execute($sql)->GetRows();
		
		$html = '<select name="r[grupa_id]">';
		$html .='<option value="0">...</option>';
		foreach ($grupySelect as $index => $dane)
		{
			
			$html .='<option value="'.$dane['id'].'" '.($dane['id']==$id_wybrana?'selected':'').'>'.$dane['nazwa'].'</option>';
		}
		$html .= '<select>';

		return $html;
	}
};
