<?php
/**
 * brandcards functions and definitions
 *
 * @package brandcards
 */


if ( ! function_exists( 'brandcards_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function brandcards_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Marketing Menu', 'brandcards' ),
		'account' => __( 'Account Menu', 'brandcards' ),
		'account_inner' => __( 'Account Inner Menu', 'brandcards' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

}
endif; // brandcards_setup
add_action( 'after_setup_theme', 'brandcards_setup' );


/**
 * Hide Admin Bar.
 */
add_filter('show_admin_bar', '__return_false');


/**
 * Enqueue scripts and styles.
 */
function brandcards_scripts() {
	wp_enqueue_style( 'brandcards-style', get_stylesheet_uri() );
	wp_enqueue_style( 'brandcards-style-main', get_template_directory_uri() . '/css/main.css' );
	wp_enqueue_style( 'open-sans-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' );
	wp_enqueue_style( 'merriweather-font', 'https://fonts.googleapis.com/css?family=Merriweather:400,300,700' );


	wp_enqueue_script( 'brandcards-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), '20120206', true );
	wp_enqueue_script( 'brandcards-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'brandcards-image-upload', get_template_directory_uri() . '/js/image-upload.js', array(), '20120206', true );
	wp_enqueue_script( 'brandcards-card', get_template_directory_uri() . '/js/card.js', array(), '20120206', true );
	wp_enqueue_script( 'brandcards-form', get_template_directory_uri() . '/js/forms.js', array(), '20120206', true );


}
add_action( 'wp_enqueue_scripts', 'brandcards_scripts' );


/**
 * Custom functions that handel base customization of Paid Membership Pro Plugin.
 */
require get_template_directory() . '/paid-memberships-pro/pmpro-functions.php';


/**
 * Custom functions for front end forms.
 */
require get_template_directory() . '/functions/functions-create-brand.php';

/**
 * Invite User Processing Function
 */
require get_template_directory() . '/functions/functions-invite.php';

/**
 * Handles Transfer of Ownership on Brands
 */
require get_template_directory() . '/functions/functions-transfer.php';


/**
 * Handles Changing Avatar
 */
require get_template_directory() . '/functions/functions-upload-avatar.php';




/**
 * Custom Classes
 *
 *
 *
 *
 * Account Class that extends WP_User
 */
require get_template_directory() . '/classes/class-account.php';
require get_template_directory() . '/classes/class-brand.php';



