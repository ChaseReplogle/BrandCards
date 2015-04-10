<?php
/**
 * This file handles functionality related to the users account.
 *
 *
 *	1. $creation_limit
 *	2. get_blogs_of_user_by_role()
 *	3. admin_blog_count()
 *  4. user_brand_list()
 *	5. remove_user_fields()
 *  6. user_profile_image()
 *  7. account_header()
 *  8. archived_brands()
 *
 */



/**
* 1. Set some basic variables to be used for controling membership limits.
*
* @param creates the $creation_limit variable that is used in functions below.
* If I ever need to change the number of sites allowed this should be the only place to make that change.
*
*/

wp_get_current_user();

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



/**
 * 2. Get the blogs of a user where they have a given role.
 *
 * @param int    $user_id The ID of the user.
 * @param string $role    The slug of the role.
 *
 * @return object[] The blog details for each blog the user has the role for.
 */
function get_blogs_of_user_by_role( $user_id, $role ) {

	// Get ALL blogs for a given user by their ID.
    $blogs = get_blogs_of_user( $user_id );

    // Set up foreach loop to go through the blogs.
    foreach ( $blogs as $blog_id => $blog ) {

        // Get the user object for the user for this blog.
        $user = new WP_User( $user_id, '', $blog_id );

        // Remove this blog from the list if the user doesn't have the role for it.
        if ( ! in_array( $role, $user->roles ) ) {
            unset( $blogs[ $blog_id ] );
        }

        // This removes the main BrandCards site which every member automatically becomes a subsriber of.
        if ($blog_id == 1) {
        	unset($blogs[ $blog_id ] );
        }

        // Remove the blog if it is archived
        $blog_details = get_blog_details($blog_id);

        if ($blog_details->archived == 1) {
          unset($blogs[ $blog_id ] );
        }
    }

    return $blogs;
}




/**
 * 3. Count the number of brand sites that a user is the admin.
 *
 * @param int    $user_id The ID of the user.
 *
 * @return number total number of sites .
 */
function admin_blog_count($user_id) {

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
    return $creation_count;

}



/**
 * 4. Create an unordered list of the users brands.
 *
 * @param int    $user_id The ID of the user.
 *
 * @return html of the ul list
 */
function user_brand_list($user_id) {

  // Sets role to admin.
  $role = 'administrator';

  // Get ALL blogs for a given user by their ID.
  $blogs = get_blogs_of_user( $user_id ); ?>

  <ul class="no-bullets">

  <?php // Set up foreach loop to go through the blogs.
    foreach ( $blogs as $blog_id => $blog ) { ?>

        <?php // Remove the BrandCards from the list.
        if ($blog_id == 1) {
          continue;
        } ?>

        <li><?php print_r($blog->blogname); ?></li>

    <?php } ?>

  </ul>

<?php }







/**
 * 5. This funciton and hook adds some jquery to the footer that controls
 * what is displayed on the edit profile page.
 *
 *
 */
// Hook into the admin footer
add_action( 'wp_footer', 'remove_user_fields' );

// Print jQuery that removes unneeded elements
function remove_user_fields(){
  ?>
  <script type="text/javascript">
    jQuery(document).ready( function($) {
      var ids = ['#rich_editing', // Rich editing button
                 '#admin_color_classic', // Admin color scheme
                 '#comment_shortcuts', // Keyboard shortcuts for comment moderation
                 '#aim', // AOL instant messenger
                 '#yim', // Yahoo messenger
                 '#jabber', // Jabber
                 '#nickname',
                 '#display_name',
                 '#user_login',
                 '#simple-local-avatar-ratings']; // User bio
      for (var i = 0; i < ids.length; i++) {
        $(ids[i]).closest('tr').remove();
      }
    });
  </script>
  <?php
}






/**
 * 6. Checks for a profile image and returns image or default.
 *
 *
 */
function user_profile_image($user_id, $size) {

  echo get_avatar( $user_id, $size, $default, $alt );
}







/**
 * 7. Account Header that can be used on account page.
 *
 *
 */

