<div class="listaHeader listaHeaderFormularz">
    <table>
        <tr>
            <td class="left">&nbsp;</td>
            <td class="middle">
                <h3>Kategorie produktów &raquo; {$form_nazwa}</h3>
            </td>
            <td class="right">&nbsp;</td>
        </tr>
    </table>
</div>

<div class="formularz">

    <form action="{$link}edytuj/id:{$r->id}" method="post">

        <input type="hidden" name="r[id]" value="{$r->id}"/>


        <div class="wiersz">
            <label>Przypisane produkty:</label>

            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <a href="/cms/produkt/index/s_kategoria:{$r->id}">przejdź do listy produktów</a>
                    </div>
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
                            <input type="text" name="r[nazwa][{$jezykId}]" value="{$r->nazwa[$jezykId]}"/>
                        </div>
                        <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}"/>&nbsp;</span>
                    </div>
                {/foreach}
            </div>
        </div>

        <div class="wiersz">
            <label>Kolor tekstu:</label>

            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[kolor_tekst]" id="kolor_tekst" value="{$r->kolor_tekst}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Kolor tła:</label>

            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[kolor_tlo]" id="kolor_tlo" value="{$r->kolor_tlo}"/>
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
                        <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}"/>&nbsp;</span>
                    </div>
                {/foreach}
            </div>
        </div>

        <div class="wiersz">
            <label>Sposób wyświetlania:<br/><span style="font-size: 9px;">(określa co jest wyświetlane po kliknięciu w tą kategorię: lista produktów lub kafelki z podkategoriami)</span></label>

            <div class="fieldSet">
                {foreach from=$view_types item=type}
                    <div class="field fieldInline">
                        <div class="fieldWrapper">
                            <label>
                                <div style="margin: 3px 24px;"><img
                                            src="{$img_path}icon/category_view_{$type.value}.png"/></div>
                                <input type="radio" name="r[view_type]" value="{$type.value}"
                                       {if $type.value == $r->view_type}checked="checked"{/if} /> {$type.name}
                            </label>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>

        <div class="wiersz">
            <label>Uprawnienia edycji:</label>

            <div class="fieldSet">
                {foreach from=$grupyUprawenienia item=grupa}
                    <div class="field fieldInline">
                        <div class="fieldWrapper">
                            <input type="hidden" name="r[grupaUprawnienia][{$grupa.id}]" value="0"/>
                            <input type="checkbox" name="r[grupaUprawnienia][{$grupa.id}]" value="1"
                                   {if $grupa.stan == 1}checked="checked"{/if} />
                            {$grupa.nazwa}
                        </div>
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
                            <input type="hidden" name="r[aktywna][{$jezykId}]" value="0"/>
                            <input type="checkbox" name="r[aktywna][{$jezykId}]" value="1"
                                   {if $r->aktywna[$jezykId]}checked="checked"{/if} />
                        </div>
                        <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}"/>&nbsp;</span>
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
                                <input type="hidden" name="r[tag][{$tag->id}]" value="1" />
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
                                <input type="hidden" name="r[tag][{$tag->id}]" value="0" />
                                {$tag->name}
                            </li>
                        {/foreach}
                    </ul>
                </div>

                <script>
                    {literal}
                    jQuery(function() {
                        jQuery("#sortable1").data('tagVal', '1');
                        jQuery("#sortable2").data('tagVal', '0');
                        jQuery("#sortable1, #sortable2").sortable({
                            connectWith: ".connectedSortable",
                            receive: function(event, ui) {
                                var tagVal = jQuery(event.target).data('tagVal');
                                jQuery('input', event.toElement).val(tagVal);
                            }
                        }).disableSelection();
                    });
                    {/literal}
                </script>
            </div>
        </div>

        <div class="wiersz">
            <label>Produkty w kategorii (sortowanie):</label>

            <div class="fieldSet">
                <ul id="produktySortowanie">
                    {foreach from=$produkty item=produkt}
                        <li style="list-style-type: square; cursor: pointer;">
                            <input class="inputMiejsce" type="hidden" name="produkty[{$produkt->id}][miejsce]" value="{$produkt->miejsce[$jezykId]}" />
                            <b>{$produkt->nazwa[$jezykId]}</b>
                        </li>
                    {/foreach}
                </ul>
            </div>

            <script>
                {literal}
                jQuery(function () {
                    var listEl = jQuery("#produktySortowanie");
                    listEl.sortable({
                        update: function (event, ui) {
                            console.log('change');
                            var miejsce = 0;
                            jQuery('li', listEl).each(function () {
                                miejsce += 10;
                                //jQuery('.miejsce', this).html(miejsce);
                                jQuery('.inputMiejsce', this).val(miejsce);
                            });
                        }
                    }).disableSelection();
                });
                {/literal}
            </script>
        </div>

        <div style="overflow:hidden; position:relative;">
            <a href="{$link}index/{$link_powrot}"><img src="{$img_path}button-back.png" style="float:left;"/></a>
            <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png"
                   style="float:right;"/>
            {if $button_del==1}
                <a href="{$link}usun/id:{$r->id}" onclick="confirm('Czy na pewno usunąć kategorię?');"><img
                            src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
            {/if}
        </div>

    </form>

</div>

<script type="text/javascript">
    {literal}
    jQuery(document).ready(function () {
        jQuery('#kolor_tekst').iris({
            hide: false,
            palettes: ['#ffffff', '#00619e', '#626262']
        });
        jQuery('#kolor_tlo').iris({
            hide: false,
            palettes: ['#626262', '#bee7fb', '#ffc20e', '#329dc9', '#004b87', '#bf1e2e']
        });
    });
    {/literal}
</script>
