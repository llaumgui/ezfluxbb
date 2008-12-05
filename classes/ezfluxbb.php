<?php
//
// Definition of eZFluxBB class
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


/*! \file ezfluxbb.php
*/

/*!
  \class eZFluxBB ezfluxbb.php
  \brief Fonction FluxBB dans eZ Publish
 */
class eZFluxBB
{
    protected $fluxBBUser = array();                // Utilisateur FluxBB
    public $fluxBBConfig = array();                 // Configuration Flux BB
    public $fluxBBInfo = array();                   // Information dans le la section FluxBBInfo de ezfluxbb.ini
    protected $fluxBBCookie = array('user_id' => 1, 'password_hash' => 'Guest');            // Cookie utilisateur


    /*!
     Constructeur
     */
    function __construct()
    {
        $this->getConfig( $this->fluxBBConfig );

        /* Constante nécessaires pour FluxBB */
        define( 'PUN_ROOT', $this->fluxBBInfo['Path'] );
    }



    /*!
     Permet d'instancier l'objet eZFluxBB.

     \return object eZFluxBB
     \note Bon, j'ai viré les strtolower car j'aime qu'on respecte la case !
     */
    static function instance()
    {
        $impl = &$GLOBALS["eZFluxBBGlobalInstance"];

        $eZFluxBBIni = eZINI::instance( "ezfluxbb.ini" );
        $version = $eZFluxBBIni->variable( "FluxBBInfo", "Version" );
        $classVersion = 'eZFluxBB' . str_replace( '.', '', $version );
        $class = get_class( $impl );

        if ( $class != $classVersion)
        {
            $impl = new $classVersion();
        }
        return $impl;
    }



    /*!
     Récupération de la configuration FluxBB

     \params $config
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



    /*!
     Récupération des informations sur l'utilisateurs courant

     \return array
     */
    public function getStats()
    {
        $db = eZFluxBBDB::instance();
        /* Requête inbriquée pour récupérer toutes les stats */
        $stats = $db->arrayQuery(   'SELECT SUM(f.num_topics) as num_topics, ' .
                                    'SUM(f.num_posts) as num_posts, ' .
                                    '(SELECT COUNT(id)-1 FROM '.$this->fluxBBConfig['db_prefix'].'users) as num_members ' .
                                    'FROM '.$this->fluxBBConfig['db_prefix'].'forums f');
        /* Infos sur le dernier membre */
        $lastMember = $db->arrayQuery(  'SELECT id, username FROM '.$this->fluxBBConfig['db_prefix'].'users ' .
                                        'ORDER BY registered DESC ' .
                                        'LIMIT 1');

        $stats= array_merge( $stats[0], array( 'last_member' => $lastMember[0] ));

        return $stats;
    }



    /*!
     Récupération des informations sur les membres en ligne

     \return array
     */
    public function getOnline()
    {
        $db = eZFluxBBDB::instance();

        $getOnline = array( 'total'     => 0,
                            'guests'    => 0,
                            'users'     => 0,
                            'list'      => array()
                            );

        $online = $db->arrayQuery(  'SELECT user_id, ident ' .
                                    'FROM '.$this->fluxBBConfig['db_prefix'].'online ' .
                                    'WHERE idle=0 ' .
                                    'ORDER BY ident');
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



    /*!
     Récupération des informations sur des topics

     \param $params Paramètres dans un array
     \return array
     */
    public function getTopics( $params )
    {
        $db = eZFluxBBDB::instance();

        $select =   't.id topic_id, t.subject topic_name, t.poster creator, t.num_replies, t.posted published, ' .
                    't.last_post_id, t.last_post last_post_published, t.last_poster last_post_creator';
        $leftJoin = array();
        $innerJoin = array();
        $where = array();

        /* Jointure groupe_id */
        if ( $params['group_id'] )
        {
            $select .= ', f.id forum_id, f.forum_name';
            $innerJoin[] = 'INNER JOIN '.$this->fluxBBConfig['db_prefix'].'forums AS f ON f.id=t.forum_id';
            $leftJoin[] = 'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id=' . $params['group_id'] . ')';
            $where[] = '(fp.read_forum IS NULL OR fp.read_forum=1)';
            $where[] = 't.moved_to IS NULL';
        }

        /* Jointure avec les messages */
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

        $topics = $db->arrayQuery(  'SELECT ' . $select . ' ' .
                                    'FROM '.$this->fluxBBConfig['db_prefix'].'topics t ' .
                                        implode( ' ', $innerJoin) . ' ' .
                                        implode( ' ', $leftJoin) . ' ' .
                                    'WHERE t.forum_id ' . $params['forum_id'] . ' ' .
                                    $where . ' ' .
                                    'ORDER BY ' . $params['sort_by'] .' ' .
                                    'LIMIT ' . $params['offset'] . ', ' . $params['limit']);

        return $topics;
    }



    /*!
     Transforme du bbCode en HTML

     \param str string Texte en bbCode
     */
    function bbCode2HTML( &$str )
    {
        if ( !function_exists( 'do_bbcode' ) ) {
            require_once PUN_ROOT . 'include/parser.php';
        }
        $str = do_bbcode( $str );
    }

}    //EOC

?>