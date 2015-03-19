<?php
	global $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $levels;

	//if a member is logged in, show them some info here (1. past invoices. 2. billing information with button to update.)
	if($current_user->membership_level->ID)
	{
	?>
	<div id="pmpro_account">


		<div id="pmpro_account-profile" class="pmpro_box row row-border-bottom">
			<?php get_currentuserinfo(); ?>
			<div class="col span_18">
				<h2><?php _e("Account Details", "pmpro");?></h2>
				<?php if($current_user->user_firstname) { ?>
					<p class="secondary"><strong><?php echo $current_user->user_firstname?> <?php echo $current_user->user_lastname?></strong></p>
				<?php } ?>
					<p class="secondary"><?php _e("Email", "pmpro");?>: <?php echo $current_user->user_email?></p>
			</div>
			<div class="col span_6">
				<a class="button button-block button-secondary" href="<?php echo admin_url('profile.php')?>"><?php _e("Edit Profile", "pmpro");?></a>
			</div>
		</div> <!-- end pmpro_account-profile -->

		<?php
			//last invoice for current info
			//$ssorder = $wpdb->get_row("SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' AND membership_id = '" . $current_user->membership_level->ID . "' AND status = 'success' ORDER BY timestamp DESC LIMIT 1");
			$ssorder = new MemberOrder();
			$ssorder->getLastMemberOrder();
			$invoices = $wpdb->get_results("SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' ORDER BY timestamp DESC LIMIT 6");
			if(!empty($ssorder->id) && $ssorder->gateway != "check" && $ssorder->gateway != "paypalexpress" && $ssorder->gateway != "paypalstandard" && $ssorder->gateway != "twocheckout")
			{
				//default values from DB (should be last order or last update)
				$bfirstname = get_user_meta($current_user->ID, "pmpro_bfirstname", true);
				$blastname = get_user_meta($current_user->ID, "pmpro_blastname", true);
				$baddress1 = get_user_meta($current_user->ID, "pmpro_baddress1", true);
				$baddress2 = get_user_meta($current_user->ID, "pmpro_baddress2", true);
				$bcity = get_user_meta($current_user->ID, "pmpro_bcity", true);
				$bstate = get_user_meta($current_user->ID, "pmpro_bstate", true);
				$bzipcode = get_user_meta($current_user->ID, "pmpro_bzipcode", true);
				$bcountry = get_user_meta($current_user->ID, "pmpro_bcountry", true);
				$bphone = get_user_meta($current_user->ID, "pmpro_bphone", true);
				$bemail = get_user_meta($current_user->ID, "pmpro_bemail", true);
				$bconfirmemail = get_user_meta($current_user->ID, "pmpro_bconfirmemail", true);
				$CardType = get_user_meta($current_user->ID, "pmpro_CardType", true);
				$AccountNumber = hideCardNumber(get_user_meta($current_user->ID, "pmpro_AccountNumber", true), false);
				$ExpirationMonth = get_user_meta($current_user->ID, "pmpro_ExpirationMonth", true);
				$ExpirationYear = get_user_meta($current_user->ID, "pmpro_ExpirationYear", true);
				?>

				<div id="pmpro_account-billing" class="pmpro_box  row row-border-bottom">
					<h2><?php _e("Billing Information", "pmpro");?></h2>
					<div class="col span_6">
						<?php if(!empty($baddress1)) { ?>
						<p  class="secondary">
							<strong><?php _e("Billing Address", "pmpro");?></strong><br />
							<?php echo $bfirstname . " " . $blastname?>
							<br />
							<?php echo $baddress1?><br />
							<?php if($baddress2) echo $baddress2 . "<br />";?>
							<?php if($bcity && $bstate) { ?>
								<?php echo $bcity?>, <?php echo $bstate?> <?php echo $bzipcode?> <?php echo $bcountry?>
							<?php } ?>
							<br />
							<?php echo formatPhone($bphone)?>
						</p>
						<?php } ?>
					</div>
					<div class="col span_12">
						<?php if(!empty($AccountNumber)) { ?>
						<p class="secondary">
							<strong><?php _e("Payment Method", "pmpro");?></strong><br />
							<?php echo $CardType?>: <?php echo last4($AccountNumber)?> (<?php echo $ExpirationMonth?>/<?php echo $ExpirationYear?>)
						</p>
						<?php } ?>
					</div>
					<div class="col span_6">
						<?php
							if((isset($ssorder->status) && $ssorder->status == "success") && (isset($ssorder->gateway) && in_array($ssorder->gateway, array("authorizenet", "paypal", "stripe", "braintree", "payflow", "cybersource"))))
							{
								?>
								<a class="button button-block button-secondary"  href="<?php echo pmpro_url("billing", "")?>"><?php _e("Edit Billing Information", "pmpro"); ?></a>
								<?php
							}
						?>
					</div>
				</div> <!-- end pmpro_account-billing -->
			<?php
			}
		?>

		<?php if(!empty($invoices)) { ?>
		<div id="pmpro_account-invoices" class="pmpro_box row row-border-bottom">
			<h2><?php _e("Past Invoices", "pmpro");?></h2>
			<ul class="invoices">
				<?php
					$count = 0;
					foreach($invoices as $invoice)
					{
						if($count++ > 5)
							break;
						?>
						<li class="invoice-item"><a class="secondary" href="<?php echo pmpro_url("invoice", "?invoice=" . $invoice->code)?>"><?php echo date_i18n(get_option("date_format"), $invoice->timestamp)?> (<?php echo pmpro_formatPrice($invoice->total)?>)</a></li>
						<?php
					}
				?>
			</ul>
			<?php if($count == 6) { ?>
				<p><a class="secondary" href="<?php echo pmpro_url("invoice"); ?>"><?php _e("View All Invoices", "pmpro");?></a></p>
			<?php } ?>
		</div> <!-- end pmpro_account-billing -->
		<?php } ?>

		<div id="pmpro_account-links" class="pmpro_box">
				<?php
					do_action("pmpro_member_links_top");
				?>
				<p><a class="secondary" href="<?php echo pmpro_url("cancel")?>"><?php _e("Cancel Your Plan", "pmpro");?></a></p>
				<?php
					do_action("pmpro_member_links_bottom");
				?>
			</ul>
		</div> <!-- end pmpro_account-links -->
	</div> <!-- end pmpro_account -->
	<?php
	}
?>
