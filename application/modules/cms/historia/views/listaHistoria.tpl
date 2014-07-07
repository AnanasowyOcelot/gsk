{if  $lista_historia|@count gt 0}
<table class="tableHistoria">
{foreach from=$lista_historia key=index  item=rekord}
<tr class="{if $klucz eq $rekord.historia_klucz} activeRow {/if}">	
	<td><a href="{$link_modul}przywroc/klucz:{$rekord.historia_klucz}"><img src="/www/cms/img/restore.png"></a></td>
	<td><a href="{$link_modul}przywroc/klucz:{$rekord.historia_klucz}">{$rekord.historia_czas}</a></td>
	<td><a href="{$link_modul}przywroc/klucz:{$rekord.historia_klucz}">{$rekord.admin}</a></td>
	<td><a href="{$link_modul}przywroc/klucz:{$rekord.historia_klucz}">{$rekord.historia_operacja}</a></td>
	
</tr>
{/foreach}
</table>
{/if}