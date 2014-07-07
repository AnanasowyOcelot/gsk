<!--<p style="text-align: right;"><a href="{$link}dodaj"><img src="/www/cms/img/button-add.png" /></a></p>-->

<div class="listaHeader">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Administratorzy</h3>
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
    <th>Imie</th>
    <th>Nazwisko</th>
    <th>Email</th>
    <th>Login</th>
    <th>Grupa</th>
    <th>Aktywny</th>
</tr>
{foreach from=$rekordy key=k item=r}
    <tr>
        <td class="colEdit"><a class="buttonEdit" href="{$link}edytuj/id:{$r.id}">&nbsp;</a></td>
        <td class="colDelete"><a class="buttonDelete" href="{$link}usun/id:{$r.id}">&nbsp;</a></td>
        <td>{$r.id}</td>
        <td>{$r.imie}</td>
        <td>{$r.nazwisko}</td>
        <td>{$r.email}</td>
        <td>{$r.login}</td>
        <td>{$r.grupa_nazwa}</td>
        <td>{if $r.aktywny}tak{/if}</td>
    </tr>
{/foreach}
</table>
<div class="listaFooter">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <div class="pagination">{$porcjowarka}</div>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>