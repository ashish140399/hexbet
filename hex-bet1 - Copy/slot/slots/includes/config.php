<?php

class Config {
	const DB_HOST = "localhost"; // Host / address of your database server
	const DB_USERNAME = "root"; // DB Username
	const DB_PASSWORD = ""; // DB Password
	const DB_NAME = "slots"; // Name of your database

	// To prevent collisions, by default, all game tables are prefixed with this value.
	// If you modify this, you will also need to change the `.sql` scripts that create the
	//   DB tables, changing all table names to have the new prefix, instead of "luckscript_".
	const TABLE_PREFIX = "luckscript_";

	// Name of your users table (ignores the prefix). If you have a user management system,
	// this is the table where users are stored
	const USERS_TABLE = "luckscript_users";

	// Name of the primary key field in your users table. This will generally be "id"
	const USERS_ID_FIELD = "id";

	// Name of the column that stores the user's balance / credits for playing the games.
	// If you are using a money management system, this is the column which stores the user's balance.
	const USERS_BALANCE_FIELD = "balance";

	// If the visit reaches the game and is not logged in, he will (by default) be redirected
	//   to your login page. This constant defines where that page is.
	// If you'd like to do something different than redirecting logged out users, modify
	//   function `UserNotLoggedInAction` in the `includes/login_custom.php` file.
	const LOGIN_PAGE_URL = "/login.php";

	// If the user tries to play a game, but doesn't have enough balance, he will see a dialog
	//   asking him to add more balance to his account. That dialog will have a button that will
	//   link to the page where they can add more balance. This constant defines where that page is.
	const ADD_BALANCE_URL = "/add_balance.php";
}

// Set this to your current time zone. You can see a list of valid values here:
//   http://php.net/manual/en/timezones.php
// If something else in your codebase sets the timezone, you might want to remove the line
//   below.
date_default_timezone_set('Europe/London');
