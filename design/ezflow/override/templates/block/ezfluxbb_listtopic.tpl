<!-- BLOCK: START -->
<div class="block-type-ezfluxbb-listtopic">

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

<h2>{$block.name|wash()}</h2>
<div class="content pun">
{def $topics = fetch( 'ezfluxbb', 'topics', hash(
    group_id, 3,
	limit, $block.custom_attributes.limit_topics,
	sort_by, array('last_post_published', false()) ) )
}
	<table cellspacing="0" summary="{$block.name|wash()}" class="list">
		<thead>
			<tr>
                <th class="tcl" scope="col">{"Forums"|i18n("design/ezfluxbb/topics")}</th>
                <th class="tc2" scope="col">{"Discussions"|i18n("design/ezfluxbb/topics")}</th>
                <th class="tc3" scope="col">{"Answers"|i18n("design/ezfluxbb/topics")}</th>
                <th class="tcr" scope="col">{"Last message"|i18n("design/ezfluxbb/topics")}</th>
			</tr>
		</thead>
	{foreach $topics as $t}
		<tbody>
			<tr>
				<td class="tcl"><a title="{"Forum:"|i18n("design/ezfluxbb/topics")} {$t.forum_name|wash()}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewforum.php?id={$t.forum_id}">{$t.forum_name|wash()}</a></td>
				<td class="tcr"><a title="{$t.forum_name|wash()} : {$t.topic_name|wash()}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewtopic.php?id={$t.topic_id}">{$t.topic_name|wash()}</a></td>
				<td class="tc3">{$t.num_replies}</td>
				<td class="tcr"><a title="{$t.forum_name|wash()} : {"go to last discussion"|i18n("design/ezfluxbb/topics")}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewtopic.php?pid={$t.last_post_id}#p{$t.last_post_id}">{$t.last_post_published|l10n(shortdatetime)}</a> {"by"|i18n("design/ezfluxbb/topics")} {$t.last_post_creator|wash()}</td>
			</tr>
		</tbody>
	{/foreach}
{undef $topics}
	</table>
</div>

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