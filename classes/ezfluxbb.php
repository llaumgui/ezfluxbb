<?php
/**
 * File containing the eZFluxBB class
 *
 * @version //autogentag//
 * @package EZFluxBB
 * @copyright Copyright (C) 2008-2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The eZFluxBB class provide FluxBB functions in eZ Publish
 *
 * @package EZFluxBB
 * @version //autogentag//
 */
class eZFluxBB
{
    public $Charset = 'utf-8'; // Charset of the FluxBB database
    public $Config = array(); // FluxBB configurtation
    public $Path = ''; // Path of FluxBB sources
    public $Queries = array(); // Path of FluxBB sources
    public $User = array(); // FluxBB user
    protected $UserCookie = array( // User cookie
        'user_id' => 1,
        'password_hash' =>	'Guest'
    );
    public $Version = false; // Version of FluxBB



    /**
     * Constructor
     */
    protected function __construct()
    {
        // Set objects variables from ini
        $eZFluxBBIni = eZINI::instance( "ezfluxbb.ini" );
        $this->Charset = $eZFluxBBIni->variable( "DataBase", "Charset" );
        $this->Path = $eZFluxBBIni->variable( 'FluxBBInfo', 'Path' );
        $this->Queries = array_merge(
            $eZFluxBBIni->group( "Queries" ),
            array( 'Topics' => $eZFluxBBIni->group( "QueriesTopics" ) )
        );
        $this->Version = $eZFluxBBIni->variable( 'FluxBBInfo', 'Version' );

        // Test if config is Good
        if ( count( explode( '.', $this->Version ) ) != 2 )
        {
            eZDebugSetting::writeError( 'eZFluxBB', 'Set a right full version like "x.y.z"', 'eZFluxBBDB' );
        }

        // Get config from FluxBB
        $this->getConfig( $this->Config );

        // Constant needed by FluxBB
        define( 'PUN_ROOT', $this->Path . '/' );
        define( 'FORUM_CACHE_DIR', PUN_ROOT.'cache/' );
    }





/* ****************************************************************** System */

    /**
     * Instanciate an eZFluxBB object in terms of FluxBB version.
     *
     * @return eZFluxBB
     */
    public static function instance()
    {
        if ( !isset( $GLOBALS['eZFluxBBGlobalInstance'] ) ||
            !( $GLOBALS['eZFluxBBGlobalInstance'] instanceof eZFluxBB ) )
        {

            $GLOBALS['eZFluxBBGlobalInstance'] = new eZFluxBB();;
        }
        return $GLOBALS['eZFluxBBGlobalInstance'];
    }



    /**
     * Get configuration of FluxBB
     *
     * @param array $config
     */
    private function getConfig( &$config )
    {
        include $this->Path . '/config.php';

        $config = array(
            'db_type' => $db_type,
            'db_host' => $db_host,
            'db_name' => $db_name,
            'db_username' => $db_username,
            'db_password' => $db_password,
            'db_prefix' => $db_prefix,
            'p_connect' => $p_connect,
            'cookie_name' => $cookie_name,
            'cookie_domain' => $cookie_domain,
            'cookie_path' => $cookie_path,
            'cookie_secure' => $cookie_secure,
            'cookie_seed' => $cookie_seed,
        );
    }





/* ********************************************************** Authentication */

    /**
     * Get informations about current user.
     *
     * @return array
     */
    public function getCurrentUserInfo()
    {
        if ( !array_key_exists( 'id', $this->User ) )
        {
            $this->checkCookie( $this->User );
        }
        return $this->User;
    }



