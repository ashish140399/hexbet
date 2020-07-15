<?php

session_start();
include('includes/config.php');
include('includes/db.php');
include('includes/game_utils.php');
include('includes/php_utils.php');
include('includes/users.php');
include('includes/custom_hooks.php');

// Un-comment *one* (and only one) of the following lines. This will decide how your users are managed.
//   - login_freeplay.php: Anyone that access the game get a user record created automatically and free credits.
//       - Users are remembered through a cookie so they can come back and keep playing.
//       - There is no real security involved. It cannot be used to handle prize giving securely
//       - Useful for trade shows or in-person situations where prizes are handed out manually.
//       - Also useful for lead capture, where you can redirect users somewhere else when they win, and gather
//           their details.
//       - Finally, useful for "fun" use cases. See our gallery for some ideas.
//
//   - login_custom.php: You provide your own user management system.
//       - Use this file when you already have a system for users to register / login on your site.
//       - Provides real security, and can be used when handling money / real prizes.
//       - You will need to modify login_custom.php to integrate with your users system.
//
//   - login_luckscript.php: Use this if you've purchased Luckscript's User Management System (coming soon)

include('includes/login_freeplay.php');
// include('includes/login_custom.php');
// include('includes/login_luckscript.php');
