<div id="content">
    <div style="position: absolute; left: 680px; top: 30px; z-index: -1;"><img src="/www/page/img/bg_poswiata_3.png" /></div>
    <div style="width:628px; height: 530px; float: left; overflow: hidden;">
        <h1 style="margin: 36px 0 10px 0;" class="cufonPageHeader">{$podstrona->tytul.$jezyk_id}</h1>
        <div id="podstronaTresc" style="height: 436px; padding: 16px 0 16px 0; border-top: 1px solid #1d1d1d; border-bottom: 1px solid #1d1d1d;">{$tresc}</div>
    </div>
     <div style="float: left; overflow: hidden; width: 246px; height:155px; padding: 20px; margin: 35px 0 0 30px; background: #000;">
    	{$box_punkty}
    	
    </div>
     <div style="float: left; overflow: hidden; width: 246px; height:257px; padding: 25px 20px 20px 20px; margin: 0px 0 0 30px; background: #000;">
   	{$box_nagrody}    	
    </div>
   
    
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
