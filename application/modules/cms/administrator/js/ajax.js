//==============================================================
function testAjax(komunikat)
{
	$.ajax({
		type : 'POST',
		url : '/cms/uzytkownik/ajax',
		dataType : 'html',
		data: {
			polecenie: "ping",
			komunikat: komunikat			
		},
		success : function(data){			
			$("#ajax_response").html(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("wystąpił blad");
		}
	});

}