<?php
//
// Definition of ezjscoreFluxBBServerCallFunctions class
//
// Created on: <28 déc. 2010 14:51:22 llaumgui>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZFluxBB
// SOFTWARE RELEASE: 2.0
// BUILD VERSION:
// COPYRIGHT NOTICE: Copyright (c) 2008-2011 Guillaume Kulakowski and contributors
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
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
            return   $tpl->fetch( "design:ezfluxbb/loginbox/annonymous.tpl" );

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
        if ( !array_key_exists( 0, $cookie ) || sizeof($cookie) < 3 || intval($cookie[0]) <= 1 )
            return   $tpl->fetch( "design:ezfluxbb/loginbox/annonymous.tpl" );

        $tpl->setVariable( 'fluxbb_user_id', intval($cookie[0]) );

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