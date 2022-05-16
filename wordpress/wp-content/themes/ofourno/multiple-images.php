<?php

function process_post_creation() {
    if(isset($_POST['jobs_nonce_field']) && wp_verify_nonce($_POST['jobs_nonce_field'], 'jobs_nonce')) {

        if(strlen(trim($_POST['job_title'])) < 1 || strlen(trim($_POST['job_desc'])) < 1) {
            $redirect = add_query_arg('post', 'failed', home_url($_POST['_wp_http_referer']));
        } else {
            $job_info = array(
                'post_title' => esc_attr(strip_tags($_POST['job_title'])),
                'post_type' => 'news',
                'post_content' => esc_attr(strip_tags($_POST['job_desc'])),
                // 'post_category' => array($_POST['cat']),
                'tags_input'    => array($tags),
                'post_status' => 'pending'
            );
            $job_id = wp_insert_post($job_info);

            if($job_id) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $images= array();
                $pos_id=wp_insert_post( $my_post );
                foreach($_FILES as $value){
                    for ($i=0; $i <count($value['name']); $i++)
                    {
                        $errors= array();
                        $file_name = $value['name'][$i];
                        $file_size = $value['size'][$i];
                        $file_tmp = $value['tmp_name'][$i];
                        $file_type = $value['type'][$i];
                        $file_ext=strtolower(end(explode('.',$value['name'][$i])));

                        if(empty($errors)==true) {
                            $wordpress_upload_dir = wp_upload_dir();
                            $profilepicture = $wordpress_upload_dir['path'].'/';
                            move_uploaded_file($file_tmp, $profilepicture.$file_name);
                        }else{
                            print_r($errors);
                        }
                        $file_name_and_location = $profilepicture.$file_name;
                        $file_title_for_media_library = $value['name'][$i];
                        $fieldname = $value['name'][$i];
                        $arr_file_type     = wp_check_filetype(basename($fieldname));
                        $uploaded_file_type = $arr_file_type['type'];
                        $attachment = array(
                            'post_mime_type' => $uploaded_file_type,
                            'post_title' => addslashes($file_title_for_media_library),
                            'post_content' => '',
                            'post_status' => 'inherit',
                            'post_parent' =>  0,
                            'post_author' => get_current_user_id(),
                        );
                        wp_read_image_metadata( $file_name_and_location );
                        $attach_id     = wp_insert_attachment( $attachment, $file_name_and_location,true,false);
                        $attach_data = wp_generate_attachment_metadata($attach_id,$file_name_and_location );
                        wp_update_attachment_metadata( $attach_id, $attach_data );
                        $images[]= array("image" => $attach_id);
                    }
                }
                $field_key = "images_fildes";
                update_field($field_key,$images,$pos_id);
                add_row($field_key,$images,$pos_id);
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                require( dirname(__FILE__) . '/../../../wp-load.php' );
                set_post_thumbnail( $job_id, $thumbnail_id );

                $upload = wp_upload_bits($_FILES["test"]["name"], null, file_get_contents($_FILES["test"]["tmp_name"]));
                $uploaddir = wp_upload_dir();
                $file = $_FILES["test"]["name"];
                $uploadfile = $uploaddir['path'] . '/' . basename( $file );

                move_uploaded_file( $file , $uploadfile );
                $filename = basename( $uploadfile );

                $wp_filetype = wp_check_filetype(basename($filename), null );
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),

                );
                $attach_id = wp_insert_attachment( $attachment, $uploadfile );
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $attach_data = wp_generate_attachment_metadata( $attach_id, $uploadfile );
                wp_update_attachment_metadata( $attach_id, $attach_data );
                update_post_meta($job_id,'_thumbnail_id',$attach_id);


                add_theme_support( 'post-thumbnails' );
                register_post_type( 'news', array(
                    'supports' => array('title', 'thumbnail'),
                ));

                update_post_meta($job_id, 'u_name', esc_attr(strip_tags($_POST['user_name'])));
                update_post_meta($job_id, 'u_email', esc_attr(strip_tags($_POST['user_email'])));
                update_post_meta($job_id, 'inq_email', esc_attr(strip_tags($_POST['inquiry_email'])));

                $redirect = add_query_arg('post', 'successfull', home_url($_POST['_wp_http_referer']));
            }
        }
        wp_redirect($redirect); exit;
    }
}