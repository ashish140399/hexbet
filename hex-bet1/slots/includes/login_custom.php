<?php

// Custom Login Manager: Integrate with any existing User Management Systems
//
// If you have an existing user management system, modify `common_includes.php` to include
//   this file instead of `login_freeplay.php`.
//
// Then, modify function `LoggedUserID` to return the ID of the user that's currently logged in.
//
// Typically, this will be a $_SESSION value. You will need to find out what's the Session
//   key that your user management system uses to store the User ID, and modify the function
//   below to use the appropriate key. (By default, it's set to key 'userID').
//
// Finally, adapt function `UserNotLoggedInAction` to do what you need when the user is
//   not logged in. Typically, this is redirecting to the login page.
class Login {
	// Return the ID of the user currently logged in.
	public static function LoggedUserID() {
		return $_SESSION['userID'];
	}

	// Define what to do when there is no user logged in.
	// Generally, this is a redirect to the login page.
	// This action *must* finish the request. It must not be allowed to continue.
	public static function UserNotLoggedInAction() {
		header('Location: ' . Config::LOGIN_PAGE_URL);
		die(); // DO NOT REMOVE THIS. The request must end here.
	}
}
