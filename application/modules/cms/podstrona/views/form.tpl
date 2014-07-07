
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Podstrony  &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div class="formularz">

<form action="{$link}edytuj/id:{$r->id}" method="post">

<input type="hidden" name="r[id]" value="{$r->id}" />




<div class="wiersz">
    <label>Podgląd:</label>
    <div class="fieldSet">
        <div class="field">
            {foreach from=$jezyki key=jezykId item=jezykSkrot}
                <a href="/{$jezykSkrot}/{$r->url[$jezykId]}" target="_blank"><span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span> wyświetl stronę</a>
            {/foreach}
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Nadrzędna:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <select name="r[id_nadrzedna]">
                	{$parentSelect}
           <!--     {foreach from=$parentSelect item=el}-->
                    <!--<option value="{$el.id}" {if $el.id == $r->id_nadrzedna}selected="selected"{/if}>{$el.nazwa}</option>-->
                <!--{/foreach}-->
                </select>
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Nazwa:</label>
    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field">
                <div class="fieldWrapper">
                    <input type="text" name="r[nazwa][{$jezykId}]" value="{$r->nazwa[$jezykId]}" />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Tytuł:</label>
    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field">
                <div class="fieldWrapper">
                    <input type="text" name="r[tytul][{$jezykId}]" value="{$r->tytul[$jezykId]}" />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Szablon:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <select name="r[szablon_id]">
                    {$szablonSelect}
                </select>
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Elementy html:</label>
    <div class="fieldSet">
        <div class="field" style="overflow: auto;">
            <div class="fieldWrapper" style="width: 520px; display:block; padding: 5px;">
                {$szablonHtml}
                <input type="text" name="element_parametr" id="element_parametr" placeholder="parametr wartość" value="" style="width:130px;"/>
                <input type="text" name="element_tpl" id="element_tpl" placeholder="tpl nazwa" value="" style="width:130px;"/>
                <a href="javascript:;" onClick="dodajElementStrona()"><img src="/www/cms/img/add.png" style="vertical-align:middle; padding-bottom:3px;"></a>
            </div>            
        </div>
    </div>
</div>

<div class="wiersz">
    <label>&nbsp;</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper" id="listaElementowStrona">                        
            {$listaElementowHtml}
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Url:</label>
    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field">
                <div class="fieldWrapper">
                    <input type="text" name="r[url][{$jezykId}]" value="{$r->url[$jezykId]}" />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Link:</label>
    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field">
                <div class="fieldWrapper">
                    <input type="text" name="r[link][{$jezykId}]" value="{$r->link[$jezykId]}" />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Moduł:</label>
    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field">
                <div class="fieldWrapper">
                    <input type="text" name="r[modul][{$jezykId}]" value="{$r->modul[$jezykId]}" />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Treść html:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <div id="tabs_tresc">
                    <ul>
                        {foreach from=$jezyki key=jezykId item=jezykSkrot}                               
                            <li><a href="#tab-{$jezykId}"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span> {$jezykSkrot}</a></li>                            
                        {/foreach}
                    </ul>
                    {foreach from=$jezyki key=jezykId item=jezykSkrot}            
                        <div id="tab-{$jezykId}">{$pole_tresc[$jezykId]}</div>
                    {/foreach}        
                </div>    
            </div>
        </div>
    </div>
</div>


<div class="wiersz">
    <label>Czy w menu:</label>
    <div class="fieldSet">
        <div class="field">
            <label>górne:</label>
            {foreach from=$jezyki key=jezykId item=jezykSkrot}
                <div class="fieldInline">
                    <div class="fieldWrapper">
                        <input type="hidden" name="r[menu_gora][{$jezykId}]" value="0" />
                        <input type="checkbox" name="r[menu_gora][{$jezykId}]" value="1" {if $r->menu_gora[$jezykId]}checked="checked"{/if} />
                    </div>
                    <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
                </div>
            {/foreach}
        </div>
        <div class="field">
            <label>dolne:</label>
            {foreach from=$jezyki key=jezykId item=jezykSkrot}
                <div class="fieldInline">
                    <div class="fieldWrapper">
                        <input type="hidden" name="r[menu_dol][{$jezykId}]" value="0" />
                        <input type="checkbox" name="r[menu_dol][{$jezykId}]" value="1" {if $r->menu_dol[$jezykId]}checked="checked"{/if} />
                    </div>
                    <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
                </div>
            {/foreach}
        </div>
        <div class="field">
            <label>lewe:</label>
            {foreach from=$jezyki key=jezykId item=jezykSkrot}
                <div class="fieldInline">
                    <div class="fieldWrapper">
                        <input type="hidden" name="r[menu_lewa][{$jezykId}]" value="0" />
                        <input type="checkbox" name="r[menu_lewa][{$jezykId}]" value="1" {if $r->menu_lewa[$jezykId]}checked="checked"{/if} />
                    </div>
                    <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
                </div>
            {/foreach}
        </div>
    </div>
</div>


<div class="wiersz">
    <label>Kolejność:</label>
    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field">
                <div class="fieldWrapper">
                    <input type="text" name="r[miejsce][{$jezykId}]" value="{$r->miejsce[$jezykId]}" />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Aktywny:</label>
    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field fieldInline">
                <div class="fieldWrapper">
                    <input type="hidden" name="r[aktywna][{$jezykId}]" value="0" />
                    <input type="checkbox" name="r[aktywna][{$jezykId}]" value="1" {if $r->aktywna[$jezykId]}checked="checked"{/if} />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
            </div>
        {/foreach}
    </div>
</div>

<div style="overflow:hidden; position:relative;">
    <a href="{$link}index/{$link_powrot}"><img src="{$img_path}button-back.png" style="float:left;"/></a>    
    <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
    {if $button_del==1}
    <a href="{$link}usun/id:{$r->id}"><img src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
    {/if}
</div>

</form>

</div>
