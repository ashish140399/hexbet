<?php
// ---------------------------------------------------------------------------
//
// Must define $userID and $gameType before including this PHP template!
//
// ---------------------------------------------------------------------------

if (!$userID) { throw new Exception('Logged User not defined. You must define $userID before requiring slots_template.php'); }
if (!$gameType) { throw new Exception('Game Type not defined. You must define $gameType before requiring slots_template.php'); }

$gameSettings = Slots::GetGameSettings($gameType, true);
$prizes = PrizesAndReels::ListPrizesForRendering($gameType);
?>

<div class="slot_machine_outer_container" data-game-settings="<?php echo str_replace('"', "&quot;",json_encode($gameSettings)); ?>">
	<div class="slot_machine_win_bg"> <!-- Just to be able to set the "won!" extra background -->

		<div class="slot_machine_prizes_list">
			<?php
				foreach ($prizes as $prize) { ?>
					<div class="slot_machine_prize_row slot_machine_prize_row_<?php echo $prize['id']; ?>">
						<div class="slot_machine_prize_reel_sprites">
							<div class="slot_machine_prize_reel1 slot_machine_prize_reel_icon <?php echo $prize['reel1_classname']; ?>"></div>
							<div class="slot_machine_prize_reel2 slot_machine_prize_reel_icon <?php echo $prize['reel2_classname']; ?>"></div>
							<div class="slot_machine_prize_reel3 slot_machine_prize_reel_icon <?php echo $prize['reel3_classname']; ?>"></div>
							<div class="clearer"></div>
						</div>
						<span class="slot_machine_prize_payout" data-basePayout="<?php echo $prize['payout_winnings']; ?>"><?php echo (float) $prize['payout_winnings']; ?></span>
						<div class="clearer"></div>
					</div>
			<?php }	?>
		</div>

		<div class="slot_machine_container">

			<div class="slot_machine_reel_container">
				<div class="slot_machine_reel slot_machine_reel1"></div>
				<div class="slot_machine_reel slot_machine_reel2"></div>
				<div class="slot_machine_reel slot_machine_reel3"></div>
				<div class="reel_overlay"></div>
			</div>

			<div class="slot_machine_logged_out_message" style="display: none;"><span class="large">Sorry, you have been logged off.</span><br />
				<b>No bids</b> have been deducted from this spin, because you're not logged in anymore.
				Please <a href="/login">login</a> and try again.
			</div>
			<div class="slot_machine_failed_request_message" style="display: none;"><span class="large">Sorry, we're unable to display your spin because your connection to our server was lost. </span><br />
				Rest assured that your spin was not wasted.
				Please check your connection and <a href="#" onclick="window.location.reload();">refresh</a> to try again.
			</div>

			<div class="slot_machine_controls">
				<span class="slot_machine_output_last_win"></span>
				<span class="slot_machine_output_balance"></span>
				<span class="slot_machine_output_day_winnings">0</span>
				<span class="slot_machine_output_lifetime_winnings"><?php echo (float) Users::GetUserField($userID, 'slots_lifetime_winnings'); ?></span>
                <span class="slot_machine_output_bet"></span>
				<div class="slot_machine_bet_increase_button"></div>
				<div class="slot_machine_bet_decrease_button"></div>
			</div>

			<div class="slot_machine_spin_button"></div>
		</div>

		<div class="slot_machine_sound_toggle_button"></div>
	</div>
</div>
