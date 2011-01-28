<!-- BLOCK: START -->
<div class="block-type-ezfluxbb-online">

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
{def $topics = fetch( 'ezfluxbb', 'topics', hash(
    group_id, 3,
	limit, $block.custom_attributes.limit_topics,
	sort_by, array('last_post_published', false()) ) )
}
<ul>
	{foreach $topics as $t}
		<li>
            <a title="{$t.forum_name|wash()} : {$t.topic_name|wash()}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewtopic.php?id={$t.topic_id}">{$t.topic_name|wash()}</a>
		</li>
	{/foreach}
{undef $topics}
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