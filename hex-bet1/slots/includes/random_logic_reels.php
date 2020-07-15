<?php

// Runs the randomization logic based on reel probabilities.
// For each of the reels, it'll come up with a random number, see which slot the reel
//   falls in, and then figure out which prize the user should get for that reel combination
// It'll return both the prizeID and the reels to show to the user.
class RandomLogic {
	public static function PrizeAndReels($gameType, $gameSettings) {
		$reels = array(
			self::RandomReelSpin($gameType, 1),
			self::RandomReelSpin($gameType, 2),
			self::RandomReelSpin($gameType, 3)
		);
		$prizeID = PrizesAndReels::PrizeIDForReels($gameType, $gameSettings, $reels);
		return array('prize_id' => $prizeID, 'reels' => $reels);
	}

    //-------------------------------------------------------------

    // Find the outcome of each reel, given the probabilities specified for each reel position
	private static function RandomReelSpin($gameType, $reel) {
		$outcomes = self::ReelOddsTable($gameType, $reel);
		$totalWeight = $outcomes[count($outcomes) - 1]['accWeight'];

		$r = mt_rand() * $totalWeight / mt_getrandmax();
		for ($i=0; $i < count($outcomes); $i++) {
			if ($outcomes[$i]['accWeight'] >= $r) {
				return $outcomes[$i]['outcome'];
			}
		}
	}

	private static function ReelOddsTable($gameType, $reel) {
		$reelData = array();
		$totalWeight = 0;
		$result = DB::RS("SELECT outcome, probability
							FROM " . Config::TABLE_PREFIX . "slots_reels
							WHERE game_type = '". DB::DQ($gameType) ."'
							AND reel = " . DB::DQ($reel) . ";");

		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$totalWeight += $row["probability"];
			$row["accWeight"] = $totalWeight;
			$reelData[] = $row;
		}

		return $reelData;
	}
}