    /**
     * Get FluxBB cookie
     *
     * @param array $user
     */
    protected function checkCookie( &$user )
    {
        $now = time();
        $expire = $now + 31536000; // The cookie expires after a year

        // If a cookie is set, we get the user_id and password hash from it
        if ( isset($_COOKIE[ $this->Config['cookie_name'] ]) )
        {
            $this->UserCookie = self::cookie2Array();
        }

        if ($this->UserCookie['user_id'] > 1)
        {
            // Check if there's a user with the user ID and password hash from the cookie
            $db = eZFluxBBDB::instance();
            $user = $db->arrayQuery( sprintf( eZFluxBBDB::setQuery( $this->Queries['User'] ), intval( $this->UserCookie['user_id'] ) ) );

            if ( array_key_exists( 0, $user ) )
            {
                $user = $user[0];
            }

            /*
             * Check password
             */
            // FluxBB >= 1.4.4
            if ( version_compare( $this->Version, '1.4.4' ) >= 0)
            {
                $checkWith = hash_hmac( 'sha1', $user['password'], $this->Config['cookie_seed'].'_password_hash' );
            }
            // FluxBB <=  1.4.3
            else
            {
                $checkWith = md5( $this->Config['cookie_seed'].$user['password'] );
            }

            // If user authorisation failed
            if ( !isset($user['id']) || $checkWith !== $this->UserCookie['password_hash'] )
            {
                $this->setDefaultUser( $user );
                return;
            }

            if ( array_key_exists( 'save_pass', $user ) && $user['save_pass'] == '0' )
            {
                $expire = 0;
            }

            // Define this if you want this visit to affect the online list and the users last visit data
            if ( !defined( 'PUN_QUIET_VISIT' ) )
            {
                // Update the online list
                if (!$user['logged'])
                {
                    $db->query( sprintf( eZFluxBBDB::setQuery( $this->Queries['UserOnline']['Inser'] ), $user['id'], $db->escapeString( $user['username'] ), $now ) );
                }
                else
                {
                    $idle_sql = ($user['idle'] == '1') ? ', idle=0' : '';
                    $db->query( sprintf( eZFluxBBDB::setQuery( $this->Queries['UserOnline']['Update'] ), $now.$idle_sql, $user['id'] ) );
                }
            }
            $user['is_guest'] = false;
        }
        else
        {
            $this->setDefaultUser( $user );
        }
    }



    /**
     * Get cookies informations
     *
     * @return array
     */
    public static function cookie2Array()
    {
        $eZFluxBB = eZFluxBB::instance();
        $userCookie = array();

        // FluxBB >= 1.4.4
        if ( version_compare( $eZFluxBB->Version, '1.4.4' ) >= 0 )
        {
            if ( preg_match( '/^(\d+)\|([0-9a-fA-F]+)\|(\d+)\|([0-9a-fA-F]+)$/', $_COOKIE[ $eZFluxBB->Config['cookie_name'] ], $matches ) )
            {
                $userCookie['user_id'] = intval( $matches[1] );
                $userCookie['password_hash'] = $matches[2];
                $userCookie['expiration_time'] = $matches[3];
            }
        }
        // FluxBB <=  1.4.3
        else
        {
            list( $userCookie['user_id'], $userCookie['password_hash'], $userCookie['expiration_time'] ) = @unserialize( $_COOKIE[ $eZFluxBB->Config['cookie_name'] ] );
        }
        return $userCookie;
    }



    /**
     * Initialize a guest user
     *
     * @param array $fluxUser
     */
    private function setDefaultUser( &$fluxUser )
    {
        $remote_addr =  eZSys::serverVariable( 'REMOTE_ADDR', true );

        // Fetch guest user
        $db = eZFluxBBDB::instance();
        $fluxUser = $db->arrayQuery( 'SELECT u.*, g.*, o.logged FROM '.$this->Config['db_prefix'].'users AS u INNER JOIN '.$this->Config['db_prefix'].'groups AS g ON u.group_id=g.g_id LEFT JOIN '.$this->Config['db_prefix'].'online AS o ON o.ident=\''.$remote_addr.'\' WHERE u.id=1' );
        if ( array_key_exists( 0, $fluxUser ) )
        {
            $fluxUser = $fluxUser[0];
        }

        // Update online list
        if (!$fluxUser['logged'])
        {
            $db->query( sprintf( eZFluxBBDB::setQuery( $this->Queries['UserOnline']['Inser'] ), 1, $db->escapeString( $remote_addr ), time() ) );
        }
        else
        {
            $db->query( sprintf( eZFluxBBDB::setQuery( $this->Queries['UserOnline']['UpdateAnonym'] ), time(), $db->escapeString( $remote_addr ) ) );
        }

        $fluxUser['is_guest'] = true;
    }





/* ********************************************************************* Misc */

    /**
     * Convert bbCode to HTML
     *
     * @param string $str bbCode to convert
     */
    public static function bbCode2HTML( &$str )
    {
        global $re_list;

        if ( !function_exists( 'do_bbcode' ) )
        {
            include_once PUN_ROOT . 'include/parser.php';
        }
        if ( !function_exists( 'pun_htmlspecialchars' ) )
        {
            include_once PUN_ROOT . 'include/functions.php';
        }
        if ( !defined( 'UTF8' ) )
        {
            include_once PUN_ROOT . 'include/utf8/utf8.php';
        }

        $str = do_bbcode( $str );
    }

}

?>