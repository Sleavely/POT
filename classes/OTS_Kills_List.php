<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * List of kills.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
class OTS_Kills_List extends OTS_Base_List
{
/**
 * Sets list parameters.
 * 
 * <p>
 * This method is called at object creation.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    public function init()
    {
        $this->table = 'killers';
        $this->class = 'Kill';
    }
}

?>
