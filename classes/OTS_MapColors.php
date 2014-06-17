<?php

/**
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

class OTS_MapColors {

  /**
   * Main array used to map items' mapcolor attribute to RGB
   */
  private static $rgbs = array(
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //0
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //4
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //8
    array(0,102,0),  array(0,0,0),      array(0,0,0),      array(0,0,0),       //12
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //16
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //20
    array(0,204,0),  array(0,0,0),      array(0,0,0),      array(0,0,0),       //24
    array(0,0,0),    array(0,0,0),      array(0,255,0),    array(0,0,0),       //28
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //32
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //36
    array(51,0,204), array(0,0,0),      array(0,0,0),      array(0,0,0),       //40
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //44
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //48
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //52
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //56
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //60
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //64
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //68
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //72
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //76
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //80
    array(0,0,0),    array(0,0,0),      array(102,102,102),array(0,0,0),       //84
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //88
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //92
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //96
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //100
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //104
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //108
    array(0,0,0),    array(0,0,0),      array(163,51,0),   array(0,0,0),       //112
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //116
    array(0,0,0),    array(153,102,51), array(0,0,0),      array(0,0,0),       //120
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //124
    array(0,0,0),    array(153,153,153),array(0,0,0),      array(0,0,0),       //128
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //132
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //136
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //140
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //144
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //148
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //152
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //156
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //160
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //164
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //168
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //172
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(204,255,255), //176
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //180
    array(0,0,0),    array(0,0,0),      array(255,51,0),   array(0,0,0),       //184
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //188
    array(255,102,0),array(0,0,0),      array(0,0,0),      array(0,0,0),       //192
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //196
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //200
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(255,204,153), //204
    array(0,0,0),    array(0,0,0),      array(255,255,0),  array(0,0,0),       //208
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(255,255,255), //212
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //216
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //220
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //224
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //228
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //232
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //236
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //240
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //244
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //248
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0)        //252
  );
  
  /**
   * Retrieve the color of a loaded item.
   *
   * @param OTS_ItemType $item Item loaded by OTS_ItemsList
   * @return bool|array RGB or false if no color exists
   */
  public static function getItem(OTS_ItemType $item)
  {
    if($item->hasAttribute('minimapColor'))
    {
      return self::$rgbs[$item->getAttribute('minimapColor')];
    }
    else
    {
      return false;
    }
  }
  
  /**
   * @param int $byte A value between 0-255 used in OTB to indicate color.
   * @return bool|array RGB or false if no color matches
   */
  public static function getAttribute($byte)
  {
    if(!isset(self::$rgbs[$byte])) return false;
    return self::$rgbs[$byte];
  }
}
