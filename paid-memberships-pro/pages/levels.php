<?php
global $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user;
if($pmpro_msg)
{
?>
<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
<?php
}
?>
<div class="row levels-header">
	<h1 class="centered">Find a plan that's right for you and your brands.</h1>
	<p class="centered">All plans are billed monthly and can be canceled at any time.</p>
</div>

<div id="pmpro_levels_table" class="pmpro_checkout row container gutters">

	<?php
	$count = 0;
	foreach($pmpro_levels as $level)
	{
	  if( $level->id == 4){ // Removes the Free Membership level from the list.
        continue;
   	  }
	  if(isset($current_user->membership_level->ID))
		  $current_level = ($current_user->membership_level->ID == $level->id);
	  else
		  $current_level = false;
	?>
	<div class=" col span_8 level-item <?php if($count++ % 2 == 0) { ?>odd<?php } ?><?php if($current_level == $level) { ?> active<?php } ?>">
		<div class="level-title"><h3><?php echo $current_level ? "<strong>{$level->name}</strong>" : $level->name?></h3></div>
		<div class="level-cost">
			<h2><?php
				if(pmpro_isLevelFree($level))
					$cost_text = "<strong>Free</strong>";
				else
					$cost_text = pmpro_getLevelCost($level, true, true);
				$expiration_text = pmpro_getLevelExpiration($level);
				if(!empty($cost_text) && !empty($expiration_text))
					echo $cost_text . "<br />" . $expiration_text;
				elseif(!empty($cost_text))
					echo $cost_text;
				elseif(!empty($expiration_text))
					echo $expiration_text;
			?></h2>
		</div>
		<div class="level-description">
			<ul>
				<li><strong>Manage <?php echo $level->description; ?></strong></li>
				<li>Build Online Brand Books</li>
				<li>Share Brand Files</li>
				<li>Create Adobe Color Palettes</li>
				<li>Invite Users to the Brand</li>
			</ul>
		</div>
		<div>
		<?php if(empty($current_user->membership_level->ID)) { ?>
			<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'pmpro');?></a>
		<?php } elseif ( !$current_level ) { ?>
			<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'pmpro');?></a>
		<?php } elseif($current_level) { ?>

			<?php
				//if it's a one-time-payment level, offer a link to renew
				if(!pmpro_isLevelRecurring($current_user->membership_level) && !empty($current_user->membership_level->enddate))
				{
				?>
					<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Renew', 'pmpro');?></a>
				<?php
				}
				else
				{
				?>
					<a class="pmpro_btn disabled" href="<?php echo pmpro_url("account")?>"><?php _e('Your&nbsp;Current&nbsp;Level', 'pmpro');?></a>
				<?php
				}
			?>

		<?php } ?>
		</div>
	</div>
	<?php
	}
	?>

</div>
<nav id="nav-below" class="navigation" role="navigation">
	<div class="nav-previous alignleft">
		<?php if(!empty($current_user->membership_level->ID)) { ?>
			<a href="<?php echo pmpro_url("account")?>"><?php _e('&larr; Return to Your Account', 'pmpro');?></a>
		<?php } else { ?>
			<a href="<?php echo home_url()?>"><?php _e('&larr; Return to Home', 'pmpro');?></a>
		<?php } ?>
	</div>
</nav>
