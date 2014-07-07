<div id="headerZgloszenie" style="margin:30px 0px 30px 0px; font-size:20px;">Formularz zgłoszeniowy</div>
<form id="formularz" name="formularz" method="POST" action=""  enctype="multipart/form-data">
<input type="hidden" name="zapis" id="zapis" value="1">
<input type="hidden" name="nie_wymagane" value="biust,talia,biodra">
<input type="hidden" name="polecenie" id="polecenie" value="zgloszenie_hostessy">
<div style="position:relative; overflow:hidden;" id="formZgloszenie">
    <div style="float:left; width:463px; text-align: justify; font-size:14px; ">
    	<div><label for="imie">Imię</label><input type="text" id="imie" name="imie" value="{$dane.imie}" class="{$errors.imie}"/></div>
    	<div><label for="nazwisko">Nazwisko</label><input type="text" id="nazwisko" name="nazwisko" value="{$dane.nazwisko}" class="{$errors.nazwisko}"/></div>
    	<div><label for="plec">Płeć</label><input type="radio" name="plec" value="1" style="width:50px;" {if $dane.plec=='' || $dane.plec=='1'}checked{/if}>kobieta<input type="radio" name="plec" value="2" style="width:50px;" {if $dane.plec=='2'}checked{/if}>mężczyzna</div>
    	<div><label for="data_ur">Data ur.</label><input type="text" id="data_ur" name="data_ur" value="{$dane.data_ur}" class="{$errors.data_ur}"/></div>
    	<div><label for="telefon">Telefon</label><input type="text" id="telefon" name="telefon" value="{$dane.telefon}" class="{$errors.telefon}"/></div>
    	<div><label for="email">E-mail</label><input type="text" id="email" name="email" value="{$dane.email}" class="{$errors.email}"/></div>  
    	<div>załącz zdjęcia</div> 	
    	<div style="margin-top:20px;">
		<input type="file" id="zdjecie_1" name="zdjecie_1" class="inputFile">
		<input type="file" id="zdjecie_1" name="zdjecie_2" class="inputFile">
		<input type="file" id="zdjecie_1" name="zdjecie_3" class="inputFile">
		<input type="file" id="zdjecie_1" name="zdjecie_4" class="inputFile">
		<input type="file" id="zdjecie_1" name="zdjecie_5" class="inputFile">
		<input type="file" id="zdjecie_1" name="zdjecie_6" class="inputFile">
	</div>	
</div>
<div class="zgloszenieLewaKolumna">           
      	<div><label for="wzrost">Wzrost</label><input type="text" id="wzrost" name="wzrost" value="{$dane.wzrost}" class="{$errors.wzrost}"/></div>
      	<div><label for="biust">Biust</label><input type="text" id="biust" name="biust" value="{$dane.biust}" class="{$errors.biust}"/></div>
      	<div><label for="talia">Talia</label><input type="text" id="talia" name="talia" value="{$dane.talia}" class="{$errors.talia}"/></div>
      	<div><label for="biodra">Biodra</label><input type="text" id="biodra" name="biodra" value="{$dane.biodra}" class="{$errors.biodra}"/></div>
      	<div><label for="kolor_oczu">Kolor oczu</label><input type="text" id="kolor_oczu" name="oczy" value="{$dane.oczy}" class="{$errors.oczy}"/ ></div>
      	<div><label for="kolor_wlosow">Kolor włosów</label><input type="text" id="kolor_wlosow" name="wlosy" value="{$dane.wlosy}" class="{$errors.wlosy}"/></div>
     	 <div><label for="rozmiar_ubrania">Rozmiar ubrania</label><input type="text" id="rozmiar_ubrania" name="ubrania" value="{$dane.ubrania}" class="{$errors.ubrania}"/></div>
     	 <div><label for="jezyki">Języki obce</label><input type="text" id="jezyki" name="jezyki" value="{$dane.jezyki}" class="{$errors.jezyki}"/></div>
	<div><label for="dyspozycyjnosc">Dyspozycyjność</label><input type="text" id="dyspozycyjnosc" name="dyspozycyjnosc" value="{$dane.dyspozycyjnosc}" class="{$errors.dyspozycyjnosc}"/></div>
      </div>
</div>
	


<div id="zgoda" style="margin-top:20px; position:relative">
	<div style="float:left; width:27px;" value="1">
		<input type="hidden" name="zgoda" value="">
		<input type="checkbox" name="zgoda" value="1"  {if $dane.zgoda=='1'}checked{/if}>
	</div>
	<div style="float:left; width:900px;" class="{$errors.zgoda}"> Wyrażam zgodę na przetwarzanie moich danych osobowych przez Agencję Hostess DIAM zgodnie z Ustawą z dn. 29/08/1997 o ochronie danych osobowych, Dziennik Ustaw nr 133 pozycja 883. Wysyłając swoje zgłoszenie wyrażam jednocześnie zgodę na zamieszczenie zdjęć ze swoim wizerunkiem oraz danych osobowych zawartych w formularzu zgłoszeniowym na stronie internetowej diam.pl</div>
</div>
<div style="margin-top:20px;">
	<a href="javascript:;" onclick="$('#formularz').submit();" class="linkZgloszenie">wyślij zgłoszenie</a>
</div>

</form>