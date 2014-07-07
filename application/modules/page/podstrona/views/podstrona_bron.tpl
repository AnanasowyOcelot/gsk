<script>
{literal}
var bronObrazekSzerokosc = 1649;
var bronAktywnaNumer = 0;
var a_listaIdBroni = [];

function bronPowiekszAktywna() {
    for (var i = 0; i < a_listaIdBroni.length; i ++) {
        var idBroni = a_listaIdBroni[i];
        if(i == bronAktywnaNumer) {
            var obrazekSrc = jQuery('#bronGaleria img#obrazek_' + idBroni).attr('src');
            jQuery.fancybox({
                'href': obrazekSrc,
                'type': 'image',
                'autoSize': true,
                'fitToView': true,
                'closeClick': true,
                'scrolling': 'no',
                'helpers': {
                    overlay: {
                        opacity: 0.9,
                        closeClick: true,
                        css : {
                            //'background' : 'rgba(0, 0, 0, 0.95)'
                        }
                    }
                    
                }/*,
                'tpl': {
                    wrap: '<div class="fancybox-wrap bronPowiekszenie"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>'
                }*/
            });
            
            
            
            return true;
        }
    }
    return false;
}

function bronPrzewinDoAktywnej() {
    if(bronAktywnaNumer > 0) {
        jQuery('#bronGaleria .arrLeft').stop().fadeIn();
    } else {
        jQuery('#bronGaleria .arrLeft').stop().fadeOut();
    }
    
    if(bronAktywnaNumer < a_listaIdBroni.length - 1) {
        jQuery('#bronGaleria .arrRight').stop().fadeIn();
    } else {
        jQuery('#bronGaleria .arrRight').stop().fadeOut();
    }
    
    jQuery('#bronGaleria .wrapper').stop().animate({
        left: -bronObrazekSzerokosc * bronAktywnaNumer
    }, 600);
    
    for (var i = 0; i < a_listaIdBroni.length; i ++) {
        var idBroni = a_listaIdBroni[i];
        if(i == bronAktywnaNumer) {
            jQuery('#bronOpis' + idBroni).stop(true, true).fadeIn();
            jQuery('#bronLista a.aktywna').removeClass('aktywna');
            jQuery('#bronLista #wierszBron_'+idBroni+' a').addClass('aktywna');
        } else {
            jQuery('#bronOpis' + idBroni).stop(true, true).fadeOut();
        }
    }
}

function bronPrzewinDoBroniOId(idBroni) {
    jQuery('#bronLista a.aktywna').removeClass('aktywna');
    jQuery('#bronLista #wierszBron_'+idBroni+' a').addClass('aktywna');
    
    for (var i = 0; i < a_listaIdBroni.length; i ++) {
        var idBroniTmp = a_listaIdBroni[i];
        if(idBroni == idBroniTmp) {
            if(bronAktywnaNumer != i) {
                bronAktywnaNumer = i;
                bronPrzewinDoAktywnej();
                return true;
            }
        }
    }
    return false;
}

function zaladujZdjeciaBroni() {
    jQuery('#bronGaleria .zdjecieBroni').each(function () {
        jQuery(this).attr('src', jQuery(this).attr('data_src'));
    });
}

jQuery(document).ready(function () {
    var listaIdBroni = '' + jQuery('#indexyObrazki').val();
    a_listaIdBroni = listaIdBroni.split(',');
    
    jQuery('#bronGaleria .wrapper').css('width', bronObrazekSzerokosc * a_listaIdBroni.length + 10);
    
    jQuery('#bronGaleria .arrLeft').click(function () {
        if(bronAktywnaNumer > 0) {
            bronAktywnaNumer --;
            bronAktywnaNumer = Math.max(bronAktywnaNumer, 0);
        
            bronPrzewinDoAktywnej();
        } else {
            bronAktywnaNumer = 0;
        }
    });
    
    jQuery('#bronGaleria .arrRight').click(function () {
        if(bronAktywnaNumer < a_listaIdBroni.length - 1) {
            bronAktywnaNumer ++;
            bronAktywnaNumer = Math.min(bronAktywnaNumer, a_listaIdBroni.length - 1);
            
            bronPrzewinDoAktywnej();
        } else {
            bronAktywnaNumer = a_listaIdBroni.length - 1;
        }
    });

    jQuery('#bronGaleria a.zoom').click(function () {
        bronPowiekszAktywna();
    });

    jQuery('#bronLista').jScrollPane({
        showArrows: true
    });
    
    bronPrzewinDoAktywnej();

    zaladujZdjeciaBroni();
});
{/literal}
</script>

<div id="content">

    <div id="bronGaleria">
       
        
        {$lista_broni}
   
        <a href="javascript:void(0);" class="arrLeft" style="display: none;"><img src="/www/page/img/galeria_arr_left.png" /></a>
        <a href="javascript:void(0);" class="arrRight"><img src="/www/page/img/galeria_arr_right.png" /></a>
        
        <a href="javascript:void(0);" class="zoom"><img src="/www/page/img/zoom.png" /></a>

        <div id="bronLista">
            <div id="bronListaWrapper">
            {$menu_broni}
               
            </div>
        </div>


    </div>
</div>
