<p><a href="{ezini('GauffrSettings', 'GauffrRegisterURL', 'gauffr.ini')}">{'No account yet?'|i18n('design/fedora_v5/loginbox/annonymous')}</a></p>
    <form action="{ezini('GauffrSettings', 'GauffrLoginActionURL', 'gauffr.ini')}" method="post">
    <label for="req_username">{'Identifiant:'|i18n('design/fedora_v5/loginbox/annonymous')}</label>
    <input type="text" id="req_username" name="req_username" />
    <label for="req_password">{'Password:'|i18n('design/fedora_v5/loginbox/annonymous')}</label>
    <input type="password" id="req_password" name="req_password" /><br />
    <label for="save_pass">{'Remember ?'|i18n('design/fedora_v5/loginbox/annonymous')}</label>
    <input type="checkbox" tabindex="3" value="1" id="save_pass" name="save_pass" /><br />
    <input type="hidden" value="1" name="form_sent" />
    <input type="hidden" value="http://{ezini( 'SiteSettings', 'SiteURL', 'site.ini')}" name="redirect_url" />
    <input type="submit" name="login" title="{'Connect to Fedora-fr'|i18n('design/fedora_v5/loginbox/annonymous')}" value="{'Identification'|i18n('design/fedora_v5/loginbox/annonymous')}" />
</form>