
<div style="" id="loginFormBox">
	<div class="cufonHeader" style="font-size:36px; color:#f68702;">{$naglowki.$jezyk_id.tytul}</div>
	
	<!--<div id="login_error" style="display:none;">Please, enter data</div>-->
	
	<div style="margin:10px 0px;">
		<div style="margin-bottom: 7px;">
			<label for="login" style="display:inline-block; width:120px; padding-bottom:5px; font-size:18px;" class="label">{$naglowki.$jezyk_id.email}: </label>
			<input type="text" id="login" name="login" style="width:220px; height:25px; border:1px solid #FFF; background:#633b0c; color:#fff; padding: 0 3px;"/>
		</div>
		<div style="margin-bottom: 7px;">
			<label for="login" style="display:inline-block; width:120px; padding-bottom:5px; font-size:18px;" class="label">{$naglowki.$jezyk_id.haslo}: </label>
			<input type="password" id="passwd" name="passwd" style="width:220px; height:25px; border:1px solid #FFF; background:#633b0c; color:#fff; padding: 0 3px;" />
		</div>
		<div>
			<label for="login" style="display:inline-block; width:120px; padding-bottom:5px; font-size:18px;" class="label">{$naglowki.$jezyk_id.haslo_potw}: </label>
			<input type="password" id="passwd_potw" name="passwd_potw" style="width:220px; height:25px; border:1px solid #FFF; background:#633b0c; color:#fff; padding: 0 3px;" />
		</div>
	</div>
	<div style="position:relative; overflow:hidden; width:348px;">	
        <a href="javascript:void(0)" onclick="zmianaHaslaSend('{$jezyk_id}')" style="float:right; color:#f68702; text-decoration:none;  font-size:25px;" class="buttonLogin">{$naglowki.$jezyk_id.button}</a>			
      	<!--<a href="javascript:void(0)" onclick="$.fancybox.close();" style="margin-right: 145px; float:right; display:block; width:150px; height:18px; border:1px solid #f68702; background-color:#FFF; color:#f68702; text-decoration:none; text-align:center; padding:5px 0px; font-size:18px;" class="buttonLogin">zamknij</a>-->
		<div id="login_error"></div>
	</div>
</div>

<script>
{literal}
jQuery(document).ready(function () {
    Cufon.replace(".cufonHeader" , { fontFamily: 'PF Handbook Pro' });
    Cufon.replace(".label" , { fontFamily: 'PF Handbook Pro' });
    Cufon.replace(".buttonLogin" , { fontFamily: 'PF Handbook Pro' });
});
{/literal}
</script>
