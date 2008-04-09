<?php

// to not repeat all that stuff
include('quickstart.php');

// loads item typing information
POT::getInstance()->loadItems('/path/to/your/ots/data/items');

// creates new player object
$player = new OTS_Player();
$player->find('Wrzasq');

/*
    Items loading example.
*/

// loading item from ammunition slot
$item = $player->getSlot(POT::SLOT_AMMO);

echo $player->getName(), ' has item with id ', $item->getId(), ' in his/her ammo slot.', "\n";

// checks if item is a container
if($item instanceof OTS_Container)
{
    // list backpack content
    foreach($item as $inside)
    {
        echo 'Container contains item with id ', $inside->getId(), '.', "\n";
    }
}

/*
    Items tree composing example.
*/

// creates container - here it would be a depot locker (we pass ID of item to create)
$container = new OTS_Container(2590);

// now let's create depot chest
$chest = new OTS_Container(2594);

// let's put chest inside locker
$container->addItem($chest);

// now let's put something deeper - into the chest
$item1 = new OTS_Item(3015);
$chest->addItem($item1);

// and more...
$item2 = new OTS_Item(3013);
$chest->addItem($item2);

// let's set count for an item
$item2->setCount(2);

/*
Here is a tree of items which we created:

$container [depot locker]
`-- $chest [depot chest]
    |-- $item1 [first item inserted into chest]
    `-- $item2 [second item inserted into chest] count=2
*/

/*
    Items saving example.
*/

// now we simply put those items into players depot (2 is depot ID)
$player->setDepot(2, $container);

?>
