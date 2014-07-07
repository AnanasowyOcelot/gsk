<div id="content">
    <div style="position: absolute; top: -152px; left: 418px; z-index: -1;"><img src="/www/page/img/bg_podstrona.png" /></div>
    <div style="width:628px; height: 530px; float: left; overflow: hidden;">
        <h1 style="margin: 36px 0 10px 0;">tytul podstrony{$tytul}</h1>
        <div id="podstronaTresc" style="height: 436px; padding: 16px 0 16px 0; border-top: 1px solid #1d1d1d; border-bottom: 1px solid #1d1d1d;">{$tresc}</div>
    </div>
    <div style="float: left; overflow: hidden; width: 242px; padding: 22px; margin: 80px 0 0 30px; background: #000;">{$box_1}</div>
    <div style="float: left; overflow: hidden; width: 242px; padding: 22px; margin: 10px 0 0 30px; background: #000;">{$box_2}</div>
</div>
<script>
{literal}
    jQuery(document).ready(function () {

        jQuery('#podstronaTresc').jScrollPane({
            showArrows: true
        });

    });
{/literal}
</script>
