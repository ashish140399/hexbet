<?php
include('includes/common_includes.php');
include('includes/slots.php');

switch ($_POST['action']) {
	case "spin": slotsSpin(); break;
	default: throw new Exception("Invalid Action");
}

//==============================================================================
//							Slots
//==============================================================================

function slotsSpin() {
	$userID = GameUtils::DemandLoginOnAJAXAction();

	// Validate parameters
	$gameType = GameUtils::RequirePOSTParam('game_type');
	$bet = GameUtils::RequirePOSTParam('bet');
	$windowID = GameUtils::RequirePOSTParam('window_id');

	$gameSettings = Slots::GetGameSettings($gameType);

	if ($gameSettings['min_bet'] > 0) {
		GameUtils::ValidateBet($userID, $bet);
	}

	// Spin
	$spinResult = Slots::Spin($userID, $gameType, $bet, $windowID);

	$extraData = array(
		'success'=> true,
		'balance' =>  Users::GetUserBalance($userID),
		'day_winnings' => 0,
		'lifetime_winnings' => (float) Users::GetUserField($userID, 'slots_lifetime_winnings')
	);

	echo json_encode(array_merge($spinResult, $extraData));
}
