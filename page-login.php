<?php
/*  Template Name: Account: Login  */

/**
 * This template for displaying all pages.
 *
 * This template contains a custom built login form used on the marketing site.
 * It should be used for all logins.
 * Upon login, the user is reidrected to the dashboard.
 *
 * @package brandcards
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<div class="login-form">
					<img src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" width="100%" class="branding" />
					<?php get_template_part( 'content', 'page' ); ?>
				</div>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
