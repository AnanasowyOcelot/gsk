{assign var="sekcja_id" value=$sekcja.cs_id}
<div class="cennikWartosci">
	<div class="opis">{$sekcja.cs_opis}&nbsp;</div>
	<div class="boxCennik">			
			<div class="boxCennikBig">
				<div class="boxNaglowekBig">{$naglowki.$jezyk_id.cennik_wynajecie_podstawowa}</div>
				<div class="boxCenaBig">{$wartosci.bron_podstawowa.$sekcja_id.cena} zł</div>
			</div>
			<div class="boxCennikBig">
				<div class="boxNaglowekBig">{$naglowki.$jezyk_id.cennik_wynajecie_extra}</div>
				<div class="boxCenaBig">{$wartosci.bron_extra.$sekcja_id.cena} zł</div>
			</div>			
	</div>
</div>
