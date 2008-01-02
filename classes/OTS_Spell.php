<?php

/**#@+
 * @version 0.0.7
 * @since 0.0.7
 */

/**
 * @package POT
 * @version 0.1.0+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for spell info.
 * 
 * @package POT
 * @version 0.1.0+SVN
 * @property-read int $type Spell type.
 * @property-read string $name Spell name.
 * @property-read int $id Spell ID.
 * @property-read string $words Spell formula.
 * @property-read bool $isAgressive Does spell marks action as an attack.
 * @property-read int $charges Rune charges count.
 * @property-read int $level Required level.
 * @property-read int $magicLevel Required magic level.
 * @property-read int $mana Mana usage.
 * @property-read int $soul Soul points usage.
 * @property-read bool $hasParams Does spell has any arguments.
 * @property-read bool $isEnabled Is spell enabled.
 * @property-read bool $isFarUseAllowed Can the spell be used from distance.
 * @property-read bool $isPremium Does spell requires PACC.
 * @property-read bool $isLearnNeeded Does the spell needs to be learned.
 * @property-read OTS_ItemType|null $conjure Conjure item type.
 * @property-read OTS_ItemType|null $reagent Item required to cast this spell.
 * @property-read int $conjuresCount Amount of items created with conjure cast.
 * @property-read array $vocations List of vocations allowed to use.
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
 * @version 0.1.0+SVN
 * @since 0.1.0+SVN
 * @return bool Is spell aggressive.
 */
    public function isAggressive()
    {
        return $this->element->getAttribute('aggressive') != '0';
    }

/**
 * This method is the same as {@link OTS_Spell::isAggressive() OTS_Spell::isAggressive()}. It was created first by typo misstake. Left for backward compatibility.
 * 
 * @version 0.1.0+SVN
 * @return bool Is spell aggressive.
 * @deprecated 0.1.0+SVN Use isAggressive().
 */
    public function isAggresive()
    {
        return $this->isAggressive();
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
 * @deprecated 0.1.0+SVN Use getConjure()->getId().
 */
    public function getConjureId()
    {
        return (int) $this->element->getAttribute('conjureId');
    }

/**
 * Returns item type of conjured item.
 * 
 * @version 0.1.0+SVN
 * @since 0.1.0+SVN
 * @return OTS_ItemType|null Returns item type of conjure item (null if not exists).
 */
    public function getConjure()
    {
        return POT::getInstance()->getItemsList()->getItemType( (int) $this->element->getAttribute('conjureId') );
    }

/**
 * Returns ID of item that is used by spell.
 * 
 * @return int Reagent ID.
 * @deprecated 0.1.0+SVN Use getReagent()->getId().
 */
    public function getReagentId()
    {
        return (int) $this->element->getAttribute('reagentId');
    }

/**
 * Returns item type of reagent item.
 * 
 * @version 0.1.0+SVN
 * @since 0.1.0+SVN
 * @return OTS_ItemType|null Returns item type of reagent item (null if not exists).
 */
    public function getReagent()
    {
        return POT::getInstance()->getItemsList()->getItemType( (int) $this->element->getAttribute('reagentId') );
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

/**
 * Creates conjure item.
 * 
 * @version 0.1.0+SVN
 * @since 0.1.0+SVN
 * @return OTS_Item Conjured item.
 */
    public function createConjure()
    {
        $item = $this->getConjure()->createItem();
        $item->setCount( $this->getConjureCount() );
        return $item;
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.0+SVN
 * @since 0.1.0+SVN
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'type':
                return $this->getType();

            case 'name':
                return $this->getName();

            case 'id':
                return $this->getId();

            case 'words':
                return $this->getWords();

            case 'isAggressive':
                return $this->isAggressive();

            case 'charges':
                return $this->getCharges();

            case 'level':
                return $this->getLevel();

            case 'magicLevel':
                return $this->getMagicLevel();

            case 'mana':
                return $this->getMana();

            case 'soul':
                return $this->getSoul();

            case 'hasParams':
                return $this->hasParams();

            case 'isEnabled':
                return $this->isEnabled();

            case 'isFarUseAllowed':
                return $this->isFarUseAllowed();

            case 'isPremium':
                return $this->isPremium();

            case 'isLearnNeeded':
                return $this->isLearnNeeded();

            case 'conjure':
                return $this->getConjure();

            case 'reagent':
                return $this->getReagent();

            case 'conjuresCount':
                return $this->getConjuresCount();

            case 'vocations':
                return $this->getVocations();

            default:
                throw new OutOfBoundsException();
        }
    }
}

/**#@-*/

?>
