<?php
/**
 * By default a url with paramaters that are unknown get forwarded to the PMPro Checkout page. The function below handles adding the user to the brand as owner.
 *
 *
 */
function my_pmpro_after_checkout_transfer($user_id) {

	// It gets a hidden field variable for the transfer blog id and role id that have been stored in the new users meta information.
	$transfer = $_GET['action'];
	$transfer_id = $_GET['id'];
	$role = "administrator";

		// Checks to make sure there is an existing transfer.
		if (isset($transfer)) {
			// It then adds the current user to the transfering blog and sets it's role to admin.
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
 * The first condition statement checks to see if there is a transfer link, if the email exists as a user, and the user is not logged in...
 If all are true it redirects to the login page with the transfer ID for the transfer_login function.
 *
 *
 */
 if(isset($_GET['email'])) {
 	$email = $_GET['email'];
 }

if(isset($_GET['action']) && $_GET['action'] == 'transfer_ownership' && !is_user_logged_in() && email_exists( $email ) ) {
	$transfer_id = $_GET['id'];
	wp_redirect( '/login/?transfer_id='. $transfer_id  ); exit;
}


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
 * This function is run if a user is logged in and there is a transfer link.
 * It will also test to see if they current user has a plan with a brand slot available.
 * TODO: if the number available is not atleast one I need to add a redirect page that recommends transfering or upgrading your account.
 *
 */

	if(isset($_GET['action']) && $_GET['action'] == 'transfer_ownership' && is_user_logged_in() ) {

		$transfer_id = $_GET['id'];
		$user_id = get_current_user_id();
		$role = "administrator";


		  $creation_limit = '';
		  $brand_limit = 1;
		  $designer_limit = 5;
		  $agency_limit = 10;
		  $member_limit = 0;

		  if(pmpro_hasMembershipLevel(1)) {
		    $creation_limit = $brand_limit;

		  } elseif(pmpro_hasMembershipLevel(2)) {
		    $creation_limit = $designer_limit;

		  } elseif(pmpro_hasMembershipLevel(3)) {
		    $creation_limit = $agency_limit;

		  } elseif(pmpro_hasMembershipLevel(4)) {
		    $creation_limit = $member_limit;
		  }

		   // Sets role to admin.
		  $role = 'administrator';

		  // Get ALL blogs for a given user by their ID.
		  $blogs = get_blogs_of_user( $user_id, false );

		  // set up a counter
		  $count = 0;

		  // Set up foreach loop to go through the blogs.
		    foreach ( $blogs as $blog_id => $blog ) {

		      // Add 1 to the counter for each site
		      $count ++;

		      // Get the user object for the user for this blog.
		        $user = new WP_User( $user_id, '', $blog_id );

		        // Remove this blog from the list if the user doesn't have the role for it.
		        if ( ! in_array( $role, $user->roles ) ) {

		          // Subtract 1 for each site that is removed
		            --$count;
		        }

		    }

		    $creation_count = $count;

		if ($creation_limit > $creation_count) {

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
		} elseif ($creation_limit <= $creation_count) {
			wp_redirect( '/need-room/?transfer_id='. $transfer_id  ); exit;
		}

	}

