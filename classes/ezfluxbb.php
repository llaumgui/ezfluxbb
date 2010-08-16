<?php
//
// Definition of eZFluxBB class
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


/*! \file ezfluxbb.php
*/

/*!
  \class eZFluxBB ezfluxbb.php
  \brief FluxBB functions in eZ Publish
 */
class eZFluxBB
{
    protected $fluxBBUser = array(); // FluxBB user
    public $fluxBBConfig = array(); // FluxBB configurtation
    public $fluxBBInfo = array(); // Section FluxBBInfo of ezfluxbb.ini
    protected $fluxBBCookie = array('user_id' => 1, 'password_hash' => 'Guest'); // User cookie


    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->getConfig( $this->fluxBBConfig );


        /* Constant needed by FluxBB */
        define( 'PUN_ROOT', $this->fluxBBInfo['Path'] );
        define( 'FORUM_CACHE_DIR', PUN_ROOT.'cache/' );
    }





######################################################################### System
#
    /**
     * Instanciate an eZFluxBB object in terms of FluxBB version.
     *
     * @return eZFluxBB
     */
    public static function instance()
    {
        $eZFluxBBIni = eZINI::instance( "ezfluxbb.ini" );
        $version = $eZFluxBBIni->variable( "FluxBBInfo", "Version" );
        $classVersion = 'eZFluxBB' . str_replace( '.', '', $version );

        $globalsKey = "eZFluxBBGlobalInstance-$version";
        $globalsIsLoadedKey = "eZFluxBBGlobalIsLoaded-$version";

        if ( !isset( $GLOBALS[$globalsKey] ) ||
            !( $GLOBALS[$globalsKey] instanceof $classVersion ) )
        {

            $GLOBALS[$globalsIsLoadedKey] = false;
            $GLOBALS[$globalsKey] = new $classVersion();;
            $GLOBALS[$globalsIsLoadedKey] = true;
        }
        return $GLOBALS[$globalsKey];
    }



    /**
     * Get configuration of FluxBB
     *
     * @param array &$config
     */
    private function getConfig( &$config )
    {
        $eZFluxBBIni = eZINI::instance( "ezfluxbb.ini" );
        $fluxBBInfo = $eZFluxBBIni->variableMulti( 'FluxBBInfo', array(
                            'Path'      => 'Path',
                            'Version'   => 'Version'
                                ) );

        $this->fluxBBInfo = array( "Path"      => $fluxBBInfo['Path'],
                                   "Version"   => $fluxBBInfo['Version']
                                    );

        require $this->fluxBBInfo['Path'] . 'config.php';

        $config = array(    'db_type'           => $db_type,
                            'db_host'           => $db_host,
                            'db_name'           => $db_name,
                            'db_username'       => $db_username,
                            'db_password'       => $db_password,
                            'db_prefix'         => $db_prefix,
                            'db_charset'        => $eZFluxBBIni->variable( "DataBase", "Charset" ),
                            'p_connect'         => $p_connect,
                            'cookie_name'       => $cookie_name,
                            'cookie_domain'     => $cookie_domain,
                            'cookie_path'       => $cookie_path,
                            'cookie_secure'     => $cookie_secure,
                            'cookie_seed'       => $cookie_seed,
                            'version'           => $this->fluxBBInfo['Version']
                        );
    }





