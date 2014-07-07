<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>GSK</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Cache-Control" content="no-cache"/>
    <meta http-equiv="Expires" content="Sat, 01 Dec 2001 00:00:00 GMT"/>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta content="IE=7" http-equiv="X-UA-Compatible"/>
    <link href="/www/page/css/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
    <link href="/www/page/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/www/page/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="/www/page/css/style.css" rel="stylesheet" type="text/css"/>
    {if $jezyk_id==1}
        <link href="/www/page/css/style-pl.css" rel="stylesheet" type="text/css"/>
    {else}
        <link href="/www/page/css/style-en.css" rel="stylesheet" type="text/css"/>
    {/if}

    <link href="/www/page/css/validationEngine.jquery.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="/www/page/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="/www/page/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/www/page/js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="/www/page/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/www/page/js/controller.class.js"></script>

    {if $jezyk_id==1}
        <script type="text/javascript" src="/www/page/js/jquery.validationEngine-pl.js"></script>
    {else}
        <script type="text/javascript" src="/www/page/js/jquery.validationEngine-en.js"></script>
    {/if}
    <script type="text/javascript" src="/www/page/js/jquery.validationEngine.js"></script>
</head>
<body>


<div class="container">
    <div class="header">
        <!--{$menuPodstronyGora}-->
        <!--<ul class="nav nav-pills pull-right">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>-->
        <!--<div>
            {if $jezyk_id==1}
                <a href="javascript:void(0)" onclick="zmienJezyk(1,2,'{$url_page}');">english version</a>
            {else}
                <a href="javascript:void(0)" onclick="zmienJezyk(2,1,'{$url_page}');">polish version</a>
            {/if}
        </div>-->
        <!--<form class="navbar-form pull-right" method="post" action="/cms/login/zaloguj">
            <input id="login" name="login" class="input-small" type="text" placeholder="Email" />
            <input id="haslo" name="haslo" class="input-small" type="password" placeholder="Hasło" />
            <button type="submit" class="btn">Zaloguj</button>
        </form>-->
        <!--<div id="topLoginBox">
            {if $czy_zalogowany eq 1}
                Witaj
                <span>{$zalogowany_imie} {$zalogowany_nazwisko}</span>
                |
                <a href="javascript:void(0);"
                   onclick="zmianaDanychPanel({$jezyk_id});">{$naglowki.$jezyk_id.zmien_dane}</a>
                |
                <a href="/klient/wylogowanie">wyloguj</a>
            {else}
                <a href="javascript:void(0);"
                   onclick="loginPanel({$jezyk_id},'{$url}');">{$naglowki.$jezyk_id.logowanie}</a>
                &nbsp; | &nbsp;
                <a href="javascript:void(0);"
                   onclick="rejestracjaPanel({$jezyk_id});">{$naglowki.$jezyk_id.zarejestruj_sie}</a>
            {/if}
        </div>-->
        <h3 class="text-muted">GlaxoSmithKline</h3>
    </div>

    {$tresc}

</div>

</body>
</html>
