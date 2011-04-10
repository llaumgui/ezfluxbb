<?php
//
// Definition of eZFluxBB12 class
//
// Created on: <01-Sep-2008 19:00:00 llaumgui>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZFluxBB
// SOFTWARE RELEASE: 1.2
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

/*! \file ezfluxbb12.php
*/

/*!
  \class eZFluxBB12 ezfluxbb12.php
  \brief FluxBB functions in eZ Publish. Specific to FluxBB 1.2.
 */
class eZFluxBB12 extends eZFluxBB
{

    /**
     * Convert bbCode to HTML
     *
     * @param string &$str bbCode to convert
     */
    public function bbCode2HTML( &$str )
    {
        if ( !function_exists( 'do_bbcode' ) ) {
            require_once PUN_ROOT . 'include/parser.php';
        }
        $str = do_bbcode( $str );
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
            $joinOn = 'p.topic_id=t.id AND p.posted=t.posted';
            $select .= ', p.id post_id, p.message';
            $innerJoin[] = 'INNER JOIN '.$this->fluxBBConfig['db_prefix'].'posts p ON (' . $joinOn . ')';
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
}

?>