<?php

/**#@+
 * @version 0.0.7+SVN
 * @since 0.0.7+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @todo 1.0.0: Link conjures with item types when items support will be added.
 */

/**
 * Wrapper for spell info.
 * 
 * @package POT
 */
class OTS_Spell
{
/**
 * Spell type.
 * 
 * @var int
 */
    private $type;

/**
 * Spell info resource.
 * 
 * @var DOMElement
 */
    private $element;

/**
 * Sets spell info.
 * 
 * @param int $type Spell type.
 * @param DOMElement $spell Spell info.
 */
    public function __construct($type, DOMElement $spell)
    {
        $this->type = $type;
        $this->element = $spell;
    }

/**
 * Returns spell type.
 * 
 * @return int Spell type.
 */
    public function getType()
    {
        return $this->type;
    }

/**
 * Returns spell name.
 * 
 * @return string Name.
 */
    public function getName()
    {
        return $this->element->getAttribute('name');
    }

/**
 * Returns rune item id.
 * 
 * @return int Rune item ID.
 */
    public function getID()
    {
        return (int) $this->element->getAttribute('id');
    }

/**
 * Returns spell formula.
 * 
 * @return string Formula.
 */
    public function getWords()
    {
        return $this->element->getAttribute('words');
    }

/**
 * Checks if spell is threated as unfriendly by other creatures.
 * 
 * @return bool Is spell aggressive.
 */
    public function isAggresive()
    {
        return $this->element->getAttribute('aggressive') != '0';
    }

/**
 * Number of rune charges.
 * 
 * @return int Rune charges.
 */
    public function getCharges()
    {
        return (int) $this->element->getAttribute('charges');
    }

/**
 * Level required for use.
 * 
 * @return int Required level.
 */
    public function getLevel()
    {
        return (int) $this->element->getAttribute('lvl');
    }

/**
 * Magic level required to cast.
 * 
 * @return int Required magic level.
 */
    public function getMagicLevel()
    {
        return (int) $this->element->getAttribute('maglv');
    }

/**
 * Mana cost.
 * 
 * @return int Mana usage.
 */
    public function getMana()
    {
        return (int) $this->element->getAttribute('mana');
    }

/**
 * Soul points cost.
 * 
 * @return int Soul points usage.
 */
    public function getSoul()
    {
        return (int) $this->element->getAttribute('soul');
    }

/**
 * Checks if spell has parameter.
 * 
 * @return bool True if spell takes a parameter.
 */
    public function hasParams()
    {
        return $this->element->getAttribute('params') == '1';
    }

/**
 * Checks if spell is enabled.
 * 
 * @return bool Is spell enabled.
 */
    public function isEnabled()
    {
        return $this->element->getAttribute('enabled') != '0';
    }

/**
 * Checks if distance use allowed.
 * 
 * @return bool Is it possible to use this spell from distance.
 */
    public function isFarUseAllowed()
    {
        return $this->element->getAttribute('allowfaruse') == '1';
    }

/**
 * Checks if spell requires PACC.
 * 
 * @return bool Is spell only for Premium Account.
 */
    public function isPremium()
    {
        return $this->element->getAttribute('prem') == '1';
    }

/**
 * Checks if spell needs to be learned.
 * 
 * @return bool Does this spell need to be learned.
 */
    public function isLearnNeeded()
    {
        return $this->element->getAttribute('needlearn') == '1';
    }

/**
 * Returns ID of item conjured by this spell.
 * 
 * @return int Item ID.
 */
    public function getConjureId()
    {
        return (int) $this->element->getAttribute('conjureId');
    }

/**
 * Returns ID of item that is used by spell.
 * 
 * @return int Reagent ID.
 */
    public function getReagentId()
    {
        return (int) $this->element->getAttribute('reagentId');
    }

/**
 * Returns amount of items conjured by this spell.
 * 
 * @return int Items count.
 */
    public function getConjureCount()
    {
        return (int) $this->element->getAttribute('conjureCount');
    }

/**
 * Returns list of vocations that are allowed to learn this spell.
 * 
 * @return array List of vocation names.
 */
    public function getVocations()
    {
        $vocations = array();

        foreach( $this->element->getElementsByTagName('vocation') as $vocation)
        {
            $vocations[] = $vocation->getAttribute('name');
        }

        return $vocations;
    }
}

/**#@-*/

?>
