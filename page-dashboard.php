<?php
/*  Template Name: Account: Dashboard  */

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

						<?php $user_id = get_current_user_id(); ?>

						<div id="pmpro_account-membership" class="pmpro_box row">
						    <div class="col span_3">
						      <a href="/membership-account"><?php user_profile_image($current_user->id, 100); ?></a>
						    </div>

						    <div class="col span_15 top-padding dashboard-hello">
						      <?php $user_info = get_userdata($user_id);?>
						      <?php if( $user_info->first_name ) { ?>
						      	<p>Hey, <?php echo $user_info->first_name; ?>! Let's build some brands.</p>
						      <?php } else { ?>
								<p>Hello, Let's build some brands.</p>
						      <?php } ?>
						      <p class="secondary lighten">Learn: <a class="secondary" href="#">Understanding file formats...</a></p>
						    </div>

							<div class="col span_6">
						     	<?php $creation_count = admin_blog_count($user_id);
								global $creation_limit;

								if ($creation_limit > $creation_count) { ?>
									<a class="button button-primary button-block" href="/create-brand">Create New Brand</a>
								<?php } else {
									echo "<a class='button button-block button-secondary' href='/membership-account/membership-levels/'>Upgrade Your Account</a>
										<p class='tiny right italic ligthen'>Looks like your plan doesn't have any brand credits";
								} ?>
							</div>
						</div>


						<header class="bar-nav dash-nav">
							<div class="row">
								<ul class="menu centered">
									<li class="menu-item current-menu-item"><a href="#all" id="all">All Brands</a></li>
									<li class="menu-item"><a href="#own">I Own</a></li>
									<li class="menu-item"><a href="#edit">I Can Edit</a></li>
									<li class="menu-item"><a href="#view">I Can View</a></li>
									<li class="menu-item"><a href="#archived">Archived</a></li>
								</ul>
							 </div>
						</header>

						<?php if(pmpro_hasMembershipLevel(array(1,2,3))) { ?>
							<?php $role = 'administrator';?>
							<?php $user_id = get_current_user_id(); ?>
							<?php $sites = get_blogs_of_user_by_role($user_id, $role); ?>
							<?php if($sites) { ?>
								<div class="row covers own" id="own">
									<p class="secondary row-border-bottom">Brands I Own <span class="lighten">(<?php echo admin_blog_count($user_id); ?>/<?php echo $creation_limit; ?>)</span></p>
									<?php foreach ($sites as $site) { ?>
										<?php brand_cover($site, $role); ?>
									<?php } ?>
								</div>
							<?php } else { ?>
								<div class="row covers own" id="own">
									<div class="covers dashboard-blank_slate blank_slate">
										<h2>Let's Get Started! Create A Brand Above.</h2>
										<p class="quote">&ldquo;The secret of getting ahead is getting started.<br />The secret of getting started is breaking your complex tasks into small manageable tasks, and then just starting on the first one.&rdquo;</p>
										<p class="citation">- Mark Twain, American Humorist & Writer</p>
									</div>
								</div>
							<?php } ?>
						<?php } ?>

							<?php $role = 'editor';?>
							<?php $sites = get_blogs_of_user_by_role($user_id, $role); ?>
							<?php if($sites) { ?>
								<div class="row covers top-padding edit" id="edit">
									<p class="secondary row-border-bottom">I Can Edit</p>
										<?php foreach ($sites as $site) {?>
											<?php brand_cover($site, $role); ?>
										<?php } ?>
								</div>
							<?php } else { ?>
							<?php } ?>


							<?php $role = 'subscriber';?>
							<?php $sites = get_blogs_of_user_by_role($user_id, $role); ?>
							<?php if($sites) { ?>
								<div class="row covers top-padding view" id="view">
									<p class="secondary row-border-bottom">I Can View</p>
										<?php foreach ($sites as $site) {?>
											<?php brand_cover($site, $role); ?>
										<?php } ?>
								</div>
							<?php } else { ?>
							<?php } ?>



						<?php if(pmpro_hasMembershipLevel(array(1,2,3))) { ?>
							<?php $role = 'administrator';?>
							<?php $sites = archived_brands($user_id, $role); ?>
							<?php if($sites) { ?>
								<div class="row covers top-padding archived archived" id="archived">
									<p class="secondary row-border-bottom">Archived</p>
										<?php foreach ($sites as $site) {?>
											<li><?php echo $site->blogname; ?>
												<?php if ($creation_limit > $creation_count) { ?>
													<?php $url = add_query_arg(array('action'=>'unarchive_brand', 'brand_id'=>$site->userblog_id)); ?>
														<a href="<?php echo $url; ?>" class="secondary restore">(restore)</a>
												<?php } ?>
											</li>
										<?php } ?>
								</div>
							<?php } else { ?>
							<?php } ?>
						<?php } ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
