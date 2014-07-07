
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Klient &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div style="overflow:hidden; position:relative;">

	<!-- FORMULARZ ------>
	<div class="formularz" style="float:left;">

		<form action="{$link}edytuj/id:{$r->id}" method="post" enctype="multipart/form-data">
		
		<input type="hidden" name="wymagane" value="nazwa#s,cena#s">	
		<input type="hidden" name="r[id]" id="szkolenie_id" value="{$r->id}" />	
		
		<div class="wiersz">
		    <label>Imię:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[imie]" value="{$r->imie}" class="{$errors.imie}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Nazwisko:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[nazwisko]" value="{$r->nazwisko}" class="{$errors.nazwisko}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Adres:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[adres]" value="{$r->adres}" class="{$errors.adres}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Kod:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[kod]" value="{$r->kod}" class="{$errors.kod}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Miasto:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[miasto]" value="{$r->miasto}" class="{$errors.miasto}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Telefon:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[telefon]" value="{$r->telefon}" class="{$errors.telefon}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>E-mail:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[email]" value="{$r->email}" class="{$errors.email}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Firma nazwa:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[firma_nazwa]" value="{$r->firma_nazwa}" class="{$errors.firma_nazwa}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Firma adres:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[firma_adres]" value="{$r->firma_adres}" class="{$errors.firma_adres}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Firma kod:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[firma_kod]" value="{$r->firma_kod}" class="{$errors.firma_kod}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Firma miasto:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[firma_miasto]" value="{$r->firma_miasto}" class="{$errors.firma_miasto}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		
		<div class="wiersz">
		    <label>Firma NIP:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[firma_nip]" value="{$r->firma_nip}" class="{$errors.firma_nip}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Firma telefon:</label>
		    <div class="fieldSet">		      
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[firma_telefon]" value="{$r->firma_telefon}" class="{$errors.firma_telefon}"/>
		                </div>		      
		            </div>
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Punkty:</label>
		    <div class="fieldSet">		       
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[punkty]" value="{$r->punkty}" class="{$errors.punkty}"/>
		                </div>		               
		            </div>		    
		    </div>
		</div>
		
		<div class="wiersz">
		    <label>Nr. karty klubowej:</label>
		    <div class="fieldSet">		       
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[nr_karty]" value="{$r->nr_karty}" class="{$errors.nr_karty}"/>
		                </div>		               
		            </div>		    
		    </div>
		</div>
		
		
		{*
		<div class="wiersz">
		    <label>Login:</label>
		    <div class="fieldSet">		       
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="text" name="r[login]" value="{$r->login}" class="{$errors.login}"/>
		                </div>		               
		            </div>		    
		    </div>
		</div>
		*}
		
	  	<div class="wiersz">
		    <label>Haslo:</label>
		    <div class="fieldSet">		       
		            <div class="field">
		                <div class="fieldWrapper">
		                    <input type="password"  name="r[haslo]" value="{$r->haslo}" class="{$errors.haslo}"/>
		                </div>		               
		            </div>		    
		    </div>
		</div>
		
		
		

		{*
		
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
		*}
		
		
		<div class="wiersz">
		    <label>Aktywny:</label>
		    <div class="fieldSet">
		                <div class="fieldWrapper">
		                    <input type="hidden" name="r[aktywna]" value="0" />
		                    <input type="checkbox" name="r[aktywny]" value="1" {if $r->aktywny}checked="checked"{/if} />
		                </div>
		      
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
