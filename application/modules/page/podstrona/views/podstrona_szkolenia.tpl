<div id="content">
    <div style="position: absolute; left: 570px; top: 260px; z-index: -1;"><img src="/www/page/img/bg_poswiata_2.png" /></div>
    <div style="position: absolute; top: -152px; left: 418px; z-index: -1;"><img src="/www/page/img/bg_podstrona.png" /></div>
    <div style="width:628px; height: 530px; float: left; overflow: hidden;">
        <h1 style="margin: 36px 0 10px 0;" class="cufonPageHeader">{$podstrona->tytul.$jezyk_id}</h1>
        <div id="podstronaTresc" style="height: 436px; padding: 16px 0 16px 0; border-top: 1px solid #1d1d1d; border-bottom: 1px solid #1d1d1d;">{$szczegoly_szkolenia}</div>
    </div>
    <div style="float: left; overflow: hidden; width: 246px; height:225px; padding: 20px; margin: 80px 0 0 30px; background: #000;">
    	<div class="boxHeaderLinia">Rodzaje szkoleń</div>
    	<div>
    		{$box_lista_szkolen}
    	</div>
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
