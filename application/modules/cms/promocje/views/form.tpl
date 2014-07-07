<div class="listaHeader listaHeaderFormularz">
    <table>
        <tr>
            <td class="left">&nbsp;</td>
            <td class="middle">
                <h3>Promocje &raquo; {$form_nazwa}</h3>
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
                            <input type="text" name="r[nazwa]" value="{$r->nazwa}" class="{$errors.nazwa}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wiersz">
                <label>Kod Icoguar:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="text" name="r[kod_icoguar]" value="{$r->kod_icoguar}"
                                   class="{$errors.kod_icoguar}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wiersz">
                <label>Cena zakupu:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="text" name="r[cena_zakupu_nagrody]" value="{$r->cena_zakupu_nagrody}"
                                   class="{$errors.cena_zakupu_nagrody}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wiersz">
                <label>Etapy:</label>

                <div class="fieldSet">
                    {foreach from=$etapy item=etap}
                        <div class="field">
                            <div class="fieldWrapper">
                                <label>
                                    <input type="hidden" name="r[etapy][{$etap->id}]" value="0"/>
                                    <input type="checkbox" name="r[etapy][{$etap->id}]" value="1" {if $r->hasEtap($etap->id)}checked="checked"{/if} />
                                    {$etap->nazwa}
                                </label>
                            </div>

                        </div>
                    {/foreach}
                </div>
            </div>

            <div class="wiersz">
                <label>Data:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="text" name="r[formularz_data]" id="poleData" value="{$r->formularz_data}"
                                   class="{$errors.formularz_data}"/>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                {literal}
                jQuery(document).ready(function () {
                    jQuery('#poleData').MonthPicker({
                        ShowIcon: false
                    });
                });
                {/literal}
            </script>

            <div class="wiersz">
                <label>Aktywna:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="checkbox" name="r[aktywna]" value="1" class="{$errors.aktywna}"
                                   {if $r->aktywna}checked="chedcked"{/if} />
                        </div>
                    </div>
                </div>
            </div>

            <div style="overflow:hidden; position:relative;">
                <a href="{$link}index/{$linkParams}"><img src="{$img_path}button-back.png" style="float:left;"/></a>
                <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png"
                       style="float:right;"/>
                {if $button_del==1}
                    <a href="{$link}usun/{$linkParams}id:{$r->id}"><img src="{$img_path}buttons/button-del.png"
                                                                        style="float:right; padding-right:20px;"/></a>
                {/if}
            </div>
    </div>
    <!-- FORMULARZ ------>


    <!-- HISTORIA ------>
    <div style="clear: both; overflow: hidden;">
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
</form>
