<?php

function delete_previous_post_media($post_id)
{
    $medias = get_children([
        'post_parent' => $post_id,
        'post_type' => 'attachment',
        'post_mime_type' => 'image'
    ]);

    array_map(
        fn($media) => wp_delete_attachment($media->ID),
         $medias);
}

function upload_recipe_images($recipe_id, $is_update=false){
if ( isset( $_FILES['images'] ) && has_uploaded_files( $_FILES['images'] )) {
        if($is_update){
            $images = get_children([
                'post_parent' => $recipe_id,
                'post_type' => 'attachment',
                'post_mime_type' => 'image'
            ]);
            delete_previous_post_media($recipe_id);
        }
        
        $images = $_FILES['images'];

        for ($i = 0; $i < count($images['name']); $i++) {
            if ($images) {
                $file = [
                    'name'     => $images['name'][$i],
                    'type'     => $images['type'][$i],
                    'tmp_name' => $images['tmp_name'][$i],
                    'error'    => $images['error'][$i],
                    'size'     => $images['size'][$i]
                ];

                $_FILES = ["my_file_upload" => $file];
                
                foreach ($_FILES as $file => $array) {
                    if ($file) {
                        $image_id = media_handle_upload($file, $recipe_id);
                        if (is_wp_error($image_id)) {
                            print_error_message($image_id->get_error_message());
                            die();
                        }
                        if (!(int)$image_id) die("We have a situation :o ");

                        set_post_thumbnail($recipe_id, $image_id);
                    }
                }
            }
        }
    }
}