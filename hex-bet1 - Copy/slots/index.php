
<!DOCTYPE html>
<html>
<head>
	<title>Slots</title>

	<meta name="viewport" content="width=880px, user-scalable=no, target-densitydpi=device-dpi" />
</head>

<body>
    <?php
        // This will render ALL the types of slots that come with the game.
        // leave only the one you want!

        // Default slot
        $gameType = "default"; // Modify on this line which game Type you'd like to show
        echo '<h2>' . $gameType . '</h2>';
        include('includes/slots_template.php');
    ?>

    <script type="text/javascript">
        var remaining_balance = <?php echo $userBalance; ?>;
        var windowID = <?php echo $windowID; ?>;
    </script>
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
    <script type="text/javascript" src="js/soundmanager2.js"></script>
    <script type="text/javascript" src="js/slots.js"></script>
</body>
</html>
