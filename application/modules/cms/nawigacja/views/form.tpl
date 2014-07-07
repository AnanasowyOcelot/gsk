
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Nawigacja &raquo; edytuj</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div class="formularz">

<form action="{$link}edytuj/id:{$r->id}" method="post">

<input type="hidden" name="r[id]" value="{$r->id}" />

<div class="wiersz">
    <label>Nadrzędna:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <select name="r[parent_id]">
                {foreach from=$parentSelect item=el}
                    <option value="{$el.id}" {if $el.id == $r->parent_id}selected="selected"{/if}>{$el.nazwa}</option>
                {/foreach}
                </select>
            </div>
        </div>
    </div>
</div>

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
    <label>Moduł:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <select name="r[modul]">
                	{$modulySelect}
                </select>
            </div>
        </div>
    </div>
</div>



{*
<div class="wiersz">
    <label>Moduł:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[modul]" value="{$r->modul}" />
            </div>
        </div>
    </div>
</div>
*}

<div class="wiersz">
    <label>Akcja:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[akcja]" value="{$r->akcja}" />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Kolejność:</label>
    <div class="fieldSet">     
     <div class="field">
           <div class="fieldWrapper">
                <input type="text" name="r[miejsce]" value="{$r->miejsce}" />
            </div>       
    </div>
    </div>
</div>

<div class="wiersz">
    <label>Aktywny:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="hidden" name="r[aktywny]" value="0" />
                <input type="checkbox" name="r[aktywny]" value="1" {if $r->aktywny}checked="chedcked"{/if} />
            </div>
        </div>
    </div>
</div>

<div style="overflow:hidden; position:relative;">
    <a href="{$link}"><img src="{$img_path}button-back.png" style="float:left;"/></a>    
    <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
    <a href="{$link}"><img src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
</div>

</form>

</div>
