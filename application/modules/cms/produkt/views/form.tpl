<style>
    {literal}
    table, th, tr, td {
        background-color: #F9F9F9;
        border-bottom: 1px solid #A4A4A4;
        font-size: 11px;
        border-collapse: separate;
        border-spacing: 0;
    }

    table {
        border: 1px solid #A4A4A4;
    }

    td, th {
        padding: 4px;
    }

    th {
        background-color: #E6E6E6;

    }

    tr td:nth-child(even) {
        background-color: #F2F2F2;
        text-align: right;

    }

    #klient {
        background-color: #FFFFFF;
        border-bottom: 1px solid #A4A4A4;
        /*border-top: 2px solid #A4A4A4;*/

    }
    {/literal}
</style>
<div class="listaHeader listaHeaderFormularz">
    <table>
        <tr>
            <td class="left">&nbsp;</td>
            <td class="middle">
                <h3>Produkty &raquo; {$form_nazwa}</h3>
            </td>
            <td class="right">&nbsp;</td>
        </tr>
    </table>
</div>

<div style="overflow:hidden; position:relative;">

<!-- FORMULARZ ------>
<div class="formularz" style="float:left;">

<form action="{$link}edytuj/id:{$r->id}" method="post" enctype="multipart/form-data">
<input type="hidden" name="wymagane" value="nazwa#s">
<input type="hidden" name="r[id]" id="gal_id" value="{$r->id}"/>

<div class="wiersz">
    <label>Kategoria:</label>

    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <select name="r[kategoria_id]">
                    {$kategoriaSelect}
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
                    <input type="text" name="r[nazwa][{$jezykId}]" value="{$r->nazwa[$jezykId]}"
                           class="{$errors.nazwa.$jezykId}"/>
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}"/></span>
            </div>
        {/foreach}
    </div>
</div>

<!--
		<div class="wiersz">
		    <label>Nazwa długa:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[nazwa_dluga][{$jezykId}]" value="{$r->nazwa_dluga[$jezykId]}" class="{$errors.nazwa_dluga.$jezykId}"/>
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
        -->

<div class="wiersz">
    <label>EAN produktu:</label>

    <div class="fieldSet">
        <div class="field">
            <input type="text" name="r[ean]" value="{$r->ean}" class="{$errors.ean}"/>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Wcześniejsze numery EAN produktu:<br/></label>

    <div class="fieldSet">
        <div>
            <small>te numery nie są wyświetlane w karcie produktu i służą do identyfikacji wewnątrz systemu (np. importy
                z excela)
            </small>
        </div>
        {foreach from=$r->numery_ean key=numerNum item=numerEan}
            <div class="field">
                <div class="fieldWrapper" style="width: 60%;">
                    numer: <input style="width: 80%;" type="text" name="r[numery_ean][]" value="{$numerEan}"/>
                </div>
            </div>
        {/foreach}
        <div id="newNumerEanTemplate" style="display: none;">
            <div class="field">
                <div class="fieldWrapper" style="width: 60%;">
                    numer: <input style="width: 80%;" type="text" name="r[numery_ean][]" value=""/>
                </div>
            </div>
        </div>
        <div style="text-align: right; padding-right: 24px;">
            <a id="btnDodajNumerEan" href="javascript: void(0);">
                <img src="/www/cms/img/button-add.png"/>
            </a>
        </div>
        <script>
            {literal}
            jQuery(document).ready(function () {
                jQuery('#btnDodajNumerEan').click(function () {
                    var templateWrapper = jQuery('#newNumerEanTemplate');
                    var template = templateWrapper.html();
                    templateWrapper.before(template);
                });
            });
            {/literal}
        </script>
    </div>
</div>

