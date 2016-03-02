<?php
/**
 * This function hooks into Paid Mebership Pro Checkout:
 *
 * @param int    $user_id The ID of the user.
 *
 * @return action   Hadles adding invited users to blog upon checkout.
 */
function my_pmpro_after_checkout_transfer($user_id)
{

	// It gets a hidden field variable for the invitiation blog id and role id that have been stored in the new users meta information.
	$transfer = $_GET['action'];
	$transfer_id = get_user_meta($user_id, "transfer_id", true);
	$role = "administrator";

		// Checks to make sure there is an existing invitation.
		if (isset($transfer)) {
			// It then adds the current user to the invited blog and sets it's
	    	add_user_to_blog($transfer_id, $user_id, $role);

	    	// Finally, it deletes the stored meta info in order to keep things clean.
	    	update_user_meta( $user_id, "transfer_id", " ", $transfer_id );

	    	$user_info = get_userdata($user_id);
	    	$transfer_email = $user_info->user_email;
	    	  global $transfer_email;
	    	  global $switched;
			    switch_to_blog($transfer_id);
			   		$transfers = get_posts( array(
			   			'post_type'  => 'transfers',
			   			'meta_key'   => 'transfer_email',
			   			'meta_value' => $transfer_email
			   		) );
			   		foreach ( $transfers as $transfer ) {
			   			wp_delete_post( $transfer->ID, true);
			   		}

					$blog_id = get_current_blog_id();
					$args = array(
						'blog_id'      => $blog_id,
						'role'         => 'administrator',
				 	);

					$admins = get_users( $args );

					foreach ( $admins as $admin ) {
						if($admin->ID == $user_id ) {
							} else {
								 wp_update_user( array( 'ID' => $admin->ID, 'role' => 'editor' ) );
							}
					}

			    restore_current_blog();

    	}
}
add_action("pmpro_after_checkout", "my_pmpro_after_checkout_transfer");






/**
 * This function hooks into Paid Mebership Pro Checkout:
 *
 * @param int    $user_id The ID of the user.
 *
 * @return action   Hadles adding invited users to blog upon checkout.
 */
function transfer_login($user_login) {

	if( isset($_GET['transfer_id']) ) {
		 $user = get_user_by('login', $user_login );

		$transfer_id = $_GET['transfer_id'];
		$user_id = $user->ID;
		$role = "administrator";

		add_user_to_blog($transfer_id, $user_id, $role);

		$user_info = get_userdata($user_id);
	    	$transfer_email = $user_info->user_email;
	    	  global $transfer_email;
	    	  global $switched;
			    switch_to_blog($transfer_id);
			   		$transfers = get_posts( array(
			   			'post_type'  => 'transfers',
			   			'meta_key'   => 'transfer_email',
			   			'meta_value' => $transfer_email
			   		) );
			   		foreach ( $transfers as $transfer ) {
			   			wp_delete_post( $transfer->ID, true);
			   		}

					$args = array(
						'blog_id'      => $transfer_id,
						'role'         => 'administrator',
				 	);

					$admins = get_users( $args );

					foreach ( $admins as $admin ) {
						if($admin->ID == $user_id ) {
							} else {
								 wp_update_user( array( 'ID' => $admin->ID, 'role' => 'editor' ) );
							}
					}

			    restore_current_blog();

	}

}
add_action('wp_login', 'transfer_login');




/**
 * This function hooks into Paid Mebership Pro Checkout:
 *
 * @param int    $user_id The ID of the user.
 *
 * @return action   Hadles adding invited users to blog upon checkout.
 */
function transfer_logged_in() {



	if(isset($_GET['action']) && $_GET['action'] == 'transfer_ownership' && is_user_logged_in() ) {

		$transfer_id = $_GET['id'];
		$user_id = get_current_user_id();
		$role = "administrator";

		add_user_to_blog($transfer_id, $user_id, $role);

		$user_info = get_userdata($user_id);
	    	$transfer_email = $user_info->user_email;
	    	  global $transfer_email;
	    	  global $switched;
			    switch_to_blog($transfer_id);
			   		$transfers = get_posts( array(
			   			'post_type'  => 'transfers',
			   			'meta_key'   => 'transfer_email',
			   			'meta_value' => $transfer_email
			   		) );
			   		foreach ( $transfers as $transfer ) {
			   			wp_delete_post( $transfer->ID, true);
			   		}

					$args = array(
						'blog_id'      => $transfer_id,
						'role'         => 'administrator',
				 	);

					$admins = get_users( $args );

					foreach ( $admins as $admin ) {
						if($admin->ID == $user_id ) {
							} else {
								 wp_update_user( array( 'ID' => $admin->ID, 'role' => 'editor' ) );
							}
					}

			    restore_current_blog();

	}

}
add_action('wp', 'transfer_logged_in');

