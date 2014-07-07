<form id="edycjaHurt" method="post">
    <input type="hidden" name="akcja" id="akcjaHurt" value=""/>

    <div class="listaHeader">
        <table>
            <tr>
                <td class="left">&nbsp;</td>
                <td class="middle">
                    <h3>Zamówienia</h3>
                    <a href="{$link}dodaj" style="text-align:right;"><img src="/www/cms/img/button-add.png"/></a>
                </td>
                <td class="right">&nbsp;</td>
            </tr>
        </table>
    </div>

    <table class="lista">
        <tr>
            {if $uprawnienia.usuwanie==1}
                <th>
                    <input type="checkbox" name="selectAll" id="selectAll"
                           onclick="selectAllRow( this.id, 'checkboxHurt' )"/>
                </th>
            {/if}
            {if $uprawnienia.edytowanie==1}
                <th></th>
            {/if}
            {if $uprawnienia.usuwanie==1}
                <th></th>
            {/if}
            <th><a href="{$link}index/{$linkParams}col:id">Id</a></th>
            <th><a href="{$link}index/{$linkParams}col:promotionId">Promocja</a></th>
            <th><a href="{$link}index/{$linkParams}col:przedstawicielId">Przedstawiciel</a></th>
            <th><a href="{$link}index/{$linkParams}col:statusId">Status</a></th>
            <th><a href="{$link}index/{$linkParams}col:nextEditorName">Do zatwierdzenia przez</a></th>
        </tr>
        {foreach from=$lista  item=rekord}
            <tr>
                {if $uprawnienia.usuwanie==1}
                    <td class="colHurt"><input type="checkbox" class="checkboxHurt" value="{$rekord->id}"
                                               name="id[{$rekord->id}]"/></td>
                {/if}
                {if $uprawnienia.edytowanie==1}
                    <td class="colEdit"><a class="buttonEdit"
                                           href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">&nbsp;</a>
                    </td>
                {/if}
                {if $uprawnienia.usuwanie==1}
                    <td class="colDelete"><a class="buttonDelete"
                                             href="{$link}usun/{$linkParams}{$primaryKeyName}:{$rekord->id}">&nbsp;</a>
                    </td>
                {/if}
                <td class="colID"><a class="linkEdit"
                                     href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">{$rekord->id}</a>
                </td>

                {assign var=promotionId value=$rekord->promotionId}
                {assign var=promocjaNazwa value=$promocjeNazwy.$promotionId}
                <td><a class="linkEdit"
                       href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">{$promocjaNazwa}</a>
                </td>

                {assign var=przedstawicielId value=$rekord->przedstawicielId}
                {assign var=userName value=$userNames.$przedstawicielId}
                <td><a class="linkEdit"
                       href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">{$userName}</a>
                </td>

                {assign var=statusId value=$rekord->statusId}
                {assign var=status value=$statusy.$statusId}
                <td><a class="linkEdit"
                       href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">{$status->nazwa}</a>
                </td>

                <td><a class="linkEdit"
                       href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">{$rekord->nextEditorName}</a>
                </td>
            </tr>
        {/foreach}
    </table>

    <div class="listaFooter">
        <table>
            <tr>
                <td class="left">&nbsp;</td>
                <td class="middle">
                    {if $uprawnienia.usuwanie==1}
                        <div style="float:left;">
                            {literal}
                                <a href="javascript:void(0);"
                                   onclick="if(confirm('Czy na pewno usunąć zaznaczone rekordy?')) { jQuery('input#akcjaHurt').val('usun'); jQuery('form#edycjaHurt').submit(); }"><img
                                            src="/www/cms/img/buttons/usun-zaznaczone.png"
                                            style="float:right; padding-right:20px;"/></a>
                            {/literal}
                        </div>
                    {/if}
                    <div class="pagination">{$porcjowarka}</div>
                </td>
                <td class="right">&nbsp;</td>
            </tr>
        </table>
    </div>
</form>

<script>
    {literal}
    jQuery(document).ready(function () {
        jQuery('.buttonDelete').click(function () {
            return confirm('Czy na pewno usunąć rekord?');
        });
    });
    {/literal}
</script>
