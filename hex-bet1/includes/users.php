<?php

class Users {
	public static function GetUserBalance($userID) {
		return (float) DB::Scalar(
			"SELECT " . Config::USERS_BALANCE_FIELD .
			"  FROM " . Config::USERS_TABLE .
			"  WHERE " . Config::USERS_ID_FIELD . " = " . DB::DQ($userID) . ";");
	}

	public static function ChangeBalance($userID, $amount, $reason) {
		$sign = ($amount < 0 ? "-" : "+");

		DB::Execute(
			"UPDATE " . Config::USERS_TABLE .
			"  SET " . Config::USERS_BALANCE_FIELD . " = " . Config::USERS_BALANCE_FIELD . " " . $sign . " " . abs($amount) .
			" WHERE " . Config::USERS_ID_FIELD . " = " . DB::DQ($userID) . ";");

		CustomHooks::UserBalanceChange($userID, $amount, $reason);
	}

	public static function GetUserField($userID, $field) {
		return DB::Scalar(
				"SELECT " . DB::DQ($field) .
				" FROM " . Config::USERS_TABLE .
				" WHERE " . Config::USERS_ID_FIELD . " = " . DB::DQ($userID) . ";");
	}

	public static function IncrementUserField($userID, $field, $increment) {
		$sign = ($increment < 0 ? "-" : "+");

		DB::Execute(
				"UPDATE " . Config::USERS_TABLE .
				"  SET " . $field . " = " . $field . " " . $sign . " " . abs($increment) .
				" WHERE " . Config::USERS_ID_FIELD . " = " . DB::DQ($userID) . ";");
	}
}
