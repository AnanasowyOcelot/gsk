<div id="content">
    <div style="position: absolute; top: -110px; left: 65px; z-index: -1;"><img src="/www/page/img/bg_karty.png" /></div>
    <div style="width:628px; height: 530px; float: left; overflow: hidden;">
        <h1 style="margin: 36px 0 10px 0;" class="cufonPageHeader">{$podstrona->tytul.$jezyk_id}</h1>
        <div id="podstronaTresc" style="height: 436px; padding: 16px 0 16px 0; border-top: 1px solid #1d1d1d; border-bottom: 1px solid #1d1d1d;">{$karty_podarunkowe}</div>
    </div>
    <div style="float: left; overflow: hidden; width: 246px; height:225px; padding: 20px; margin: 80px 0 0 30px; ">
    	
    </div>
    {$box_7}
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
