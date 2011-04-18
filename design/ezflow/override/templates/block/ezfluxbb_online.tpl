{def 	$fluxbbOnline = fetch( 'ezfluxbb', 'online' )
		$nbUser			= 0
}
<!-- BLOCK: START -->
<div class="block-type-ezfluxbb-online">

<div class="border-box block-style2-box-outside">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">
<div class="border-content">

<!-- BLOCK BORDER INSIDE: START -->

<div class="border-box block-style2-box-inside">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">
<div class="border-content">

<!-- BLOCK CONTENT: START -->
	<h2>{$block.name|wash()}</h2>

    <ul>
        <li><strong>{$fluxbbOnline.guests}</strong> {"guests"|i18n('design/ezfluxbb/online')}</li>
        <li><strong>{$fluxbbOnline.users}</strong> {"members"|i18n('design/ezfluxbb/online')}</li>
    </ul>

    {if count($fluxbbOnline.list)|gt(0)}
    {set $nbUser = count($fluxbbOnline.list)|dec()}
    <ul class="list">
        {foreach $fluxbbOnline.list as $key => $user}
        <li><a href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/profile.php?id={$user.user_id}" title="{"Profile of %user"|i18n("design/ezfluxbb/stats",,hash('%user', $user.ident))}">{$user.ident}</a>{if $key|lt($nbUser)}, {/if}</li>
        {/foreach}
    </ul>
    {/if}
<!-- BLOCK CONTENT: END -->

</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

<!-- BLOCK BORDER INSIDE: END -->


</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

</div>
<!-- BLOCK: END -->

{undef $fluxbbOnline $nbUser}