########################### FluxBB informations (stats, members, topics, etc...)
#
    /**
     * Get informations about board stats
     *
     * @return array
     */
    public function getStats()
    {
        $db = eZFluxBBDB::instance();
        $stats = $db->arrayQuery(
        	'SELECT SUM(f.num_topics) as num_topics, ' .
            'SUM(f.num_posts) as num_posts, ' .
            	'(SELECT COUNT(id)-1 FROM '.$this->fluxBBConfig['db_prefix'].'users) as num_members ' .
            	'FROM '.$this->fluxBBConfig['db_prefix'].'forums f'
        );

        $lastMember = $db->arrayQuery(
        	'SELECT id, username FROM '.$this->fluxBBConfig['db_prefix'].'users ' .
            'ORDER BY registered DESC ' .
            'LIMIT 1'
        );

        $stats= array_merge( $stats[0], array( 'last_member' => $lastMember[0] ));

        return $stats;
    }



    /**
     * Get informations about online members
     *
     * @return array
     */
    public function getOnline()
    {
        $db = eZFluxBBDB::instance();

        $getOnline = array(
            'total'     => 0,
            'guests'    => 0,
            'users'     => 0,
            'list'      => array()
        );

        $online = $db->arrayQuery(
            'SELECT user_id, ident ' .
            'FROM '.$this->fluxBBConfig['db_prefix'].'online ' .
            'WHERE idle=0 ' .
            'ORDER BY ident'
        );
        $getOnline['total'] = count($online);

        foreach( $online as $user )
        {
            if ( $user['user_id'] > 1)
            {
                $getOnline['list'][]        = $user;
                $getOnline['users']++;
            }
            else
            {
                $getOnline['guests']++;
            }
        }

        return $getOnline;
    }



    /**
     * Get informations about topics
     *
     * @param array $params
     * @return array
     */
    public function getTopics( $params )
    {
        $db = eZFluxBBDB::instance();

        $select =   't.id topic_id, t.subject topic_name, t.poster creator, t.num_replies, t.posted published, ' .
                    't.last_post_id, t.last_post last_post_published, t.last_poster last_post_creator';
        $leftJoin = array();
        $innerJoin = array();
        $where = array();

        /* join groupe_id */
        if ( $params['group_id'] )
        {
            $select .= ', f.id forum_id, f.forum_name';
            $innerJoin[] = 'INNER JOIN '.$this->fluxBBConfig['db_prefix'].'forums AS f ON f.id=t.forum_id';
            $leftJoin[] = 'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id=' . $params['group_id'] . ')';
            $where[] = '(fp.read_forum IS NULL OR fp.read_forum=1)';
            $where[] = 't.moved_to IS NULL';
        }

        /* join with post */
        if ( $params['get_first_message'] )
        {
            $select .= ', p.id post_id, p.message';
            $leftJoin[] = 'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'posts p ON (p.topic_id=t.id AND t.posted = p.posted)';
        }

        if ( count($where) > 0 )
        {
            $where = ' AND ' . implode( ' AND ', $where) . ' ';
        }
        else
        {
            $where = '';
        }

        $topics = $db->arrayQuery(
            'SELECT ' . $select . ' ' .
            'FROM '.$this->fluxBBConfig['db_prefix'].'topics t ' .
                implode( ' ', $innerJoin) . ' ' .
                implode( ' ', $leftJoin) . ' ' .
            'WHERE t.forum_id ' . $params['forum_id'] . ' ' .
                $where . ' ' .
            'ORDER BY ' . $params['sort_by'] .' ' .
            'LIMIT ' . $params['offset'] . ', ' . $params['limit']);

        return $topics;
    }





