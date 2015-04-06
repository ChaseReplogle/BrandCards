<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package brandcards
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<?php if ( is_user_logged_in() ) {
		$user_id = get_current_user_id(); ?>
		<header class="main-nav main-nav-right marketing-nav no-print">
			<div class="container mobile-nav">
			 	<a href="<?php get_bloginfo('url') ?>/dashboard"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" width="150px" class="branding" /></a>
				<a href="#" class="mobile-menu-icon"><svg width="40px" height="40px" viewBox="0 0 240 200" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"><g id="Page 1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Menu" fill="#444444"><path d="M0,160 L0,200 L240,200 L240,160 L0,160 Z M0,160" id="Rectangle 3"></path><path d="M0,80 L0,120 L240,120 L240,80 L0,80 Z M0,80" id="Rectangle 2"></path><path d="M0,0 L0,40 L240,40 L240,0 L0,0 Z M0,0" id="Rectangle 1"></path></g></g></svg></a>
			</div>
			<div class="container wide-nav">
				<a href="<?php get_bloginfo('url') ?>/dashboard"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" width="150px" class="branding" /></a>
				<a href="/membership-account"><?php user_profile_image($user_id, 30); ?></a>
			 	<?php wp_nav_menu( array('menu' => 'Account Menu' )); ?>
			 </div>
		</header>
	<?php } ?>

	<div id="content" class="container">
