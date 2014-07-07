<div class="listaHeader listaHeaderFormularz">
  <table>
    <tr>
      <td class="left">&nbsp;</td>
      <td class="middle">
        <h3>Asortyment sieci - klienci &raquo; {$form_nazwa}</h3>
      </td>
      <td class="right">&nbsp;</td>
    </tr>
  </table>
</div>

<div style="overflow:hidden; position:relative;">

  <!-- FORMULARZ ------>
  <div class="formularz" style="float:left;">

    <form action="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekordId}" method="post" enctype="multipart/form-data">
      <input type="hidden" name="wymagane" value="firma#s">
      <input type="hidden" name="r[{$primaryKeyName}]" value="{$r->$primaryKeyName}"/>

      <div class="wiersz">
        <label>Nazwa:</label>

        <div class="fieldSet">
          <div class="field">
            <div class="fieldWrapper">
              {$r->nazwa}
            </div>
          </div>
        </div>
      </div>


      <div class="wiersz">
        <label>Logo:</label>

        <div class="fieldSet">
          <div class="field">
            <div class="fieldWrapper">
              <input type="file" name="p_0">
            </div>
          </div>
        </div>
      </div>
      <div class="wiersz">
        <label>&nbsp;</label>

        <div class="fieldSet">
          <div class="field">
            <div class="fieldWrapper">
              <a href="/images/AsortymentSieci_KlientEntity/p_0/4/{$r->pictures[0]}" target="_blank">
                <img src="/images/Model_AsortymentSieci_KlientEntity/p_0/1/{$r->id}.png" />
              </a>
            </div>
          </div>
        </div>
      </div>


      <div style="overflow:hidden; position:relative;">
        <a href="{$link}index/{$linkParams}"><img src="{$img_path}button-back.png" style="float:left;"/></a>
        <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
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
