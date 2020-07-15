<?php

// Custom Hooks: This file is where all your custom logic will generally live.
//
// The functions below will be called when certain events happen, allowing you to act
//   according to your needs. Each function has a comment describing when it gets called,
//   why you would modify it, and what you should return (if anything).
//
// Some functions will also include a commented out code sample for you to adapt to your
//   needs, if applicable.

class CustomHooks {

	// Called when a user's balance changes.
	// Specifies the amount of the change, and the reason, which could be starting a game
	//   (negative amount), winning a prize (positive amount), etc.
	//
	// This is useful if you keep an audit of balance changes. You should use this function
	//   to log to it.
	public static function UserBalanceChange($userID, $amount, $reason) {
		// Example:
		// DB::Execute(
		//   "INSERT INTO balance_changes (user_id, amount, reason, created) " .
		//   "  VALUES (". DB::DQ($userID) . ", " . DB::DQ($amount) . ", '" . DB::DQ($amount) . "', now());");
	}

	// Called when a spin is started.
	// Returns the ID of the prize the user will get in this spin, if we want to force it, or `null`
	//   if the regular prize probabilities should take place.
	//
	// Use this function if you want to force a prize for a user under certain conditions,
	//   without going via the normal prize selection process.
	// For example, you can use this to make every user win a small prize on their first try,
	//  making them more likely to keep playing.
	//
	// If a forced prize should be returned, return its ID in the prizes table.
	// Return null to allow the game to pick the prize as usual.
	public static function GetForcedPrize($userID, $gameType) {
		return null;
	}

	// Called each time a user wins a prize, for you to have the ability to act on that.
	//
	// If your prizes are "numeric" in nature (amounts credited to an account, etc), you
	//   probably don't need to modify this function.
	// However, if your prizes are non-numeric (t-shirts, laptops, whatever), you will need
	//   to use this function to store *somewhere* in your database that the user won these,
	//   and act in consequence, since the slots are only prepared to give out numeric prizes (balance)
	//
	// Another use may be to send an e-mail (either internally or to the user), update
	//   campaign data in external systems like Mailchimp, etc.
	public static function PrizeWon($userID, $gameType, $bet, $prizeID) {
	}
}
