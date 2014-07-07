<!--<p style="text-align: right;"><a href="{$link}dodaj"><img src="/www/cms/img/button-add.png" /></a></p>-->

<div class="filters">
    <form name="formSzukaj" id="formSzukaj" action="/cms/produkt/index">
        <input type="hidden" name="s_modul" id="s_modul" attr_in="0" value="{$modul}">
        nazwa:<input type="text" name="s_nazwa" id="s_nazwa" attr_s_name="s_nazwa" attr_in="1" value="{$parametry.s_nazwa}" />
        kategoria:<select name="s_kategoria" attr_s_name="s_kategoria" attr_in="1">{$kategoriaSelect}</select>
    </form>
    <a href="javascript:void(0);" onclick="szukaj();">filtruj</a>
</div>

<div class="listaHeader">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Produkty</h3>
            <a href="{$link}dodaj" style="text-align:right;"><img src="/www/cms/img/button-add.png" /></a>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<form id="edycjaHurt" method="post">
    <input type="hidden" name="akcja" id="akcjaHurt" value="" />

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
	<th><a href="{$link}index/{$linkParams}col:id">Id</a></th>
	<th>Zdjęcie</th>
	<th><a href="{$link}index/{$linkParams}col:id">Dodane duże zdjęcie</a></th>
	<th><a href="{$link}index/{$linkParams}col:nazwa">Nazwa</a></th>
	<th>EAN</th>
	<th>EAN op.</th>
	<th>PKWIU</th>
	<th><a href="{$link}index/{$linkParams}col:typ">Typ wyświetlania</a></th>
	<th><a href="{$link}index/{$linkParams}col:aktywny">Aktywny</a></th>
  <th>Tagi</th>
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
      <td><a class="linkEdit" href="{$link}edytuj/id:{$rekord->id}" style="text-align: center;"><img src="/images/produkt/z1/1/{$rekord->zdjecie_1}" style="max-height:50px;"></a></td>
        <td>{if $rekord->czy_duze_zdjecie}tak{/if}</td>
      <td><a class="linkEdit" href="{$link}edytuj/id:{$rekord->id}">{$rekord->nazwa.$jezyk_id}</a></td>
      <td><a class="linkEdit" href="{$link}edytuj/id:{$rekord->id}">{$rekord->ean}</a></td>
      <td><a class="linkEdit" href="{$link}edytuj/id:{$rekord->id}">{$rekord->ean_opakowania}</a></td>
      <td><a class="linkEdit" href="{$link}edytuj/id:{$rekord->id}">{$rekord->pkwiu}</a></td>
      <td>
        <a class="linkEdit" href="{$link}edytuj/id:{$rekord->id}">
        {if $rekord->typ eq 1}
                pełny opis
        {elseif $rekord->typ eq 2}
          tylko zdjęcie
        {/if}
        </a>
      </td>
      <td class="colAktywna">{if $rekord->aktywny.$jezyk_id}tak{/if}</td>
      <td>
        {foreach from=$rekord->tagIds item=tagId}
          <span class="tagLabel" style="background-color: {$aTags.$tagId->color};">{$aTags.$tagId->name}</span>
        {/foreach}
      </td>
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

<script>
    {literal}
    jQuery(document).ready(function () {
        jQuery('.buttonDelete').click(function () {
            return confirm('Czy na pewno usunąć rekord?');
        });
    });
    {/literal}
</script>
