<?php
//
// Definition of eZFluxBBFetchFonctions class
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
        $eZFluxBB = eZFluxBB::instance();

        $result = array( 'result' => $eZFluxBB->getCurrentUserInfo() );
        return $result;
    }



    /**
     * Get informations about FluxBB stats
     *
     * @return array
     */
    function fetchStats()
    {
        $eZFluxBB = eZFluxBB::instance();

        return array( 'result' => $eZFluxBB->getStats() );
    }



    /**
     * Get informations about online users
     *
     * @return array
     */
    function fetchOnline()
    {
        $eZFluxBB         = eZFluxBB::instance();

        return array( 'result' => $eZFluxBB->getOnline() );
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
        $sortingString = 't.posted';
        $sortOrder = true; // true is ascending
        $sortingForum_id = 'IN';

        /* Sort_by */
        if ( is_array($sort_by) )
        {
            if ( array_key_exists(0, $sort_by) && !empty($sort_by[0]) )
            {
                $sortingString = $sort_by[0];
            }
            if ( array_key_exists(1, $sort_by) && is_bool($sort_by[1]) )
            {
                $sortOrder = $sort_by[1];
            }
        }
        else
        {
            if ( !empty($sort_by ) )
            {
                $sortingString = $sort_by;
            }

        }
        $sortingOrder = $sortOrder ? ' ASC' : ' DESC';

        /* $forum_id */
        if ( $forum_id_filter_array )
        {
            if ( $forum_id_filter_type == 'exclude' )
            {
                $sortingForum_id = 'NOT IN';
            }
            if ( is_array( $forum_id_filter_array ) )
            {
                $sortingForum_id .= ' (' . implode( ', ', $forum_id_filter_array) . ')';
            }
            else
            {
                $sortingForum_id .= ' (' . $forum_id_filter_array . ')';
            }
        }
        else
        {
            $sortingForum_id = "";
        }

        $eZFluxBB = eZFluxBB::instance();
        $params = array(    'forum_id'              => $sortingForum_id,
                            'limit'                 => $limit,
                            'offset'                => $offset,
                            'sort_by'               => $sortingString . ' ' . $sortingOrder,
                            'group_id'              => $group_id,
                            'get_first_message'     => $get_first_message);

        return array( 'result' => $eZFluxBB->getTopics( $params ) );
    }

}

?>