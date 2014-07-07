
    {foreach from=$aktualnosci  key=index item=rekord}
    	<div  class="wierszAktualnosc">
    		<div style="text-align:center;">
    		
	    		<a href="{$rekord->adres.$jezyk_id}" class="aktualnoscHref">{$rekord->podajSkroconaTytul($jezyk_id, 35)}</a>
	    	
		</div>
	</div>
     {/foreach}
