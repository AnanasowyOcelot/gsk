{assign var="sekcja_id" value=$sekcja.cs_id}
<div class="cennikWartosci">
	<div class="opis">{$sekcja.cs_opis}&nbsp;</div>
	<div class="boxCennik">
			
			<div class="boxCennikSmall">
				<div class="boxNaglowekSmall">{$wartosci.karnet_5h.$sekcja_id.naglowek}h</div>
				<div class="boxCenaSmall">{$wartosci.karnet_5h.$sekcja_id.cena} zł</div>
			</div>
			<div class="boxCennikSmall">
				<div class="boxNaglowekSmall">{$wartosci.karnet_10h.$sekcja_id.naglowek}h</div>
				<div class="boxCenaSmall">{$wartosci.karnet_10h.$sekcja_id.cena} zł</div>
			</div>
			<div class="boxCennikSmall">
				<div class="boxNaglowekSmall">{$wartosci.karnet_15h.$sekcja_id.naglowek}h</div>
				<div class="boxCenaSmall">{$wartosci.karnet_15h.$sekcja_id.cena} zł</div>
			</div>
	</div>
</div>
