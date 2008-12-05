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
 * Classes regroupant les différents fetche de l'extension
 * 
 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 * @version 1.0
 */

class eZFluxBBFetchFonctions
{
	
	/**
	 * Fonction fetch permettant de retourner les information sur l'utilisateur FluxBB courrant
	 *
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
	 * @return array
	 */
	function fetchCurrentUser()
	{
		$eZFluxBB 		= eZFluxBB::instance();
		$currentUser	= $eZFluxBB->getCurrentUserInfo();
		//print_r($currentUser);
		
		$result 		= array( 'result' => array( 'id'			=> $currentUser['id'],
													'group_id'		=> $currentUser['group_id'],
													'username'		=> $currentUser['username'],
													'last_visit'	=> $currentUser['last_visit'],
											) );
		return $result;
	}
	
	
	
	/**
	 * Fonction fetch permettant de retourner les statistique sur la board.
	 *
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
	 * @return array
	 */
	function fetchStats()
	{
		$eZFluxBB 		= eZFluxBB::instance();
		$stats			= $eZFluxBB->getStats();
		
		return array( 'result' => $stats );
	}
	

	
	/**
	 * Fonction fetch permettant de retourner les statistique sur la board.
	 *
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
	 * @return array
	 */
	function fetchOnline()
	{
		$eZFluxBB 		= eZFluxBB::instance();
		$online			= $eZFluxBB->getOnline();
		
		return array( 'result' => $online );
	}
	
	
	
	/**
	 * XXX
	 *
	 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 	 * @since 1.0
 	 * 
	 * @return array
	 */
	function fetchTopics( $forum_id_filter_type, $forum_id_filter_array, $limit, $offset, $sort_by, $group_id, $get_first_message )
	{
		$sortingString 		= 't.posted';
		$sortOrder 			= true; // true is ascending
		$sortingForum_id	= 'IN';
		
		/* Sort_by */
		if ( is_array($sort_by) )
		{
			if ( array_key_exists(0, $sort_by) && !empty($sort_by[0]) )
        	{
        		$sortingString 	= $sort_by[0];
        	}
        	if ( array_key_exists(1, $sort_by) && is_bool($sort_by[1]) ) 
        	{
        		$sortOrder 		= $sort_by[1];
        	}
		}
		else
		{
        	if ( !empty($sort_by ) )
        	{
        		$sortingString = $sort_by;
        	}
        	
        }
		$sortingOrder 	= $sortOrder ? ' ASC' : ' DESC';
		
		/* $forum_id */
		if ( $forum_id_filter_array )
		{
	        if ( $forum_id_filter_type == 'exclude' )
	        {
	        	$sortingForum_id	= 'NOT IN';
	        }
	        if ( is_array( $forum_id_filter_array ) )
	        {
	        	$sortingForum_id	.= ' (' . implode( ', ', $forum_id_filter_array) . ')';
	        }
	        else
	        {
	        	$sortingForum_id	.= ' (' . $forum_id_filter_array . ')';
	        }
		}
		else
		{
			$sortingForum_id	= "";
		}
		
		$eZFluxBB 		= eZFluxBB::instance();
		$params			= array(	'forum_id'					=> $sortingForum_id,
									'limit'						=> $limit,
									'offset'					=> $offset,
									'sort_by' 					=> $sortingString . ' ' . $sortingOrder,
									'group_id' 					=> $group_id,
									'get_first_message' 		=> $get_first_message);
		
		$topics			= $eZFluxBB->getTopics( $params );

		return array( 'result' => $topics );
	}
}
 
?>