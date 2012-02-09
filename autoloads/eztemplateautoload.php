<?php
/**
 * Template autoload for eZFluxBB
 *
 * @version //autogentag//
 * @package EZFluxBB
 * @copyright Copyright (C) 2008-2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

// Operator autoloading
$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array(
    'script' => 'extension/ezfluxbb/classes/ezfluxbboperators.php',
    'class' => 'eZFluxBBOperators',
    'operator_names' => array(
        'bbcode2html',
    )
);

?>