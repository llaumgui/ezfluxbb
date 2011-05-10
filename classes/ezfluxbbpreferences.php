<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: Fedora-Fr - eZP Base
// SOFTWARE RELEASE: 5.0.0
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

class eZFluxBBPreferences extends eZPersistentObject
{
    /**
     * Mandatory method defining the eZFindElevationConfiguration persistent object
     *
     * @return array An array defining the eZFindElevationConfiguration persistent object
     */
    public static function definition()
    {
        return array(
            "fields" => array( "id"               => array(    'name' => 'ID',
                                                               'datatype' => 'int',
                                                               'default' => '',
                                                               'required' => true ),
                               "name"             => array(    'name' => 'name',
                                                               'datatype' => 'string',
                                                               'default' => 0,
                                                               'required' => true ),
                               "fluxbb_user_id"   => array(    'name' => 'fluxBBUserID',
                                                               'datatype' => 'int',
                                                               'default' => '',
                                                               'required' => true ),
        					   "value" 			  => array(    'name' => 'fluxBBUserID',
                                                               'datatype' => 'int',
                                                               'default' => '',
                                                               'required' => true )
                             ),
            "keys" => array( "id" ),
            "function_attributes" => array(),
			"increment_key" => "id",
            "class_name" => "eZFluxBBPreferences",
            "sort" => array( "id" => "asc" ),
            "name" => "ezfluxbbpreferences"
         );
    }

}
 
?>