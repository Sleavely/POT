<?php

/**#@+
 * @version 0.0.6+SVN
 * @since 0.0.6+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * This interface describe binary files cache control drivers.
 * 
 * @package POT
 */
interface IOTS_FileCache
{
/**
 * Returns cache.
 * 
 * @param string $md5 MD5 hash of file.
 * @return OTS_NodeStruct|null Root node (null if file cache is not valid).
 */
    public function readCache($md5);
/**
 * Writes node cache.
 * 
 * @param string $md5 MD5 checksum of current file.
 * @param OTS_NodeStruct $root Root node of file which should be cached.
 */
    public function writeCache($md5, OTS_NodeStruct $root);
}

?>
