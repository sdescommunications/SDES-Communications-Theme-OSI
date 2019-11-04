<?php

function image_selector() {  
   
  if ( ! did_action( 'wp_enqueue_media' ) ) {
    wp_enqueue_media();
  }
 
  wp_enqueue_script( 'uploadscript', get_stylesheet_directory_uri() . '/js/image_selector.js', array('jquery'), null, false );
}
 
add_action( 'admin_enqueue_scripts', 'image_selector' );

/*
 * @param string $name Name of option or name of post custom field.
 * @param string $value Optional Attachment ID
 * @return string HTML of the Upload Button
 */
function image_uploader_field( $name, $value = '', $choice = '') {
  $image = ' button">Upload image';
  $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
  $display = 'none'; // display state ot the "Remove image" button
 
  if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
 
    // $image_attributes[0] - image URL
    // $image_attributes[1] - image width
    // $image_attributes[2] - image height
    
    $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
    $image2 = '<img class="card-img-top img-fluid" src="' . $image_attributes[0] . '"alt="" />';
    $display = 'inline-block';
 
  } 
 
  if ($choice == 2) {
    return $image2;
  } else {
    return '
  <div>
    <a href="#" class="misha_upload_image_button' . $image . '</a>
    <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
    <a href="#" class="misha_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
  </div>';
  }

}

?>