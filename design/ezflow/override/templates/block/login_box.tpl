<!-- BLOCK: START -->
<div class="block-type-ezfluxbb-listtopic block-type-ezfluxbb-listtopic-compact">

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

<h3><span>{$block.name|wash()}</span></h3>
<p id="ajaxloginbox_{$block.id}"><img src={"ajax-loader.gif"|ezimage()} alt="{'Loading'|i18n('design/ezfluxbb/login_box')}" /></p>

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
<script type="text/javascript">//<![CDATA[
$(document).ready(function(){ldelim}$.ez('ezfluxbb::loginBox',{ldelim}{rdelim},function(data){ldelim}if(!data.error_text){ldelim}$('#ajaxloginbox_{$block.id}').replaceWith(data.content);{rdelim}{rdelim});{rdelim});
//]]></script>
<!-- BLOCK: END -->