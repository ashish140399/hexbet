<?php
include('includes/common_includes.php');
include('includes/slots.php');

// This line is here for security, in case you inadvertently leave this file in your server.
// Remove the line below to run the stats analyzer.
// DO NOT FORGET to remove it from your server once you are done, or everyone will be able
// to see your odds and profit margin!
die("Forbidden! Refer to your user manual to enable Stats Analyzer");

$gameType = 'default';
$runtime = 25; // in seconds
$initialBalance = 1000000;
$bet = 1;

$balance = $initialBalance;
$winnings = 0;
$spins = 0;
$winning_spins = 0;
$prizes_won = array();
$startTime = microtime(true);
$gameSettings = Slots::GetGameSettings($gameType);

set_time_limit($runtime * 2);
ini_set('max_execution_time', $runtime * 2);

while(microtime(true) < $startTime + $runtime) {
	$balance -= $bet;
	$data = RandomLogic::PrizeAndReels($gameType, $gameSettings);
    $spins += 1;

	if ($data['prize_id'] != null) {
	    $winning_spins += 1;
	    $prize = PrizesAndReels::PrizeData($data['prize_id']);
		$balance += $prize['payout_credits'] * $bet;
		$winnings += $prize['payout_winnings'] * $bet;

		if (!isset($prizes_won[$data['prize_id']])){
            $prizes_won[$data['prize_id']] = 0;
		}
        $prizes_won[$data['prize_id']] += 1;
	}
}

echo "<pre>";

echo "\n\n";
echo "Gametype: " . $gameType . "\n";
echo "Initial Balance: " . $initialBalance . "\n";
echo "End Balance: " . $balance . "\n";
echo "Spins: " . $spins . "\n";
echo "Winning Spins: " . $winning_spins . " (". round($winning_spins / $spins * 100, 2)."%)\n";
echo "Payout: " . $winnings . " (". round($winnings / $spins * 100, 2)."%)\n";
echo "Slots Gross Profit: " . ($initialBalance - $balance) . " (". round(($initialBalance - $balance) / $spins * 100, 2)."%)\n";
echo  "\n";

echo "Prizes:\n";
echo str_pad("ID", 4, " ") . "|";
echo str_pad("Payout", 14, " ") . "|";
echo str_pad("Winnings", 14, " ") . "|";
echo str_pad("Wins", 6, " ") . "|";
echo str_pad("Total Payout", 14, " ") . "|";
echo str_pad("Total Winnings", 14, " ") . "\n"; 

$prizes = PrizesAndReels::PrizesForGameType($gameType);
foreach ($prizes as $prize) {
	echo str_pad($prize['id'], 4, " ", STR_PAD_LEFT) . "|"; 
	echo str_pad($prize['payout_credits'], 14, " ", STR_PAD_LEFT) . "|";
	echo str_pad($prize['payout_winnings'], 14, " ", STR_PAD_LEFT) . "|";
	$wins = isset($prizes_won[$prize['id']]) ? $prizes_won[$prize['id']] : 0;
	echo str_pad($wins, 6, " ", STR_PAD_LEFT) . "|";
	echo str_pad($wins * $prize['payout_credits'], 14, " ", STR_PAD_LEFT) . "|";
	echo str_pad($wins * $prize['payout_winnings'], 14, " ", STR_PAD_LEFT) . "\n";
}

echo "</pre>";
