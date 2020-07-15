<?php

// This class serves three purposes:
//  - Given a prize that we've decided to give the user, pick a random set of reels that'll
//      yield that prize.
//  - Given a set of reel positions, figure out wich prize the user should get (if any).
//  - Be the central repository of knowledge about prizes. For example, it can provide a list
//    of prizes with rendering information added to them

define("ICONS_PER_REEL", 6); // How many icons you'll have per reel
define("ALLOW_BLANK_SPACES", false); // whether we allow reels to fall in the white spaces between icons

class PrizesAndReels {

    // Returns all prizes for this game type, including parsed matching rules, and
    // a class name we can use to render little sprites of the matching rules in the prizes list
    public static function ListPrizesForRendering($gameType) {
        $prizes = self::PrizesForGameType($gameType);

        foreach ($prizes as &$row) {
            $row['reel1_classname'] = self::ImageClassNameFromMatchingRule($row['reel1']);
            $row['reel2_classname'] = self::ImageClassNameFromMatchingRule($row['reel2']);
            $row['reel3_classname'] = self::ImageClassNameFromMatchingRule($row['reel3']);
        }
        return $prizes;
    }

    // Return information about a given prize, include parsed matching rules
    public static function PrizeData($prizeID) {
        if ($prizeID == null) { return null; }

        $row = DB::SingleRow(
            "SELECT * " .
            "  FROM " . Config::TABLE_PREFIX . "slots_prizes " .
            "  WHERE id = " . DB::DQ($prizeID) . "");

        $row = self::ParsePrizeReelRules($row);

        return $row;
    }

    // Given a set of reel positions, returns the ID of the prize they should give out
    // Could be NULL
    //
    // Parameters: `$reels` is an array of integers/floats
    public static function PrizeIDForReels($gameType, $gameSettings, $reels) {
        $prizes = self::PrizesForGameType($gameType);

        foreach ($prizes as $row) {
            if (self::CompareReel($reels[0], $row['reel1_parsed']) &&
                self::CompareReel($reels[1], $row['reel2_parsed']) &&
                self::CompareReel($reels[2], $row['reel3_parsed'])
            ) {
                return $row['id'];
            }
        }

        return null;
    }


    // Given a prize ID the user should get (could be NULL if no prize),
    // find a set of reel positions that yields that prize
    // If PrizeID is NULL, returns a losing combination.
    //
    //
    // Returns: array of integers
    public static function ReelsForPrizeID($gameType, $gameSettings, $prizeID) {
        $matchedPrizeID = -1;
        $attempts = 0;

        // Get the matching rules for the prize we should give out (or "anything" if no prize)
        if ($prizeID != null) {
            $parsedRules = self::PrizeData($prizeID);
        } else {
            // Ask for anything, really
            $reelRules = array('reel1' => '*', 'reel2' => '*', 'reel3' => '*');
            $parsedRules = self::ParsePrizeReelRules($reelRules);
        }

        // Generate plausible values for each reel, given the rules of the prize
        // HOWEVER, when we force the 3 reels to be each within the rule for the
        // prize, they may end up being in the rule for a HIGHER prize too, so we
        // need to double check that the user is actually getting the prize we want,
        // otherwise, the chance of getting one of the very high prizes is ENORMOUSLY greater than normal
        while ($matchedPrizeID != $prizeID) {
            $reels = array(
                self::ForcedReelOutcome($gameSettings, $parsedRules['reel1_parsed']),
                self::ForcedReelOutcome($gameSettings, $parsedRules['reel2_parsed']),
                self::ForcedReelOutcome($gameSettings, $parsedRules['reel3_parsed'])
            );

            // Check which prize we actually get given these reels
            $matchedPrizeID = self::PrizeIDForReels($gameType, $gameSettings, $reels);

            $attempts++;
            if ($attempts > 1000) { throw new Exception("Reels not found"); } // If after 1000 tries we didn't get it, move on, it just didn't work.
        }

        return $reels;
    }


    //-------------------------------------------------------------

