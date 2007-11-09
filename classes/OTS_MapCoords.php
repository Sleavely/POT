<?php

/**#@+
 * @version 0.0.6
 * @since 0.0.6
 */

/**
 * Code in this file bases on oryginal OTServ OTBM format loading C++ code (iomapotbm.h, iomapotbm.cpp).
 * 
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Map position point.
 * 
 * @package POT
 */
class OTS_MapCoords
{
/**
 * X
 * 
 * @var int
 */
    private $x;

/**
 * Y
 * 
 * @var int
 */
    private $y;

/**
 * Z
 * 
 * @var int
 */
    private $z;

/**
 * Sets coords for point.
 * 
 * @param int $x X.
 * @param int $y Y.
 * @param int $z Z.
 */
    public function __construct($x, $y, $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @internal Magic PHP5 method.
 * @param array $properties List of object properties.
 */
    public static function __set_state($properties)
    {
        return new self($properties['x'], $properties['y'], $properties['z']);
    }

/**
 * Returns X.
 * 
 * @return int X.
 */
    public function getX()
    {
        return $this->x;
    }

/**
 * Returns Y.
 * 
 * @return int Y.
 */
    public function getY()
    {
        return $this->y;
    }

/**
 * Returns Z.
 * 
 * @return int Z.
 */
    public function getZ()
    {
        return $this->z;
    }
}

/**#@-*/

?>
