<style>
{literal}
.registerInput{
    width:260px; height:27px; border:1px solid #FFF; background:#633b0c; color: #FFF; padding: 0 3px; float: right;
}
.registerTextarea{
    width:260px; height:60px; border:1px solid #FFF; background:#633b0c; color: #FFF; padding: 0 3px; float: right;
}

.label{
    display:inline-block; width: 150px; padding-bottom:2px; font-size:15px;
}
.wiersz{
    margin-bottom: 6px;
    overflow: hidden;
}
{/literal}
</style>

<form method="POST" name="imprezaZapytanieForm" id="imprezaZapytanieForm" action="">
    <div style="padding:0px 20px 0px 20px; width: 432px;" id="mainBox">

        <div class="cufonHeader" style="font-size:36px; color:#f68702;">{$naglowki.$jezyk_id.tytul}</div>

        <div style="overflow:hidden; position:relative;">
            <div style="margin:0px 0px 0px 0px; float:left;">
                <div class="wiersz">
                    <label for="" class="label" style="width: 100%; padding: 10px 0;">{$naglowki.$jezyk_id.opis}</label>                    
                </div>
                <div class="wiersz">
                    <label for="imie" class="label">{$naglowki.$jezyk_id.rodzaj_imprezy }:</label>
                    <input type="text" id="rodzaj_imprezy" name="rodzaj_imprezy" class="registerInput validate[required]"/>
                </div>
                <div class="wiersz">
                    <label for="nazwisko" class="label">{$naglowki.$jezyk_id.planowana_data }: </label>
                    <input type="text" id="planowana_data" name="planowana_data" class="registerInput"/>
                </div>
                <div class="wiersz">
                    <label for="adres" class="label">{$naglowki.$jezyk_id.lokalizacja }: </label>
                    <input type="text" id="lokalizacja" name="lokalizacja" class="registerInput"/>
                </div>
                <div class="wiersz">
                    <label for="adres" class="label">{$naglowki.$jezyk_id.liczba_osob }: </label>
                    <input type="text" id="liczba_osob" name="liczba_osob" class="registerInput"/>
                </div>
                <div class="wiersz">
                    <label for="kod" class="label">{$naglowki.$jezyk_id.czas_trwania }: </label>
                    <input type="text" id="czas_trwania" name="czas_trwania" class="registerInput"/>
                </div>
                <div class="wiersz">
                    <label for="kod" class="label">{$naglowki.$jezyk_id.osoba_do_kontaktu }: </label>
                    <input type="text" id="osoba_do_kontaktu" name="osoba_do_kontaktu" class="registerInput validate[required]" value="{$zalogowany_imie} {$zalogowany_nazwisko}"/>
                </div>
                <div class="wiersz">
                    <label for="miasto" class="label">{$naglowki.$jezyk_id.email_telefon }: </label>
                    <input type="text" id="email_telefon" name="email_telefon" class="registerInput validate[required]" value="{$zalogowany_email} / {$zalogowany_telefon}"/>
                </div>
            </div>

        </div>

        <div style="position:relative; overflow:hidden;">    
            <a href="javascript:void(0)" {literal}onclick="if(jQuery('#imprezaZapytanieForm').validationEngine('validate')) {imprezaZapytanie({/literal}{$jezyk_id}{literal});}"{/literal} style="float:right; color:#f68702; text-decoration:none; font-size:25px;" class="buttonLogin">{$naglowki.$jezyk_id.button}</a>
            <a href="javascript:void(0)" onclick="" style="float:right; color:#535353; text-decoration:none; font-size:25px; margin-right: 15px;" class="buttonLogin przyciskWyczysc">{$naglowki.$jezyk_id.button_wyczysc}</a>                        
        </div>

        <div id="info"></div>
    </div>
</form>

<script>
{literal}
jQuery(document).ready(function () {
    Cufon.replace(".cufonHeader" , { fontFamily: 'PF Handbook Pro' });
    Cufon.replace(".label" , { fontFamily: 'PF Handbook Pro' });
    Cufon.replace(".buttonLogin" , { fontFamily: 'PF Handbook Pro' });
    
    jQuery('#imprezaZapytanieForm .przyciskWyczysc').click(function () {
        jQuery('#imprezaZapytanieForm input').val('');
        
        $("#imprezaZapytanieForm").validationEngine('hideAll');
    });
    
    jQuery("#imprezaZapytanieForm").validationEngine('attach', {
        promptPosition: 'inline'
    });
    
    jQuery("#imprezaZapytanieForm input[type=text]").click(function () {
        jQuery(this).validationEngine('hide');
    });
});
{/literal}
</script>
