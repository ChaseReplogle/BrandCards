<?php
/**
 * This function hooks into Paid Mebership Pro Checkout:
 *
 * @param int    $user_id The ID of the user.
 *
 * @return action   Hadles adding invited users to blog upon checkout.
 */
function my_pmpro_after_checkout($user_id)
{
	// It gets a hidden field variable for the invitiation blog id and role id that have been stored in the new users meta information.
	$invite_blog_id = get_user_meta($user_id, "invite_blog_id", true);
	$invite_user_role = get_user_meta($user_id, "invite_role", true);
	if ($invite_user_role == 1) {
    	$role = "editor";
	} else {
		$role = "subscriber";
	}

		// Checks to make sure there is an existing invitation.
		if (isset($invite_blog_id)) {
			// It then adds the current user to the invited blog and sets it's
	    	add_user_to_blog($invite_blog_id, $user_id, $role);

	    	// Finally, it deletes the stored meta info in order to keep things clean.
	    	update_user_meta( $user_id, "invite_blog_id", " ", $invite_blog_id );
	    	update_user_meta( $user_id, "invite_role", " ", $invite_user_role );
	    	$user_info = get_userdata($user_id);
	    	$invite_email = $user_info->user_email;
	    	  global $invite_email;
	    	  global $switched;
			    switch_to_blog($invite_blog_id);
			   		$invites = get_posts( array(
			   			'post_type'  => 'invites',
			   			'meta_key'   => 'invite_email',
			   			'meta_value' => $invite_email
			   		) );
			   		foreach ( $invites as $invite ) {
			   			wp_delete_post( $invite->ID, true);
			   		}
			    restore_current_blog();
    	}
}
add_action("pmpro_after_checkout", "my_pmpro_after_checkout");






/**
 * This function hooks into Paid Mebership Pro Checkout:
 *
 * @param int    $user_id The ID of the user.
 *
 * @return action   Hadles adding invited users to blog upon checkout.
 */
function invite_login($user_login) {

	if( isset($_GET['id']) ) {
		 $user = get_user_by('login', $user_login );

		$invite_blog_id = $_GET['id'];
		$user_id = $user->ID;
		$num_role = $_GET['role'];
		$role = "";
			if ($num_role == 1) {
	    		$role = "editor";
			} else {
				$role = "subscriber";
			}

		add_user_to_blog($invite_blog_id, $user_id, $role);

		$user_info = get_userdata($user_id);
	    $invite_email = $user_info->user_email;

		global $invite_email;
	    global $switched;
	    switch_to_blog($invite_blog_id);
	   		$invites = get_posts( array(
	   			'post_type'  => 'invites',
	   			'meta_key'   => 'invite_email',
	   			'meta_value' => $invite_email
	   		) );
	   		foreach ( $invites as $invite ) {
	   			wp_delete_post( $invite->ID, true);
	   		}
	    restore_current_blog();

	}

}
add_action('wp_login', 'invite_login');




/**
 * This function hooks into WordPress Load
 *
 * @param int    $user_id The ID of the user.
 *
 * @return action   Hadles adding invited users to blog if already logged in.
 */
function invite_already_login($user_login) {

	if( isset($_GET['role']) && is_user_logged_in()) {
		 $user = get_current_user_id();

		$invite_blog_id = $_GET['id'];
		$user_id = get_current_user_id();
		$num_role = $_GET['role'];
		$role = "";
			if ($num_role == 1) {
	    		$role = "editor";
			} else {
				$role = "subscriber";
			}

		add_user_to_blog($invite_blog_id, $user_id, $role);

		$user_info = get_userdata($user_id);
	    $invite_email = $user_info->user_email;

		global $invite_email;
	    global $switched;
	    switch_to_blog($invite_blog_id);
	   		$invites = get_posts( array(
	   			'post_type'  => 'invites',
	   			'meta_key'   => 'invite_email',
	   			'meta_value' => $invite_email
	   		) );
	   		foreach ( $invites as $invite ) {
	   			wp_delete_post( $invite->ID, true);
	   		}
	    restore_current_blog();

	}

}
add_action('wp', 'invite_already_login');