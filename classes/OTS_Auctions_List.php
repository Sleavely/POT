<?php

/**
 * @package POT
 * @version 0.2.0b+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * List of auctions.
 * 
 * @package POT
 * @version 0.2.0b+SVN
 */
 
class OTS_Auctions_List extends OTS_Base_List
{
/**
 * Sets list parameters.
 * 
 * <p>
 * This method is called at object creation.
 * </p>
 * 
 * @version 0.0.5
 * @since  0.2.0b+SVN
 */
 
 	public function init()
 	{
 		$this->table = 'house_auctions';
 		$this->class = 'Auction';
 	}
 	
 /**
 * Returns string representation of object.
 * 
 * <p>
 * If any display driver is currently loaded then it uses it's method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0b+SVN
 * @return string String representation of object.
 */
 
 	public function __toString()
 	{
 		// checks if display driver is loaded
 		if ( POT::isDisplayDriverLoaded() )
 		{
 			return POT::getDisplayDriver()->displayAuctionsList($this);
 		}
 		
 		return (string) $this->count();
 	}
}
 
 ?>