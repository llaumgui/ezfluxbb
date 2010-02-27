<?php
//
// Definition of eZFluxBB12 class
//
// Created on: <01-Sep-2008 19:00:00 bf>
//
// SOFTWARE NAME: eZFluxBB
// SOFTWARE RELEASE: 1.0
// BUILD VERSION:
// COPYRIGHT NOTICE: Copyright (c) 2008 Guillaume Kulakowski and contributors
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


/*! \file ezfluxbb12.php
*/

/*!
  \class eZFluxBB12 ezfluxbb12.php
  \brief Fonction FluxBB dans eZ Publish. Fonction propre à la version 1.2 de FluxBB.
 */
class eZFluxBB12 extends eZFluxBB
{


    /*!
     Récupération des informations sur l'utilisateurs courrant

     \return array
     */
    public function getCurrentUserInfo()
    {
        if ( !array_key_exists( 'id', $this->fluxBBUser ) )
        {
            $this->checkCookie( $this->fluxBBUser );
        }

        return $this->fluxBBUser;
    }



    /*!
     Permet de récupérer les informations sur le cookies FluxBB

     \TODO : Comprendre les parties commentées ;-)
     \param $fluxUser array
     */
    function checkCookie( &$fluxUser )
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
            $fluxUser = $db->arrayQuery('SELECT u.*, g.*, o.logged, o.idle, COUNT(pm.id) AS total_pm ' .
                                        'FROM '.$this->fluxBBConfig['db_prefix'].'users AS u ' .
                                            'INNER JOIN '.$this->fluxBBConfig['db_prefix'].'groups AS g ON u.group_id=g.g_id ' .
                                            'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'online AS o ON o.user_id=u.id ' .
                                            'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'messages AS pm ON pm.owner=u.id ' .
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

            if ($fluxUser['save_pass'] == '0')
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



    /*!
     Initialisation du l'utilisateur comme étant l'invité

     \param $fluxUser array
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



    /*!
     Définition d'un cookie FluxBB

     \param $user_id int
     \param $password_hash string
     \param $expire int
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



    /*!
     Generate a random password of length $len

     \param int $len
     \return String Password
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