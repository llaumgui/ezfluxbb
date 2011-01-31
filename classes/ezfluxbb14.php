<?php
//
// Definition of eZFluxBB14 class
//
// Created on: <26-Jul-2010 09:00:00 llaumgui>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZFluxBB
// SOFTWARE RELEASE: 1.1
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


/*! \file ezfluxbb14.php
*/

/*!
  \class eZFluxBB14 ezfluxbb14.php
  \brief FluxBB functions in eZ Publish. Specific to FluxBB 1.2.
 */
class eZFluxBB14 extends eZFluxBB
{
    /**
     * Convert bbCode to HTML
     *
     * @param string &$str bbCode to convert
     */
    public function bbCode2HTML( &$str )
    {
    	global $re_list;

        if ( !function_exists( 'do_bbcode' ) )
            require_once PUN_ROOT . 'include/parser.php';
        if ( !function_exists( 'pun_htmlspecialchars' ) )
            require_once PUN_ROOT . 'include/functions.php';
        if (!defined('UTF8'))
            require_once PUN_ROOT . 'include/utf8/utf8.php';

        $str = do_bbcode( $str );
    }
}

?>
