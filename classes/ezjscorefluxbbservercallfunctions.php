<?php
//
// Definition of ezjscoreFluxBBServerCallFunctions class
//
// Created on: <28 déc. 2010 14:51:22 llaumgui>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: Fedora-Fr - eZP Base
// SOFTWARE RELEASE: 5.0.0
// COPYRIGHT NOTICE: Copyright (C) 2008-2010 Guillaume Kulakowski
// SOFTWARE LICENSE:
// NOTICE: >
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezjscorefluxbbservercallfunctions.php
*/

/*!
  \class ezjscoreFluxBBServerCallFunctions ezjscorefluxbbservercallfunctions.php
  \brief eZJSCore server call used by Fedora-Fr
*/
class ezjscoreFluxBBServerCallFunctions extends ezjscServerFunctions
{
    /**
     * LoginBox
     * @param array $args
     */
    public static function loginBox( $args )
    {
        $tpl = eZTemplate::factory();
        $eZFluxBBINI = eZINI::instance( 'ezfluxbb.ini');
        $fluxVersion = $eZFluxBBINI->variable( 'FluxBBInfo', 'Version' );
        $cookieName = $eZFluxBBINI->variable( 'FluxBBInfo', 'CookieName' );
        $boardURL = $eZFluxBBINI->variable( 'FluxBBInfo', 'BoardURL' );

        $tpl->setVariable( 'board_url', $boardURL );

        // No cookie:
        if ( !array_key_exists($cookieName, $_COOKIE) )
            return   $tpl->fetch( "design:ezfluxbb/loginbox_annonymous.tpl" );

        // FluxBB >=  1.4.4
        if ( version_compare( $fluxVersion, '1.4.4') >= 0)
        {
             $cookie = explode( '|', $_COOKIE[$cookieName] );
        }
        // FluxBB <=  1.4.3
        else
        {
            $cookie = unserialize( $_COOKIE[$cookieName] );
        }

        // Bad cookie
        if ( !array_key_exists( 0, $cookie ) || sizeof($cookie) < 3 || intval($cookie[0]) <= 0 )
            return   $tpl->fetch( "design:ezfluxbb/loginbox_annonymous.tpl" );

        $tpl->setVariable( 'fluxbb_user_id', intval($cookie[0]) );
        return   $tpl->fetch( "design:ezfluxbb/loginbox_guest.tpl" );
    }
}

?>