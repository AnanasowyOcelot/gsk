var licznik_elementow = 0;

function dodajElementStrona()
{
	var element_klucz = $("#element_id option:selected").val(); 
	var element_nazwa = $("#element_id option:selected").attr('attr_nazwa'); 
	var element_id = $("#element_id option:selected").attr('attr_id'); 
	var element_parametry = $("#element_parametr").val();
	var element_tpl = $("#element_tpl").val();
	var licznik_elementow = $("#licznik_elementow").val();
	
	var html = '<div id="element_'+licznik_elementow+'"  style="width:520px; height:20px; border:1px solid #000; padding:5px 0px; margin:3px 2px; background-color:#abd8ef;">\
			<input type="hidden" name="r[elementy_podstrona]['+licznik_elementow+'][element_id]" value="'+element_id+'">\
			<input type="hidden" name="r[elementy_podstrona]['+licznik_elementow+'][element_parametr]" value="'+element_parametry+'">\
			<input type="hidden" name="r[elementy_podstrona]['+licznik_elementow+'][element_tpl_nazwa]" value="'+element_tpl+'">\
			<span style="width:200px; display:inline-block; border-right:1px solid #999;">&nbsp;'+element_nazwa+'</span>\
			<span style="width:140px; display:inline-block; border-right:1px solid #999;">&nbsp;'+element_parametry+'</span>\
			<span style="width:130px; display:inline-block; border-right:1px solid #999;">&nbsp;'+element_tpl+'</span>\
			<a href="javascript:;" onClick="removeElementStrona('+licznik_elementow+')"><img src="/www/cms/img/remove.png" style="vertical-align:middle; "></a>\
		    </div>';
	
	
	$("#listaElementowStrona").append(html);
	licznik_elementow++;
	
	$("#licznik_elementow").val(licznik_elementow);
	$("#element_parametr").val("");
	$("#element_tpl").val("");
}


function removeElementStrona(element_id)
{
	$("#element_"+element_id).remove();
}