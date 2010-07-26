{def 	$fluxbbOnline = fetch( 'ezfluxbb', 'online' )
		$nbUser			= 0
}
<div class="block online ezfluxbb">
    <h3><span>{$block.name|wash()}</span></h3>
	<ul>
		<li><strong>{$fluxbbOnline.guests}</strong> {"guests"|i18n('design/ezfluxbb/online')}</li>
		<li><strong>{$fluxbbOnline.users}</strong> {"members"|i18n('esign/ezfluxbb/online')}</li>
	</ul>

	{if count($fluxbbOnline.list)|gt(0)}
	{set $nbUser = count($fluxbbOnline.list)|dec()}
	<ul class="list">
		{foreach $fluxbbOnline.list as $key => $user}
		<li><a href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/profile.php?id={$user.user_id}" title="{"Profile of %user"|i18n("esign/ezfluxbb/stats",,hash('%user', $user.ident))}">{$user.ident}</a>{if $key|lt($nbUser)}, {/if}</li>
		{/foreach}
	</ul>
	{/if}
</div>
{undef $fluxbbOnline $nbUser}