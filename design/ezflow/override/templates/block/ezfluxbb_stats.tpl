{def $fluxbbStats = fetch( 'ezfluxbb', 'stats' )}

<!-- BLOCK: START -->
<div class="block-type-ezfluxbb-stats">

<div class="border-box block-style1-box-outside">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">
<div class="border-content">

<!-- BLOCK BORDER INSIDE: START -->

<div class="border-box block-style1-box-inside">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">
<div class="border-content">

<!-- BLOCK CONTENT: START -->

<h3><span>{$block.name|wash()}</span></h3>
<ul>
    <li><strong>{$fluxbbStats.num_topics}</strong> {"subjects"|i18n('design/ezfluxbb/stats')}</li>
    <li><strong>{$fluxbbStats.num_posts}</strong> {"answers"|i18n('design/ezfluxbb/stats')}</li>
    <li><strong>{$fluxbbStats.num_members}</strong> {"members"|i18n('design/ezfluxbb/stats')}</li>
    <li>{"Last member"|i18n('design/ezfluxbb/stats')}&nbsp;: <a href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/profile.php?id={$fluxbbStats.last_member.id}" title="{"Profile of %user"|i18n("design/ezfluxbb/stats",,hash('%user', $fluxbbStats.last_member.username))}">{$fluxbbStats.last_member.username}</a></li>
</ul>

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

{undef $fluxbbStats}