function account_header() {

  global $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $levels; ?>

  <div id="pmpro_account-membership" class="pmpro_box row no-print">
    <div class="col span_3">
      <a href="/membership-account"><?php user_profile_image($current_user->id, 100); ?></a>
    </div>
    <div class="col span_21">
      <h1>My Account</h1>
      <p class="status status-active"><?php _e("<strong>Your plan is currently active</strong>.", "pmpro");?></p>
      <p class="secondary"><?php _e("Plan", "pmpro");?>: <?php echo $current_user->membership_level->name?>
        <?php if($current_user->membership_level->billing_amount > 0) { ?>
           |
          <?php _e("Cost", "pmpro");?>:
          <?php
            $level = $current_user->membership_level;
            if($current_user->membership_level->cycle_number > 1) {
              printf(__('%s every %d %s', 'pmpro'), pmpro_formatPrice($level->billing_amount), $level->cycle_number, pmpro_translate_billing_period($level->cycle_period, $level->cycle_number));
            } elseif($current_user->membership_level->cycle_number == 1) {
              printf(__('%s / %s', 'pmpro'), pmpro_formatPrice($level->billing_amount), pmpro_translate_billing_period($level->cycle_period));
            } else {
              echo pmpro_formatPrice($current_user->membership_level->billing_amount);
            }
          ?>
          </p>
      <?php } ?>

      <?php if($current_user->membership_level->billing_limit) { ?>
        <p class="secondary status status-warning"><?php _e("Duration", "pmpro");?>: <?php echo $current_user->membership_level->billing_limit.' '.sornot($current_user->membership_level->cycle_period,$current_user->membership_level->billing_limit)?></p>
      <?php } ?>

      <?php if($current_user->membership_level->enddate) { ?>
        <p class="secondary status status-warning"><?php _e("Membership Expires", "pmpro");?>: <?php echo date_i18n(get_option('date_format'), $current_user->membership_level->enddate)?></p>
      <?php } ?>

      <?php if($current_user->membership_level->trial_limit == 1)
      {
        printf(__("Your first payment will cost %s.", "pmpro"), pmpro_formatPrice($current_user->membership_level->trial_amount));
      }
      elseif(!empty($current_user->membership_level->trial_limit))
      {
        printf(__("Your first %d payments will cost %s.", "pmpro"), $current_user->membership_level->trial_limit, pmpro_formatPrice($current_user->membership_level->trial_amount));
      }
      ?>
    </div>

  </div> <!-- end pmpro_account-membership -->

<?php }





/**
 * 8. Get list of archived blogs.
 *
 * @param int    $user_id The ID of the user.
 * @param string $role    The slug of the role.
 *
 * @return object[] The blog details for each blog the user has the role for.
 */

function archived_brands( $user_id, $role ) {

  // Get ALL blogs for a given user by their ID.
    $blogs = get_blogs_of_user( $user_id, true );

    // Set up foreach loop to go through the blogs.
    foreach ( $blogs as $blog_id => $blog ) {


        // Get the user object for the user for this blog.
        $user = new WP_User( $user_id, '', $blog_id );

        // Remove this blog from the list if the user doesn't have the role for it.
        if ( ! in_array( $role, $user->roles ) ) {
            unset( $blogs[ $blog_id ] );
        }


        // Remove the blog if it isn't archived
        $blog_details = get_blog_details($blog_id);

        if ($blog_details->archived == 0) {
          unset($blogs[ $blog_id ] );
        }
    }

    return $blogs;
}






/**
 * 9. Unarchive Brand Link
 *
 *
 *
 *
 */

if(isset($_GET['action']) && $_GET['action']=='unarchive_brand') {
    add_action('init','unarchive_brand');
}

function unarchive_brand(){

    $brand_ID = $_GET['brand_id'];
    $archived = 0;

    update_archived( $brand_ID, $archived );
    $main_site = get_site_url(1);
    wp_redirect( $main_site . "/dashboard"); exit;
}






/**
 * 10. Check User Status on Invite
 *
 *
 *
 *
 */

if(isset($_GET['action']) && $_GET['action']=='check_user_status') {
    add_action('init','check_user_status');
}

function check_user_status(){

    $id = $_GET['id'];
    $role = $_GET['role'];
    $email = $_GET['email'];
    $decode_email = urldecode($_GET['email']);

    $exists = email_exists($decode_email);
    if ( $exists ) {
       wp_redirect( home_url() . "/login/?level=4&role=$role&id=$id" ); exit;
    } else {
       wp_redirect( home_url() . "/membership-account/membership-checkout/?level=4&role=$role&id=$id" ); exit;
    }
}





/**
 * 11. Check User Status on Transfer of Ownership
 *
 *
 *
 *
 */

if(isset($_GET['action']) && $_GET['action']=='transfer_ownership') {
    add_action('init','check_user_status_transfer');
}

function check_user_status_transfer(){

    $id = $_GET['id'];
    $email = $_GET['email'];
    $decode_email = urldecode($_GET['email']);

    $exists = email_exists($decode_email);
    $user = get_user_by( 'email', $exists );

    if(pmpro_hasMembershipLevel(array(1,2,3), $user->ID) ) {
       wp_redirect( home_url() . "/login/?working=&transfer_id=$id" ); exit;
    } else {
       wp_redirect( home_url() . "/membership-account/membership-checkout/?level=1&transfer_id=$id" ); exit;
    }
}
