
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Tagi kategorie &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div style="overflow:hidden; position:relative;">

	<!-- FORMULARZ ------>
	<div class="formularz" style="float:left;">
		<form action="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekordId}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="wymagane" value="nazwa#s">
            <input type="hidden" name="r[id]" value="{$r->id}" />

            <div class="wiersz">
                <label>Nazwa:</label>
                <div class="fieldSet">
                    <div class="field">
                        <div class="fieldWrapper">
                            <input type="text" name="r[name]" value="{$r->name}" class="{$errors.name}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div style="overflow:hidden; position:relative;">
                <a href="{$link}index/{$linkParams}"><img src="{$img_path}button-back.png" style="float:left;"/></a>
                <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
                {if $button_del==1}
                    <a href="{$link}usun/{$linkParams}id:{$r->id}"><img src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
                {/if}
            </div>

        </form>
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

<script type="text/javascript">
    /* {literal} */
    jQuery(document).ready(function () {
        jQuery('#color').iris({
            hide: false,
            palettes: ['#626262', '#bee7fb', '#ffc20e', '#329dc9', '#004b87', '#bf1e2e']
        });
    });
    /* {/literal} */
</script>
