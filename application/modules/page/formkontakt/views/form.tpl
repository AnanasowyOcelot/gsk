<div class="kontaktForm">
    <form id="kontaktForm">
        <label>{$naglowki.$jezyk_id.imie_i_nazwisko}</label>
        <input type="text" class="validate[required]" />
        <label>{$naglowki.$jezyk_id.adres_email}</label>
        <input type="text" class="validate[required,custom[email]]" />
        <label>{$naglowki.$jezyk_id.tresc}</label>
        <textarea class="validate[required]"></textarea>
        <div style="text-align: right;">
            <a href="javascript:void(0);" class="przycisk przyciskWyczysc">{$naglowki.$jezyk_id.wyczysc}</a>
            <a href="javascript:void(0);" class="przycisk przyciskWyslij">{$naglowki.$jezyk_id.wyslij}</a>
        </div>
    </form>
</div>
                
<script>
{literal}
jQuery(document).ready(function () {
    // CUFON ////////////////////////////////////////////////////////
    Cufon.replace(".kontaktForm label");
    Cufon.replace(".kontaktForm .przycisk");
    
    jQuery('#kontaktForm .przyciskWyczysc').click(function () {
        jQuery('#kontaktForm input').val('');
        jQuery('#kontaktForm textarea').val('');
        
        $("#kontaktForm").validationEngine('hideAll');
    });
    
    $("#kontaktForm input[type=text]").click(function () {
        jQuery(this).validationEngine('hide');
    });
    
    $("#kontaktForm").validationEngine('attach', {
        promptPosition: 'inline'
    });
    
    jQuery('#kontaktForm .przyciskWyslij').click(function () {
        jQuery('#kontaktForm').submit();
    });
    
});
{/literal}
</script>      