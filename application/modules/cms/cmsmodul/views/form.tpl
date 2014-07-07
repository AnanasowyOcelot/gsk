
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>CMS moduły &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div style="overflow:hidden; position:relative;">

	<!-- FORMULARZ ------>
	<div class="formularz" style="float:left;">

		<form action="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekordId}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="wymagane" value="nazwa#s">
		<input type="hidden" name="r[index]" value="{$r->index}" />
		
        <div class="wiersz">
            <label>Id:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[id]" value="{$r->id}" class="{$errors.id}"/>
                    </div>
                </div>
            </div>
        </div>

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
            <label>Aktywny:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="checkbox" name="r[aktywny]" value="1" class="{$errors.aktywny}" {if $r->aktywny}checked="chedcked"{/if} />
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
		</div>
	<!-- FORMULARZ ------>
	
	
	<!-- HISTORIA ------>
	<div  style="float:left;  ">
		{if $historia_html!=''}
		<div class="wiersz" style="position:relative; overflow:hidden;">		  
		   <div class="fieldSet" id="box_historia" style="float:left; {if $historiaOpen==0}display:none;{/if}  background-color:#f9f9f9; width:400px; padding:5px; border-top:1px solid #c2ccce; border-right:1px solid #c2ccce; border-bottom:1px solid #c2ccce;">	
		            <div>
			{$historia_html}
		            </div>		  
		    </div>
		   <div style="float:left; background-color:#c2ccce; width:15px; text-align:center; height:160px; padding:5px; border-top:1px solid #c2ccce; border-right:1px solid #c2ccce; border-bottom:1px solid #c2ccce;" >
		   	<a href="javascript:;" onclick="jQuery('#box_historia').toggle(); jQuery(this).blur();" style="text-decoration:none;">
		   	H
		   	I
		   	S
		   	T
		   	O
		   	R
		   	I		   	
		   	A
		   	<
		   	</a>
		   </div>
		   {/if}
		</div>
	</div>
	<!-- HISTORIA ------>

</div>
</form>
