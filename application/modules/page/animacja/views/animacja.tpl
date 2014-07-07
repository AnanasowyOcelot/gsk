<div id="bn_frame">
	<div id="navPicsPagination"></div>
	<div id="navPics">		
		{foreach from=$a_zdjecia  key=index item=rekord}
		<div class="picsBox" style="position:relative;">
			<div class="pic"><img title="" alt="" src="/images/animacja/2/{$rekord->zdjecie}" style="max-height:280px;"/></div>	
			<div class="headers">
				<div class="header">{$rekord->opis.$jezyk_id}</div>
				{if $rekord->link.$jezyk_id!=''}
				<div class="btn"><a href="{$rekord->link.$jezyk_id}" class="linkWiecej">więcej...</a></div>
				{/if}
			</div>
		</div>	
		{/foreach}
	</div>
</div>