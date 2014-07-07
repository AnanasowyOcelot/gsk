<div id="content">
    <div style="position: absolute; left: 570px; top: 260px; z-index: -1;"><img src="/www/page/img/bg_poswiata_2.png" /></div>
    <div style="position: absolute; top: -152px; left: 418px; z-index: -1;"><img src="/www/page/img/bg_podstrona.png" /></div>
    <div style="width:628px; height: 530px; float: left; overflow: hidden;">
        <h1 style="margin: 36px 0 10px 0;" class="cufonPageHeader">{$podstrona->tytul.$jezyk_id}</h1>
        <div id="podstronaTresc" style="height: 436px; padding: 16px 0 16px 0; border-top: 1px solid #1d1d1d; border-bottom: 1px solid #1d1d1d;">{$tresc}</div>
    </div>
    {$box_podstrona}
    {$box_anim}
    {$box_2}
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
