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
 * @todo 0.1.0: Houses SQL part support.
 */

/**
 * Wrapper for houses list.
 * 
 * Note: as this class extends DOMDocument class and contains exacly respond XML tree you can work on it as on normal DOM tree.
 * 
 * @package POT
 */
class OTS_Houses extends DOMDocument
{
/**
 * Loads houses information.
 * 
 * @param string $path Houses file.
 */
    public function __construct($path)
    {
        parent::__construct();
        $this->load($path);
    }

/**
 * Returns house information.
 * 
 * @param int $id House ID.
 * @return OTS_House|null House information wrapper (null if not found house).
 */
    public function getHouse($id)
    {
        foreach( $this->documentElement->getElementsByTagName('house') as $house)
        {
            // checks houses id
            if( $house->getAttribute('houseid') == $id)
            {
                return new OTS_House($house);
            }
        }

        return null;
    }

/**
 * Returns ID of house with given name.
 * 
 * @param string $name House name.
 * @return int|bool House ID (false if not found).
 */
    public function getHouseId($name)
    {
        foreach( $this->documentElement->getElementsByTagName('house') as $house)
        {
            // checks houses id
            if( $house->getAttribute('name') == $name)
            {
                return $house->getAttribute('houseid');
            }
        }

        return false;
    }
}

/**#@-*/

?>
