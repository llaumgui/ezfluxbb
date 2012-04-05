<?php
/**
 * File containing the ezjscoreFluxBBServerCallFunctions class
 *
 * @version //autogentag//
 * @package EZFluxBB
 * @copyright Copyright (C) 2008-2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The eZFluxBBPreferences provide eZJSCore server call.
 *
 * @package EZFluxBB
 * @version //autogentag//
 */
class ezjscoreFluxBBServerCallFunctions extends ezjscServerFunctions
{

    /**
     * LoginBox
     *
     * @param array $args
     */
    public static function loginBox( $args )
    {
        $tpl = eZTemplate::factory();
        $eZFluxBBINI = eZINI::instance( 'ezfluxbb.ini' );
        $cookieName = $eZFluxBBINI->variable( 'FluxBBInfo', 'CookieName' );
        $boardURL = $eZFluxBBINI->variable( 'FluxBBInfo', 'BoardURL' );

        $tpl->setVariable( 'board_url', $boardURL );

        // No cookie:
        if ( !array_key_exists( $cookieName, $_COOKIE ) )
        {
            return   $tpl->fetch( "design:ezfluxbb/loginbox/annonymous.tpl" );
        }

        $cookie = eZFluxBB::cookie2Array();

        // Bad cookie
        if ( !array_key_exists( 'user_id', $cookie ) || sizeof( $cookie ) < 3 || intval( $cookie['user_id'] ) <= 1 )
        {
            return   $tpl->fetch( "design:ezfluxbb/loginbox/annonymous.tpl" );
        }

        $tpl->setVariable( 'fluxbb_user_id', intval( $cookie['user_id'] ) );

        // Full version
        if ( isset($args[0]) && $args[0] == 'full' )
        {
            $fluxbb_current_user= eZFunctionHandler::execute( 'ezfluxbb', 'current_user', array() );
            $tpl->setVariable( 'fluxbb_current_user', $fluxbb_current_user );
            return   $tpl->fetch( "design:ezfluxbb/loginbox/guest_full.tpl" );
        }

        return   $tpl->fetch( "design:ezfluxbb/loginbox/guest.tpl" );
    }
}

?>