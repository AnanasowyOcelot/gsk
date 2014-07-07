<div style="float: left; overflow: hidden; width: 246px; height:222px ; padding: 25px 20px 20px 20px; margin: 80px 0 0 30px; background: #000000 url('/www/page/img/boxy/bg_imprezy_bron.png')">
	<div class="cufonHeader liniaHeader">{$box->tytul.$jezyk_id}</div> 
	<div class="" style="height:190px; overflow:hidden;">
	{if $box->zdjecie ne ""}
	<img src="/images/boxy/0/{$box->zdjecie}">
	{/if}
	</div>
	<div style="font-weight:bold;">{$box->tytul_tresc.$jezyk_id}</div>
</div>