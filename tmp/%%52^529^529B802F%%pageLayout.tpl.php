<?php /* Smarty version 2.6.18, created on 2014-07-07 11:50:08
         compiled from C:%5Cxampp%5Chtdocs%5Cgsk%5Capplication%5Cviews%5Cpage%5CpageLayout.tpl */ ?>
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
    <?php if ($this->_tpl_vars['jezyk_id'] == 1): ?>
        <link href="/www/page/css/style-pl.css" rel="stylesheet" type="text/css"/>
    <?php else: ?>
        <link href="/www/page/css/style-en.css" rel="stylesheet" type="text/css"/>
    <?php endif; ?>

    <link href="/www/page/css/validationEngine.jquery.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="/www/page/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="/www/page/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/www/page/js/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="/www/page/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/www/page/js/controller.class.js"></script>

    <?php if ($this->_tpl_vars['jezyk_id'] == 1): ?>
        <script type="text/javascript" src="/www/page/js/jquery.validationEngine-pl.js"></script>
    <?php else: ?>
        <script type="text/javascript" src="/www/page/js/jquery.validationEngine-en.js"></script>
    <?php endif; ?>
    <script type="text/javascript" src="/www/page/js/jquery.validationEngine.js"></script>
</head>
<body>


<div class="container">
    <div class="header">
        <!--<?php echo $this->_tpl_vars['menuPodstronyGora']; ?>
-->
        <!--<ul class="nav nav-pills pull-right">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>-->
        <!--<div>
            <?php if ($this->_tpl_vars['jezyk_id'] == 1): ?>
                <a href="javascript:void(0)" onclick="zmienJezyk(1,2,'<?php echo $this->_tpl_vars['url_page']; ?>
');">english version</a>
            <?php else: ?>
                <a href="javascript:void(0)" onclick="zmienJezyk(2,1,'<?php echo $this->_tpl_vars['url_page']; ?>
');">polish version</a>
            <?php endif; ?>
        </div>-->
        <!--<form class="navbar-form pull-right" method="post" action="/cms/login/zaloguj">
            <input id="login" name="login" class="input-small" type="text" placeholder="Email" />
            <input id="haslo" name="haslo" class="input-small" type="password" placeholder="Hasło" />
            <button type="submit" class="btn">Zaloguj</button>
        </form>-->
        <!--<div id="topLoginBox">
            <?php if ($this->_tpl_vars['czy_zalogowany'] == 1): ?>
                Witaj
                <span><?php echo $this->_tpl_vars['zalogowany_imie']; ?>
 <?php echo $this->_tpl_vars['zalogowany_nazwisko']; ?>
</span>
                |
                <a href="javascript:void(0);"
                   onclick="zmianaDanychPanel(<?php echo $this->_tpl_vars['jezyk_id']; ?>
);"><?php echo $this->_tpl_vars['naglowki'][$this->_tpl_vars['jezyk_id']]['zmien_dane']; ?>
</a>
                |
                <a href="/klient/wylogowanie">wyloguj</a>
            <?php else: ?>
                <a href="javascript:void(0);"
                   onclick="loginPanel(<?php echo $this->_tpl_vars['jezyk_id']; ?>
,'<?php echo $this->_tpl_vars['url']; ?>
');"><?php echo $this->_tpl_vars['naglowki'][$this->_tpl_vars['jezyk_id']]['logowanie']; ?>
</a>
                &nbsp; | &nbsp;
                <a href="javascript:void(0);"
                   onclick="rejestracjaPanel(<?php echo $this->_tpl_vars['jezyk_id']; ?>
);"><?php echo $this->_tpl_vars['naglowki'][$this->_tpl_vars['jezyk_id']]['zarejestruj_sie']; ?>
</a>
            <?php endif; ?>
        </div>-->
        <h3 class="text-muted">GlaxoSmithKline</h3>
    </div>

    <?php echo $this->_tpl_vars['tresc']; ?>


</div>

</body>
</html>