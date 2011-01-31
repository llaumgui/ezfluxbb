<?php
//
// Created on: <01-Sep-2008 19:00:00 llaumgui>
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

$FunctionList = array();

$FunctionList['current_user'] = array(
    'name'                  => 'current_user',
    'operation_types'       => array( 'read' ),
    'call_method'           => array(
        'include_file'          => 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
        'class'                 => 'eZFluxBBFetchFonctions',
        'method'            => 'fetchCurrentUser'
    ),
    'parameter_type'        => 'standard',
    'parameters'            => array( )
);


$FunctionList['stats'] = array(
    'name'                  => 'stats',
    'operation_types'       => array( 'read' ),
    'call_method'           => array(
        'include_file'          => 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
        'class'                 => 'eZFluxBBFetchFonctions',
        'method'                => 'fetchStats'
    ),
    'parameter_type'        => 'standard',
    'parameters'            => array( )
);


$FunctionList['online'] = array(
    'name'                  => 'online',
    'operation_types'       => array( 'read' ),
    'call_method'           => array(
        'include_file'          => 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
        'class'                 => 'eZFluxBBFetchFonctions',
        'method'                => 'fetchOnline'
    ),
    'parameter_type'        => 'standard',
    'parameters'            => array( )
);


$FunctionList['topics'] = array(
    'name'                  => 'topics',
    'operation_types'       => array( 'read' ),
    'call_method'           => array(
            'include_file'      => 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
            'class'             => 'eZFluxBBFetchFonctions',
            'method'            => 'fetchTopics'
    ),
    'parameter_type'        => 'standard',
    'parameters'            => array(
        array(  'name'     => 'forum_id_filter_type',
                'type'     => 'string',
                'required' => false,
                'default'  => 'include'
        ),
        array(  'name'     => 'forum_id_filter_array',
                'type'     => 'mixed',
                'required' => false,
                'default'  => false
        ),
        array(  'name'     => 'limit',
                'type'     => 'integer',
                'required' => false,
                'default'  => 20
        ),
        array(  'name'     => 'offset',
                'type'     => 'integer',
                'required' => false,
                'default'  => 0
        ),
        array(     'name'     => 'sort_by',
                'type'     => 'array',
                'required' => false,
                'default'  => array( 't.posted', false )
        ),
        array(  'name'     => 'group_id',
                'type'     => 'integer',
                'required' => false,
                'default'  => false
        ),
        array(  'name'     => 'get_first_message',
                'type'     => 'boolean',
                'required' => false,
                'default'  => false
        ),
    )
);

?>
