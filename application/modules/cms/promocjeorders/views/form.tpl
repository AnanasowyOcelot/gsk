<div class="listaHeader listaHeaderFormularz">
    <table>
        <tr>
            <td class="left">&nbsp;</td>
            <td class="middle">
                <h3>Zamówienia &raquo; {$form_nazwa}</h3>
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
            <input type="hidden" name="wymagane" value="#s">
            <input type="hidden" name="r[{$primaryKeyName}]" value="{$r->$primaryKeyName}"/>
            <input type="hidden" name="r[promotionId]" value="{$r->promotionId}"/>
            <input type="hidden" name="r[addressId]" value="{$r->addressId}"/>

            <div class="wiersz">
                <label>Status:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input name="r[statusId]" type="hidden" id="statusIdHidden" value="{$r->statusId}"/>
                            <select name="r[statusId]" id="statusId" disabled="disabled">
                                {foreach from=$statusy item=status}
                                    <option value="{$status->id}"
                                            {if $r->statusId == $status->id}selected="selected"{/if}>{$status->nazwa}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    {foreach from=$statusButtons item=statusButton}
                        <a href="javascript:void(0);" class="statusChangeButton btn" d_statusId="{$statusButton.id}">{$statusButton.name}</a>
                    {/foreach}
                    <script>
                        {literal}
                        jQuery(function () {
                            jQuery('.statusChangeButton').click(function () {
                                var id = jQuery(this).attr('d_statusId');
                                jQuery('#statusIdHidden').val(id);
                                jQuery('#statusId').val(id);
                            });
                        });
                        {/literal}
                    </script>
                </div>
            </div>

            <div class="wiersz">
                <label>Przedstawiciel:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <select name="r[przedstawicielId]">
                                <option value="0"></option>
                                {foreach from=$przedstawiciele item=przedstawiciel}
                                    <option value="{$przedstawiciel->id}"
                                            {if $r->przedstawicielId == $przedstawiciel->id}selected="selected"{/if}>{$przedstawiciel->name}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wiersz">
                <label>Promocja:</label>

                <div class="fieldSet">
                    <div class="field">{$promotionName}</div>
                </div>
            </div>

            <div class="wiersz">
                <label>Etapy:</label>

                <div class="fieldSet">
                    {foreach from=$etapy item=etap}
                        {assign var="stageId" value=$etap->id}
                        <div class="field">
                            <div class="fieldWrapper" style="width: 100%;">
                                <label style="width: 100%;">
                                    <span style="width: 200px; display: inline-block;">{$etap->nazwa}</span>
                                    <input name="r[items][{$stageId}][stageId]"
                                           type="hidden"
                                           value="{$stageId}"/>
                                    <input name="r[items][{$stageId}][amount]"
                                           type="text"
                                           value="{$r->items.$stageId.amount}"
                                           style="width: 40px; text-align: right;"/>
                                </label>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>

            <div class="wiersz">
                <label>Data utworzenia:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            {$r->dataUtworzenia}
                        </div>
                    </div>
                </div>
            </div>

            <div class="wiersz">
                <label>Data aktualizacji:</label>

                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            {$r->dataAktualizacji}
                        </div>
                    </div>
                </div>
            </div>

            <div style="overflow:hidden; position:relative;">
                <a href="{$link}index/{$linkParams}"><img src="{$img_path}button-back.png" style="float:left;"/></a>
                <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png"
                       style="float:right;"/>
                {if $button_del==1}
                    <a id="formButtonDelete" href="{$link}usun/{$linkParams}id:{$r->id}"><img
                                src="{$img_path}buttons/button-del.png"
                                style="float:right; padding-right:20px;"/></a>
                {/if}
            </div>
        </form>
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

<script>
    {literal}
    jQuery(document).ready(function () {
        jQuery('#formButtonDelete').click(function () {
            return confirm('Czy na pewno usunąć rekord?');
        });
    });
    {/literal}
</script>
