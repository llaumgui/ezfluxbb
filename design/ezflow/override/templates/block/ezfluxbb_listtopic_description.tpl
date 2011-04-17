{def    $fluxbbOnline = fetch( 'ezfluxbb', 'online' )
        $nbUser         = 0
}
<!-- BLOCK: START -->
<div class="block-type-2items block-type-ezfluxbb-listtopic block-type-ezfluxbb-listtopic-description">

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

{def $news = fetch( 'ezfluxbb', 'topics', hash(
    forum_id_filter_type, 'include',
	forum_id_filter_array, $block.custom_attributes.id_forums,
	limit, $block.custom_attributes.limit_topics,
	get_first_message, true() ))
}
{foreach $news as $n}
    <div class="class-article float-break">

        <div class="attribute-header">
            <h2><a title="{$block.name|wash()} : {$n.topic_name|wash()}<br />{"Published %date"|i18n("design/ezfluxbb/topics",,hash('%date', $n.published|l10n(shortdatetime)))}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewtopic.php?pid={$n.post_id}#{$n.post_id}">{$n.topic_name|wash()}</a></h2>
        </div>

        <div class="attribute-short">
            <p>{$n.message|bbcode2html()|strip_tags()|shorten($block.custom_attributes.limit_characters)|wash()} <a title="{$n.topic_name|wash()}" href="{ezini( 'FluxBBInfo', 'BoardURL', 'ezfluxbb.ini' )}/viewtopic.php?pid={$n.post_id}#{$n.post_id}">{"Read more"|i18n('design/ezfluxbb/topics')} ({$n.num_replies} {"answers"|i18n('design/ezfluxbb/topics')})</a></p>
        </div>

    </div>
    {delimiter}
    <div class="separator"></div>
    {/delimiter}
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