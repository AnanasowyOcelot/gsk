<form id="edycjaHurt" method="post">
    <input type="hidden" name="akcja" id="akcjaHurt" value=""/>

    <div class="listaHeader">
        <table>
            <tr>
                <td class="left">&nbsp;</td>
                <td class="middle">
                    <h3>Asortyment sieci - klienci</h3>
                    <a href="{$link}dodaj" style="text-align:right;"><img src="/www/cms/img/button-add.png"/></a>
                </td>
                <td class="right">&nbsp;</td>
            </tr>
        </table>
    </div>

    <table class="lista">
        <tr>
            <th><a href="{$link}index/{$linkParams}col:id">Id</a></th>
            <th><a href="{$link}index/{$linkParams}col:nazwa">Nazwa</a></th>
            <th>Logo</th>
        </tr>
        {foreach from=$lista  item=rekord}
            <tr>
                <td class="colID"><a class="linkEdit"
                                     href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">{$rekord->id}</a>
                </td>
                <td><a class="linkEdit"
                       href="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekord->id}">{$rekord->nazwa}</a>
                </td>
                <td>
                  <img src="/images/Model_AsortymentSieci_KlientEntity/p_0/1/{$rekord->id}.png" />
                </td>
            </tr>
        {/foreach}
    </table>

    <div class="listaFooter">
        <table>
            <tr>
                <td class="left">&nbsp;</td>
                <td class="middle">
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
