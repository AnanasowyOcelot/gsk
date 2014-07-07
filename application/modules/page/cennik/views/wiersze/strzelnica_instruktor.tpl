{assign var="sekcja_id" value=$sekcja.cs_id}
<div class="cennikWartosci">
	<div class="opis">{$sekcja.cs_opis}&nbsp;</div>
	<div class="boxCennikLinia">
			
			<div class="boxCennikBig">
				<div class="boxNaglowekBig">{$wartosci.tor_wynajecie_30.$sekcja_id.naglowek} min.</div>
				<div class="boxCenaBig">{$wartosci.tor_wynajecie_30.$sekcja_id.cena} zł</div>
			</div>
			<div class="boxCennikBig">
				<div class="boxNaglowekBig">{$wartosci.tor_wynajecie_60.$sekcja_id.naglowek} min.</div>
				<div class="boxCenaBig">{$wartosci.tor_wynajecie_60.$sekcja_id.cena} zł</div>
			</div>			
	</div>
</div>
