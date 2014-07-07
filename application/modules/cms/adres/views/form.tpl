
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Adresy &raquo; {$form_nazwa}</h3>
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
            <label>Firma:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[firma]" value="{$r->firma}" class="{$errors.firma}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Miasto:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[miejscowosc]" value="{$r->miejscowosc}" class="{$errors.miejscowosc}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Kod pocztowy:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[kodPocztowy]" value="{$r->kodPocztowy}" class="{$errors.kodPocztowy}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Ulica:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[ulica]" value="{$r->ulica}" class="{$errors.ulica}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Nr lokalu:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[nrLokalu]" value="{$r->nrLokalu}" class="{$errors.nrLokalu}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Osoba odpowiedzialna - imię, nazwisko:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[osobaOdpowiedzialna]" value="{$r->osobaOdpowiedzialna}" class="{$errors.osobaOdpowiedzialna}"/>
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
            <label>Aktywny:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="checkbox" name="r[aktywny]" value="1" class="{$errors.aktywny}" {if $r->aktywny}checked="chedcked"{/if} />
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Data utworzenia:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        {$r->dataUtworzenia}
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Data aktualizacji:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        {$r->dataAktualizacji}
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
