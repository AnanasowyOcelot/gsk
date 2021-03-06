<div class="listaHeader">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Kategorie produktów</h3>
            {if $uprawnienia.dodawanie==1}<a href="{$link}dodaj" style="text-align:right;"><img src="/www/cms/img/button-add.png" /></a>{/if}
            <div class="spacer">&nbsp;</div>
            <a href="javascript:;" onclick="szukaj()"><img src="/www/cms/img/buttons/button-search.png" /></a>
            <span>
	            <form name="formSzukaj" id="formSzukaj">
		            <input type="hidden" name="s_modul" id="s_modul" attr_in="0" value="{$modul}">
		            id:<input type="text" name="s_id" id="s_id" attr_s_name="id" attr_in="1" value="{$parametry.id}">
		            nazwa:<input type="text" name="s_nazwa" id="s_nazwa" attr_s_name="nazwa" attr_in="1" value="{$parametry.nazwa}">
	            </form>
            </span>
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
                    <input type="checkbox" name="selectAll" id="selectAll"
                           onclick="selectAllRow( this.id, 'checkboxHurt' )"/>
                </th>
            {/if}
            {if $uprawnienia.edytowanie==1}
                <th></th>
            {/if}
            {if $uprawnienia.usuwanie==1}
                <th></th>
            {/if}
            <th>
                <a href="javascript:void(0);" onclick="sortujKolumne('{$modul}','id','asc','{$link_parametry}')"><img
                            src="/www/cms/img/up.gif"></a>
                Id
                <a href="javascript:void(0);" onclick="sortujKolumne('{$modul}','id','desc','{$link_parametry}')"><img
                            src="/www/cms/img/down.gif"></a>
            </th>
            <th>
                <a href="javascript:void(0);" onclick="sortujKolumne('{$modul}','nazwa','asc','{$link_parametry}')"><img
                            src="/www/cms/img/up.gif"></a>
                Nazwa
                <a href="javascript:void(0);"
                   onclick="sortujKolumne('{$modul}','nazwa','desc','{$link_parametry}')"><img
                            src="/www/cms/img/down.gif"></a>
            </th>
            <th>
                Kolor
            </th>
            <th>
                Przypisane produkty
            </th>
            <th>
                <a href="javascript:void(0);" onclick="sortujKolumne('{$modul}','kolejnosc','asc','{$link_parametry}')"><img
                            src="/www/cms/img/up.gif"></a>
                Miejsce
                <a href="javascript:void(0);"
                   onclick="sortujKolumne('{$modul}','kolejnosc','desc','{$link_parametry}')"><img
                            src="/www/cms/img/down.gif"></a>
            </th>
            <th>
                <a href="javascript:void(0);"
                   onclick="sortujKolumne('{$modul}','aktywna','asc','{$link_parametry}')"><img
                            src="/www/cms/img/up.gif"></a>
                Aktywny
                <a href="javascript:void(0);"
                   onclick="sortujKolumne('{$modul}','aktywna','desc','{$link_parametry}')"><img
                            src="/www/cms/img/down.gif"></a>
            </th>
            <th>Tagi</th>
        </tr>
	
	{foreach from=$lista  item=rekord}
	    <tr>
	    	{if $uprawnienia.usuwanie==1}
	    		<td class="colHurt"><input type="checkbox" class="checkboxHurt" value="{$rekord->id}" name="id_zaznaczone[{$rekord->id}]" /></td>
	    	{/if}
	    	{if $uprawnienia.edytowanie==1}
          <td class="colEdit"><a class="buttonEdit" href="{$link}edytuj/id:{$rekord->id}">&nbsp;</a></td>
        {/if}
        {if $uprawnienia.usuwanie==1}
          <td class="colDelete"><a class="buttonDelete" href="{$link}usun/id:{$rekord->id}">&nbsp;</a></td>
        {/if}
        <td class="colID">{$rekord->id}</td>
        <td><a href="{$link}edytuj/id:{$rekord->id}" class="linkEdit">{$rekord->nazwa_pelna_sciezka.$jezyk_id}</a></td>
        <td class="colAktywna"><div style="width: 40px; height: 12px; background-color: {$rekord->kolor_tlo};">&nbsp;</div></td>
        <td class="colAktywna"><a href="/cms/produkt/index/s_kategoria:{$rekord->id}">przejdź&nbsp;do&nbsp;listy</a> ({$rekord->liczba_produktow}&nbsp;produktów)</td>
        <td class="colAktywna">{$rekord->miejsce.$jezyk_id}</td>
        <td class="colAktywna">{if $rekord->aktywna.$jezyk_id}tak{/if}</td>
        <td>
          {foreach from=$rekord->tagIds item=tagId}
            <span class="tagLabel" style="background-color: {$aTags.$tagId->color};">{$aTags.$tagId->name}</span>
          {/foreach}
        </td>
	    </tr>
	{/foreach}
	</table>
<form>
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

<script>
    {literal}
    jQuery(document).ready(function () {
        jQuery('.buttonDelete').click(function () {
            return confirm('Czy na pewno usunąć rekord?');
        });
    });
    {/literal}
</script>
