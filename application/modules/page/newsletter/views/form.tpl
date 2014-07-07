
<div style="background-color:#000000;">
	<div style="padding: 20px;">
        <div class="cufonHeader" style="font-size:36px; color:#f68702;">NEWSLETTER</div>
	<div style="font-size:12px; color:#FFF; padding:5px 0px 0px 0px; width:440px;">
	{$naglowki.$jezyk_id.tekst}
	</div>
        <form id="newsletterForm">
            <div style="margin:10px 0px 20px 0px;">
                <div >
                    <label for="login" style="display:block; padding:15px 0px 10px 0px; font-size:11px; color:#FFF;" >{$naglowki.$jezyk_id.naglowek}: </label>
                    <input type="text" name="email_newsletter" id="email_newsletter" value="" style="width:420px; height:36px; border:2px solid #FFF; background:#633b0c; color: #fff; padding: 0 10px;" class="validate[required,custom[email]]" />
                </div>
            </div>
        </form>
        <div style="position:relative; overflow:hidden; width:440px;">    
            <a href="javascript:void(0)" onclick="newsletterZapisz();" style="float:right; color:#f68702; text-decoration:none; font-size:25px;" class="buttonLogin">{$naglowki.$jezyk_id.button}</a>            
            <div id="login_error"></div>
        </div>
	</div>
</div>

<script>
{literal}
    jQuery(document).ready(function () {
        Cufon.replace(".cufonHeader" , { fontFamily: 'PF Handbook Pro' });
        Cufon.replace(".label" , { fontFamily: 'PF Handbook Pro' });
        Cufon.replace(".buttonLogin" , { fontFamily: 'PF Handbook Pro' });
    });
    
    
    $("#newsletterForm").validationEngine('attach', {
        promptPosition: 'inline'
    });
    
    $("#newsletterForm input[type=text]").click(function () {
        jQuery(this).validationEngine('hide');
    });
    
{/literal}
</script>    
