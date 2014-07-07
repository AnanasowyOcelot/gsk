
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Grupa &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div class="formularz">

<form action="{$link}edytuj/id:{$r->id}" method="post">

<input type="hidden" name="r[id]" value="{$r->id}" />

<div class="wiersz">
    <label>Nazwa:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[nazwa]" value="{$r->nazwa}" />
            </div>
        </div>
    </div>
</div>


<div class="wiersz">
    <label>Aktywny:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="hidden" name="r[aktywna]" value="0" />
                <input type="checkbox" name="r[aktywna]" value="1" {if $r->aktywna}checked="chedcked"{/if} />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Uprawnienia:</label>
    <div class="fieldSet">
        <div class="field">
            &nbsp;
        </div>
    </div>
</div>

<div class="wiersz">
	<table>
	{foreach from=$uprawnienia key=modul item=akcje} 
		<tr>
			<td style="width:180px; padding:3px 0px; ">{$modul}</td>
			{foreach from=$akcje key=akcja item=stan}
				<input type="hidden" name="r[uprawnienia][{$modul}][{$akcja}]" value="0" >
				<td style="width:120px;  padding:3px 0px;"><input type="checkbox" name="r[uprawnienia][{$modul}][{$akcja}]" value="1" {if $stan ==1} checked {/if} style="margin-right:5px; vertical-align:middle;">{$akcja}</td>
			{/foreach}
		</tr>
	{/foreach}
	</table>
</div>

<div style="overflow:hidden; position:relative;">
    <a href="{$link}"><img src="{$img_path}button-back.png" style="float:left;"/></a>    
    <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
    <a href="{$link}usun/id:{$r->id}"><img src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
</div>

</form>

</div>
