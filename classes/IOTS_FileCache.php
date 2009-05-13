<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * This interface describes binary files cache control drivers.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @tutorial POT/Cache_drivers.pkg
 * @example examples/cache.php cache.php
 */
interface IOTS_FileCache
{
/**
 * Checks if cache for given file exists.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param string $md5 MD5 hash of file.
 * @return bool Whether cache for given file exists or not.
 */
    public function hasCache($md5);
/**
 * Returns cache.
 * 
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @param string $md5 MD5 hash of file.
 * @return OTS_FileNode Root node.
 * @throws E_OTS_NoCache When cache for given file does not exist.
 */
    public function readCache($md5);
/**
 * Writes node cache.
 * 
 * @version 0.0.6
 * @since 0.0.6
 * @param string $md5 MD5 checksum of current file.
 * @param OTS_FileNode $root Root node of file which should be cached.
 */
    public function writeCache($md5, OTS_FileNode $root);
}

?>
