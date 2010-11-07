<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.4
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * List of guild ranks.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.4
 */
class OTS_GuildRanks_List extends OTS_Base_List
{
/**
 * Sets list parameters.
 * 
 * <p>
 * This method is called at object creation.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    public function init()
    {
        $this->table = 'guild_ranks';
        $this->class = 'GuildRank';
    }

/**
 * Returns string representation of object.
 * 
 * <p>
 * If any display driver is currently loaded then it uses it's method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return string String representation of object.
 */
    public function __toString()
    {
        // checks if display driver is loaded
        if( POT::isDisplayDriverLoaded() )
        {
            return POT::getDisplayDriver()->displayGuildRanksList($this);
        }

        return (string) $this->count();
    }
}

?>