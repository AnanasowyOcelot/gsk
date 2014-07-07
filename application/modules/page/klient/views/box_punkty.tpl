<div>
	{if $zalogowany eq 1}
	<div class="cufonHeader ">{$imie} {$nazwisko}</div> 
	<div class="klubZalogowanyTxt">
		{$komunikaty.$jezyk_id.text_zalogowany}
	</div>
	<div>
		<div class="klubPunktyUser">
			
				{$klient->punkty}
			
		</div>
	</div>
	{else}
	<div class="cufonHeader ">{$komunikaty.$jezyk_id.przylacz}</div> 
	<div class="klubNiezalogowanyWiersz " style="margin-top:10px;">{$komunikaty.$jezyk_id.text_niezalogowany}</div> 
	<div class="klubNiezalogowanyWiersz ">{$komunikaty.$jezyk_id.niezalogowany}</div> 
	
    <div class="linkCufon"><a style="font-size:15px;color:#f68702;font-weight:bold;" href="javascript:void(0);" onclick="rejestracjaPanel({$jezyk_id});">{$komunikaty.$jezyk_id.zapisz_sie}</a></div>
	{/if}
	
</div>