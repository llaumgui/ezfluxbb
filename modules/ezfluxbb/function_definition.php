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
 * Fonction fetch de l'extension eZFluxBB.
 * 
 * @author Guillaume Kulakowski <guillaume_AT_llaumgui_DOT_com>
 * @version 1.0
 */
 

$FunctionList = array();

$FunctionList['current_user'] 	= array(
	'name'                 	=> 'current_user',
	'operation_types'     	=> array( 'read' ),
	'call_method'         	=> array(
		'include_file'    	=> 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
		'class'  	    	=> 'eZFluxBBFetchFonctions',
		'method'        	=> 'fetchCurrentUser'
	),
	'parameter_type'		=> 'standard',
	'parameters'         	=> array( )
);


$FunctionList['stats'] 			= array(
	'name'                 	=> 'stats',
	'operation_types'     	=> array( 'read' ),
	'call_method'         	=> array(
		'include_file'    	=> 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
		'class'  	    	=> 'eZFluxBBFetchFonctions',
		'method'        	=> 'fetchStats'
	),
	'parameter_type'		=> 'standard',
	'parameters'         	=> array( )
);


$FunctionList['online'] 		= array(
	'name'                 	=> 'online',
	'operation_types'     	=> array( 'read' ),
	'call_method'         	=> array(
		'include_file'    	=> 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
		'class'  	    	=> 'eZFluxBBFetchFonctions',
		'method'        	=> 'fetchOnline'
	),
	'parameter_type'		=> 'standard',
	'parameters'         	=> array( )
);


$FunctionList['topics'] 		= array(
	'topics'				=> 'current_user',
	'operation_types'     	=> array( 'read' ),
	'call_method'         	=> array(
			'include_file'    	=> 'extension/ezfluxbb/classes/ezfluxbbfetchfonctions.php',
			'class'  	    	=> 'eZFluxBBFetchFonctions',
			'method'        	=> 'fetchTopics'
	),
	'parameter_type'		=> 'standard',
	'parameters'         	=> array(
		array( 	'name'     => 'forum_id_filter_type',
				'type'     => 'string',
				'required' => false,
				'default'  => 'include'
		),
		array( 	'name'     => 'forum_id_filter_array',
				'type'     => 'mixed',
				'required' => false,
				'default'  => false
		),
		array( 	'name'     => 'limit',
				'type'     => 'integer',
				'required' => false,
				'default'  => 20
		),
		array( 	'name'     => 'offset',
				'type'     => 'integer',
				'required' => false,
				'default'  => 0
		),
		array( 	'name'     => 'sort_by',
				'type'     => 'array',
				'required' => false,
				'default'  => array( 't.posted', false )
		),
		array( 	'name'     => 'group_id',
				'type'     => 'integer',
				'required' => false,
				'default'  => false
		),
		array( 	'name'     => 'get_first_message',
				'type'     => 'boolean',
				'required' => false,
				'default'  => false
		),
	)
);

?>