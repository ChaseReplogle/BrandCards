<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>

<div class="edit-profile row gutters">
	<div class="col span_16 large-gutter login profile" id="theme-my-login<?php $template->the_instance(); ?>">
		<?php $template->the_action_template_message( 'profile' ); ?>
		<?php $template->the_errors(); ?>
		<form action="<?php $template->the_action_url( 'profile' ); ?>" method="post">
			<?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
			<p>
				<input type="hidden" name="from" value="profile" />
				<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
			</p>

			<?php if ( has_action( 'personal_options' ) ) : ?>

			<h3><?php _e( 'Personal Options' ); ?></h3>


			<?php do_action( 'personal_options', $profileuser ); ?>


			<?php endif; ?>

			<?php do_action( 'profile_personal_options', $profileuser ); ?>

			<label for="first_name"><?php _e( 'First Name' ); ?></label>
			<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ); ?>" class="regular-text" />

			<label for="last_name"><?php _e( 'Last Name' ); ?></label>
			<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ); ?>" class="regular-text" />


			<label for="email"><?php _e( 'E-mail' ); ?> <span class="description"></span></label>
			<input type="text" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ); ?>" class="regular-text" />

			<input type="hidden" name="nickname" id="nickname" value="<?php echo esc_attr( $profileuser->user_email ); ?>" class="regular-text" />


			<hr>

			<?php
			$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
			if ( $show_password_fields ) :
			?>

			<label for="pass1">New Password</label>
			<input type="text" name="pass1" id="pass1" size="16" value="" autocomplete="off" />

			<label for="pass2">Retype Password</label>
			<input type="text" name="pass2" id="pass2" size="16" value="" autocomplete="off" />

				<div id="pass-strength-result"><?php _e( 'Strength indicator', 'theme-my-login' ); ?></div>
				<p class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).' ); ?></p>

			<?php endif; ?>

			<div class="submit">
				<input type="hidden" name="action" value="profile" />
				<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
				<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Profile' ); ?>" name="submit" />
			</div>
		</form>
	</div>

	<div class="col span_8">
		<h1>Editing Your Profile</h1>
		<p class="secondary">Editing this information will change how your information is displayed on the following brands:</p>
		<hr />
		<?php user_brand_list($current_user->ID); ?>
	</div>
</div>
