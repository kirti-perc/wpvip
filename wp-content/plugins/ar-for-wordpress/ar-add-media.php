<?php

/**

 * AR Display

 * https://augmentedrealityplugins.com

**/
require_once __DIR__ . '/../../../wp-load.php';
require_once __DIR__ . '/../../../wp-admin/includes/file.php';
require_once __DIR__ . '/../../../wp-admin/includes/media.php';
require_once __DIR__ . '/../../../wp-admin/includes/image.php';

//include __DIR__ . '/../../../includes/image.php';
//include __DIR__ . '/../../../includes/file.php';
//include( $_SERVER[ 'DOCUMENT_ROOT' ] . '/ardisplay/wp-load.php' ); 


$post_id = $_POST['post_ID'];

$post_title = $_POST['post_title'];

$image_data = $_POST['_ar_poster_image_field'];

$image_name = $post_title."_model_poster_image.png";

$plugin_folder = substr($_SERVER["SCRIPT_URI"],0,strrpos($_SERVER["SCRIPT_URI"],"/")+1); 

file_put_contents($image_name,file_get_contents($image_data));

$url = $plugin_folder . $image_name;

upload_image($image_name, $url, $post_id, $post_title);

unlink ($image_name);

function upload_image($image_name, $url, $post_id, $post_title) {

    $image = "";

    if($url != "") {

     

        $file = array();

        $file['name'] = $image_name;

        $file['tmp_name'] = download_url($url);

        //echo 'test';exit;

        if (is_wp_error($file['tmp_name'])) {

            @unlink($file['tmp_name']);

            var_dump( $file['tmp_name']->get_error_messages( ) );

        } else {

            $attachmentId = media_handle_sideload($file, $post_id);

             

            if ( is_wp_error($attachmentId) ) {

                @unlink($file['tmp_name']);

                var_dump( $attachmentId->get_error_messages( ) );

            } else {                

                $image = wp_get_attachment_url( $attachmentId );

            }

        }

        echo $attachmentId;

    }

    return $attachmentId;

}

