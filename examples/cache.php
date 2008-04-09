<?php

// to not repeat all that stuff
include('quickstart.php');

/*
    POT binary formats cache driver.
*/

class CacheDriver implements IOTS_FileCache
{
    // cache content
    private $cache;

    // loads cache from file
    public function __construct()
    {
        $this->cache = unserialize( file_get_contents('.cache') );
    }

    // saves cache to file
    public function __destruct()
    {
        file_put_contents('.cache', serialize($this->cache) );
    }

    // returns cached content
    public function readCache($md5)
    {
        if( isset($this->cache[$md5]) )
        {
            return $this->cache[$md5];
        }
    }

    // saves cache for new file
    public function writeCache($md5, OTS_FileNode $root)
    {
        $this->cache[$md5] = $root;
    }
}

// creates instance of our driver
$cache = new CacheDriver();

// creates OTBM loaded
$otbm = new OTS_OTBMFile();

// sets cache for OTBM loader
$otbm->setCacheDriver($cache);

// if cache for this file will exist - will read it from cache
// if not - will read file directly and save new cache
$otbm->loadFile('/home/wrzasq/.otserv/data/world/map.otbm');

?>
