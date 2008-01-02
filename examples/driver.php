<?php

/**
 * @ignore
 * @package examples
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

// to not repeat all that stuff
include('quickstart.php');

/*
    POT guilds invites driver.
*/

/**
 * @ignore
 */
class InvitesDriver implements IOTS_GuildAction
{
    // assigned guild
    private $guild;

    // initializes driver
    public function __construct(OTS_Guild $guild)
    {
        $this->guild = $guild;
        // this line automates the process - you can call it manualy from outside, but why?
        $this->guild->setInvitesDriver($this);
    }

    // returns all invited players to current guild
    public function listRequests()
    {
        $invites = array();

        /* here you must create OTS_Player object for each invited player */

        return $invites;
    }

    // invites player to current guild
    public function addRequest(OTS_Player $player)
    {
        /* here you must save invitation for given player */
    }

    // un-invites player
    public function deleteRequest(OTS_Player $player)
    {
        /* here you must delete invitation for given player */
    }

    // commits invitation
    public function submitRequest(OTS_Player $player)
    {
        $rank = null;

        // finds normal member rank
        foreach( $this->guild->getGuildRanks() as $guildRank)
        {
            if( $guildRank->getLevel() == 1)
            {
                $rank = $guildRank;
                break;
            }
        }

        $player->setRank($rank);
        $player->save();

        // clears invitation
        $this->deleteRequest($player);
    }
}

/*
    Parts of this class driver has been taken from OTSCMS (http://otscms.sourceforge.net/) project source code.
*/

// loads player wiht ID 1
$player = new OTS_Player();
$player->load(1);

// loads guild with ID 1
$guild = new OTS_Guild();
$guild->load(1);

// creates invitation logic driver for your implementation for current guild
new InvitesDriver($guild);

// note that you call guild method!
$guild->invite($player);

?>
