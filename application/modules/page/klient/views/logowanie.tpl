
<div style="padding:35px;" id="loginFormBox">
	<div style="font-size:36px; color:#f68702;">{$naglowki.$jezyk_id.tytul}</div>

	<div style="margin:10px 0;">
		<div style="margin-bottom: 7px;">
			<label for="login" style="display:inline-block; width:115px; padding-bottom:5px; font-size:18px;" class="label">{$naglowki.$jezyk_id.email}: </label>
			<input type="text" id="login" name="login" style="float:right; width:220px; height:25px; border:1px solid #FFF; background:#633b0c; color:#fff; padding: 0 3px;"/>
		</div>
		<div>
			<label for="login" style="display:inline-block; width:115px; padding-bottom:5px; font-size:18px;" class="label">{$naglowki.$jezyk_id.haslo}: </label>
			<input type="password" id="passwd" name="passwd" style="float:right; width:220px; height:25px; border:1px solid #FFF; background:#633b0c; color:#fff; padding: 0 3px;" />
		</div>
	</div>
	<div style="position:relative; overflow:hidden; width:348px;">
        <a href="javascript:void(0);" onclick="zaloguj('{$url}', '{$jezyk_id}');" style="float:right; color:#f68702; text-decoration:none; font-size:25px;" class="buttonLogin">{$naglowki.$jezyk_id.button}</a>
        <a href="javascript:void(0);" onclick="przypomnijHaslo('{$jezyk_id}');" style="float:right; color:#535353; text-decoration:none; font-size:14px; padding-top: 9px; margin-right: 16px;" class="buttonLogin">{$naglowki.$jezyk_id.zapomnialem_hasla}</a>
		<div id="login_error"></div>
	</div>
</div>
