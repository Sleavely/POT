<?php

/**#@+
 * @version 0.0.6+SVN
 * @since 0.0.6+SVN
 */

/**
 * Code in this file bases on oryginal OTServ OTBM format loading C++ code (iomapotbm.h, iomapotbm.cpp).
 * 
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @todo 0.1.0: Houses support.
 * @todo 1.0.0: Complete OTBM support: link tiles with items, spawns and houses.
 * @todo 1.0.0: Spawns support.
 */

/**
 * OTBM format reader.
 * 
 * @package POT
 */
class OTS_OTBMFile extends OTS_FileLoader
{
/**
 * Description attribute.
 */
    const OTBM_ATTR_DESCRIPTION = 1;
/**
 * External file.
 */
    const OTBM_ATTR_EXT_FILE = 2;
/**
 * Tile flags.
 */
    const OTBM_ATTR_TILE_FLAGS = 3;
/**
 * Action ID.
 */
    const OTBM_ATTR_ACTION_ID = 4;
/**
 * Unique ID.
 */
    const OTBM_ATTR_UNIQUE_ID = 5;
/**
 * Text.
 */
    const OTBM_ATTR_TEXT = 6;
/**
 * Description.
 */
    const OTBM_ATTR_DESC = 7;
/**
 * Teleport destination.
 */
    const OTBM_ATTR_TELE_DEST = 8;
/**
 * Item.
 */
    const OTBM_ATTR_ITEM = 9;
/**
 * Depot ID.
 */
    const OTBM_ATTR_DEPOT_ID = 10;
/**
 * External spawns file.
 */
    const OTBM_ATTR_EXT_SPAWN_FILE = 11;
/**
 * Rune changes amount.
 */
    const OTBM_ATTR_RUNE_CHARGES = 12;
/**
 * External houses file.
 */
    const OTBM_ATTR_EXT_HOUSE_FILE = 13;
/**
 * ID of doors.
 */
    const OTBM_ATTR_HOUSEDOORID = 14;

/**
 * Root node.
 */
    const OTBM_NODE_ROOTV1 = 1;
/**
 * Map data container.
 */
    const OTBM_NODE_MAP_DATA = 2;
/**
 * Item definition.
 */
    const OTBM_NODE_ITEM_DEF = 3;
/**
 * Map tiles fragment.
 */
    const OTBM_NODE_TILE_AREA = 4;
/**
 * Single tile.
 */
    const OTBM_NODE_TILE = 5;
/**
 * Item.
 */
    const OTBM_NODE_ITEM = 6;
/**
 * Tile.
 */
    const OTBM_NODE_TILE_SQUARE = 7;
/**
 * Tile reference.
 */
    const OTBM_NODE_TILE_REF = 8;
/**
 * Spawns container.
 */
    const OTBM_NODE_SPAWNS = 9;
/**
 * Spawn.
 */
    const OTBM_NODE_SPAWN_AREA = 10;
/**
 * Monster.
 */
    const OTBM_NODE_MONSTER = 11;
/**
 * Towns container.
 */
    const OTBM_NODE_TOWNS = 12;
/**
 * Town.
 */
    const OTBM_NODE_TOWN = 13;
/**
 * Tile of house.
 */
    const OTBM_NODE_HOUSETILE = 14;

/**
 * Map width.
 * 
 * @var int
 */
    private $width;

/**
 * Map height.
 * 
 * @var int
 */
    private $height;

/**
 * Map description.
 * 
 * @var string
 */
    private $description = '';

/**
 * List of towns.
 * 
 * @var array
 */
    private $towns = array();

/**
 * Temple positions.
 * 
 * @var array
 */
    private $temples = array();

/**
 * Loads OTBM file content.
 * 
 * @param string $file Filename.
 */
    public function loadFile($file)
    {
        // loads file structure
        parent::loadFile($file);

        // root node header
        $version = $this->root->getLong();
        $this->width = $this->root->getShort();
        $this->height = $this->root->getShort();
        $majorVersionItems = $this->root->getLong();
        $minorVersionItems = $this->root->getLong();

        // checks version of root node
        if($version != 0)
        {
            throw new E_OTS_OTBMError(E_OTS_OTBMError::LOADMAPERROR_OUTDATEDHEADER);
        }

        // reads root's first child
        $node = $this->root->getChild();

        // it should be OTBM_NODE_MAP_DATA
        if( $node->getType() != self::OTBM_NODE_MAP_DATA)
        {
            throw new E_OTS_OTBMError(E_OTS_OTBMError::LOADMAPERROR_UNKNOWNNODETYPE);
        }

        $directory = dirname($file);

        // reads map attributes
        while( $node->isValid() )
        {
            switch( $node->getChar() )
            {
                // description text
                case self::OTBM_ATTR_DESCRIPTION:
                    $this->description .= $node->getString() . "\n";
                    break;

                // external spawns file
                case self::OTBM_ATTR_EXT_SPAWN_FILE:
                // external houses file
                case self::OTBM_ATTR_EXT_HOUSE_FILE:
                    // just we need to skip known attributes
                    $node->getString();
                    break;

                // unsupported attribute
                default:
                    throw new E_OTS_OTBMError(E_OTS_OTBMError::LOADMAPERROR_UNKNOWNNODETYPE);
            }
        }

        $node = $node->getChild();

        // reads map nodes
        while($node)
        {
            switch( $node->getType() )
            {
                // map definition part
                case self::OTBM_NODE_TILE_AREA:
/* we don't realy need it in POT, but in case we would, it is possible to add it here */
                    break;

                // list of towns on map
                case self::OTBM_NODE_TOWNS:
                    $town = $node->getChild();

                    // reads all towns
                    while($town)
                    {
                        // checks if it's town node
                        if( $town->getType() != self::OTBM_NODE_TOWN)
                        {
                            throw new E_OTS_OTBMError(E_OTS_OTBMError::LOADMAPERROR_UNKNOWNNODETYPE);
                        }

                        // reads basic town info
                        $id = $town->getLong();
                        $this->towns[$id] = $town->getString();

                        // temple coords
                        $x = $town->getShort();
                        $y = $town->getShort();
                        $z = $town->getChar();
                        $this->temples[$id] = new OTS_MapCoords($x, $y, $z);

                        $town = $town->getNext();
                    }
                    break;

                // unknown type
                default:
                    throw new E_OTS_OTBMError(E_OTS_OTBMError::LOADMAPERROR_UNKNOWNNODETYPE);
            }

            $node = $node->getNext();
        }
    }

/**
 * Returns map width.
 * 
 * @return int Map width.
 */
    public function getWidth()
    {
        return $this->width;
    }

/**
 * Returns map height.
 * 
 * @return int Map height.
 */
    public function getHeight()
    {
        return $this->height;
    }

/**
 * Returns map description.
 * 
 * @return string Map description.
 */
    public function getDescription()
    {
        return $this->description;
    }

/**
 * Returns town's ID.
 * 
 * @param string $name Town.
 * @return int|bool ID (false if not found).
 */
    public function getTownID($name)
    {
        return array_search($name, $this->towns);
    }

/**
 * Returns name of given town's ID.
 * 
 * @param int $id Town ID.
 * @return string|bool Name (false if not found).
 */
    public function getTownName($id)
    {
        if( isset($this->towns[$id]) )
        {
            return $this->towns[$id];
        }
        else
        {
            return false;
        }
    }

/**
 * Returns list (id => name) of loaded towns.
 * 
 * @return array List of towns.
 */
    public function getTownsList()
    {
        return $this->towns;
    }

/**
 * Returns town's temple position.
 * 
 * @param int $id Town id.
 * @return OTS_MapCoords|bool Point on map (false if not found).
 */
    public function getTownTemple($id)
    {
        if( isset($this->temples[$id]) )
        {
            return $this->temples[$id];
        }
        else
        {
            return false;
        }
    }
}

?>
