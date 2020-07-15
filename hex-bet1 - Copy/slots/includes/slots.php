<?php

include('includes/prizes_and_reels.php');

// If you would like to use "reel odds" instead of "prize odds" (Pro customers only),
// comment out the include for `random_logic_prizes.php` and uncomment the include for
// `random_logic_reels.php`.
//
// You can find out more about this in the documentation, under "reel odds"
include('includes/random_logic_prizes.php');
//include('includes/random_logic_reels.php');

class Slots {
    public static function GetGameSettings($gameType, $public = false) {
        $fieldList = "*";
        if ($public) {
            $fieldList = "id AS game_type, min_bet, max_bet, " . ICONS_PER_REEL . " AS icons_per_reel";
        }

        return DB::SingleRow(
            "SELECT " . $fieldList . " " .
            "  FROM " . Config::TABLE_PREFIX . "slots_game_types " .
            "  WHERE id = '" . DB::DQ($gameType) . "' AND enabled = 1");
    }

    public static function Spin($userID, $gameType, $bet, $windowID) {
        $gameSettings = self::GetGameSettings($gameType);
        if ($gameSettings == null) {
            throw new Exception("Invalid game type: ". $gameType);
        }

        if ($bet < $gameSettings['min_bet'] || $bet > $gameSettings['max_bet']) {
            throw new Exception("Invalid Bet");
        }

        try { DB::BeginTransaction();
            // Validate Bet and Deduct from Balance
            if ($bet > 0) {
                GameUtils::ValidateBet($userID, $bet);
                Users::ChangeBalance($userID, -$bet, 'Slot Machine Spin: ' . $gameType);
            }

            // Pick the prize the user is going to get
            $prizeID = CustomHooks::GetForcedPrize($userID, $gameType);

            // If we don't have a forced prize, pick one randomly
            if ($prizeID == null) {
                // `prize` logic will return null reels, and we fall down to the "forced prize"
                // logic of picking `ReelsForPrize`. `reel` logic will return both prize and reels
                $prizeAndReels = RandomLogic::PrizeAndReels($gameType, $gameSettings);
                $prizeID = $prizeAndReels['prize_id'];
                $reels = $prizeAndReels['reels'];
            } else {
                // If we do, pick the reels for it
                $reels = PrizesAndReels::ReelsForPrizeID($gameType, $gameSettings, $prizeID);
            }

            // Get the prize details, and scale the payout to the bet
            $prizeData = PrizesAndReels::PrizeData($prizeID);
            if ($prizeData != null) {
                $prizeData['payout_credits'] = $prizeData['payout_credits'] * $bet;
                $prizeData['payout_winnings'] = $prizeData['payout_winnings'] * $bet;
            }

            // Prepare the Result
            $result = array('reels' => $reels, 'prize' => null);
            if ($prizeID != null) {
                $result['prize'] = array(
                    'id' => $prizeID,
                    'payout_credits' => $prizeData['payout_credits'],
                    'payout_winnings' => $prizeData['payout_winnings'],
                );
            }

            // Apply all side effects to database
            Users::IncrementUserField($userID, 'slots_spins', 1);
            self::LogSpin($userID, $gameType, $bet, $windowID, $reels, $prizeData);

            if ($prizeID != null) {
                Users::ChangeBalance($userID, $prizeData['payout_credits'], 'Slot Machine Prize: ' . $prizeID);
                Users::IncrementUserField($userID, 'slots_lifetime_winnings', $prizeData['payout_winnings']);
                CustomHooks::PrizeWon($userID, $gameType, $bet, $prizeID);
            }

            DB::Commit();
        } catch (Exception $e) { DB::Rollback(); throw $e; }

        return $result;
    }

    // Logs the details of the spin to the database. You may want to modify how this is logged.
    private static function LogSpin($userID, $gameType, $bet, $windowID, $reels, $prizeData) {
        $sql = "INSERT INTO " . Config::TABLE_PREFIX . "slots_spins (
					date, user_id, game_type, window_id, action, bet,
					reel1, reel2, reel3, prize_id, payout_credits, payout_winnings)
				VALUES (
					now(), ". $userID . ", '" . DB::DQ($gameType) . "',
					'" . DB::DQ($windowID) . "', 'Spin', " . DB::DQ($bet) . ",
					" . DB::DQ($reels[0]) . ", ". DB::DQ($reels[1]) . ", ". DB::DQ($reels[2]) . ",
					" . DB::DQ($prizeData ? $prizeData['id'] : "NULL") . ",
					" . DB::DQ($prizeData ? $prizeData['payout_credits']: "NULL") . ",
					" . DB::DQ($prizeData ? $prizeData['payout_winnings']: "NULL") . "
				);";
        DB::Execute($sql);
    }
}
