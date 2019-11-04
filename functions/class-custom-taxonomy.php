<?php
/**
 * Abstract base class for creating custom taxonomies.
 */

namespace SDES;
require_once( get_stylesheet_directory().'/functions/class-sdes-static.php' );
use SDES\SDES_Static as SDES_Static;

/**
 * Abstract class for defining custom taxonomies.
 *
 * @see SDES_Static::instantiate_and_register_classes()
 **/
abstract class CustomTaxonomy {
	public
	$name			= 'custom_taxonomy',

		// Do not register the taxonomy with the post type here.
		// Register it on the `taxonomies` attribute of the post type in
		// custom-post-types.php.
	$object_type	= array(),
		// Labels.
	$singular_name      = 'Post Tag',
	$menu_name          = null,
	$all_items          = 'All Tags',
	$edit_item          = 'Edit Tag',
		$view_item          = 'View Tag',  // Not in WordPress-Generic-Theme.
		$update_item        = 'Update Tag',
		$add_new_item       = 'Add New Tag',
		$new_item_name      = 'New Tag Name',
		$parent_item        = 'Parent Category',
		$parent_item_colon  = 'Parent Category:',
		$search_items       = 'Search Tags',
		$popular_items      = 'Popular Tags',
		$separate_items_with_commas = ' Separate tags with commas',  // Not in WordPress-Generic-Theme.
		$add_or_remove_items    = 'Add or remove tags',  // Not in WordPress-Generic-Theme.
		$choose_from_most_used  = 'Choose from the most used tags',  // Not in WordPress-Generic-Theme.
		$not_found              = 'No tags found.',  // Not in WordPress-Generic-Theme.

		$general_name       = 'Post Tags',
		// Args.
		$public                = true,
		$show_in_nav_menus     = true,
		$show_in_name_menus    = null,
		$show_ui               = null,
		$show_admin_column     = false,
		$show_tagcloud         = null,
		$hierarchical          = false,
		$update_count_callback = '',
		$rewrite               = true,
		$query_var             = null,
		$capabilities          = array();

		function __construct() {
			if ( is_null( $this->show_in_name_menus ) ) { $this->show_in_name_menus = $this->public; }
			if ( is_null( $this->show_ui ) ) { $this->show_ui = $this->public; }
			if ( is_null( $this->show_tagcloud ) ) { $this->show_tagcloud = $this->show_ui; }
			if ( is_null( $this->menu_name ) ) { $this->menu_name = $this->general_name; }
		}

		public function options( $key ) {
			$vars = get_object_vars( $this );
			return $vars[ $key ];
		}

		public function labels() {
			return array(
				'name'                       => _x( $this->options( 'general_name' ), 'taxonomy general name' ),
				'singular_name'              => _x( $this->options( 'singular_name' ), 'taxonomy singular name' ),
				'search_items'               => __( $this->options( 'search_items' ) ),
				'popular_items'              => __( $this->options( 'popular_items' ) ),
				'all_items'                  => __( $this->options( 'all_items' ) ),
				'parent_item'                => __( $this->options( 'popular_items' ) ),
				'parent_item_colon'          => __( $this->options( 'parent_item_colon' ) ),
				'edit_item'                  => __( $this->options( 'edit_item' ) ),
				'update_item'                => __( $this->options( 'update_item' ) ),
				'add_new_item'               => __( $this->options( 'add_new_item' ) ),
				'new_item_name'              => __( $this->options( 'new_item_name' ) ),
				'separate_items_with_commas' => __( $this->options( 'separate_items_with_commas' ) ),
				'add_or_remove_items'        => __( $this->options( 'add_or_remove_items' ) ),
				'choose_from_most_used'      => __( $this->options( 'choose_from_most_used' ) ),
				'menu_name'                  => __( $this->options( 'menu_name' ) ),
				);
		}

		public function register() {
			$args = array(
				'labels'                => $this->labels(),
				'public'                => $this->options( 'public' ),
				'show_in_nav_menus'     => $this->options( 'show_in_nav_menus' ),
				'show_ui'               => $this->options( 'show_ui' ),
				'show_admin_column'     => $this->options( 'show_admin_column' ),
				'show_tagcloud'         => $this->options( 'show_tagcloud' ),
				'hierarchical'          => $this->options( 'hierarchical' ),
				'update_count_callback' => $this->options( 'update_count_callback' ),
				'rewrite'               => $this->options( 'rewrite' ),
				'query_var'             => $this->options( 'query_var' ),
				'capabilities'          => $this->options( 'capabilities' ),
				);
			register_taxonomy( $this->options( 'name' ), $this->options( 'object_type' ), $args );
		}

	/**
	 * @param Array $custom_taxonomies Names of taxonomy classes to register.
	 * @return  Array Array of instantiated taxonomy classes (array of arrays). Each item has the keys: 'classname', 'instance'.
	 * @see SDES_Static::instantiate_and_register_classes()
	 */
	public static function Register_Taxonomies( $custom_taxonomies ) {
		$taxonomy_instances = SDES_Static::instantiate_and_register_classes( $custom_taxonomies );
		return $taxonomy_instances;
	}
}
