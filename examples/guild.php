<?php

// to not repeat all that stuff
include('quickstart.php');

// loads guild
$guild = new OTS_Guild();
$guild->load(1);

$color = '#FFFFCC';

echo '<h1>Members of ', htmlspecialchars( $guild->getName() ), '</h1>';

?>
<table>
    <thead>
        <tr>
            <th>Rank</th>
            <th>Members</th>
        </tr>
    </thead>
    <tbody>
<?php

// lists members of all ranks
foreach($guild as $guildRank)
{
    // display rank in first row
    $first = true;
    // switches rank rows color
    $color = $color == '#FFFFCC' ? '#FFCCFF' : '#FFFFCC';

    // list members of this rank
    foreach($guildRank as $player)
    {
        echo '<tr style="background-color: ', $color, '">
    <td>', $first ? htmlspecialchars( $guildRank->getName() ) : '', '</td>
    <td>', $player->getName(), '</td>
</tr>';
        $first = false;
    }
}

?>
    </tbody>
</table>
