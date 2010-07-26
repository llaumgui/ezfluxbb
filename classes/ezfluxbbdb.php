<?php
//
// Definition of eZFluxBBDB class
//
// Created on: <01-Sep-2008 19:00:00 gkul>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZFluxBB
// SOFTWARE RELEASE: 1.1
// BUILD VERSION:
// COPYRIGHT NOTICE: Copyright (c) 2008-2010 Guillaume Kulakowski and contributors
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


/*! \file ezfluxbbdb.php
*/

/*!
  \class eZFluxBBDB ezfluxbbdb.php
  \brief Interconnection between eZ Publish and FluxBB databases
 */
class eZFluxBBDB
{

    /**
     * Constructor
     *
     * @param object $impl
     */
    private function __construct( &$impl)
    {
        /* Same DB */
        if ( $this->compareEz2FluxDB() )
        {
            eZDebugSetting::writeNotice( 'eZFluxBBDB', 'FluxBB and eZ Publish use the same database', 'eZFluxBBDB' );
            $impl = eZDB::instance();
        }
        /* Different DB */
        else
        {
            eZDebugSetting::writeNotice( 'eZFluxBBDB', 'FluxBB and eZ Publish don\'t use the same database', 'eZFluxBBDB'  );
            $ezFluxIni = eZFluxBB::instance();
            $params = array('server'                        => $ezFluxIni->fluxBBConfig['db_host'],
                            'user'                          => $ezFluxIni->fluxBBConfig['db_username'],
                            'password'                      => $ezFluxIni->fluxBBConfig['db_password'],
                            'database'                      => $ezFluxIni->fluxBBConfig['db_name'],
                            'use_persistent_connection'     => $ezFluxIni->fluxBBConfig['p_connect'],
                            'show_errors'                   => true,
                           );

            $impl = eZDB::instance( 'ez'.$ezFluxIni->fluxBBConfig['db_type'], $params, true );
        }
    }



    /**
     * Compare FluxBB and eZ databases connection parameters
     *
     * @return boolean
     */
    private function compareEz2FluxDB()
    {
        $ezini = eZINI::instance( "site.ini" );
        $ezFluxIni = eZFluxBB::instance();

        $ezDBIni = $ezini->variableMulti( 'DatabaseSettings', array(
                                            'Server'        => 'Server',
                                            'User'          => 'User',
                                            'Password'      => 'Password',
                                            'Database'      => 'Database'
                                ) );

        if ( $ezDBIni['Server'] == $ezFluxIni->fluxBBConfig['db_host']
          && $ezDBIni['User'] == $ezFluxIni->fluxBBConfig['db_username']
          && $ezDBIni['Password'] == $ezFluxIni->fluxBBConfig['db_password']
          && $ezDBIni['Database'] == $ezFluxIni->fluxBBConfig['db_name']
        )
        {
            $db = eZDB::instance();
            if ( strtolower($db->charset()) == strtolower($ezFluxIni->fluxBBConfig['db_charset']) )
            {
                eZDebugSetting::writeNotice( 'eZFluxBBDB', 'FluxBB and eZ Publish are 2 differents charset !', 'eZFluxBBDB' );
                return false;
            }
            return true;
        }
        return false;
    }



    /**
     * Instanciation
     *
     * @return eZDBInterface
     */
    static function instance()
    {
        $globalsKey = "eZFluxBBDBGlobalInstance";
        $globalsIsLoadedKey = "eZFluxBBDBGlobalIsLoaded";
        if ( !isset( $GLOBALS[$globalsKey] ) ||
            !( $GLOBALS[$globalsKey] instanceof eZDBInterface ) )
        {
            $GLOBALS[$globalsIsLoadedKey] = false;
            new eZFluxBBDB( $GLOBALS[$globalsKey] );
            $GLOBALS[$globalsIsLoadedKey] = true;
        }
        return $GLOBALS[$globalsKey];
    }

}

?>