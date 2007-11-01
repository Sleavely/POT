<?php

/**#@+
 * @since 0.0.4
 */

/**
 * @package POT
 * @version 0.0.5
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * List of guilds.
 * 
 * @package POT
 * @version 0.0.5
 */
class OTS_Guilds_List extends OTS_Base_List
{
/**
 * Deletes guild.
 * 
 * @version 0.0.5
 * @param OTS_Guild $guild Guild to be deleted.
 * @deprecated 0.0.5 Use OTS_Guild->delete().
 */
    public function deleteGuild(OTS_Guild $guild)
    {
        $this->db->query('DELETE FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $account->getId() );
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
        $this->table = 'guilds';
        $this->class = 'Guild';
    }
}

/**#@-*/

?>
