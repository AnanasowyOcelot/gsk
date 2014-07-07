
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Asortyment sieci - produkty &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div style="overflow:hidden; position:relative;">

	<!-- FORMULARZ ------>
	<div class="formularz" style="float:left;">

		<form action="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekordId}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="wymagane" value="firma#s">
		<input type="hidden" name="r[{$primaryKeyName}]" value="{$r->$primaryKeyName}" />

        <div class="wiersz">
            <label>Nazwa SKU:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        {$r->nazwaSku}
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Kategoria:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        {$r->kategoria}
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Segment:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        {$r->segment}
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>EAN:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        {$r->ean}
                    </div>
                </div>
            </div>
        </div>

		<div style="overflow:hidden; position:relative;">
		    <a href="{$link}index/{$linkParams}"><img src="{$img_path}button-back.png" style="float:left;"/></a>
		</div>
		</div>
	<!-- FORMULARZ ------>
	
	
	<!-- HISTORIA ------>
	<div style="clear: both; overflow: hidden;">
		{if $historia_html!=''}
		<div style="width: 792px; margin-top: 10px;">
            <div style="background-color:#b8ced9; text-align:center; padding:5px;" >
                <a href="javascript:void(0);" onclick="jQuery('#box_historia').toggle(); jQuery(this).blur();" style="text-decoration:none; color: #222222;">wyświetl/ukryj historię zmian</a>
            </div>
            <div id="box_historia" style="{if $historiaOpen==0}display:none;{/if} background-color:#f9f9f9;padding:5px; border:1px solid #b8ced9;">
                <div>{$historia_html}</div>
		    </div>
		</div>
        {/if}
	</div>
	<!-- HISTORIA ------>

</div>
</form>
