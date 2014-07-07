
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Box &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div style="overflow:hidden; position:relative;">

	<!-- FORMULARZ ------>
	<div class="formularz" style="float:left;">

		<form action="{$link}edytuj/id:{$r->id}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="wymagane" value="nazwa#s">
		<input type="hidden" name="r[id]" id="gal_id" value="{$r->id}" />
		
		<div class="wiersz">
		    <label>Nazwa:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[nazwa][{$jezykId}]" value="{$r->nazwa[$jezykId]}" class="{$errors.nazwa.$jezykId}"/>
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Tytuł:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[tytul][{$jezykId}]" value="{$r->tytul[$jezykId]}" class="{$errors.tytul.$jezykId}"/>
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Tytuł treść:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[tytul_tresc][{$jezykId}]" value="{$r->tytul_tresc[$jezykId]}" class="{$errors.tytul_tresc.$jezykId}"/>
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
		
		
		<div class="wiersz">
		    <label>Podstrona:</label>
		    <div class="fieldSet">
		        <div class="field">
		            <div class="fieldWrapper">
		                <select name="r[podstrona_id]">
		                	{$parentSelect}
		                </select>
		            </div>
		        </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Szablon:</label>
		    <div class="fieldSet">
		        <div class="field">
		            <div class="fieldWrapper">
		                <input type="text" name="r[szablon_id]" value="{$r->szablon_id}" />
		            </div>
		        </div>
		    </div>
		</div>
		
		
		
		<div class="wiersz">
		    <label>Opis:</label>
		    <div class="fieldSet">
		        <div class="field">
		            <div class="fieldWrapper">
		                <div id="tabs_tresc">
		                    <ul>
		                        {foreach from=$jezyki key=jezykId item=jezykSkrot}                               
		                            <li><a href="#tab-{$jezykId}"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span> {$jezykSkrot}</a></li>                            
		                        {/foreach}
		                    </ul>
		                    {foreach from=$jezyki key=jezykId item=jezykSkrot}            
		                        <div id="tab-{$jezykId}">{$pole_tresc[$jezykId]}</div>
		                    {/foreach}        
		                </div>    
		            </div>
		        </div>
		    </div>
		</div>
		
		{*
		<div class="wiersz">
		    <label>Url:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[url][{$jezykId}]" value="{$r->url[$jezykId]}" />
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
		*}
		
		<div class="wiersz">
		    <label>Link:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[link][{$jezykId}]" value="{$r->link[$jezykId]}" />
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
		
		
		
		<div class="wiersz">
		    <label>Kolejność:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[miejsce][{$jezykId}]" value="{$r->miejsce[$jezykId]}" />
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Zdjęcie lista:</label>
		    <div class="fieldSet">
		        <div class="field">
		            <div class="fieldWrapper">
		              <input type="file" name="obrazek">
		            </div>
		        </div>
		    </div>
		</div>
		<div class="wiersz">
		    <label>&nbsp;</label>
		    <div class="fieldSet">
		        <div class="field">
		            <div class="fieldWrapper">
		            {if $r->zdjecie!=''}
		              <img src="/images/boxy/1/{$r->zdjecie}">
		              {/if}
		            </div>
		        </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Aktywny:</label>
		    <div class="fieldSet">
		        {foreach from=$jezyki key=jezykId item=jezykSkrot}
		            <div class="field fieldInline">
		                <div class="fieldWrapper">
		                    <input type="hidden" name="r[aktywna][{$jezykId}]" value="0" />
		                    <input type="checkbox" name="r[aktywna][{$jezykId}]" value="1" {if $r->aktywna[$jezykId]}checked="checked"{/if} />
		                </div>
		                <span class="jezyk"><span class="flag flag-{$jezykSkrot}" alt="{$jezykSkrot}" /></span>
		            </div>
		        {/foreach}
		    </div>
		</div>
		
		
		<div style="overflow:hidden; position:relative;">
		    <a href="{$link}"><img src="{$img_path}button-back.png" style="float:left;"/></a>    
		    <input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
		    {if $button_del==1}
		    	<a href="{$link}usun/id:{$r->id}"><img src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
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
		   	<a href="javascript:;" onclick="f_przelacz('box_historia')" style="text-decoration:none;">
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
