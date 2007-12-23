<?php

/**#@+
 * @version 0.1.0+SVN
 * @since 0.1.0+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @todo 0.1.0: Bind towns with IDs.
 */

/**
 * Wrapper for house information.
 * 
 * @package POT
 */
class OTS_House
{
/**
 * Information handler.
 * 
 * @var DOMElement
 */
    private $element;

/**
 * Creates wrapper for given house element.
 * 
 * @param DOMElement $element House information.
 */
    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

/**
 * Returns house's ID.
 * 
 * @return int House ID.
 */
    public function getId()
    {
        return $this->element->getAttribute('houseid');
    }

/**
 * Return house's name.
 * 
 * @return string House name.
 */
    public function getName()
    {
        return $this->element->getAttribute('name');
    }

/**
 * Returns town ID in which house is located.
 * 
 * @return int Town ID.
 */
    public function getTownId()
    {
        return $this->element->getAttribute('townid');
    }

/**
 * Returns house rent cost.
 * 
 * @return int Rent cost.
 */
    public function getRent()
    {
        return $this->element->getAttribute('rent');
    }

/**
 * Returns house size.
 * 
 * @return int House size.
 */
    public function getSize()
    {
        return $this->element->getAttribute('size');
    }

/**
 * Returns entry position.
 * 
 * @return OTS_MapCoords Entry coordinations on map.
 */
    public function getEntry()
    {
        return new OTS_MapCoords( $this->element->getAttribute('entryx'), $this->element->getAttribute('entryy'), $this->element->getAttribute('entryz') );
    }
}

/**#@-*/

?>
