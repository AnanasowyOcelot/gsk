<div class="aktualnoscSzczegoly">
	<h2 class="aktualnoscTresc">{$video->tytul.$jezyk_id}</h2>	
	<div style="position:relative;">	
		<div style="float:left; padding:0px 15px 15px 0px;">
			{if $video->typ==0}			
				<a href="/filmy/{$video->plik_fly}" style="display:block; width:425px;height:300px; padding:0px; margin:0px;"  id="player"></a>
			{elseif $video->typ==1}				
				<iframe width="425" height="300" src="{$video->film_youtube}" frameborder="0" allowfullscreen id="ekran"></iframe>				
			{else}
				<iframe src="http://player.vimeo.com/video/{$video->url_film}?title=0&amp;byline=0&amp;portrait=0" width="640" height="360"  frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			{/if}
		</div>
		<span class="aktualnoscTresc">{$video->tresc.$jezyk_id}</a>
	</div>
	<div style="clear:both;">
	
	{if  $link_powrot!=''}
		<a href="{$link_powrot}" class="aktualnoscTresc">powrót....</a>
	{/if}
	</div>
	
</div>

{literal}
<script>
jQuery(document).ready(function () {
	flowplayer("player", "/www/page/player/flowplayer-3.2.8.swf", {
		clip: {
			autoPlay: false,
			autoBuffering: true
		}
	});
	
		$("#ekran").load(function(){
		callback($("#ekran").html());
		$("#ekran").remove();

	});
  });
</script>
{/literal}