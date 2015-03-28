<?php
/*  Template Name: Account: Profile Image  */

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

					<header class="bar-nav">
						<div class="row">
							<?php wp_nav_menu( array('menu' => 'Account Inner Menu' )); ?>
						 </div>
					</header>

					<div class="row gutters">
						<div class="col span_16 avatar_upload large-gutter">
							<form action="" method="POST" enctype="multipart/form-data">
							    <input type="file" name="file">
							    <input type="submit" name="sub" value="Upload Image">
							</form>
					 	</div>
					 	<div class="col span_8">
							<h1>Profile Image</h1>
							<p class="secondary">This photo is used to idetnify you across all brands in which you participate.</p>
							<?php
							// $current_user = wp_get_current_user();
							// user_brand_list($current_user->ID); ?>
						</div>
					</div>

				<?php } else { ?>

					<h2>Error Message: You can't view this page with your account </h2>

				<?php } ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
