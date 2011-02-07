{*def $current_fluxbb_user = fetch( ezfluxbb, current_user, hash() )*}
<ul class="conl">
    <li>
        <a href="{$board_url}/profile.php?id={$fluxbb_user_id}">{'My account'|i18n( 'design/fedora_v5/loginbox/guest' )}</a>
    </li>
    {* <li>
        <span>{'Connected (e) under the identity'|i18n( 'design/fedora_v5/loginbox/guest')}</span>
        <a href="{$board_url}/profile.php?id={$current_fluxbb_user.id}">{$current_fluxbb_user.username}</a>
    </li>
    <li>
        <span>{'Last seen'|i18n( 'design/fedora_v5/loginbox/guest' )}</span>
        {$current_fluxbb_user.last_visit|related_datetime('r_FluxBB')}
    </li> *}
</ul>

<ul class="conr">
    <li>
        <a href="{$board_url}/search.php?action=show_new">{'New posts since last visit'|i18n( 'design/fedora_v5/loginbox/guest' )}</a>
    </li>
    <li>
        <a href="{$board_url}/search.php?action=show_24h">{'Recent posts'|i18n( 'design/fedora_v5/loginbox/guest' )}</a>
    </li>
    <li>
        <a href="{$board_url}/misc.php?action=markread">{'Mark all topics as read'|i18n( 'design/fedora_v5/loginbox/guest')}</a>
    </li>
</ul>
{*undef $current_fluxbb_user*}