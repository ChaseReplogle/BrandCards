<?php

/**
 * 1. Handles displaying a brand cover within the main admin dashboard.
 *
 * @param int    $site The ID of the current site in the loop.
 * @param sting  The role which is beeing check for this brand.
 *
 * @return php and html for the dashboard page
 */
function brand_cover($site, $role) { ?>

<div class="card <?php if($role != 'administrator') { echo 'brand-cover-not-admin';} ?>">

	<?php $original_blog_id = get_current_blog_id(); ?>

	<?php switch_to_blog($site->userblog_id); ?>

		<?php $args = array(
			'post_type' => 'brand_details',
			"posts_per_page" => 1
		);

		$details = get_posts( $args );

		if ($details) {

			foreach ( $details as $detail ) : ?>
				<?php $detail_ID = $detail->ID; ?>
				<a href="http://<?php echo $site->domain; ?>">

					<?php $cover_image = get_post_meta($detail->ID, 'cover_image', true); ?>
					<?php $cover_logo = get_post_meta($detail->ID, 'cover_logo', true); ?>

					<?php if ($cover_logo) { ?>

						<div class="brand-cover brand-cover-logo" style="background: #<?php echo get_post_meta($detail->ID, 'cover_color', true); ?>;">
							<div class="card-inner">
								<img id="image" src="<?php echo $cover_logo ?>" class="card-image" />
							</div>
						</div>

					<?php } elseif ($cover_image ) { ?>

						<div class="brand-cover">
							<img src="<?php echo $cover_image ?>" class="card-image" />
						</div>

					<?php }  else { ?>
						<div class="brand-cover brand-cover-logo" style="background-color: #dedede;">
							<div class="card-inner">
								<img id="image" src="<?php network_site_url(); ?>/wp-content/themes/brandcards/images/gray.svg"  class="card-image" />
							</div>
						</div>
					<?php } ?>
				</a>
			<?php endforeach; ?>

		<?php } else { ?>
			<a href="http://<?php echo $site->domain; ?>">
				<div class="brand-cover">
					<h2 class="default-title"><?php echo $site->blogname; ?></h2>
					<img src="<?php echo get_site_url(1); ?>/wp-content/themes/brandcards/images/default_cover.jpg" />
				</div>
			</a>

		<?php } ?>

	<?php switch_to_blog( $original_blog_id ); ?>

	<?php if($role == 'administrator') { ?>
		<?php echo ""; ?>

	<?php } else { ?>
		<div class="brand-cover-author">
			<?php $site_ID = $site->userblog_id;
			$admins = get_users( "blog_id=$site_ID&role=administrator" );

			foreach ( $admins as $admin ) { ?>
				<?php user_profile_image($admin->id, 40); ?>
				<p><?php echo $admin->display_name; ?></p>
			<?php } ?>
		</div>

	<?php } ?>

</div>

<?php }





/**
 * 2. Handles displaying a brand cover for invite login and registration.
 *
 * @param int    $site The ID of the current site in the loop.
 * @param sting  The role which is beeing check for this brand.
 *
 * @return php and html for the dashboard page
 */
function invite_brand_cover($site, $role) { ?>

<div class="card">

	<?php $original_blog_id = get_current_blog_id(); ?>

	<?php switch_to_blog($site); ?>

		<?php $args = array(
			'post_type' => 'brand_details',
			"posts_per_page" => 1
		);

		$details = get_posts( $args );

		if ($details) {

			foreach ( $details as $detail ) : ?>
				<?php $detail_ID = $detail->ID; ?>
				<a href="http://<?php echo $site->domain; ?>">

					<?php $cover_image = get_post_meta($detail->ID, 'cover_image', true); ?>
					<?php $cover_logo = get_post_meta($detail->ID, 'cover_logo', true); ?>

					<?php if ($cover_logo) { ?>

						<div class="brand-cover brand-cover-logo" style="background: #<?php echo get_post_meta($detail->ID, 'cover_color', true); ?>;">
							<div class="card-inner">
								<img id="image" src="<?php echo $cover_logo ?>" class="card-image" />
							</div>
						</div>

					<?php } elseif ($cover_image ) { ?>

						<div class="brand-cover">
							<img src="<?php echo $cover_image ?>" class="card-image" />
						</div>

					<?php }  else { ?>
						<div class="brand-cover brand-cover-logo" style="background-color: #dedede;">
							<div class="card-inner">
								<img id="image" src="<?php network_site_url(); ?>/wp-content/themes/brandcards/images/gray.svg"  class="card-image" />
							</div>
						</div>
					<?php } ?>
				</a>
			<?php endforeach; ?>

		<?php } else { ?>
			<a href="http://<?php echo $site->domain; ?>">
				<div class="brand-cover">
					<h2 class="default-title"><?php echo $site->blogname; ?></h2>
					<img src="<?php echo get_site_url(1); ?>/wp-content/themes/brandcards/images/default_cover.jpg" />
				</div>
			</a>

		<?php } ?>

		<div class="brand-cover-author">
			<?php $admins = get_users( "blog_id=$site&role=administrator" );

			foreach ( $admins as $admin ) { ?>
				<?php user_profile_image($admin->id, 40); ?>
				<p><?php echo $admin->display_name; ?></p>
			<?php } ?>
		</div>

	<?php switch_to_blog( $original_blog_id ); ?>



</div>

<?php }