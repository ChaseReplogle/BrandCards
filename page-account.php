<?php
/*  Template Name: Account: Default  */

/**
 * This template is used as the main dashboard for the app.
 *
 * The dashboard displays all brands that a user has access to.
 *
 * @package brandcards
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php if(pmpro_hasMembershipLevel(array(1, 2, 3, 4))) { ?>

					<?php account_header(); ?>

					<header class="bar-nav no-print">
						<div class="row">
							<?php wp_nav_menu( array('menu' => 'Account Inner Menu' )); ?>
						 </div>
					</header>

					<?php get_template_part( 'content', 'page' ); ?>

				<?php } else { ?>

					<h2>Error Message: You can't view this page with your account </h2>

				<?php } ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
