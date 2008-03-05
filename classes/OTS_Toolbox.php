<?php

/**#@+
 * @version 0.1.1
 * @since 0.1.1
 */

/**
 * @package POT
 * @version 0.1.3+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Toolbox for common operations.
 * 
 * @package POT
 * @version 0.1.3+SVN
 */
class OTS_Toolbox
{
/**
 * Calculates experience points needed for given level.
 * 
 * @param int $level Level for which experience should be calculated.
 * @param int $experience Current experience points.
 * @return int Experience points for level.
 */
    public static function experienceForLevel($level, $experience = 0)
    {
        return 50 * ($level - 1) * ($level * $level - 5 * $level + 12) / 3 - $experience;
    }

/**
 * Finds out which level user have basing on his/her experience.
 * 
 * PHP doesn't support complex numbers natively so solving third-level polynomials would be quite hard...
 * 
 * @param int $experience Current experience points.
 * @return int Experience level.
 */
    public static function levelForExperience($experience)
    {
        // default level
        $level = 1;

        // until we will find level which requires more experience then we have we will step to next
        while( self::experienceForLevel($level + 1) <= $experience)
        {
            $level++;
        }

        return $level;
    }

/**
 * Returns list of banned players.
 * 
 * @version 0.1.3+SVN
 * @since 0.1.3+SVN
 * @return OTS_Players_List Filtered list.
 */
    public static function bannedPlayers()
    {
        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->addFilter( new OTS_SQLField('type', 'bans'), POT::BAN_PLAYER);
        $filter->addFilter( new OTS_SQLField('player', 'bans'), new OTS_SQLField('id', 'players') );

        // selects only active bans
        $actives = new OTS_SQLFilter();
        $actives->addFilter( new OTS_SQLField('time', 'bans'), 0);
        $actives->addFilter( new OTS_SQLField('time', 'bans'), time(), OTS_SQLFilter::OPERATOR_GREATER, OTS_SQLFilter::CRITERIUM_OR);
        $filter->addFilter($actives);

        // creates list and aplies filter
        $list = new OTS_Players_List();
        $list->setFilter($filter);
        return $list;
    }

/**
 * Returns list of banned accounts.
 * 
 * @version 0.1.3+SVN
 * @since 0.1.3+SVN
 * @return OTS_Accounts_List Filtered list.
 */
    public static function bannedAccounts()
    {
        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->addFilter( new OTS_SQLField('type', 'bans'), POT::BAN_ACCOUNT);
        $filter->addFilter( new OTS_SQLField('account', 'bans'), new OTS_SQLField('id', 'accounts') );

        // selects only active bans
        $actives = new OTS_SQLFilter();
        $actives->addFilter( new OTS_SQLField('time', 'bans'), 0);
        $actives->addFilter( new OTS_SQLField('time', 'bans'), time(), OTS_SQLFilter::OPERATOR_GREATER, OTS_SQLFilter::CRITERIUM_OR);
        $filter->addFilter($actives);
echo $filter, "\n";
        // creates list and aplies filter
        $list = new OTS_Accounts_List();
        $list->setFilter($filter);
        return $list;
    }
}

/**#@-*/

?>