<div class="wiersz">
    <label>EAN opakowania:</label>

    <div class="fieldSet">
        <div class="field">
            <input type="text" name="r[ean_opakowania]" value="{$r->ean_opakowania}" class="{$errors.ean_opakowania}"/>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Sztuk w opakowaniu:</label>

    <div class="fieldSet">
        <div class="field">
            <input type="text" name="r[sztuk_w_opakowaniu]" value="{$r->sztuk_w_opakowaniu}"
                   class="{$errors.sztuk_w_opakowaniu}"/>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>PKWIU:</label>

    <div class="fieldSet">
        <div class="field">
            <input type="text" name="r[pkwiu]" value="{$r->pkwiu}" class="{$errors.pkwiu}"/>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Atrybuty:</label>

    <div class="fieldSet">
        {foreach from=$r->atrybuty key=atrybutNum item=atrybut}
            <div class="field">
                <input type="hidden" name="r[atrybuty][id][{$atrybut->id}]" value="{$atrybut->id}"/>

                <div class="fieldWrapper" style="width: 30%;">
                    nazwa: <input style="width: 60%;" type="text" name="r[atrybuty][nazwa][{$atrybut->id}]"
                                  value="{$atrybut->nazwa}"/>
                </div>
                <div class="fieldWrapper" style="width: 62%; text-align: right;">
                    wartość: <input style="width: 78%;" type="text" name="r[atrybuty][wartosc][{$atrybut->id}]"
                                    value="{$atrybut->wartosc}"/>
                </div>
            </div>
        {/foreach}
        <div id="newAttributeTemplate" style="display: none;">
            <div class="field">
                <input type="hidden" name="r[atrybuty][id][]" value="0"/>

                <div class="fieldWrapper" style="width: 30%;">
                    nazwa: <input style="width: 60%;" type="text" name="r[atrybuty][nazwa][]" value=""/>
                </div>
                <div class="fieldWrapper" style="width: 62%; text-align: right;">
                    wartość: <input style="width: 78%;" type="text" name="r[atrybuty][wartosc][]" value=""/>
                </div>
            </div>
        </div>
        <div style="text-align: right; padding-right: 24px;">
            <a id="btnDodajAtrybut" href="javascript: void(0);">
                <img src="/www/cms/img/button-add.png"/>
            </a>
        </div>
        <script>
            {literal}
            jQuery(document).ready(function () {
                jQuery('#btnDodajAtrybut').click(function () {
                    var templateWrapper = jQuery('#newAttributeTemplate');
                    var template = templateWrapper.html();
                    templateWrapper.before(template);
                });
            });
            {/literal}
        </script>
    </div>
</div>

<!--
        <div class="wiersz">
            <label>Cena szt.:</label>
            <div class="fieldSet">
                <div class="field">
                    <input type="text" name="r[cena_szt]" value="{$r->cena_szt}" class="{$errors.cena_szt}"/>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Cena op:</label>
            <div class="fieldSet">
                <div class="field">
                    <input type="text" name="r[cena_op]" value="{$r->cena_op}" class="{$errors.cena_op}"/>
                </div>
            </div>
        </div>
        -->

<div class="wiersz">
    <label>Typ:</label>

    <div class="fieldSet">
        <div class="field">
            <label><input type="radio" name="r[typ]" value="1" {if $r->typ eq 1 || $r->typ eq ''} checked{/if}/>pełny
                opis</label>
            <label><input type="radio" name="r[typ]" value="2" {if $r->typ eq 2} checked {/if}/>tylko zdjęcie</label>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Opis:</label>

    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <div id="tabs_tresc">
                    <ul>
                        {foreach from=$jezyki key=jezykId item=jezykSkrot}
                            <li><a href="#tab-{$jezykId}"><span class="flag flag-{$jezykSkrot}"
                                                                alt="{$jezykSkrot}"/></span> {$jezykSkrot}</a></li>
                        {/foreach}
                    </ul>
                    {foreach from=$jezyki key=jezykId item=jezykSkrot}
                        <div id="tab-{$jezykId}">{$pole_opis[$jezykId]}</div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Kolejność:</label>

    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field">
                <div class="fieldWrapper">
                    <input type="text" name="r[miejsce][{$jezykId}]" value="{$r->miejsce[$jezykId]}"/>
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}"/></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Zdjęcie:</label>

    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="file" name="z1">
            </div>
        </div>
    </div>
</div>
<div class="wiersz">
    <label>&nbsp;</label>

    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                {if $r->zdjecie_1 != ''}
                    <a href="/images/produkt/z1/4/{$r->zdjecie_1}" target="_blank">
                        <img src="/images/produkt/z1/1/{$r->zdjecie_1}">
                    </a>
                {/if}
            </div>
        </div>
    </div>
</div>

<!--
		<div class="wiersz">
		    <label>Zdjęcie 2:</label>
		    <div class="fieldSet">
		        <div class="field">
		            <div class="fieldWrapper">
		              <input type="file" name="z2">
		            </div>
		        </div>
		    </div>
		</div>
		<div class="wiersz">
		    <label>&nbsp;</label>
		    <div class="fieldSet">
		        <div class="field">
		            <div class="fieldWrapper">
		            {if $r->zdjecie_2 != ''}
		              <img src="/images/produkt/z2/1/{$r->zdjecie_2}">
		              {/if}
		            </div>
		        </div>
		    </div>
		</div>
		-->

<div class="wiersz">
    <label>Aktywny:</label>

    <div class="fieldSet">
        {foreach from=$jezyki key=jezykId item=jezykSkrot}
            <div class="field fieldInline">
                <div class="fieldWrapper">
                    <input type="hidden" name="r[aktywny][{$jezykId}]" value="0"/>
                    <input type="checkbox" name="r[aktywny][{$jezykId}]" value="1"
                           {if $r->aktywny[$jezykId]}checked="checked"{/if} />
                </div>
                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}"/></span>
            </div>
        {/foreach}
    </div>
