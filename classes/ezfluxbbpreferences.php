<?php
/**
 * File containing the eZFluxBBPreferences class
 *
 * @version //autogentag//
 * @package EZFluxBB
 * @copyright Copyright (C) 2008-2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The eZFluxBBPreferences allow to store eZ Publish user preference inside eZ Publish
 *
 * @package EZFluxBB
 * @version //autogentag//
 */
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