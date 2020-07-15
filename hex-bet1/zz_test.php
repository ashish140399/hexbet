<?php
include('includes/common_includes.php');
include('includes/slots.php');


$userID = GameUtils::DemandLoginOnAJAXAction();

// Validate parameters
$gameType = 'default';
$bet = 1;
$windowID = 1234;
//
//$gameSettings = Slots::GetGameSettings($gameType);
//
//echo "<pre>";
//for ($i = 0; $i < 1000; $i++) {
//    $spinResult = Slots::Spin($userID, $gameType, $bet, $windowID);
//    print_r($spinResult);
//}
echo "</pre>";

?>
<form action="slots_action.php" method="POST">
    <input type="hidden" name="action" value="spin">
    <input type="hidden" name="game_type" value="default">
    <input type="hidden" name="bet" value="1">
    <input type="hidden" name="window_id" value="12345">
    <input type="submit">
</form>