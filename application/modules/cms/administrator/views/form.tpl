<!-- *********************************************************** -->
<!--<script type="text/javascript" src="/application/modules/cms/uzytkownik/js/ajax.js"></script>-->
<!-- *********************************************************** -->

<!--<p><a href="{$link}"><img src="/pub/img/button-back.png" /></a></p>-->

<div class="listaHeader listaHeaderFormularz">
    <table><tr>
        <td class="left">&nbsp;</td>
        <td class="middle">
            <h3>Administratorzy &raquo; edytuj</h3>
        </td>
        <td class="right">&nbsp;</td>
    </tr></table>
</div>

<div class="formularz">

<form action="{$link_form}" method="post" name="administratorEditForm" id="administratorEditForm">

<input type="hidden" name="r[id]" value="{$r->id}" />

<div class="wiersz">
    <label>Imię:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[imie]" value="{$r->imie}" />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Nazwisko:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[nazwisko]" value="{$r->nazwisko}" />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Email:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[email]" value="{$r->email}" />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Login:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[login]" value="{$r->login}" />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Hasło:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[haslo]" value="" />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Potwierdź hasło:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="text" name="r[haslo_potw]" value="" />
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Grupa:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                {$selectGrupy}
            </div>
        </div>
    </div>
</div>

<div class="wiersz">
    <label>Aktywny:</label>
    <div class="fieldSet">
        <div class="field">
            <div class="fieldWrapper">
                <input type="hidden" name="r[aktywny]" value="0" />
                <input type="checkbox" name="r[aktywny]" value="1" {if $r->aktywny}checked="chedcked"{/if} />
            </div>
        </div>
    </div>
</div>

<div style="overflow:hidden; position:relative;">
	<a href="{$link}"><img src="{$img_path}button-back.png" style="float:left;"/></a>	
	<input type="image" name="mysubmit" value="zapisz" src="{$img_path}buttons/button-save.png" style="float:right;"/>
	<a href="{$link}"><img src="{$img_path}buttons/button-del.png" style="float:right; padding-right:20px;"/></a>
</div>

</form>

</div>
