<div class="listaHeader listaHeaderFormularz">
    <table>
        <tr>
            <td class="left">&nbsp;</td>
            <td class="middle">
                <h3>Pliki &raquo; {$form_nazwa}</h3>
            </td>
            <td class="right">&nbsp;</td>
        </tr>
    </table>
</div>

<div style="overflow:hidden; position:relative;">

    <!-- FORMULARZ ------>
    <div class="formularz" style="float:left;">

        <form action="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekordId}" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="wymagane" value="nazwa#s">
            <input type="hidden" name="r[id]" value="{$r->id}"/>

            <div class="wiersz">
                <label>Nazwa:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="text" name="r[nazwa]" value="{$r->nazwa}" class="{$errors.nazwa}" readonly/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wiersz">
                <label>Data:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="text" name="r[dataUtworzenia]" value="{$r->dataUtworzenia}" class="{$errors.dataUtworzenia}" readonly/>
                        </div>
                    </div>
                </div>
            </div>

            <div style="overflow:hidden; position:relative;">
                <a href="{$link}index/{$linkParams}"><img src="{$img_path}button-back.png" style="float:left;"/></a>

                {if $button_del==1}
                    <a href="{$link}usun/{$linkParams}id:{$r->id}"><img src="{$img_path}buttons/button-del.png"
                                                                        style="float:right; padding-right:20px;"/></a>
                {/if}
            </div>
        </form>
    </div>
    <!-- FORMULARZ ------>
<div style="clear: both;">
    {foreach from=$klienciPromocjeLista key=nazwa item=klient}
    <table style="border: solid #000000 1px; position: relative; width: 790px">
        <tr>
            <th colspan="10">Klient: {$nazwa}</th>
        </tr>
        <tr>
            <th>Subbrand</th>
            <th>Produkt</th>
            <th>Termin</th>
            <th>Termin Rabatu Od</th>
            <th>Gazetka</th>
            <th>Cena Rekomendowana</th>
            <th>Forma Promocji</th>
            <th>Dodatkowa Lokalizacja</th>
            <th>Ilosc dodatkowych lokalizacji</th>
            <th>Uwagi</th>

        </tr>
    {foreach from=$klient item=promocja}
            <tr>
                <td>{$promocja.subbrand}</td>
                <td>{$promocja.produkt}</td>
                <td>{$promocja.termin}</td>
                <td>{$promocja.termin_rabatu_OD}</td>
                <td>{$promocja.gazetka}</td>
                <td>{$promocja.cena_rekomendowana}</td>
                <td>{$promocja.forma_promocji}</td>
                <td>{$promocja.dodatkowa_lokalizacja}</td>
                <td>{$promocja.ilosc_dodatkowych_lokalizacji}</td>
                <td>{$promocja.uwagi}</td>

            </tr>
        {/foreach}
    </table>

    {/foreach}
</div>
    <!-- HISTORIA ------>
    <div style="overflow: hidden;">
        {if $historia_html!=''}
            <div style="width: 792px; margin-top: 10px;">
                <div style="background-color:#b8ced9; text-align:center; padding:5px;">
                    <a href="javascript:void(0);" onclick="jQuery('#box_historia').toggle(); jQuery(this).blur();"
                       style="text-decoration:none; color: #222222;">wyświetl/ukryj historię zmian</a>
                </div>
                <div id="box_historia"
                     style="{if $historiaOpen==0}display:none;{/if} background-color:#f9f9f9;padding:5px; border:1px solid #b8ced9;">
                    <div>{$historia_html}</div>
                </div>
            </div>
        {/if}
    </div>
    <!-- HISTORIA ------>

</div>
