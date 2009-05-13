<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.8
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * This interface defines items.xml cache handler as an standard file cache extender.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.8
 * @tutorial POT/Cache_drivers.pkg#interface.items
 */
interface IOTS_ItemsCache extends IOTS_FileCache
{
/**
 * Checks if cache for given file exists.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param string $md5 MD5 hash of file.
 * @return bool Whether cache for given file exists or not.
 */
    public function hasItems($md5);
/**
 * Returns cache.
 * 
 * @version 0.2.0+SVN
 * @since 0.0.8
 * @param string $md5 MD5 hash of file.
 * @return array List of items.
 * @throws E_OTS_NoCache When cache for given file does not exist.
 */
    public function readItems($md5);
/**
 * Writes items cache.
 * 
 * @version 0.0.8
 * @since 0.0.8
 * @param string $md5 MD5 checksum of current file.
 * @param array $items List of items to be saved.
 */
    public function writeItems($md5, array $items);
}

?>
