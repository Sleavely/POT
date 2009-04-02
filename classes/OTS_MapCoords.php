<?php

/**
 * Code in this file bases on oryginal OTServ OTBM format loading C++ code (iomapotbm.h, iomapotbm.cpp).
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Map position point.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @property-read int $x X coord.
 * @property-read int $y Y coord.
 * @property-read int $z Z coord.
 */
class OTS_MapCoords
{
/**
 * X.
 * 
 * @version 0.0.6
 * @since 0.0.6
 * @var int
 */
    private $x;

/**
 * Y.
 * 
 * @version 0.0.6
 * @since 0.0.6
 * @var int
 */
    private $y;

/**
 * Z.
 * 
 * @version 0.0.6
 * @since 0.0.6
 * @var int
 */
    private $z;

/**
 * Sets coords for point.
 * 
 * @version 0.0.6
 * @since 0.0.6
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
 * <p>
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @param array $properties List of object properties.
 */
    public static function __set_state(array $properties)
    {
        return new self($properties['x'], $properties['y'], $properties['z']);
    }

/**
 * Returns X.
 * 
 * @version 0.0.6
 * @since 0.0.6
 * @return int X.
 */
    public function getX()
    {
        return $this->x;
    }

/**
 * Returns Y.
 * 
 * @version 0.0.6
 * @since 0.0.6
 * @return int Y.
 */
    public function getY()
    {
        return $this->y;
    }

/**
 * Returns Z.
 * 
 * @version 0.0.6
 * @since 0.0.6
 * @return int Z.
 */
    public function getZ()
    {
        return $this->z;
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'x':
            case 'y':
            case 'z':
                return $this->$name;

            default:
                throw new OutOfBoundsException();
        }
    }
}

?>
