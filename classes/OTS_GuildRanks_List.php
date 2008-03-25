<?php

/**#@+
 * @since 0.0.4
 */

/**
 * @package POT
 * @version 0.1.3+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * List of guild ranks.
 * 
 * @package POT
 * @version 0.1.3+SVN
 */
class OTS_GuildRanks_List extends OTS_Base_List
{
/**
 * Deletes guild rank.
 * 
 * @version 0.0.5
 * @param OTS_GuildRank $guildRank Rank to be deleted.
 * @deprecated 0.0.5 Use OTS_GuildRank->delete().
 */
    public function deleteGuildRank(OTS_GuildRank $guildRank)
    {
        $this->db->query('DELETE FROM ' . $this->db->tableName('guild_ranks') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $guildRank->getId() );
    }

/**
 * Sets list parameters.
 * 
 * This method is called at object creation.
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
 * If any display driver is currently loaded then it uses it's method.
 * 
 * @version 0.1.3+SVN
 * @since 0.1.0
 * @return string String representation of object.
 */
    public function __toString()
    {
        $ots = POT::getInstance();

        // checks if display driver is loaded
        if( $ots->isDisplayDriverLoaded() )
        {
            return $ots->getDisplayDriver()->displayGuildRanksList($this);
        }

        return (string) $this->count();
    }
}

/**#@-*/

?>
