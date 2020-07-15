<?php

// Runs the randomization logic based on prize probabilities.
// For each spin, it will:
//   - come up with a random number
//   - see which prize the user should get
//   - find a reel combination that yields that prize
//   - return the prize ID and the reel combination
class RandomLogic {
	public static function PrizeAndReels($gameType, $gameSettings) {
		$prizeID = self::RandomPrizeSpin($gameType);
        $reels = PrizesAndReels::ReelsForPrizeID($gameType, $gameSettings, $prizeID);
		return array('prize_id' => $prizeID, 'reels' => $reels);
	}

    //-------------------------------------------------------------

	// Find the prize given each prize's probabilities, return the ID or NULL if a losing spin.
	private static function RandomPrizeSpin($gameType) {
		$prizes = self::PrizeOddsTable($gameType);
		$r = rand() / getrandmax();
		for ($i=0; $i < count($prizes); $i++) {
			if ($prizes[$i]['accWeight'] >= $r) {
				return $prizes[$i]['id'];
			}
		}
		return null;
	}

	private static function PrizeOddsTable($gameType) {
		$prizeData = array();
		$totalWeight = 0;

		$result = DB::RS("SELECT id, probability
							FROM " . Config::TABLE_PREFIX . "slots_prizes
							WHERE game_type = '". DB::DQ($gameType) ."';");

		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$totalWeight += $row["probability"];
			$row["accWeight"] = $totalWeight;
			$prizeData[] = $row;
		}

		return $prizeData;
	}
}
