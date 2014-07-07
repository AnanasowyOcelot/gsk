
///*************************************** NEWSLETTER ********************************************************
function newsletterZapisz()
{
	var email = $("#email_newsletter");

/*
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var bValid = checkRegexp(email,email,reg,"wrong e-mail address format");
*/
    bValid = jQuery("#newsletterForm").validationEngine('validate');
    
	if(bValid)
	{
		$.ajax({
			type : 'POST',
			url : '/newsletter/zapiszAjax',
			dataType : 'html',
			data: {
				e_mail: email.val()
			},
			success : function(data){

				if(data!="OK")
				{
					updateTips(email,data);
				}
				else
				{
					updateTips(email,"e-mail add successful");
				}

			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert("blad");
			}
		});
	}

}
//==============================================================
function kontaktWyslijZapytanie()
{

	var name = $("#fullname");
	var email = $("#email");
	var message = $("#message");

	var allFields = jQuery([]).add(name).add(email).add(message);

	var nameValid = false;
	var mailValid = false;
	var messageValid = false;
	allFields.removeClass('state-error');

	var err_name = "fullname to short"
	var err_msg_short = "message to short"
	var err_email = "e-mail address to short ";
	var err_wrong_email = "wrong e-mail format";
	var err_msg = "please fill in this field";


	if(email.val()=="E-mail address" )
	{

		email.addClass('state-error');
		updateTips(email,err_msg);
	}
	else
	{
		if(checkLength(email,email,err_email,6,200))
		{
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

			if(checkRegexp(email,email,reg,err_wrong_email))
			{
				emailValid = true;
			}
		}
	}

	if(name.val()=="Full name" || name.val()==err_msg)
	{
		name.addClass('state-error');
		updateTips(name,err_msg);
	}
	else
	{
		if(checkLength(name,name,err_name, 3,200))
		{
			nameValid = true;
		}
	}

	if(message.val()=="Message" || message.val()==err_msg)
	{
		message.addClass('state-error');
		updateTips(message,err_msg);
	}
	else
	{
		if(checkLength(message,message,err_msg_short,8,200))
		{
			messageValid = true;
		}
	}

	/*
	nameValid = checkLength(name,name,"fullname to short",3,200);
	mailValid = checkLength(email,email,"e-mail address to short ",6,200);
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

	mailValid = checkRegexp(email,email,reg,"wrong e-mail address format");
	messageValid = checkLength(message,message," please fill in this field",3,200);
	*/
	if(nameValid && mailValid && messageValid)
	{
		$.ajax({
			type : 'POST',
			url : '/kontakt/wyslijAjax',
			dataType : 'html',
			data: {
				name: name.val(),
				e_mail: email.val(),
				message: message.val()
			},
			success : function(data){

				if(data!="OK")
				{
					name.val("");
					email.val("");
					message.val("");
					updateTips(message,data);
				}
				else
				{
					name.val("");
					email.val("");
					message.val("");
					updateTips(message,"message send");
				}

			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert("error");
			}
		});

	}

}
//==============================================================
function checkRegexp(tips,o,regexp,n) {

	if ( !( regexp.test( o.val() ) ) ) {
		o.addClass('state-error');
		updateTips(tips,n);
		return false;
	} else {
		return true;
	}

}
//==============================================================
function checkLength(tips,o,n,min,max)
{
	if ( o.val().length > max || o.val().length < min ) {
		o.addClass('state-error');
		updateTips(tips,n);
		return false;
	} else {
		return true;
	}
}
//==============================================================
function updateTips(tips,t)
{
	//tips.text(t).effect("highlight",{},1500);
	tips.val(t);//.effect("highlight",{},1500);
}


///*************************************** NEWSLETTER KONIEC ********************************************************

