<?php
//
// Definition of eZFluxBBFetchFonctions class
//
// Created on: <01-Sep-2008 19:00:00 llaumgui>
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

/*! \file ezfluxbbfetchfonctions.php
*/

/*!
  \class eZFluxBBFetchFonctions ezfluxbbfetchfonctions.php
  \brief Fetch functionsd for eZFluxBB
 */
class eZFluxBBFetchFonctions
{

    /**
     * Get informations about current FluxBB user
     *
     * @return array
     */
    function fetchCurrentUser()
    {
        return  array( 'result' => eZFluxBB::instance()->getCurrentUserInfo() );
    }



    /**
     * Get informations about current FluxBB user
     *
     * @return array
     */
    function getInfo()
    {
        $eZFluxBB = eZFluxBB::instance();

        return array( 'result' => $eZFluxBB->fluxBBInfo );
    }



    /**
     * Get informations about FluxBB stats
     *
     * @return array
     */
    function fetchStats()
    {
        $eZFluxBB = eZFluxBB::instance();
        $db = eZFluxBBDB::instance();

        $stats = $db->arrayQuery( eZFluxBBDB::setQuery($eZFluxBB->Queries['Stats']) );
        $lastMember = $db->arrayQuery( eZFluxBBDB::setQuery($eZFluxBB->Queries['LastMember']) );

        return array( 'result' => array_merge( $stats[0], array('last_member' => $lastMember[0]) ) );
    }



    /**
     * Get informations about online users
     *
     * @return array
     */
    function fetchOnline()
    {
        $eZFluxBB = eZFluxBB::instance();
        $db = eZFluxBBDB::instance();

        $onlineArray = array(
            'total'     => 0,
            'guests'    => 0,
            'users'     => 0,
            'list'      => array()
        );

        $online = $db->arrayQuery( eZFluxBBDB::setQuery($eZFluxBB->Queries['Online']) );

        $onlineArray['total'] = count($online);
        foreach( $online as $user )
        {
            if ( $user['user_id'] > 1)
            {
                $onlineArray['list'][]        = $user;
                $onlineArray['users']++;
            }
            else
            {
                $onlineArray['guests']++;
            }
        }

        return array( 'result' => $onlineArray );
    }



    /**
     * Get topics information function of argument
     *
     * @param string $forum_id_filter_type
     * @param mixed$ forum_id_filter_array
     * @param integer $limit
     * @param integer $offset
     * @param array $sort_by
     * @param integer $group_id
     * @param boolean $get_first_message
     *
     * @return array
     */
    function fetchTopics( $forum_id_filter_type, $forum_id_filter_array, $limit, $offset, $sort_by, $group_id, $get_first_message )
    {
        $eZFluxBB = eZFluxBB::instance();
        $db = eZFluxBBDB::instance();

        /*
         * Sort_by
         */
        $sortingString = 't.posted';
        $sortOrder = true; // true is ascending
        if ( is_array($sort_by) )
        {
            if ( array_key_exists(0, $sort_by) && !empty($sort_by[0]) )
                $sortingString = $sort_by[0];
            if ( array_key_exists(1, $sort_by) && is_bool($sort_by[1]) )
                $sortOrder = $sort_by[1];
        }
        else
        {
            if ( !empty($sort_by ) )
                $sortingString = $sort_by;
        }
        $sortingOrder = $sortOrder ? ' ASC' : ' DESC';


        /*
         * $forum_id
         */
        $sortingForumID = 'IN';
        if ( $forum_id_filter_array )
        {
            if ( $forum_id_filter_type == 'exclude' )
                $sortingForumID = 'NOT IN';
            if ( is_array( $forum_id_filter_array ) )
                $sortingForumID .= ' (' . implode( ', ', $forum_id_filter_array) . ')';
            else
                $sortingForumID .= ' (' . $forum_id_filter_array . ')';
        }
        else
        {
            $sortingForumID = "";
        }


        $select = 'SELECT ' . eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['Select']);
        $leftJoin = array();
        $innerJoin = array();
        $where = '';
        $whereArray = array();

        // join groupe_id
        if ( $group_id )
        {
            $select .= ', ' . eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['GroupID']['Select']);
            $innerJoin[] = eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['GroupID']['InnerJoin']);
            $leftJoin[] = sprintf( eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['GroupID']['LeftJoin']), $group_id );
            $whereArray[] = eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['GroupID']['Where']);
        }

        // join with post
        if ( $get_first_message )
        {
            $select .= ', ' . eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['GetFirstMessage']['Select']);
            $innerJoin[] = eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['GetFirstMessage']['InnerJoin']);
        }

        // Build WHERE
        if ( count($whereArray) > 0 )
            $where = ' AND ' . implode( ' AND ', $whereArray) . ' ';

        // Build query
        $topics = $db->arrayQuery(
            $select . ' ' .
            'FROM ' . eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['From']) . ' ' .
                implode( ' ', $innerJoin) . ' ' .
                implode( ' ', $leftJoin) . ' ' .
            'WHERE ' . sprintf( eZFluxBBDB::setQuery($eZFluxBB->Queries['Topics']['Where']), $sortingForumID ) . ' ' .
                $where . ' ' .
            'ORDER BY ' . $sortingString . ' ' . $sortingOrder .' ' .
            'LIMIT ' . $offset . ', ' . $limit
        );

        return array( 'result' => $topics );
    }

}

?>