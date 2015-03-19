<?php
/*  Template Name: Form: Create Brand  */

/**
 * This template provides the form which allows users with a membership and
 * open credits to create to create a new brand.
 *
 * Upon successful creation of the brand, the user is redirected to the brand site.
 *
 * @package brandcards
 */

// function handles redirects upon form completition
create_brand_form_processor();

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php if(pmpro_hasMembershipLevel(array(1, 2, 3))) { ?>

					<div class="row">

						<div class="col span_20">
							<h1>Create A New Brand Site</h1>
						</div>

						<div class="col span_4 right">
							<p class="secondary">Current Brands: <?php echo admin_blog_count($user_id); ?>/<?php echo $creation_limit; ?></p>
						</div>
					</div>

					<hr />

					<?php create_brand_form(); ?>

				<?php } else { ?>

					<h2>Error Message: You can't view this page with your account </h2>

				<?php } ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
