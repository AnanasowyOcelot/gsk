
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Subskrybenci &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div style="overflow:hidden; position:relative;">

	<!-- FORMULARZ ------>
    <div class="formularz" style="float:left;">
		<form action="{$link}edytuj/{$linkParams}id:{$r->id}" method="post" enctype="multipart/form-data">

            {$form->wiersz('wymagane')}

            {$form->wiersz('r[id]')}

            {$form->wiersz('r[email]')}

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
</div>

