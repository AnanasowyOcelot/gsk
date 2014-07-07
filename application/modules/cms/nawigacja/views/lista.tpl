<!--<p style="text-align: right;"><a href="{$link}dodaj"><img src="/www/cms/img/button-add.png" /></a></p>-->

<form id="edycjaHurt" method="post">

<input type="hidden" name="akcja" id="akcjaHurt" value="" />

<div class="listaHeader">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Nawigacja</h3>
            <a href="{$link}dodaj" style="text-align:right;"><img src="/www/cms/img/button-add.png" /></a>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<table class="lista">
<tr>
    <th>
        <input type="checkbox" name="selectAll" id="selectAll" onclick="selectAllRow( this.id, 'checkboxHurt' )"/>
    </th>
    <th></th>
    <th></th>
    <th>Id</th>
    <th>Nazwa</th>    
    <th>Modul</th>
    <th>Akcja</th>
    <th>Aktywny</th>
</tr>
{foreach from=$rekordy key=k item=r}
    <tr>
        <td class="colHurt"><input type="checkbox" class="checkboxHurt" value="{$r.id}" name="id[{$r.id}]" /></td>
        <td class="colEdit"><a class="buttonEdit" href="{$link}edytuj/id:{$r.id}">&nbsp;</a></td>
        <td class="colDelete"><a class="buttonDelete" href="{$link}usun/id:{$r.id}">&nbsp;</a></td>
        <td>{$r.id}</td>
        <td>{$r.nazwa}</td>     
        <td>{$r.modul}</td>
        <td>{$r.akcja}</td>
        <td>{if $r.aktywny}tak{/if}</td>
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
