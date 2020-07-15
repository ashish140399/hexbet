<?php

class GameUtils {
	// Either returns the logged User ID, or redirects / errors out.
	public static function DemandLoginOnRender() {
		$userID = Login::LoggedUserID();
		if (!$userID) {
			Login::UserNotLoggedInAction();
		}
		return $userID;
	}

	// Either returns the logged User ID, or renders JSON specifying "logged out"
	public static function DemandLoginOnAJAXAction() {
		$userID = Login::LoggedUserID();
		if (!$userID) {
			self::RenderJSONError('loggedOut');
		}
		return $userID;
	}

	// Returns a POST param (if it came in the request)
	// If not, renders a JSON error
	public static function RequirePOSTParam($paramName) {
		if (!isset($_POST[$paramName])) { GameUtils::RenderJSONError("Must specify " . $paramName); }
		return $_POST[$paramName];
	}

	// Renders and error in a shape that JS expects to show a nice dialog, and terminates the request
	public static function RenderJSONError($errorMessage) {
		echo json_encode(array('success'=> false, 'error'=> $errorMessage));
		die();
	}

	// Render the "your browser is out of sync" error.
	// This is just here because it's duplicated all over the place
	public static function RenderOutOfSyncError() {
		self::RenderJSONError('Your browser is not synced with the server. Please refresh and try again.');
	}

	// Ensure that specified user has enough balance to place the bet he's trying to place.
	// If not, return a rendered JSON error
	public static function ValidateBet($userID, $bet) {
		if (Users::GetUserBalance($userID) < $bet) {
			self::RenderJSONError('You don\'t have enough balance for this bet. <a href="#" onclick="window.location.reload();">Please refresh your browser</a> to reset your balance');
		}
	}
}
