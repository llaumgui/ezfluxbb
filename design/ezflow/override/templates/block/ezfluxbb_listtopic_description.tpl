{def    $fluxbbOnline = fetch( 'ezfluxbb', 'online' )
        $nbUser         = 0
}
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

<h3>{$block.name|wash()}</h3>
{def $news = fetch( 'ezfluxbb', 'topics', hash(
    forum_id_filter_type, 'include',
	forum_id_filter_array, $block.custom_attributes.id_forums,
	limit, $block.custom_attributes.limit_topics,
	get_first_message, true() ))
}
{foreach $news as $n}
	<h4><a title="{$block.name|wash()} : {$n.topic_name|wash()}<br />{"Published %date"|i18n("design/ezfluxbb/topics",,hash('%date', $n.published|related_datetime('r_FluxBB')))}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewtopic.php?pid={$n.post_id}#{$n.post_id}">{$n.topic_name|wash()}</a></h4>
	<p>{$n.message|bbcode2html()|strip_tags()|shorten($block.custom_attributes.limit_characters)|wash()} <a title="{$n.topic_name|wash()}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewtopic.php?pid={$n.post_id}#{$n.post_id}">{"Read more"|i18n('design/ezfluxbb/topics')} ({$n.num_replies} {"answers"|i18n('design/ezfluxbb/topics')})</a></p>
{/foreach}

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

{undef $news}