(function ($, F) {

	// Opening animation - fly from the top
	F.transitions.dropIn = function() {
		var endPos = F._getPosition(true);

		endPos.top = (parseInt(endPos.top, 10) - 200) + 'px';
		endPos.opacity = 0;

		F.wrap.css(endPos).show().animate({
			top: '+=200px',
			opacity: 1
		}, {
			duration: F.current.openSpeed,
			complete: F._afterZoomIn
		});
	};

	// Closing animation - fly to the top
	F.transitions.dropOut = function() {
		F.wrap.removeClass('fancybox-opened').animate({
			top: '-=200px',
			opacity: 0
		}, {
			duration: F.current.closeSpeed,
			complete: F._afterZoomOut
		});
	};

	// Next gallery item - fly from left side to the center
	F.transitions.slideIn = function() {
		var endPos = F._getPosition(true);

		endPos.left = (parseInt(endPos.left, 10) - 200) + 'px';
		endPos.opacity = 0;

		F.wrap.css(endPos).show().animate({
			left: '+=200px',
			opacity: 1
		}, {
			duration: F.current.nextSpeed,
			complete: F._afterZoomIn
		});
	};

	// Current gallery item - fly from center to the right
	F.transitions.slideOut = function() {
		F.wrap.removeClass('fancybox-opened').animate({
			left: '+=200px',
			opacity: 0
		}, {
			duration: F.current.prevSpeed,
			complete: function () {
				$(this).trigger('onReset').remove();
			}
		});
	};

}(jQuery, jQuery.fancybox));


//===============================================================================
function showONas(jezyk_skrot)
{
    $.ajax({
        type : 'POST',
        url : '/'+jezyk_skrot+'/onas',
        dataType : 'html',
        success : function(data) {
            jQuery.fancybox({
                'content': data
            });
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
        }
    });
}

//===============================================================================
function showPartnerzy()
{
    $.ajax({
        type : 'POST',
        url : '/partnerzy/listaAjax',
        dataType : 'html',
        success : function(data){
            $.fancybox({
                'content': data
            });
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
        }
    });
}

//===============================================================================
function showGalery()
{
	var _items = [];
	$.getJSON('/galeria/show', function(data) {


		$.each(data, function(key, val) {			
			_items.push({'href' : key, 'title' : val});
		});
		//	_items.push({'href' : 'http://farm5.static.flickr.com/4044/4286199901_33844563eb.jpg', 'title' : ''});
		//	_items.push({'href' : 'http://farm3.static.flickr.com/2687/4220681515_cc4f42d6b9.jpg', 'title' : ''});
		//	_items.push({'href' : 'http://farm5.static.flickr.com/4005/4213562882_851e92f326.jpg', 'title' : ''});

//		'scrolling'   : 'no',
//		'autoDimensions' : false,
//		 'autoScale'         : false,

		$.fancybox(_items, {
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false,
		 'autoScale'         : false,
		openMethod : 'dropIn',
		openSpeed : 2,
		

		closeMethod : 'dropOut',
		closeSpeed : 1,

		nextMethod : 'changeIn',
		nextSpeed : 2,

		prevMethod : 'changeOut',
		prevSpeed : 2,
		
		//fitToView : true,
		//autoSize : false
		

		/*
		  afterShow: function() {
		        setTimeout(function() {
		            var contentHeight = $.fancybox.current.height;
		            var innerHeight = $.fancybox.inner.height();
		            var skinWidth = $.fancybox.skin.width();
		            var innerWidth = $.fancybox.inner.width();
		
		            if (contentHeight > innerHeight) {
		                var scrollbarWidth = $.getScrollbarWidth();
		                $.fancybox.skin.width(skinWidth + scrollbarWidth);
		                $.fancybox.inner.width(innerWidth + scrollbarWidth);
		            }
		        }, 210);
		    }
		    */

		});

		/*
		openMethod : 'dropIn',
		openSpeed : 250,

		closeMethod : 'dropOut',
		closeSpeed : 150,

		nextMethod : 'slideIn',
		nextSpeed : 250,

		prevMethod : 'slideOut',
		prevSpeed : 250
		*/

	});
}

