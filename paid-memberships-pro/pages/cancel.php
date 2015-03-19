<?php
	global $pmpro_msg, $pmpro_msgt, $pmpro_confirm;

	if($pmpro_msg)
	{
?>
	<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
<?php
	}
?>

<?php if(!$pmpro_confirm) { ?>

<div class="cancel">
	<h2 class="centered"><?php _e('Are you sure you want to cancel your plan?', 'pmpro');?></h2>
	<p class="centered secondary">The brands you currently own will be archived. We recommend transfering those brands to new owners before canceling your membership.</p>
	<br />
	<br />
	<p class="centered">
		<a class="pmpro_yeslink yeslink button button-secondary button-inline" href="<?php echo pmpro_url("cancel", "?confirm=true")?>"><?php _e('Yes, cancel my account', 'pmpro');?></a>
		<a class="pmpro_nolink nolink button button-secondary button-inline" href="<?php echo pmpro_url("account")?>"><?php _e('No, keep my account', 'pmpro');?></a>
	</p>
</div>
<?php } else { ?>
	<p><a href="<?php echo get_home_url()?>"><?php _e('Click here to go to the home page.', 'pmpro');?></a></p>
<?php } ?>