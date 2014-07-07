{counter start=0 print=0}
<table>
	{foreach from=$szkolenia key=index  item=rekord_szkolenie}
	<tr>
		<td class="szkolenieLink"><a href="/{$jezyk_skrot}/Szkolenia/{$rekord_szkolenie->url.$jezyk_id}"  {if $rekord_szkolenie->url.$jezyk_id==$wybrany_url} class="selected"{/if}>{counter}.  {$rekord_szkolenie->tytul.$jezyk_id}</a></td>
	</tr>
	{/foreach}	
</table>