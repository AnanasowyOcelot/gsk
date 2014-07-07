<style>
{literal}
.registerInput {
    width:160px; height:27px; border:1px solid #FFF; background:#633b0c; color: #FFF; padding: 0 3px;
}
.label {
    display:inline-block; width: 100px; padding-bottom:2px; font-size:15px;
}
.wiersz {
    margin-bottom: 6px;
    overflow: hidden;
}
.wiersz a {
    color: #ffffff;
}
.wiersz a:visited {
    color: #ffffff;
}

{/literal}
</style>

<form method="POST" name="zmianaDanychForm" id="zmianaDanychForm" action="/klient/zmienDane">
    <div style="padding:0px 20px 0px 20px; width: 630px;" id="mainBox">
        <div class="cufonHeader" style="font-size:36px; color:#f68702;">{$naglowki.$jezyk_id.tytul}</div>
        
		<div class="info" style="font-size: 16px;">{$naglowki.$jezyk_id.info}</div>
	
		<div style="float:left; overflow:hidden; position:relative; margin:10px 0px 15px 0px; padding:10px 0px 5px 0px;">
			<div style="margin:0px 0px 0px 0px; float:left;">
				<div class="wiersz">
					<label for="" class="label"><b>{$naglowki.$jezyk_id.dane_klienta}:</b></label>					
				</div>
				<div class="wiersz">
					<label for="imie" class="label">{$naglowki.$jezyk_id.imie }: </label>
					<input type="text" id="imie" name="klient_imie" class="registerInput" value="{$o_klient->imie}" />
				</div>
				<div class="wiersz">
					<label for="nazwisko" class="label">{$naglowki.$jezyk_id.nazwisko }: </label>
					<input type="text" id="nazwisko" name="klient_nazwisko" class="registerInput" value="{$o_klient->nazwisko}" />
				</div>
				<div class="wiersz">
					<label for="adres" class="label">{$naglowki.$jezyk_id.adres }: </label>
					<input type="text" id="adres" name="klient_adres" class="registerInput" value="{$o_klient->adres}" />
				</div>
				<div class="wiersz">
					<label for="kod" class="label">{$naglowki.$jezyk_id.kod }: </label>
					<input type="text" id="kod" name="klient_kod" class="registerInput" value="{$o_klient->kod}" />
				</div>
				<div class="wiersz">
					<label for="miasto" class="label">{$naglowki.$jezyk_id.miasto }: </label>
					<input type="text" id="miasto" name="klient_miasto" class="registerInput" value="{$o_klient->miasto}" />
				</div>
				<div class="wiersz">
					<label for="telefon" class="label">{$naglowki.$jezyk_id.telefon }: </label>
					<input type="text" id="telefon" name="klient_telefon" class="registerInput" value="{$o_klient->telefon}" />
				</div>
                <div class="wiersz">
                    <label class="label">&nbsp;</label>
                </div>
			</div>
			<div style="margin:0px 0px 0px 45px; float:left;">
				<div class="wiersz">
					<label for="" class="label"><b>{$naglowki.$jezyk_id.dane_firmy}:</b></label>					
				</div>
				<div class="wiersz">
					<label for="nazwa" class="label">{$naglowki.$jezyk_id.firma_nazwa}: </label>
					<input type="text" id="nazwa" name="firma_nazwa" class="registerInput" value="{$o_klient->firma_nazwa}" />
				</div>
				<div class="wiersz">
					<label for="firmaadres" class="label">{$naglowki.$jezyk_id.firma_adres}: </label>
					<input type="text" id="firmaadres" name="firma_adres" class="registerInput" value="{$o_klient->firma_adres}" />
				</div>
				<div class="wiersz">
					<label for="firmakod" class="label">{$naglowki.$jezyk_id.firma_kod}: </label>
					<input type="text" id="firmakod" name="firma_kod" class="registerInput" value="{$o_klient->firma_kod}" />
				</div>
				<div class="wiersz">
					<label for="firmamiasto" class="label">{$naglowki.$jezyk_id.firma_miasto}: </label>
					<input type="text" id="firmamiasto" name="firma_miasto" class="registerInput" value="{$o_klient->firma_miasto}" />
				</div>
				<div class="wiersz">
					<label for="firmanip" class="label">{$naglowki.$jezyk_id.firma_nip}: </label>
					<input type="text" id="firmanip" name="firma_nip" class="registerInput" value="{$o_klient->firma_nip}" />
				</div>
				<div class="wiersz">
					<label for="firmatelefon" class="label">{$naglowki.$jezyk_id.firma_telefon}: </label>
					<input type="text" id="firmatelefon" name="firma_telefon" class="registerInput" value="{$o_klient->firma_telefon}" />
				</div>
                <div class="wiersz" style="text-align:right; margin-top:10px;">
                    <a href="javascript:void(0)" {literal}onclick="if(jQuery('#zmianaDanychForm').validationEngine('validate')) {zmianaDanych({/literal}{$jezyk_id}{literal});}"{/literal} style="float:right; color:#f68702; text-decoration:none;  font-size:25px;" class="buttonLogin">{$naglowki.$jezyk_id.button}</a>
                </div>
			</div>
		
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
    Cufon.replace(".info" , { fontFamily: 'PF Handbook Pro' });
});

$("#zmianaDanychForm").validationEngine('attach', {
    promptPosition: 'inline'
});

$("#zmianaDanychForm input[type=text]").click(function () {
    jQuery(this).validationEngine('hide');
});

{/literal}
</script>
