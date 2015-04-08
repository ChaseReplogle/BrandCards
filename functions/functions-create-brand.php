<?php
/**
 * This file handles all functions for allowing users to create new blogs.
 *
 *
 *  1. create_brand_form()
 *  2. create_brand_form_processor()
 *
 */



/**
 * 1. This function adds the brand creation form to the template file.
 * @param Template File: page-create_brand.php
 *
 */
function create_brand_form($user_id) { ?>

<!--!!! Need to add a condition here that makes sure the current user has avaiable credits to create this site.
        They shouldn't be able to find this page, but incase they do, they should get a message not the form. -->

        <form action="" id="primaryPostForm" method="POST">

        <div class="row create-brand">
            <div class="col span_24">
                <!-- Name of the Brand Site -->
                <label for="brandName"><?php _e('Brand Name', 'framework') ?></label>
                <input type="text" name="brandName" id="brandName" class="required"  value="<?php if ( isset( $_POST['brandName'] ) ) echo $_POST['brandName']; ?>"/>
            </div>

            <div class="col span_8">
                <!-- URL Subdomain name. example.brandcards.com -->
                <label for="url"><?php _e('URL Subdomain', 'framework') ?></label>
                <input type="text" name="url" id="url" class="required" value="<?php if ( isset( $_POST['url'] ) ) echo $_POST['url']; ?>"/>
            </div>
            <div class="col span_16">
                <span class="url_extention">.brandcards.com</span>
            </div>
            <div class="col span_24">
                <p class="secondary"><span class="description">The subdomain name you provide will create the url associated with your brand. Do not inlude "http://" or "www."</span></p>
                <hr />
            </div>


            <div class="col span_24">
                <input type="hidden" name="createBrand" id="submitted" value="true" />
                <?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
                <button type="submit" class="button-primary brand-create-submit"><?php _e('Create Brand', 'framework') ?></button>
            </div>
        </div>

        </form>

    <?php }


/**
 * 2. This function process the brand creation form and redirects to the brand details page if sucessful.
 * @param Template File: page-create_brand.php
 *
 * IMPORTANT: In order for the redirect at the end of this function to work,
 * This function must be placed before the WP_Head in the templates get_header();
 *
 */
function create_brand_form_processor() {

    /** The variable and if statement below runs a basic validation to ensure that
    *   the url subdomain field has been filled in. If not it throws an error saying
    *   that a url is required.
    *
    *   The php validation also checks for the Wordpress nonce field.
    *
    *   We are also using jquery validation. This validation is really just a backup.
    */
    $postURLError = '';

    if ( isset( $_POST['createBrand'] ) && trim( $_POST['url'] ) === ''  && isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

        // Set up error message
        $postURLError = 'A URL is required for creating a brand.';
        $hasError = true;

        // post error message
        if ( $postURLError != '' ) { ?>
           <div id="message" class="error"><p><?php echo $postURLError; ?></p></div>
        </div><?php }
    }

    // If the post has been set and url has a value then run the processing functions below.
    elseif ( isset( $_POST['createBrand'] ) && trim( $_POST['url'] ) != '' ) {

        // Gets the main url for the site
        $basedomain = get_site_url(1);
        // Strips that domain of the http://
        $basedomain = str_replace ('http://', '', $basedomain);

        // Lowercases all letters and removes any character other than letters.
        $domain = wp_strip_all_tags( $_POST['url'] );
        $domain = strtolower($domain);
        $domain = preg_replace("/[^a-z]/", "", $domain);

        // Adds the submitted subdomain to the main url
        $domain = $domain . '.' . $basedomain;

        // Created at the root level (No Sub-directory)
        $path = wp_strip_all_tags( $_POST['/'] );

        // Gets the title of the brand site
        $title = wp_strip_all_tags( $_POST['brandName'] );

        // Gets the current users ID
        $user_id = get_current_user_id();



        // Creates the new Brand site and stores the response in $result
        $meta['public'] = 1;
        $result = wpmu_create_blog($domain, $path, $title, $user_id, $meta, 1);

        // Tests the returned $result object for errors and displays them
        if ( is_wp_error( $result ) ) {
           $error_string = $result->get_error_message();
           echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
        }

        // If no errors are found.
        // The user is redirected to the details form on the site they just created.
        else {
            $new_blog = get_site_url($result, '/details');

            wp_redirect($new_blog);
            die();
        }
    }
}