################################################################# Authentication
#
    /**
     * Get informations about current user.
     *
     * @return array
     */
    public function getCurrentUserInfo()
    {
        if ( !array_key_exists( 'id', $this->fluxBBUser ) )
        {
            $this->checkCookie( $this->fluxBBUser );
        }

        return $this->fluxBBUser;
    }



    /**
     * Get FluxBB cookie
     *
     * @param array &$fluxUser
     */
    protected function checkCookie( &$fluxUser )
    {
        $now = time();
        $expire = $now + 31536000;    // The cookie expires after a year

        // If a cookie is set, we get the user_id and password hash from it
        if ( isset($_COOKIE[ $this->fluxBBConfig['cookie_name'] ]) )
        {
            list($this->fluxBBCookie['user_id'], $this->fluxBBCookie['password_hash']) = @unserialize($_COOKIE[ $this->fluxBBConfig['cookie_name'] ]);
        }

        if ($this->fluxBBCookie['user_id'] > 1)
        {
            // Check if there's a user with the user ID and password hash from the cookie
            $db = eZFluxBBDB::instance();
            $fluxUser = $db->arrayQuery(
                'SELECT u.*, g.*, o.logged, o.idle ' .
                'FROM '.$this->fluxBBConfig['db_prefix'].'users AS u ' .
                    'INNER JOIN '.$this->fluxBBConfig['db_prefix'].'groups AS g ON u.group_id=g.g_id ' .
                    'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'online AS o ON o.user_id=u.id ' .
                'WHERE u.id='.intval($this->fluxBBCookie['user_id']).' ' .
                'GROUP BY u.id');

            if ( array_key_exists( 0, $fluxUser) )
            {
                $fluxUser = $fluxUser[0];
            }

            // If user authorisation failed
            if ( !isset($fluxUser['id']) || md5($this->fluxBBConfig['cookie_seed'].$fluxUser['password']) !== $this->fluxBBCookie['password_hash'] )
            {
                $this->flux_setcookie(0, $this->random_pass(8), $expire);
                $this->set_default_user( $fluxUser );

                return;
            }

            /*if (!$fluxUser['disp_topics'])
                $fluxUser['disp_topics'] = $pun_config['o_disp_topics_default'];
            if (!$fluxUser['disp_posts'])
                $fluxUser['disp_posts'] = $pun_config['o_disp_posts_default'];*/

            if ( array_key_exists('save_pass', $fluxUser) && $fluxUser['save_pass'] == '0' )
            {
                $expire = 0;
            }

            // Define this if you want this visit to affect the online list and the users last visit data
            if (!defined('PUN_QUIET_VISIT'))
            {
                // Update the online list
                if (!$fluxUser['logged'])
                {
                    $db->query('INSERT INTO '.$this->fluxBBConfig['db_prefix'].'online (user_id, ident, logged) VALUES('.$fluxUser['id'].', \''.$db->escapeString($fluxUser['username']).'\', '.$now.')');
                }
                else
                {
                    // Special case: We've timed out, but no other user has browsed the forums since we timed out
                    /*if ($fluxUser['logged'] < ($now-$pun_config['o_timeout_visit']))
                    {
                        $db->query('UPDATE '.$this->fluxBBConfig['db_prefix'].'users SET last_visit='.$fluxUser['logged'].' WHERE id='.$fluxUser['id']);
                        $fluxUser['last_visit'] = $fluxUser['logged'];
                    }*/

                    $idle_sql = ($fluxUser['idle'] == '1') ? ', idle=0' : '';
                    $db->query('UPDATE '.$this->fluxBBConfig['db_prefix'].'online SET logged='.$now.$idle_sql.' WHERE user_id='.$fluxUser['id']);
                }
            }
            $fluxUser['is_guest'] = false;
        }
        else
        {
            $this->set_default_user( $fluxUser );
        }
    }



    /**
     * Initialize a guest user
     *
     * @param array &$fluxUser
     */
    private function set_default_user( &$fluxUser )
    {
        $remote_addr =  eZSys::serverVariable( 'REMOTE_ADDR', true );

        // Fetch guest user
        $db = eZFluxBBDB::instance();
        $fluxUser = $db->arrayQuery('SELECT u.*, g.*, o.logged FROM '.$this->fluxBBConfig['db_prefix'].'users AS u INNER JOIN '.$this->fluxBBConfig['db_prefix'].'groups AS g ON u.group_id=g.g_id LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'online AS o ON o.ident=\''.$remote_addr.'\' WHERE u.id=1');
        if ( array_key_exists( 0, $fluxUser) )
        {
            $fluxUser = $fluxUser[0];
        }

        // Update online list
        if (!$fluxUser['logged'])
        {
            $db->query('INSERT INTO '.$this->fluxBBConfig['db_prefix'].'online (user_id, ident, logged) VALUES(1, \''.$db->escapeString($remote_addr).'\', '.time().')');
        }
        else
        {
            $db->query('UPDATE '.$this->fluxBBConfig['db_prefix'].'online SET logged='.time().' WHERE ident=\''.$db->escapeString($remote_addr).'\'');
        }

        /*$fluxUser['disp_topics'] = $pun_config['o_disp_topics_default'];
        $fluxUser['disp_posts'] = $pun_config['o_disp_posts_default'];
        $fluxUser['timezone'] = $pun_config['o_server_timezone'];
        $fluxUser['language'] = $pun_config['o_default_lang'];
        $fluxUser['style'] = $pun_config['o_default_style'];*/
        $fluxUser['is_guest'] = true;
    }



    /**
     * Define the FluxBB cookie
     *
     * @param int $user_id
     * @param string $password_hash
     * @param int $expire
     */
    private function flux_setcookie($user_id, $password_hash, $expire)
    {
        // Enable sending of a P3P header by removing // from the following line (try this if login is failing in IE6)
        //    @header('P3P: CP="CUR ADM"');
        if (version_compare(PHP_VERSION, '5.2.0', '>='))
        {
            setcookie(  $this->fluxBBConfig['cookie_name'],
                        serialize(array($user_id, md5($this->fluxBBConfig['cookie_seed'].$password_hash))),
                        $expire,
                        $this->fluxBBConfig['cookie_path'],
                        $this->fluxBBConfig['cookie_domain'],
                        $this->fluxBBConfig['cookie_secure'],
                        true );
        }
        else
        {
            setcookie(  $this->fluxBBConfig['cookie_name'],
                        serialize(array($user_id, md5($this->fluxBBConfig['cookie_seed'].$password_hash))),
                        $expire,
                        $this->fluxBBConfig['cookie_path'] . '; HttpOnly',
                        $this->fluxBBConfig['cookie_domain'],
                        $this->fluxBBConfig['cookie_secure'] );
        }
    }



    /**
     * Generate a random password of length $len
     *
     * @param int $len
     * @return string Password
     */
    private function random_pass($len)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $password = '';
        for ($i = 0; $i < $len; ++$i)
            $password .= substr($chars, (mt_rand() % strlen($chars)), 1);

        return $password;
    }

}

?>