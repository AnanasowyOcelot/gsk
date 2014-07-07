<div class="aktualnoscLista">
    {foreach from=$video  key=index item=rekord}
    	<div  class="boxVideo">
    		<div>    	
    		<a href="{$rekord->adres.$jezyk_id}">
    		{if $rekord->obrazek!=''}
    			<a href="{$rekord->adres.$jezyk_id}"><img src="/images/video/1/{$rekord->obrazek}"  style="max-width:165px; max-height:100px;"></a>
    		{else}
    			{if $index % 2 ==1} 
    				<img src="/www/page/img/animacja_1.png"  style="max-width:165px; max-height:100px;">
    			{elseif $index % 3 == 1}
    				<img src="/www/page/img/animacja_2.png"  style="max-width:165px; max-height:100px;">
    			{elseif $index % 6 == 1}
    				<img src="/www/page/img/animacja_3.png"  style="max-width:165px; max-height:100px;">
    			{else}
    				<img src="/www/page/img/animacja_5.png"  style="max-width:165px; max-height:100px;">
    			 {/if}
    		{/if}    		
    		</a>
    		</div>	       
	    	<div class="aktualnoscListaNazwa">
	    		<a href="{$rekord->adres.$jezyk_id}" class="videoHref">{$rekord->podajSkroconaNazwe($jezyk_id, 35)}</a>
	    	
		</div>
	</div>
     {/foreach}
</div>    
<div class="porcjowarka">
	{$porcjowarka}
</div>
