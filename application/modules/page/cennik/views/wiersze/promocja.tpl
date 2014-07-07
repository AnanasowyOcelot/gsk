{assign var="sekcja_id" value=$sekcja.cs_id}
<div class="cennikWartosci">
	<div class="opis">{$sekcja.cs_opis}&nbsp;</div>
	<div class="boxCennik">
		<div class="boxCennikBig" style="float:right;">				
				<div class="boxCenaBig">{$wartosci.promocja.$sekcja_id.cena} zł</div>
			</div>
	</div>
</div>
