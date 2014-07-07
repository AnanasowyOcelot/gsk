<style>
{literal}
.registerInput {
    width:160px; height:27px; border:1px solid #FFF; background:#633b0c; color: #FFF; padding: 0 3px;
}
.label {
    display:inline-block; width: 100px; padding-bottom:2px; font-size:15px;
}
.wiersz {
    margin-bottom: 6px;
    overflow: hidden;
}
{/literal}
</style>

<div style="padding:0px 20px 0px 20px; width: 590px; height: 470px; overflow: auto;" id="mainBox">
    {foreach from=$partnerzy  key=index item=rekord}
         {if $rekord->logo!=""}
             <div style="float:left; margin:0px 5px 5px 5px; display:block; overflow:hidden;">
                 <img src="/images/partnerzy/0/{$rekord->logo}" />
                 <div style="text-align: center; margin: 5px 0;">{$rekord->nazwa.$jezyk_id}</div>
             </div>
        {/if}
    {/foreach}
</div>

<script>
{literal}
    jQuery('#mainBox').jScrollPane({
        showArrows: true
    });
{/literal}
</script>
