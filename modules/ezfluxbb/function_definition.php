<?php
/**
 * File containing the functions definition for module ezfluxbb.
 *
 * @version //autogentag//
 * @package EZFluxBB
 * @copyright Copyright (C) 2008-2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

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

$FunctionList['info'] = array(
    'name'                  => 'info',
    'operation_types'       => array( 'read' ),
    'call_method'           => array(
        'include_file'          => 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
        'class'                 => 'eZFluxBBFetchFonctions',
        'method'            => 'getInfo'
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