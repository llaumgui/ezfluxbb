<?php
/**
 * File containing the eZFluxBBDB class
 *
 * @version //autogentag//
 * @package EZFluxBB
 * @copyright Copyright (C) 2008-2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The eZFluxBBDB provide interconnection between eZ Publish and FluxBB databases
 *
 * @package EZFluxBB
 * @version //autogentag//
 */
class eZFluxBBDB
{

    public static $db_supported = array(
        'mysql',
        'mysql_innodb',
        'mysqli',
        'mysqli_innodb'
    );



    /**
     * Constructor
     *
     * @param object $impl
     */
    private function __construct( &$impl)
    {
        // Same DB
        if ( $this->compareEz2FluxDB() )
        {
            eZDebugSetting::writeNotice( 'eZFluxBBDB', 'FluxBB and eZ Publish use the same database', 'eZFluxBBDB' );
            $impl = eZDB::instance();
        }
        // Different DB
        else
        {
            eZDebugSetting::writeNotice( 'eZFluxBBDB', 'FluxBB and eZ Publish don\'t use the same database', 'eZFluxBBDB'  );
            $ezFluxBB = eZFluxBB::instance();
            $params = array(
                'server' => $ezFluxBB->Config['db_host'],
                'user' => $ezFluxBB->Config['db_username'],
                'password' => $ezFluxBB->Config['db_password'],
                'database' => $ezFluxBB->Config['db_name'],
                'use_persistent_connection' => $ezFluxBB->Config['p_connect'],
                'show_errors' => true,
            );

            // Remove extended information like _innodb
            $dbType = explode( '_', $ezFluxBB->Config['db_type'] );
            $dbType = $dbType[0];

            $impl = eZDB::instance( 'ez'.$dbType, $params, true );
        }
    }



    /**
     * Compare FluxBB and eZ databases connection parameters
     *
     * @return boolean
     */
    private function compareEz2FluxDB()
    {
        $eZFluxBB = eZFluxBB::instance();
        $ezDBIni = eZINI::instance( "site.ini" )->variableMulti( 'DatabaseSettings', array(
            'Server'  => 'Server',
            'User' => 'User',
            'Password' => 'Password',
            'Database' => 'Database'
        ) );

        // Test if DB is supported
        if ( !in_array( $eZFluxBB->Config['db_type'], self::$db_supported ) )
        {
            throw new Exception('FluxBB database implementation not supported in eZFluxBB: ' . $eZFluxBB->Config['db_type']);
        }

        if ( $ezDBIni['Server'] == $eZFluxBB->Config['db_host']
          && $ezDBIni['User'] == $eZFluxBB->Config['db_username']
          && $ezDBIni['Password'] == $eZFluxBB->Config['db_password']
          && $ezDBIni['Database'] == $eZFluxBB->Config['db_name']
        )
        {
            $db = eZDB::instance();
            if ( strtolower( $db->charset() ) == strtolower( $eZFluxBB->Charset ) )
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
    public static function instance()
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



    /**
     * Set a FluxBB query with path prefix, etc...
     *
     * @param string $query
     */
    public static function setQuery($query)
    {
        $eZFluxBB = eZFluxBB::instance();
        return str_replace( '%db_prefix%', $eZFluxBB->Config['db_prefix'], $query );
    }

}

?>