</div>

<div class="wiersz">
    <label>Tagi:</label>

    <div class="fieldSet">
        <div style="width: 48%; float: left; overflow: hidden; margin: 2px;">
            Przypisane:
            <ul id="sortable1" class="connectedSortable" style="border: 1px solid #cccccc; padding: 10px;">
                {foreach from=$connectedTags item=tag}
                    <li class="tagLabel" title="{$tag->description}" style="background-color: {$tag->color};">
                        <input type="hidden" name="r[tag][{$tag->id}]" value="1"/>
                        {$tag->name}
                    </li>
                {/foreach}
            </ul>
        </div>
        <div style="width: 48%; float: left; overflow: hidden; margin: 2px;">
            Dostępne:
            <ul id="sortable2" class="connectedSortable" style="border: 1px solid #cccccc; padding: 10px;">
                {foreach from=$availableTags item=tag}
                    <li class="tagLabel" title="{$tag->description}" style="background-color: {$tag->color};">
                        <input type="hidden" name="r[tag][{$tag->id}]" value="0"/>
                        {$tag->name}
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
    {if !empty($klienciPromocjeLista)}
    <div class="wiersz" style="clear: both;">
        <table style="position: relative; width: 790px">
            <tr>
                {*<th>Subbrand</th>*}
                <th>Termin</th>
                <th>Termin Rabatu Od</th>
                <th>Gazetka</th>
                <th>Cena<br/>rekomendowana</th>
                <th>Forma Promocji</th>
                <th>Dodatkowa Lokalizacja</th>
                <th>Ilosc dodatkowych lokalizacji</th>
                <th>Uwagi</th>
                <th>EAN</th>

            </tr>
            {foreach from=$klienciPromocjeLista key=nazwa item=klient}
            {*</br>*}
                <tr>
                    <th colspan="11" id="klient">Klient: {$nazwa}</th>
                </tr>
                {foreach from=$klient item=promocja}
                    <tr>
                        {*<td>{$promocja.subbrand}</td>*}
                        <td>{$promocja.termin}</td>
                        <td>{$promocja.termin_rabatu_OD}</td>
                        <td>{$promocja.gazetka}</td>
                        <td>{$promocja.cena_rekomendowana}</td>
                        <td>{$promocja.forma_promocji}</td>
                        <td>{$promocja.dodatkowa_lokalizacja}</td>
                        <td>{$promocja.ilosc_dodatkowych_lokalizacji}</td>
                        <td>{$promocja.uwagi}</td>
                        <td>{$promocja.EAN}</td>


                    </tr>
                {/foreach}
            {/foreach}
        </table>

    </div>
    {/if}
    <div>
        <script>
            {literal}
            jQuery(function () {
                jQuery("#sortable1").data('tagVal', '1');
                jQuery("#sortable2").data('tagVal', '0');
                jQuery("#sortable1, #sortable2").sortable({
                    connectWith: ".connectedSortable",
                    receive: function (event, ui) {
                        var tagVal = jQuery(event.target).data('tagVal');
                        jQuery('input', event.toElement).val(tagVal);
                    }
                }).disableSelection();
            });
            {/literal}
        </script>
    </div>
</div>

<div style="overflow:hidden; position:relative; margin-top: 30px;">
    <a href="{$link}"><img src="{$img_path}button-back.png" style="float:left;"/></a>
    <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
    {if $button_del==1}
        <a href="{$link}usun/id:{$r->id}" onclick="confirm('Czy na pewno usunąć produkt?');"><img
                    src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
    {/if}
</div>
</div>
<!-- FORMULARZ ------>


<!-- HISTORIA ------>
<div style="float:left;  ">
    {if $historia_html!=''}
    <div class="wiersz" style="position:relative; overflow:hidden;">
        <div class="fieldSet" id="box_historia"
             style="float:left; {if $historiaOpen==0}display:none;{/if}  background-color:#f9f9f9; width:400px; padding:5px; border-top:1px solid #c2ccce; border-right:1px solid #c2ccce; border-bottom:1px solid #c2ccce;">
            <div>
                {$historia_html}
            </div>
        </div>
        <div style="float:left; background-color:#c2ccce; width:15px; text-align:center; height:160px; padding:5px; border-top:1px solid #c2ccce; border-right:1px solid #c2ccce; border-bottom:1px solid #c2ccce;">
            <a href="javascript:void(0);" onclick="f_przelacz('box_historia')" style="text-decoration:none;">
                H
                I
                S
                T
                O
                R
                I
                A
                <
            </a>
        </div>
        {/if}
    </div>
</div>
<!-- HISTORIA ------>

</div>
</form>
