<?php
/**
 * File containing the eZFluxBBOperators class
 *
 * @version //autogentag//
 * @package EZFluxBB
 * @copyright Copyright (C) 2008-2012 Guillaume Kulakowski and contributors
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0
 */

/**
 * The eZFluxBB class template operator for FluxBB
 *
 * @package EZFluxBB
 * @version //autogentag//
 */
class eZFluxBBOperators
{

    private $Operators;


    /**
     * Constructor
     */
    function __construct()
    {
        /* Opérateurs */
        $this->Operators = array(     'bbcode2html',
                                 );
    }



    /**
     * Return list of operators
     *
     * @return multitype:string
     */
    function &operatorList()
    {
        return $this->Operators;
    }



    /**
     * Return named parameters by operator
     *
     * @return boolean
     */
    function namedParameterPerOperator()
    {
        return true;
    }



    /**
     * Return named parameters list
     *
     * @return multitype:multitype:
     */
    function namedParameterList()
    {
         return array(
             'bbcode2html' => array( )
         );
    }



    /**
     * Excecute template operator action
     *
     * @param eZTemplate_type $tpl
     * @param string $operatorName
     * @param array $operatorParameters
     * @param operatorList $rootNamespace
     * @param operatorList $currentNamespace
     * @param string $operatorValue
     * @param array $namedParameters
     */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace,
        &$currentNamespace, &$operatorValue, &$namedParameters
    ) {
        switch ( $operatorName )
        {
            case 'bbcode2html':
                $eZFluxBB = eZFluxBB::instance();
                $eZFluxBB->bbCode2HTML( $operatorValue );
                break;
        }
    }

}

?>