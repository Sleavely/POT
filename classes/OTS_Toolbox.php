<?php

/**#@+
 * @version 0.1.1+SVN
 * @since 0.1.1+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Toolbox for common operations.
 * 
 * @package POT
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
}

/**#@-*/

?>