    // Returns a list of all the prizes for this game type, with parsed reel rules.
    // The resulting rows include all DB columns, plus 3 extra `reel{n}_parsed` with the
    // matching rule of each reel parsed into its constituent parts (it's an array or a string)
    public static function PrizesForGameType($gameType) {
        $prizes = array();
        $dbPrizes = DB::FillArray("SELECT * FROM
									" . Config::TABLE_PREFIX . "slots_prizes
								WHERE game_type = '". DB::DQ($gameType) ."'
								ORDER BY payout_winnings DESC, payout_credits DESC;");

        foreach ($dbPrizes as $row) {
            $prizes[] = self::ParsePrizeReelRules($row);
        }

        return $prizes;
    }


    // Given a row with `reel1`, `reel2`, etc fields, will parse each of those
    // and augment the row with `reel{n}_parsed` columns.
    // See `ParseReelRule` for the interesting bit.
    private static function ParsePrizeReelRules($prizeData) {
        $prizeData['reel1_parsed'] = self::ParseReelRule($prizeData['reel1']);
        $prizeData['reel2_parsed'] = self::ParseReelRule($prizeData['reel2']);
        $prizeData['reel3_parsed'] = self::ParseReelRule($prizeData['reel3']);
        return $prizeData;
    }


    // Receives a reel matching rule as specified in the DB, and splits it, trims it,
    // etc, so we don't have to do it every time on CompareReel
    // May return an array or a string, depending on whether the matching rule has many
    // options
    //
    // Example input: '1/2/3` or `*.5`
    // Returns: ['1', '2', '3'] or '*.5'
    private static function ParseReelRule($rule) {
        $rules = explode("/", $rule);
        $rules = array_map('trim', $rules);
        if (count($rules) == 1) {
            $rules = $rules[0];
        }
        return $rules;
    }


    // Compare the position a reel fell on with the matching rules of a prize for that
    // reel, to see if matches. Returns a boolean.
    // The `$parsed_rule` should be the output of `ParseReelRule`
    //
    // $reel_position is the actual rolled value of the reel (float)
    // $parsed_rule is either:
    //		* (anything)
    //      *.0 (any non-blank)
    //		*.5 (any blank)
    //		A float (the actual outcome)
    //		An array of several of these
    private static function CompareReel($reelOutcome, $parsedRule) {
        if ($parsedRule == "*") { return true; }
        if ($parsedRule == "*.0") { return ($reelOutcome == ((int) $reelOutcome)); }
        if ($parsedRule == "*.5") { return ($reelOutcome != ((int) $reelOutcome)); }

        if (is_array($parsedRule)) {
            foreach ($parsedRule as $v) {
                if (self::CompareReel($reelOutcome, $v) == true) { return true; }
            }
            return false;
        }

        return ($reelOutcome == $parsedRule);
    }


    // Generate a random position for a reel to fall on, that matched the rule passed in.
    // The correct way would be to "generate" a reel, but it's easier to just randomize and compare.
    private static function ForcedReelOutcome($gameSettings, $parsedRule) {
        $outcome = self::RandomReelOutcome($gameSettings);
        $count = 0;
        while (!self::CompareReel($outcome, $parsedRule)) {
            $outcome = self::RandomReelOutcome($gameSettings);
            $count++;
            if ($count > 1000) { throw new Exception("Reels not found"); } // If after 1000 tries we didn't get it, move on, it just didn't work.
        }
        return $outcome;
    }


    // Get a random outcome for a reel. Simple uniform distribution
    private static function RandomReelOutcome($gameSettings) {
        if (ALLOW_BLANK_SPACES) {
            $randMax = ICONS_PER_REEL * 2 + 1; // * 2 because of blank spaces, + 1 because of the last blank space (there is no 0.5, it's ICONS_PER_REEL.5)
            return mt_rand(2, $randMax) / 2;
        } else {
            return mt_rand(1, ICONS_PER_REEL);
        }
    }


    // Given a reel's matching rule, turn it into something we can match in CSS
    // Used only for rendering prizes in the UI
    private static function ImageClassNameFromMatchingRule($rule) {
        return 'prize_' . str_replace(array(" ","*",".","/"), array("","star","dot","slash"), $rule);
    }
}
