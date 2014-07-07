<div class="aktualnoscSzczegoly">
	<h2 class="aktualnoscTresc">{$aktualnosc->tytul.$jezyk_id}</h2>
	<div style="position:relative;">	
	{if $aktualnosc->zdjecie_glowne!=''}<img src="/images/aktualnosci/2/{$aktualnosc->zdjecie_glowne}"  style="float:left; margin:0px 20px 20px 0px">{/if}
	
	<div style="display:table;">
	
	{foreach from=$aktualnosc->zdjecia key=id_zdjecie item=sciezka}
		<img src="/images/aktualnosci/1/{$sciezka}"  style="max-width:165px; max-height:100px; padding:0px 15px 15px 0px;">
	{/foreach}
		
		<!--<img src="/www/page/img/animacja_5.png"  style="max-width:165px; max-height:100px; padding-right:15px;">
		<img src="/www/page/img/animacja_5.png"  style="max-width:165px; max-height:100px; padding-right:15px;">
		<img src="/www/page/img/animacja_5.png"  style="max-width:165px; max-height:100px; padding-right:15px;">
		<img src="/www/page/img/animacja_5.png"  style="max-width:165px; max-height:100px; padding-right:15px;">
		<img src="/www/page/img/animacja_5.png"  style="max-width:165px; max-height:100px; padding-right:15px;">-->
		
		
	</div>
	
	<span class="aktualnoscTresc" >{$aktualnosc->tresc.$jezyk_id}</span>
	
	</div>
	<div style="clear:both;">
	{if  $link_powrot!=''}
		<a href="{$link_powrot}" class="aktualnoscTresc">powrót....</a>
	{/if}
	</div>
</div>