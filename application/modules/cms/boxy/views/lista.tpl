<!--<p style="text-align: right;"><a href="{$link}dodaj"><img src="/www/cms/img/button-add.png" /></a></p>-->

<form id="edycjaHurt" method="post">
<input type="hidden" name="akcja" id="akcjaHurt" value="" />

<div class="listaHeader">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Boxy</h3>
            <a href="{$link}dodaj" style="text-align:right;"><img src="/www/cms/img/button-add.png" /></a>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<table class="lista">
<tr>
	{if $uprawnienia.usuwanie==1}
	<th>	    	
		<input type="checkbox" name="selectAll" id="selectAll" onclick="selectAllRow( this.id, 'checkboxHurt' )"/>	
	</th>
	{/if}
	{if $uprawnienia.edytowanie==1}
		<th></th>
	{/if}
	{if $uprawnienia.usuwanie==1}
		<th></th>
	{/if}
	<th>Id</th>
	<th>Nazwa</th>	
	<th>Aktywny</th>
</tr>
{foreach from=$lista  item=rekord}
    <tr>
	{if $uprawnienia.usuwanie==1}
		<td class="colHurt"><input type="checkbox" class="checkboxHurt" value="{$rekord->id}" name="id[{$rekord->id}]" /></td>
	{/if}
	{if $uprawnienia.edytowanie==1}
		<td class="colEdit"><a class="buttonEdit" href="{$link}edytuj/id:{$rekord->id}">&nbsp;</a></td>
	{/if}
	{if $uprawnienia.usuwanie==1}
		<td class="colDelete"><a class="buttonDelete" href="{$link}usun/id:{$rekord->id}">&nbsp;</a></td>
	{/if}
	<td  class="colID">{$rekord->id}</td>
	<td><a class="linkEdit" href="{$link}edytuj/id:{$rekord->id}">{$rekord->nazwa.$jezyk_id}</a></td>	
	<td class="colAktywna">{if $rekord->aktywna.$jezyk_id}tak{/if}</td>
    </tr>
{/foreach}
</table>
<div class="listaFooter">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
        	{if $uprawnienia.usuwanie==1}
	        	<div style="float:left;">
			{literal}
				<a href="javascript:void(0);" onclick="if(confirm('Czy na pewno usunąć zaznaczone rekordy?')) { jQuery('input#akcjaHurt').val('usun'); jQuery('form#edycjaHurt').submit(); }"><img src="/www/cms/img/buttons/usun-zaznaczone.png" style="float:right; padding-right:20px;"/></a>
			{/literal}
		</div>
	{/if}
            <div class="pagination">{$porcjowarka}</div>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<form>