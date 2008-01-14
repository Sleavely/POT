<?php

/**#@+
 * @version 0.1.0
 * @since 0.1.0
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for spells list.
 * 
 * @package POT
 * @property-read array $runesList List of rune spells.
 * @property-read array $instantsList List of instant spells.
 * @property-read array $conjuresList List of conjure spells.
 */
class OTS_SpellsList
{
/**
 * Rune spell.
 */
    const SPELL_RUNE = 0;
/**
 * Instant spell.
 */
    const SPELL_INSTANT = 1;
/**
 * Conjure spell.
 */
    const SPELL_CONJURE = 2;

/**
 * Rune spells.
 * 
 * @var array
 */
    private $runes = array();

/**
 * Instant spells.
 * 
 * @var array
 */
    private $instants = array();

/**
 * Conjure spells.
 * 
 * @var array
 */
    private $conjures = array();
/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @internal Magic PHP5 method.
 * @param array $properties List of object properties.
 */
    public function __set_state($properties)
    {
        $object = new self();

        // loads properties
        foreach($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
    }

/**
 * Loads spells list.
 * 
 * @param string $file Spells file name.
 */
    public function __construct($file)
    {
        // loads DOM document
        $spells = new DOMDocument();
        $spells->load($file);

        // loads runes
        foreach( $spells->getElementsByTagName('rune') as $rune)
        {
            $this->runes[ $rune->getAttribute('name') ] = new OTS_Spell(self::SPELL_RUNE, $rune);
        }

        // loads instants
        foreach( $spells->getElementsByTagName('instant') as $instant)
        {
            $this->instants[ $instant->getAttribute('name') ] = new OTS_Spell(self::SPELL_INSTANT, $instant);
        }

        // loads conjures
        foreach( $spells->getElementsByTagName('conjure') as $conjure)
        {
            $this->conjures[ $conjure->getAttribute('name') ] = new OTS_Spell(self::SPELL_CONJURE, $conjure);
        }
    }

/**
 * Returns list of runes.
 * 
 * @return array List of rune names.
 */
    public function getRunesList()
    {
        return array_keys($this->runes);
    }

/**
 * Returns given rune spell.
 * 
 * @param string $name Rune name.
 * @return OTS_Spell|null Rune spell wrapper (null if rune does not exist).
 */
    public function getRune($name)
    {
        if( isset($this->runes[$name]) )
        {
            return $this->runes[$name];
        }
        else
        {
            return null;
        }
    }

/**
 * Returns list of instants.
 * 
 * @return array List of instant spells names.
 */
    public function getInstantsList()
    {
        return array_keys($this->instants);
    }

/**
 * Returns given instant spell.
 * 
 * @param string $name Spell name.
 * @return OTS_Spell|null Instant spell wrapper (null if rune does not exist).
 */
    public function getInstant($name)
    {
        if( isset($this->instants[$name]) )
        {
            return $this->instants[$name];
        }
        else
        {
            return null;
        }
    }

/**
 * Returns list of conjure spells.
 * 
 * @return array List of conjure spells names.
 */
    public function getConjuresList()
    {
        return array_keys($this->conjures);
    }

/**
 * Returns given conjure spell.
 * 
 * @param string $name Spell name.
 * @return OTS_Spell|null Conjure spell wrapper (null if rune does not exist).
 */
    public function getConjure($name)
    {
        if( isset($this->conjures[$name]) )
        {
            return $this->conjures[$name];
        }
        else
        {
            return null;
        }
    }

/**
 * Magic PHP5 method.
 * 
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'runesList':
                return $this->getRunesList();

            case 'instantsList':
                return $this->getInstantsList();

            case 'conjuresList':
                return $this->getConjuresList();

            default:
                throw new OutOfBoundsException();
        }
    }
}

/**#@-*/

?>
