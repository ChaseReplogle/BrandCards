<?php
/**
 * This file adds additional functionality to the Paid Membership Pro plugin.
 *
 *
 *  1. my_init_email_as_username()
 *  2. pmprorh_sanitize_user()
 *  3. my_pmpro_gettext_membership()
 *
 */



// 1. This function auto places the email address as the username.
function my_init_email_as_username()
{
  //check for level as well to make sure we're on checkout page
  if(empty($_REQUEST['level']))
    return;

  if(!empty($_REQUEST['bemail']))
    $_REQUEST['username'] = $_REQUEST['bemail'];

  if(!empty($_POST['bemail']))
    $_POST['username'] = $_POST['bemail'];

  if(!empty($_GET['bemail']))
    $_GET['username'] = $_GET['bemail'];
}
add_action('init', 'my_init_email_as_username');




// 2. Allows symbols in email address to be accepted in the username field.
function pmprorh_sanitize_user( $username, $raw_username, $strict = false )
{
	//only check if there is a + in the raw username
	if(strpos($raw_username, "+") === false)
		return $username;

	//start over
	$username = $raw_username;

	$username = wp_strip_all_tags( $username );
	$username = remove_accents( $username );
	// Kill octets
	$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
	$username = preg_replace( '/&.+?;/', '', $username ); // Kill entities

	// If strict, reduce to ASCII for max portability.
	if ( $strict )
		$username = preg_replace( '|[^a-z0-9 _.\-@\+]|i', '', $username );		//added a + here

	$username = trim( $username );
	// Consolidate contiguous whitespace
	$username = preg_replace( '|\s+|', ' ', $username );

	return $username;
}
add_filter("sanitize_user", "pmprorh_sanitize_user", 1, 3);


// Don't require the email to be confirmed
add_filter('pmpro_checkout_confirm_email', '__return_false');





/*
 3. Change term "membership" to "subscription" for plugin generated text
*/
function change_translate_text( $translated ) {
	$text = array(
		'Membership' => 'Plan',
	);
	$translated = str_ireplace(  array_keys($text),  $text,  $translated );
	return $translated;
}
add_filter( 'gettext', 'change_translate_text', 20 );




/*
 4. Add Name to free registration
*/

function pmproan2c_pmpro_checkout_after_password()
{
$first_name = $_REQUEST['first_name'];
$last_name = $_REQUEST['last_name'];
?>
<div class="first_name_field">
<label for="first_name">First Name</label>
<input id="first_name" name="first_name" type="text" class="input pmpro_required" size="30" value="<?=$first_name?>" />
</div>
<div  class="last_name_field">
<label for="last_name">Last Name</label>
<input id="last_name" name="last_name" type="text" class="input pmpro_required" size="30" value="<?=$last_name?>" />
</div>
<?php
}
add_action('pmpro_checkout_after_username', 'pmproan2c_pmpro_checkout_after_password');
//require the fields
function pmproan2c_pmpro_registration_checks()
{
global $pmpro_msg, $pmpro_msgt, $current_user;
if(!empty($_REQUEST['first_name']))
$first_name = $_REQUEST['first_name'];
elseif(!empty($_SESSION['first_name']))
$first_name = $_SESSION['first_name'];
else
$first_name = "";
if(!empty($_REQUEST['last_name']))
$last_name = $_REQUEST['last_name'];
elseif(!empty($_SESSION['last_name']))
$last_name = $_SESSION['last_name'];
else
$last_name = "";
if($first_name && $last_name || $current_user->ID)
{
//all good
return true;
}
else
{
$pmpro_msg = "The first and last name fields are required.";
$pmpro_msgt = "pmpro_error";
return false;
}
}
add_filter("pmpro_registration_checks", "pmproan2c_pmpro_registration_checks");
//update the user after checkout
function pmproan2c_update_first_and_last_name_after_checkout($user_id)
{
if(!empty($_REQUEST['first_name']))
$first_name = $_REQUEST['first_name'];
elseif(!empty($_SESSION['first_name']))
$first_name = $_SESSION['first_name'];
else
$first_name = "";
if(!empty($_REQUEST['last_name']))
$last_name = $_REQUEST['last_name'];
elseif(!empty($_SESSION['last_name']))
$last_name = $_SESSION['last_name'];
else
$last_name = "";
update_user_meta($user_id, "first_name", $first_name);
update_user_meta($user_id, "last_name", $last_name);
}
add_action('pmpro_after_checkout', 'pmproan2c_update_first_and_last_name_after_checkout');
function pmproan2c_pmpro_paypalexpress_session_vars()
{
//save our added fields in session while the user goes off to PayPal
$_SESSION['first_name'] = $_REQUEST['first_name'];
$_SESSION['last_name'] = $_REQUEST['last_name'];
}
add_action("pmpro_paypalexpress_session_vars", "pmproan2c_pmpro_paypalexpress_session_vars");
/*
Function to add links to the plugin row meta
*/
function pmproan2c_plugin_row_meta($links, $file) {
if(strpos($file, 'pmpro-add-name-to-checkout.php') !== false)
{
$new_links = array(
'<a href="' . esc_url('http://paidmembershipspro.com/support/') . '" title="' . esc_attr( __( 'Visit Customer Support Forum', 'pmpro' ) ) . '">' . __( 'Support', 'pmpro' ) . '</a>',
);
$links = array_merge($links, $new_links);
}
return $links;
}
add_filter('plugin_row_meta', 'pmproan2c_plugin_row_meta', 10, 2);





/*
 5. Redirect everyone except for super admin to the dashboard
*/
add_action( 'admin_init', 'custom_wpadmin_blockusers_init' );
function custom_wpadmin_blockusers_init() {
  if ( !current_user_can( 'manage_network' ) ) {
    wp_redirect( home_url() );
    exit;
  }
}