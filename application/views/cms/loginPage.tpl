<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>My Mass Market</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>

<link href="/www/cms/css/main.css" rel="stylesheet" type="text/css" />
<link href="/www/cms/css/formalize.css" rel="stylesheet" type="text/css" />


<script type="text/javascript" src="/www/cms/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="/www/cms/js/engine.js"></script>
</head>
	<body id="login">		
		<div id="login_container">	
		{if $bledy!=''}	
		<div class="login_error">
			{$bledy}
		</div>
		{/if}
			<div id="login_form">
				<form method="post" action="/cms/login/zaloguj">
				<p>
					<input type="text" id="login" name="login" placeholder="Login" class="" />
				</p>
				<p>
					<input type="password" id="haslo" name="haslo" placeholder="Hasło" class="" />
				</p>
				<button type="submit" class="button blue"><span class="glyph"></span> Login</button>
				</form>
			</div>			
		</div>
	</body>
</html>
