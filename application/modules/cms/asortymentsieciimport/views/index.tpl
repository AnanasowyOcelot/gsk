
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
            <td class="left">&nbsp;</td>
            <td class="middle">
                <h3>Asortyment sieci &raquo; import</h3>
            </td>
            <td class="right">&nbsp;</td>
        </tr></table>
</div>

<div style="overflow:hidden; position:relative;">
    <div class="formularz" style="float:left;">

        <form action="{$link}importuj" method="post" enctype="multipart/form-data">

            <div class="wiersz">
                <label>Plik xls:</label>
                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="file" name="plikXls">
                        </div>
                    </div>
                </div>
            </div>

            <div style="overflow:hidden; position:relative;">
                <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
            </div>

        </form>

    </div>
</div>

{$debug}
