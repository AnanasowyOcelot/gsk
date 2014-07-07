<style>
{literal}
.registerInput{
    width:260px; height:27px; border:1px solid #FFF; background:#633b0c; color: #FFF; padding: 0 3px; float: right;
}
.registerTextarea{
    width:260px; height:60px; border:1px solid #FFF; background:#633b0c; color: #FFF; padding: 0 3px; float: right;
}
.label{
    display:inline-block; width: 150px; padding-bottom:2px; font-size:15px;
}
.wiersz{
    margin-bottom: 6px;
    overflow: hidden;
}
*.cufon-canvas {
     z-index: 0;
 }
{/literal}
</style>

<div style="padding:0px; width: 610px; height: 220px; overflow: hidden;" id="mainBox">
    <div class="cufonHeader" style="font-size:36px; color:#f68702; height: 40px;">{$tytul}</div>
    {$tresc}
</div>

<script>
{literal}
    jQuery('#mainBox').jScrollPane({
        showArrows: true
    });
    
    Cufon.replace("div.cufonHeader" , {fontFamily: 'PF Handbook Pro'});
{/literal}
</script>