//===================== LOGOWANIE =========================================
function zaloguj(url, jezyk_id)
{
	var login = $("#login").val();
	var passwd = $("#passwd").val();

	var funcWyczyscBledy = function () {
        $("#login_error").html('');
    }
    $("#login").click(funcWyczyscBledy);
    $("#passwd").click(funcWyczyscBledy);

	$.ajax({
		type : 'POST',
		url : '/klient/logowanie',
		dataType : 'html',
		data: {
			login: login,
			haslo: passwd,
            jezyk_id: jezyk_id
		},
		success : function(data){

			if(data=="1")
			{
				if(url!="")
				{
					window.location = url;
				}
				else
				{				
					window.location = "/";
				}
			}
			else
			{
                var divInnerEl = '<div class="formErrorContent">' + data + '<br></div>';
                var divEl = '<div class="formError" style="opacity: 0.87; position: absolute; top: 0px; left: 0px; margin-top: 0px;">' + divInnerEl + '</div>'
				$("#login_error").html(divEl);
			}

		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});

}

//===============================================================================
function przypomnijHaslo(jezyk_id)
{
	//$("#loginFormBox").html("xcxczdc");

	$.ajax({
		type : 'POST',
		url : '/klient/panelHaslo',
		dataType : 'html',
		data: {
			jezyk_id: jezyk_id
			
		},
		success : function(data){
			$("#loginFormBox").html(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}
//===============================================================================
function zmianaHaslaSend(jezyk_id)
{
	//$("#loginFormBox").html("xcxczdc");
	
	var login = $("#login").val();
	var passwd = $("#passwd").val();
	var passwd_potw = $("#passwd_potw").val();
	

	$.ajax({
		type : 'POST',
		url : '/klient/przypomnienieHasla',
		dataType : 'html',
		data: {
			jezyk_id: jezyk_id,
			login: login,
			passwd: passwd,
			passwd_potw: passwd_potw
			
		},
		success : function(data){
			$("#loginFormBox").html(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}

//===============================================================================
function loginPanel(jezyk_id,url)
{
	$.ajax({
		type : 'POST',
		url : '/klient/panelLogowanie',
		dataType : 'html',
		data: {
			jezyk_id: jezyk_id,
			url: url
		},
		success : function(data){


			$.fancybox(
			{
			'content': data

			}
			);

		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});

}
//===============================================================================
function zmienJezyk(jezyk_in, jezyk_out, url)
{	
	$.ajax({
		type : 'POST',
		url : '/podstrona/zmienJezyk',
		dataType : 'html',
		data: {
			jezyk_in: jezyk_in,
			jezyk_out: jezyk_out,
			url: url
		},
		success : function(data){
			
			if(data!="")
			{
				window.location = data;
			}
			else
			{
				window.location = '/pl/';
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}
//===============================================================================
function imprezaZapytanie(jezyk_id)
{
	var formularz = $("#imprezaZapytanieForm").serializeArray();

	$.ajax({
		type : 'POST',
		url : '/impreza/imprezaZapytanie',
		dataType : 'html',
		data: {
			daneform: formularz
		},
		success : function(data){			
			
			if(data == 1)
			{
				var html = '';
                if(jezyk_id == 2) {
                    html = '<div class="fancyKomunikat">Query has been sent.</div>';
                } else {
                    html = '<div class="fancyKomunikat">Zapytanie zostało wysłane.</div>';

                }
				$("#mainBox").html(html);
			}
			else
			{
				$("#info").html("Wystąpił błąd podczas zapisu");
				alert(data);
			}
			


		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}
//===============================================================================
function kupTerazWyslij(jezyk_id)
{
	var formularz = $("#kupTerazForm").serializeArray();

	$.ajax({
		type : 'POST',
		url : '/karta/kupKarteZapis',
		dataType : 'html',
		data: {
			daneform: formularz
		},
		success : function(data)
		{
			if(data==1)
			{
				var html = '';
				if(jezyk_id == 2) {
                    html = '<div class="fancyKomunikat">Thank you for your order. You will receive email with instructions to finalise this transaction.</div>';
                } else {
                    html = '<div class="fancyKomunikat">Dziękujemy za zakup. Za chwilę otrzymasz e-mail z dalszymi instrukcjami w celu finalizacji transakcji.</div>';
                }
                $("#mainBox").html(html);
			}
			else
			{
				$("#info").html("Wystąpił błąd podczas zapisu");
				//alert(data);
			}
			


		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}
//===============================================================================
function szkolenieZapis()
{
	var formularz = $("#rejestracjaSzkolenieForm").serializeArray();

	$.ajax({
		type : 'POST',
		url : '/szkolenie/szkolenieZapis',
		dataType : 'html',
		data: {
			daneform: formularz
		},
		success : function(data){

			
			
			if(data==1)
			{
				var html = '<div class="fancyKomunikat">Zgłoszenie zostało wysłane</div>';
				$("#mainBox").html(html);
			}
			else
			{
				$("#info").html("Wystąpił błąd podczas zapisu");
				alert(data);
			}
			


		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}
//===============================================================================
function newsletterPanel(jezyk_skrot)
{
    $.ajax({
        type : 'POST',
        url : '/'+jezyk_skrot+'/newsletter',
        dataType : 'html',

        success : function(data) {
            jQuery.fancybox({
            'content': data
            });
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
        }
    });
}

//===============================================================================
function rejestracjaPanel(jezyk_id)
{
    $.ajax({
        type : 'POST',
        url : '/klient/panelRejestracja',
        dataType : 'html',
        data: {
            jezyk_id: jezyk_id
        },

        success : function(data){
            $.fancybox(
            {
                'content': data
            }
            );
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
        }
    });
}

//===============================================================================
function zmianaDanychPanel(jezyk_id)
{
    $.ajax({
        type : 'POST',
        url : '/klient/panelZmianaDanych',
        dataType : 'html',
        data: {
            jezyk_id: jezyk_id
        },

        success : function(data){
            $.fancybox(
            {
                'content': data
            }
            );
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
        }
    });
}

//===============================================================================
function szkolenieZapiszPanel(jezyk_id)
{
    $.ajax({
        type : 'POST',
        url : '/szkolenie/panelZapisz',
        dataType : 'html',
        data: {
            jezyk_id: jezyk_id
        },

        success : function(data){
            $.fancybox(
            {
                'content': data
            }
            );
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
        }
    });
}
//===============================================================================
function showFormularzKupTeraz(jezyk_id, karta_id, zalogowany)
{
    if(zalogowany) {
        $.ajax({
                type : 'POST',
                url : '/karta/formularzKupTeraz',
                dataType : 'html',
                data: {
                    jezyk_id: jezyk_id,
                    karta_id: karta_id
                },

                success : function(data){
                    $.fancybox(
                        {
                            'content': data
                        }
                    );
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("error");
                }
        });
    } else {
        var boxHtml = '';
        if(jezyk_id == 2) {
            boxHtml = '<div style="padding:50px;">To purchase you must be logged in.</div>';
        } else {
            boxHtml = '<div style="padding:50px;">Aby dokonać zakupu musisz być zalogowany.</div>';
        }

        $.fancybox(
            {
                'content': boxHtml
            }
        );
    }
}

//===============================================================================
function impreazZapytajPanel(jezyk_id)
{
	$.ajax({
		type : 'POST',
		url : '/impreza/panelZapytaj',
		dataType : 'html',
		data: {
			jezyk_id: jezyk_id
        },

        success : function(data){
            $.fancybox(
            {
                'content': data
            }
            );
        },
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}

//===============================================================================
function rejestracja(jezyk_id)
{
	var formularz = $("#rejestracjaForm").serializeArray();

	$.ajax({
		type : 'POST',
		url : '/klient/rejestracjaZapis',
		dataType : 'html',
		data: {
			daneform: formularz
		},
		success : function(data){
			if(data == 1)
			{
                var html = '';
                if(jezyk_id == 1) {
                    html = '<div class="fancyKomunikat" style="width: 590px;">Konto zostało utworzone</div>';
                } else {
                    html = '<div class="fancyKomunikat" style="width: 590px;">Your account has been created</div>';
                }
				$("#mainBox").html(html);
			}
			else
			{
				$("#info").html("Wystąpił błąd podczas zapisu");
				alert(data);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}

//===============================================================================
function zmianaDanych(jezyk_id)
{
	var formularz = $("#zmianaDanychForm").serializeArray();

	$.ajax({
		type : 'POST',
		url : '/klient/zmianaDanychZapis',
		dataType : 'html',
		data: {
			daneform: formularz
		},
		success : function(data){
			if(data == 1)
			{
                var html = '';
                if(jezyk_id == 1) {
                    html = '<div class="fancyKomunikat" style="width: 590px;">Dane zostały zmienione</div>';
                } else {
                    html = '<div class="fancyKomunikat" style="width: 590px;">Your account has been changed</div>';
                }
				$("#mainBox").html(html);
			}
			else
			{
				$("#info").html("Wystąpił błąd podczas zapisu");
				alert(data);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("error");
		}
	});
}

