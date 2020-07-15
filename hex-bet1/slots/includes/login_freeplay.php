<?php

// Freeplay Login Manager: Anyone can play without logging in.
//   Anyone that accesses the game will get a user record created, and a certain amount
//     of free credits (defined by the FREEPLAY_CREDITS constant near the top of this class).
//
//   Accessing the game will also set a cookie to "remember" the users, which will only
//     work if they come back on the same device. If they don't have the cookie, but they
//     access from the same IP address and the same browser, they may also be found and
//     "remembered".
//
//   NOTE:
//     - There is no real security involved. It cannot be used to handle prize giving securely
//     - Useful for trade shows or in-person situations where prizes are handed out manually.
//     - Also useful for lead capture, where you can redirect users somewhere else when they win, and gather
//         their details.
//     - Finally, useful for "fun" use cases. See our gallery for some ideas.
class Login {
	const FREEPLAY_CREDITS = 100;
	const COOKIE_KEY = "LuckScriptUserID";

	public static function LoggedUserID() {
		return self::AutoLogin();
	}

	public static function UserNotLoggedInAction() {
		throw new Exception("User is not logged in. This really shouldn't have happened...");
	}

	// Tries to find a DB user that matches the cookie (if any).
	//   If there's no cookie, tries to match by IP address and User Agent.
	//   If that fails, automatically creates a new user and sets a cookie for it.
	private static function AutoLogin() {

		// Try by cookie
		if (isset($_COOKIE[self::COOKIE_KEY])) {
			$userID = self::UserIDFromRid($_COOKIE[self::COOKIE_KEY]);
			if ($userID) { return $userID; }
		}

		// Try to match by IP and UA
		$user = DB::SingleRow(
			"SELECT " . Config::USERS_ID_FIELD . ", rid" .
			"  FROM " . Config::USERS_TABLE .
			"  WHERE ip_address = '" . DB::DQ($_SERVER['REMOTE_ADDR']) . "'" .
			"  AND user_agent = '" . DB::DQ($_SERVER['HTTP_USER_AGENT']) . "';");

		if ($user) {
			setcookie(self::COOKIE_KEY, $user['rid'], time()+31536000);
			return $user[Config::USERS_ID_FIELD];
		}

		// Create a user and return it
		$rid = self::AutoCreateUser();
		return self::UserIDFromRid($rid);
	}

	private static function UserIDFromRid($rid) {
		return DB::Scalar(
				"SELECT " . Config::USERS_ID_FIELD .
				"  FROM " . Config::USERS_TABLE .
				"  WHERE rid = '" . DB::DQ($rid) . "';");
	}

	private static function AutoCreateUser() {
		$rid = DB::UniqueRandomID(Config::USERS_TABLE, 'rid');

		DB::Execute(
			"INSERT INTO " . Config::USERS_TABLE .
			"  (rid, " . Config::USERS_BALANCE_FIELD . ", created, last_request, ip_address, user_agent) " .
			"  VALUES ('". $rid . "', " . self::FREEPLAY_CREDITS . ", now(), now(), '" . DB::DQ($_SERVER['REMOTE_ADDR']) . "', '" . DB::DQ($_SERVER['HTTP_USER_AGENT']) . "');");

		setcookie(self::COOKIE_KEY, $rid, time()+31536000);

		return $rid;
	}
}
