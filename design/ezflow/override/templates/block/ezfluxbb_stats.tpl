{def $fluxbbStats = fetch( 'ezfluxbb', 'stats' )}
<div class="block stats ezfluxbb">
	<h3><span>{$block.name|wash()}</span></h3>
	<ul>
		<li><strong>{$fluxbbStats.num_topics}</strong> {"subjects"|i18n('design/ezfluxbb/stats')}</li>
		<li><strong>{$fluxbbStats.num_posts}</strong> {"answers"|i18n('design/ezfluxbb/stats')}</li>
		<li><strong>{$fluxbbStats.num_members}</strong> {"members"|i18n('design/ezfluxbb/stats')}</li>
		<li>{"Last member"|i18n('design/ezfluxbb/stats')}&nbsp;: <a href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/profile.php?id={$fluxbbStats.last_member.id}" title="{"Profile of %user"|i18n("design/ezfluxbb/stats",,hash('%user', $fluxbbStats.last_member.username))}">{$fluxbbStats.last_member.username}</a></li>
	</ul>
</div>
{undef $fluxbbStats}