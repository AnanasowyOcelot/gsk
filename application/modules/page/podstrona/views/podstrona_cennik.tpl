{* 
{$podstrona|@print_r} 
*}

<div id="content">
   <div style="position: absolute; left: 565px; top: 200px; z-index: -1;"><img src="/www/page/img/bg_poswiata_1.png" /></div>
   <div style="width:628px; height: 530px; float: left; overflow: hidden;">
        <h1 style="margin: 36px 0 10px 0;" class="cufonPageHeader">{$podstrona->tytul.$jezyk_id}</h1>
        <div id="podstronaTresc" style="height: 436px; padding: 16px 0 16px 0; border-top: 1px solid #1d1d1d; border-bottom: 1px solid #1d1d1d;"><div style="overflow: hidden;">{$box_cennik}</div></div>
    </div>
    <div style="margin: 35px 0 0 30px; float: left; overflow: hidden; ">
  	  {$box_8}
    </div>
    {$box_9}
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
