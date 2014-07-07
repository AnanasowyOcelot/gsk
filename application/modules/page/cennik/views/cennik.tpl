
	{foreach from=$cennik key=index  item=rekord}
	<div class="{$rekord.classa}">
		<div class="cennikNaglowek">{$rekord.naglowek.cennik_naglowek_nazwa}</div>
		{foreach from=$rekord.sekcje key=index_sekcja  item=sekcja}
			{$sekcja.widok}
		{/foreach}	
	</div>	
	{/foreach}	
	
