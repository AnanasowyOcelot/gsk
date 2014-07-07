<div class="listaHeader">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Grupy</h3>
            <a href="{$link}dodaj" style="text-align:right;"><img src="/www/cms/img/button-add.png" /></a>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>
<table class="lista">
<tr>
    <th></th>
    <th></th>
    <th>Id</th>
    <th>Nazwa</th>    
    <th>Aktywny</th>
</tr>
{foreach from=$rekordy key=k item=r}
    <tr>       
        <td class="colEdit"><a class="buttonEdit" href="{$link}edytuj/id:{$r.grupa_id}">&nbsp;</a></td>
        <td class="colDelete"><a class="buttonDelete" href="{$link}usun/id:{$r.grupa_id}">&nbsp;</a></td>
        <td>{$r.grupa_id}</td>
        <td><a class="linkEdit" href="{$link}edytuj/id:{$r.grupa_id}">{$r.grupa_nazwa}</a></td>
        
        <td>{if $r.grupa_aktywna}tak{/if}</td>
    </tr>
{/foreach}
</table>
<div class="listaFooter">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
           &nbsp;
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>