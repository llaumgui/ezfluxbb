<ul class="conl">
    <li>
        <a href="{$board_url}/profile.php?id={$fluxbb_user_id}">{'My account'|i18n( 'design/ezfluxbb/loginbox/guest_full' )}</a>
    </li>
    <li>
        <span>{'Connected (e) under the identity'|i18n( 'design/ezfluxbb/loginbox/guest_full')}</span>
        <a href="{$board_url}/profile.php?id={$fluxbb_current_user.id}">{$fluxbb_current_user.username}</a>
    </li>
    <li>
        <span>{'Last seen'|i18n( 'design/ezfluxbb/loginbox/guest_full' )}</span>
        {$fluxbb_current_user.last_visit|l10n(shortdatetime)}
    </li>
</ul>

<ul class="conr">
    <li>
        <a href="{$board_url}/search.php?action=show_new">{'New posts since last visit'|i18n( 'design/ezfluxbb/loginbox/guest_full' )}</a>
    </li>
    <li>
        <a href="{$board_url}/search.php?action=show_24h">{'Recent posts'|i18n( 'design/ezfluxbb/loginbox/guest_full' )}</a>
    </li>
    <li>
        <a href="{$board_url}/misc.php?action=markread">{'Mark all topics as read'|i18n( 'design/ezfluxbb/loginbox/guest_full')}</a>
    </li>
</ul>