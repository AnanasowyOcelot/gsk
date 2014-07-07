{assign var="sekcja_id" value=$sekcja.cs_id}
<div class="cennikWartosci">
	<div class="opis opisAmmo">{$sekcja.cs_opis}&nbsp;</div>
	<div class="boxCennikLinia" style="padding-bottom:3px; width: 320px;">			
			<div class="boxCennikSmall" style="width:175px;">
				<div class="boxNaglowekMicro" style="text-align:left;">{$sekcja.cs_nazwa}</div>
				<div class="ammoPicture" style="display:block;"><img src="/www/page/img/amunicja/{$sekcja.cs_zdjecie}"></div>
			</div>
			<div class="boxCennikMicro">
				<div class="boxNaglowekMicro">{$wartosci.amunicja_szt.$sekcja_id.naglowek} {$naglowki.$jezyk_id.cennik_sztuka}</div>
				<div class="boxCenaMicro">{$wartosci.amunicja_szt.$sekcja_id.cena} zł</div>
			</div>
			<div class="boxCennikMicro">
				<div class="boxNaglowekMicro">{$wartosci.amunicja_op.$sekcja_id.naglowek} {$naglowki.$jezyk_id.cennik_op}</div>
				<div class="boxCenaMicro">{$wartosci.amunicja_op.$sekcja_id.cena} zł</div>
			</div>
	</div>
</div>
