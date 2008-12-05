<?php
/*
 * #################### BEGIN LICENSE BLOCK ####################
 * This file is part of eZFluxBB.
 * Copyright (c) 2007 Guillaume Kulakowski and contributors. All
 * rights reserved.
 *
 * eZFluxBB is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * eZFluxBB is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See
 * the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public 
 * License along with ezipb; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330,
 * Boston, MA  02111-1307  USA
 * or visit http://www.gnu.org/licenses/gpl.html
 * ###################### END LICENSE BLOCK ####################
 *
 * Fonctions indépendantes de la version de FluxBB.
 * 
 * @TODO Sans avoir vu la sous la jupe de la 1.3, le fait qu'une fonction soit dans eZFluxBB ou dans eZFluxBB12
 * est limite arbitraire...
 * 
 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 * @version 1.0
 */

class eZFluxBB
{
	protected $fluxBBUser		= array();			// Utilisateur FluxBB
	protected $fluxBBConfig		= array();			// Configuration Flux BB
	protected $fluxBBCookie		= array('user_id' => 1, 'password_hash' => 'Guest');			// Cookie utilisateur
	
	
	function __construct()
	{
		$this->getConfig( $this->fluxBBConfig );
	}
	

	
	/**
	 * Permet d'instancier l'objet eZFluxBB.
	 * Bon, j'ai viré les strtolower car j'aime qu'on respecte la case !
	 * 
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
	 * @return object eZFluxBB
	 */
	static function instance()
	{
		$impl 			= &$GLOBALS["eZFluxBBGlobalInstance"];
		$eZFluxBBIni 	= eZINI::instance( "ezfluxbb.ini" );
		$version		= $eZFluxBBIni->variable( "FluxBBInfo", "Version" );
		$classVersion	= 'eZFluxBB' . str_replace( '.', '', $version );
		$class 			= get_class( $impl );

		if ( $class != $classVersion)
		{
			$impl = new $classVersion();
		}
		return $impl;
	}



	/**
	 * Récupération de la configuration FluxBB
	 * 
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
	 */
	private function getConfig( &$config )
	{
		$eZFluxBBIni 	= eZINI::instance( "ezfluxbb.ini" );
		$fluxBBPath		= $eZFluxBBIni->variable( "FluxBBInfo", "Path" );
		$version		= $eZFluxBBIni->variable( "FluxBBInfo", "Version" );
		require $fluxBBPath . 'config.php';
		
		$config	= array(	'db_type'			=> $db_type,
							'db_host'			=> $db_host,
							'db_name'			=> $db_name,
							'db_username'		=> $db_username,
							'db_password'		=> $db_password,
							'db_prefix'			=> $db_prefix,
							'p_connect'			=> $p_connect,
							'cookie_name'		=> $cookie_name,
							'cookie_domain'		=> $cookie_domain,
							'cookie_path'		=> $cookie_path,
							'cookie_secure'		=> $cookie_secure,
							'cookie_seed'		=> $cookie_seed,
							'version'			=> $version
						);
	}



	/**
	 * Récupération des informations sur l'utilisateurs courant
	 * 
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
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
	 * Récupération des informations sur l'utilisateurs courant
	 * 
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
 	 * @return array
	 */
	public function getStats()
	{
		$db 		= eZFluxBBDB::instance();
		/* Requête inbriquée pour récupérer toutes les stats */
		$stats		= $db->arrayQuery(	'SELECT SUM(f.num_topics) as num_topics, ' .
										'SUM(f.num_posts) as num_posts, ' .
										'(SELECT COUNT(id)-1 FROM '.$this->fluxBBConfig['db_prefix'].'users) as num_members ' . 
										'FROM '.$this->fluxBBConfig['db_prefix'].'forums f');
		/* Infos sur le dernier membre */
		$lastMember	= $db->arrayQuery(	'SELECT id, username FROM '.$this->fluxBBConfig['db_prefix'].'users	ORDER BY registered DESC LIMIT 1');

		$stats		= array_merge( $stats[0], array( 'last_member' => $lastMember[0] ));

		return $stats;
	}



	/**
	 * Récupération des informations sur les membres en ligne
	 * 
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
 	 * @return array
	 */
	public function getOnline()
	{
		$db 		= eZFluxBBDB::instance();
		
		$getOnline	= array(	'total'		=> 0,
								'guests'	=> 0,
								'users'		=> 0,
								'list'		=> array()
							);
		
		$online		= $db->arrayQuery(	'SELECT user_id, ident ' .
										'FROM '.$this->fluxBBConfig['db_prefix'].'online ' .
										'WHERE idle=0 ' .
										'ORDER BY ident');
		$getOnline['total']		= count($online);
		
		foreach( $online as $user )
		{
			if ( $user['user_id'] > 1)
			{
				$getOnline['list'][]		= $user;
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
	 * Récupération des informations sur des topics
	 * 
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
 	 * @return array
	 */
	public function getTopics( $params )
	{
		$db 		= eZFluxBBDB::instance();
		
		$select		= 	't.id topic_id, t.subject topic_name, t.poster creator, t.num_replies, t.posted published, ' .
						't.last_post_id, t.last_post last_post_published, t.last_poster last_post_creator';
		$leftJoin	= array();
		$innerJoin	= array();
		$where		= array();
		
		/* Jointure groupe_id */
		if ( $params['group_id'] )
		{
			$select 		.= ', f.id forum_id, f.forum_name';
			$innerJoin[]	= 'INNER JOIN '.$this->fluxBBConfig['db_prefix'].'forums AS f ON f.id=t.forum_id';
			$leftJoin[]		= 'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id=' . $params['group_id'] . ')';
			$where[]		= '(fp.read_forum IS NULL OR fp.read_forum=1)';
			$where[]		= 't.moved_to IS NULL';
		}
		
		/* Jointure avec les messages */
		if ( $params['get_first_message'] )
		{
			$select 		.= ', p.id post_id, p.message';
			$leftJoin[]		= 'LEFT JOIN '.$this->fluxBBConfig['db_prefix'].'posts p ON (p.topic_id=t.id AND t.posted = p.posted)';
		}
		
		if ( count($where) > 0 )
		{
			$where	= ' AND ' . implode( ' AND ', $where) . ' ';
		}
		else
		{
			$where	= '';	
		}
		
		$topics		= $db->arrayQuery(	'SELECT ' . $select . ' ' .
										'FROM '.$this->fluxBBConfig['db_prefix'].'topics t ' .
											implode( ' ', $innerJoin) . ' ' .
											implode( ' ', $leftJoin) . ' ' .
										'WHERE t.forum_id ' . $params['forum_id'] . ' ' .
										$where . ' ' .
										'ORDER BY ' . $params['sort_by'] .' ' .
										'LIMIT ' . $params['offset'] . ', ' . $params['limit']);

		return $topics;
	}
	
	
	
}	//EOC
 
?>