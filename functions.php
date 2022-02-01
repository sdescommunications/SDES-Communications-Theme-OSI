<?php
/**
*   Removed admin items not needed to be seen my users.
*/
require_once('functions/removed-admin-items.php');

/**
 * Entry point for a WordPress theme, along with the style.css file.
 * Includes or references all functionality for this theme.
 */

require_once('functions/class-sdes-static.php');
use SDES\SDES_Static as SDES_Static;

/**
 * Contributors for this theme should be able to edit, but not publish pages and posts.
 * Add capabilities for the Contributor role to: edit pages, delete unpublished pages, and upload files.
 * @see http://codex.wordpress.org/Roles_and_Capabilities#Contributor WP-Codex: Roles_and_Capabilities
 * @see http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init WP-Codex: admin_init
 */
function extend_contributor_caps() {
    $role = get_role( 'contributor' );
    $role->add_cap( 'edit_others_posts' );
    $role->add_cap( 'edit_others_pages' );
    $role->add_cap( 'edit_pages' );
    $role->add_cap( 'delete_pages' ); // Still cannot delete_published_pages.
    $role->add_cap( 'upload_files' );
}
add_action( 'admin_init', 'extend_contributor_caps');

//Adds in menu support into dashboard
function register_my_menus() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Main Menu' ),
      )
    );
  //menu loction for homepage
  register_nav_menu( 'home-resource-menu', __('Homepage Resources'));
  register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
}
add_action( 'init', 'register_my_menus' );

// Enqueue Datepicker + jQuery UI CSS
add_action( 'wp_enqueue_scripts', 'enqueue_scripts_and_styles');
add_action( 'admin_enqueue_scripts', 'enqueue_scripts_and_styles');
function enqueue_scripts_and_styles(){
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
}

/**
 * Enable file uploads in custom metafields
 * 
 * @see https://developer.wordpress.org/reference/hooks/post_edit_form_tag/
 * @see https://code.tutsplus.com/articles/attaching-files-to-your-posts-using-wordpress-custom-meta-boxes-part-1--wp-22291
 */
add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );
function post_edit_form_tag( ) {
   echo ' enctype="multipart/form-data"';
}

/**
 * Delete files uploaded with custom metafields when the associated post is permanently deleted
 */
function delete_associated_files( $post_id ) {
  $meta_box = SDES_Metaboxes::get_post_meta_box( $post_id );
  foreach ( $meta_box['fields'] as $field ) {
    if ( $field['type'] == 'doc' ) {
      $file = get_post_meta( $post_id, $field['id'], true )['file'];
      wp_delete_file( $file );
    }
  }
}

/**
 * Add new thumbnail size
 */
add_theme_support('pop-up-banner');
add_image_size('event-img-size', 300, 300);

add_action( 'before_delete_post', 'delete_associated_files' );

require_once( 'functions/menu-walkers.php' );

require_once( 'custom-taxonomies.php' );    // Define and Register taxonomies for this theme
require_once( 'custom-posttypes.php' );  // Define and Register custom post_type's (CPTs) for this theme

require_once( 'shortcodes.php' );

require_once( 'functions/class-render-template.php' );

require_once( 'functions/template-options.php' );

require_once( 'functions/template-billboard.php' );
require_once( 'functions/template-service.php' );
require_once( 'functions/submenu.php');

require_once( 'functions/filter-bootstrap-classes.php' ); //Adds stock Bootstrap classes where necessary

require_once( 'functions/image_selector.php' );  
  
