
<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Użytkownicy &raquo; {$form_nazwa}</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div style="overflow:hidden; position:relative;">

	<!-- FORMULARZ ------>
	<div class="formularz" style="float:left;">

		<form action="{$link}edytuj/{$linkParams}{$primaryKeyName}:{$rekordId}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="wymagane" value="name,email,password#s">
		<input type="hidden" name="r[{$primaryKeyName}]" value="{$r->$primaryKeyName}" />

        <div class="wiersz">
            <label>Przełożony:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <select name="r[supervisor_id]">
                            <option value="0">--- brak ---</option>
                            {foreach from=$supervisors item=supervisor}
                                <option {if $supervisor->id == $r->supervisor_id}selected="selected"{/if} value="{$supervisor->id}">{$supervisor->name}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Imię i nazwisko:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[name]" value="{$r->name}" class="{$errors.name}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>E-mail (login):</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[email]" value="{$r->email}" class="{$errors.email}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Hasło:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="text" name="r[password]" value="{$r->password}" class="{$errors.password}"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="wiersz">
            <label>Aktywny:</label>
            <div class="fieldSet">
                <div class="field">
                    <div class="fieldWrapper">
                        <input type="checkbox" name="r[active]" value="1" class="{$errors.active}" {if $r->active}checked="chedcked"{/if} />
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

        <div class="wiersz">
            <label>Tagi:</label>
            <div class="fieldSet">
                <div style="width: 48%; float: left; overflow: hidden; margin: 2px;">
                    Przypisane:
                    <ul id="sortable1" class="connectedSortable" style="border: 1px solid #cccccc; padding: 10px;">
                        {foreach from=$connectedTags item=tag}
                            <li class="tagLabel" title="{$tag->description}" style="background-color: {$tag->color};">
                                <input type="hidden" name="r[tag][{$tag->id}]" value="1" />
                                {$tag->name}
                            </li>
                        {/foreach}
                    </ul>
                </div>
                <div style="width: 48%; float: left; overflow: hidden; margin: 2px;">
                    Dostępne:
                    <ul id="sortable2" class="connectedSortable" style="border: 1px solid #cccccc; padding: 10px;">
                        {foreach from=$availableTags item=tag}
                            <li class="tagLabel" title="{$tag->description}" style="background-color: {$tag->color};">
                                <input type="hidden" name="r[tag][{$tag->id}]" value="0" />
                                {$tag->name}
                            </li>
                        {/foreach}
                    </ul>
                </div>

                <script>
                    {literal}
                    jQuery(function() {
                        jQuery("#sortable1").data('tagVal', '1');
                        jQuery("#sortable2").data('tagVal', '0');
                        jQuery("#sortable1, #sortable2").sortable({
                            connectWith: ".connectedSortable",
                            receive: function(event, ui) {
                                var tagVal = jQuery(event.target).data('tagVal');
                                jQuery('input', event.toElement).val(tagVal);
                            }
                        }).disableSelection();
                    });
                    {/literal}
                </script>
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
