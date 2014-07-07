<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>My Mass Market</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="/www/cms/css/flags.css" rel="stylesheet" type="text/css"/>
    <link href="/www/cms/css/fileuploader.css" rel="stylesheet" type="text/css"/>
    <link href="/www/cms/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="/www/cms/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/www/cms/css/iris.css" rel="stylesheet" type="text/css"/>
    <link href="/www/cms/css/MonthPicker.2.1.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="/www/cms/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="/www/cms/js/jquery-ui.js"></script>
    <script type="text/javascript" src="/www/cms/js/color.js"></script>
    <script type="text/javascript" src="/www/cms/js/iris.js"></script>
    <script type="text/javascript" src="/www/cms/js/MonthPicker.2.1.min.js"></script>
    <script type="text/javascript" src="/www/cms/js/engine.js"></script>

    <script type="text/javascript">

        //========================= INCLUDE ============================
        {$js_body}
        //========================= INCLUDE ============================
        {literal}

        var running = 0;
        jQuery(document).ready(function () {
            function createUploaderAktualnosci() {
                if (typeof(qq) !== "undefined") {

                    var uploader = new qq.FileUploader({
                        element: document.getElementById('zdjeciaAktualnosci'),
                        action: '/cms/aktualnosci/pliki/',
                        params: {
                            aktualnosc_id: $("#aktualnosc_id").val(),
                            formToken: $("#formToken").val()
                        },
                        onSubmit: function (id, fileName) {
                            running++;
                        },
                        onComplete: function (id, fileName, responseJSON) {
                            running--;
                            if (running == 0) {
                                reloadFotoLista($("#aktualnosc_id").val(), $("#formToken").val());
                            }
                        },
                        debug: true
                    });
                }
            }

            if (jQuery("#zdjeciaAktualnosci").length > 0) {
                createUploaderAktualnosci();
            }
            //============================================
            function createUploaderGaleria() {
                if (typeof(qq) !== "undefined") {

                    var uploader = new qq.FileUploader({
                        element: document.getElementById('file-uploader-galeria'),
                        action: '/cms/galeria/pliki/',
                        params: {
                            gal_id: $("#gal_id").val(),
                            formToken: $("#formToken").val()
                        },
                        onSubmit: function (id, fileName) {
                            running++;
                        },
                        onComplete: function (id, fileName, responseJSON) {
                            running--;
                            if (running == 0) {
                                reloadFotoListaGaleria($("#gal_id").val(), $("#formToken").val());
                            }
                        },
                        debug: true
                    });
                }
            }

            if (jQuery("#file-uploader-galeria").length > 0) {
                createUploaderGaleria();
            }


            //============================================
            function createUploaderTowary() {
                if (typeof(qq) !== "undefined") {

                    var uploader = new qq.FileUploader({
                        element: document.getElementById('zdjeciaTowary'),
                        action: '/cms/towary/pliki/',
                        params: {
                            rekord_id: $("#rekord_id").val(),
                            formToken: $("#formToken").val()
                        },
                        onSubmit: function (id, fileName) {
                            running++;
                        },
                        onComplete: function (id, fileName, responseJSON) {
                            running--;
                            if (running == 0) {
                                reloadFotoLista($("#rekord_id").val(), $("#formToken").val());
                            }
                        },
                        debug: true
                    });
                }
            }

            if (jQuery("#zdjeciaTowary").length > 0) {
                createUploaderTowary();
            }


            //============================================
            function createUploader() {
                if (typeof(qq) !== "undefined") {
                    var uploader = new qq.FileUploader({
                        element: document.getElementById('file-uploader-hostessa'),
                        action: '/cms/hostessy/pliki/',
                        params: {
                            hostessa_id: $("#hostessa_id").val()
                        },
                        debug: true
                    });
                }
            }

            if (jQuery("#file-uploader-hostessa").length > 0) {
                createUploader();
            }
            //============================================

            if (jQuery('#tabs_skrot').length > 0) {
                jQuery('#tabs_skrot').tabs();
            }

            if (jQuery('#tabs_tresc').length > 0) {
                jQuery('#tabs_tresc').tabs();
            }
            jQuery('.pole_data').datepicker({ dateFormat: 'yy-mm-dd', dayNamesMin: ['Nd', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So'], monthNames: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'], });

            jQuery('#menu2 li.row_menu').click(function () {

                var katId = jQuery(this).attr('menuKategoriaId');
                var holder = jQuery('#menu2 ul[menuKategoriaId=' + katId + ']');
                if (holder.hasClass('ukryte')) {
                    holder.removeClass('ukryte');
                    jQuery(this).removeClass('row_menu');
                    jQuery(this).addClass('active');
                } else {
                    holder.addClass('ukryte');
                    jQuery(this).removeClass('active');
                    jQuery(this).addClass('row_menu');
                }


            });


            $("#sorting ul").sortable();
            $("#sorting_hostessy ul").sortable();
            $("#sorting_aktualnosci ul").sortable();
            $("#sorting_rekordy ul").sortable();


            jQuery('.komunikatWarning').effect("pulsate", {}, 800);
            jQuery('.komunikatError').effect("pulsate", {}, 800);
        });
        {/literal}
    </script>


</head>
<body>
<div id="header">
    <a href="javascript:;" onClick="f_ukryj_konsole();"><img src="{$img_path}logo.png" class="logo"/></a>

    <div class="buttonTopMenu"><a href="/cms/login/wyloguj"><img src="{$img_path}button-logout.png"/> wyloguj </a></div>
    <div class="admin">
        <img src="{$img_path}icon/admin_icon.png" class="admin_icon"/>
        <span class="admin_dane">{$smarty.session.admin_dane}</span>
        <a href="javascript:;" onClick="f_ukryj_konsole();"><img src="{$img_path}icon/settings_icon.png"
                                                                 class="settings_icon"></a>
        <a href="http://sql.kiwi.home.pl/" target="_blank">phpMyAdmin</a>
    </div>

</div>
<div id="main">
    <!--  <div id="menu">-->
    <div id="menu2">
        <div class="topL">&nbsp;</div>
        <div class="topR">&nbsp;</div>
        <div class="bottomL">&nbsp;</div>
        <div class="bottomR">&nbsp;</div>

        {$menuGlowne}


    </div>


    <div id="content">
        {foreach from=$komunikaty key=k item=komunikatTmp}
            {if $komunikatTmp[0] == 'info'}
                <div class="komunikat komunikatInfo">{$komunikatTmp[1]}</div>
            {elseif $komunikatTmp[0] == 'ok'}
                <div class="komunikat komunikatOk">{$komunikatTmp[1]}</div>
            {elseif $komunikatTmp[0] == 'warning'}
                <div class="komunikat komunikatWarning">{$komunikatTmp[1]}</div>
            {else}
                <div class="komunikat komunikatError">{$komunikatTmp[1]}</div>
            {/if}
        {/foreach}

        <!--  <h1>{$tytul}</h1>-->
        {$tresc}
        <div style="background-color:red;">
            {$bledy}
        </div>
    </div>

</div>
</body>
</html>
