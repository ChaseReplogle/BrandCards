<?php
global $current_user;
 $user_id = get_current_user_id();
$user_last = get_user_meta($user_id);
$unserialize = unserialize($user_last['simple_local_avatar'][0]);
class Simple_Local_Avatarssss {
    private $user_id_being_edited, $avatar_upload_error, $remove_nonce, $avatar_ratings;
    public $options;
    public function unique_filename_callback( $dir, $name, $ext ) {
        $user = get_user_by( 'id', (int) $this->user_id_being_edited );
        $name = $base_name = sanitize_file_name( $user->display_name . '_avatar_' . time() );
        // ensure no conflicts with existing file names
        $number = 1;
        while ( file_exists( $dir . "/$name$ext" ) ) {
            $name = $base_name . '_' . $number;
            $number++;
        }

        return $name . $ext;
    }
    private function assign_new_user_avatar( $url_or_media_id, $user_id ) {
        // delete the old avatar
        $this->avatar_delete( $user_id );   // delete old images if successful
        $meta_value = array();
        // set the new avatar
        if ( is_int( $url_or_media_id ) ) {
            $meta_value['media_id'] = $url_or_media_id;
            $url_or_media_id = wp_get_attachment_url( $url_or_media_id );
        }
        $meta_value['full'] = $url_or_media_id;
        update_user_meta( $user_id, 'simple_local_avatar', $meta_value );   // save user information (overwriting old)
    }

    public function avatar_delete( $user_id ) {
        $old_avatars = (array) get_user_meta( $user_id, 'simple_local_avatar', true );
        if ( empty( $old_avatars ) )
            return;
        // if it was uploaded media, don't erase the full size or try to erase an the ID
        if ( array_key_exists( 'media_id', $old_avatars ) )
            unset( $old_avatars['media_id'], $old_avatars['full'] );
        if ( ! empty( $old_avatars ) ) {
            $upload_path = wp_upload_dir();
            foreach ($old_avatars as $old_avatar ) {
                // derive the path for the file based on the upload directory
                $old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
                if ( file_exists( $old_avatar_path ) )
                    unlink( $old_avatar_path );
            }
        }

        delete_user_meta( $user_id, 'simple_local_avatar' );
        delete_user_meta( $user_id, 'simple_local_avatar_rating' );
    }

    public function edit_user_profile_update( $file , $user_id ) {
                $_FILES['simple-local-avatar'] = $file['file'];
        // check for uploaded files
        if ( ! empty( $_FILES['simple-local-avatar']['name'] ) ) :
            // front end (theme my profile etc) support
            if ( ! function_exists( 'wp_handle_upload' ) )
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
            $this->user_id_being_edited = $user_id; // make user_id known to unique_filename_callback function
            $avatar = wp_handle_upload( $_FILES['simple-local-avatar'], array(
                'mimes'                     => array(
                    'jpg|jpeg|jpe'  => 'image/jpeg',
                    'gif'           => 'image/gif',
                    'png'           => 'image/png',
                ),
                'test_form'                 => false,
                'unique_filename_callback'  => array( $this, 'unique_filename_callback' )
            ) );
            if ( empty($avatar['file']) ) {     // handle failures
                switch ( $avatar['error'] ) {
                    case 'File type does not meet security guidelines. Try another.' :
                        $this->avatar_upload_error = __('Please upload a valid image file for the avatar.','simple-local-avatars');
                        break;
                    default :
                        $this->avatar_upload_error = '<strong>' . __('There was an error uploading the avatar:','simple-local-avatars') . '</strong> ' . esc_html( $avatar['error'] );
                }

                return;
            }
            $this->assign_new_user_avatar( $avatar['url'], $user_id );
        endif;
        // handle rating
        if ( isset( $avatar['url'] ) || $avatar = get_user_meta( $user_id, 'simple_local_avatar', true ) ) {
            if ( empty( $_POST['simple_local_avatar_rating'] ) || ! array_key_exists( $_POST['simple_local_avatar_rating'], $this->avatar_ratings ) )
            //  $_POST['simple_local_avatar_rating'] = key( $this->avatar_ratings );
                                update_user_meta( $user_id, 'simple_local_avatar_rating', $_POST['simple_local_avatar_rating'] );
                                return 1;
        }
    }
}

if(isset($_POST['sub']))
{
    $fname=$_FILES['file']['name'];
    $ftype=$_FILES['file']['type'];
}
if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])     ){
    $simple_local_avatars = new Simple_Local_Avatarssss;
    $result = $simple_local_avatars->edit_user_profile_update($_FILES,$user_id);
    if($result):
  echo 'Updated';
    endif; } ?>
