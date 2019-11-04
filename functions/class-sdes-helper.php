<?php
/**
 * Helper functions that are specific to this project, and are not reusable.
 * @package Rev2015 WordPress Prototype
 */
namespace SDES\BaseTheme;
require_once( get_stylesheet_directory().'/functions/class-sdes-static.php' );
use SDES\SDES_Static as SDES_Static;
/**
 * Container for helper methods specific to this repository/solution.
 * Static functions solving general problems should be added to the class SDES_Static.
 */
class SDES_Helper
{

	/**
	 * Generate a key for a post's navpill menu location.
	 * Used as the 'theme_location' by wp_nav_menu()'s $args parameter and by the "Currently set to:" in Customizer.
	 */
	public static function the_locationKey_navpills() {
		// Assume called within TheLoop.
		return 'pg-' . the_title_attribute( array( 'echo' => false ) );
	}
	/**
	 * Generate a value for a post's navpill menu location.
	 * This is the display text shown in "Menu Locations" and "Manage Locations".
	 */
	public static function the_locationValue_navpills() {
		// Assume called within TheLoop.
		return 'Page ' . the_title_attribute( array( 'echo' => false ) );
	}



	/**
	 * Fallback_navpills_warning - Call from wp_nav_menu as the 'fallback_cb' for navpills locations.
	 *    Optionally show a warning for logged in users (if the navpills are missing).
	 *
	 * @param array  $args  Accepts $args array used by wp_nav_menu (merged with any default values), plus the standard following params:
	 *  $args['echo'] - Standard echo param, output to stdout if true.
	 *  $args['warn'] - Boolean flag to display admin message (default to true).
	 *  $args['warn_message'] - Format string for warning message (where %1$s is expended to the 'theme_location').
	 *
	 * Testing Overrides.
	 * @param bool   $shouldWarn		Override login and capabilities check.
	 * @param string $get_query_var		Call to get_query_var.
	 * @param string $esc_attr			Override the sanitize function provided by WordPress (used in testing).
	 */
	public static function fallback_navpills_warning( $args,
		$shouldWarn = null, $get_query_var = 'get_query_var', $esc_attr = 'esc_attr' ) {
		SDES_Static::set_default_keyValue( $args, 'echo', false );
		SDES_Static::set_default_keyValue( $args, 'warn', true );
		SDES_Static::set_default_keyValue( $args, 'warn_message','');

		if ( 1 !== $args['depth'] ) {
			trigger_error( "Calling 'fallback_navpills_warning' with a depth that is not 1. The SDES base-theme CSS does not currently support multi-level menus." ); }

			$shouldWarn = (isset( $shouldWarn )) ? $shouldWarn
			: SDES_Static::Is_UserLoggedIn_Can( 'edit_posts' );

		// Note: caching implications for conditional output on '?preview=true'.
			$pages = '';
			if ( $args['warn'] && ! $get_query_var('preview') && $shouldWarn ) {
				$pages .= sprintf( $args['warn_message'], $args['theme_location'] );
			}

			$menu_id = $esc_attr($args['menu_id']);
			$menu_class = $esc_attr($args['menu_class']);
			$nav_menu = sprintf( $args['items_wrap'], $menu_id, $menu_class, $pages );
			if ( $args['echo'] ) {
				echo $nav_menu;
			}
			return $nav_menu;
		}

	/**
	 * Fallback_navbar_list_pages - Call from wp_nav_menu as the 'fallback_cb'.
	 *   Allow graceful failure when menu is not set by showing a formatted listing of links
	 *   instead of the default wp_page_menu output.
	 *
	 *  @see http://codex.wordpress.org/Navigation_Menus
	 *  @see developer.wordpress.org/reference/functions/wp_nav_menu/
	 *  @see developer.wordpress.org/reference/functions/wp_list_pages/
	 *  @see SDES_Static::set_default_keyValue()
	 *
	 *  @param array  $args	 Accepts $args array used by wp_nav_menu (merged with any default values), plus the standard following params:
	 *  $args['links_cb'] Array - Override the ['links'] to display (preferred over directly providing links).
	 *    ['callback'] callable - The callable provided to call_user_func_array.
	 *    ['param_arr'] array   - The param_arr provided to call_user_func_array.
	 *  $args['links'] string - Provide the links to display (defaults to results from wp_list_pages).
	 *  $args['number'] int - Number of pages to pull from wp_list_pages if it is called..
	 *  $args['echo'] bool - Standard echo param, output to stdout if true.
	 *  $args['warn'] bool - Flag to display admin message (default to true).
	 *  $args['warn_message'] string - Format string for warning message (where %1$s is expended to the 'theme_location').
	 *
	 *  Testing Overrides.
	 *  @param bool   $shouldWarn - Override login and capabilities check.
	 *  @param string $get_query_var	Override call to get_query_var.
	 *  @param string $wp_list_pages	Override the call to wp_list_pages (Returns a string containing li>a elements).
	 *  @param string $esc_attr			Override the sanitize function provided by WordPress (used in testing).
	 *  @return string  An HTML string to display when the associated wp_nav_menu fails.
	 */
	public static function fallback_navbar_list_pages( $args,
		$shouldWarn = null, $get_query_var = 'get_query_var', $wp_list_pages = 'wp_list_pages', $esc_attr = 'esc_attr' ) {
		SDES_Static::set_default_keyValue( $args, 'number', 6 );
		SDES_Static::set_default_keyValue( $args, 'echo', false );
		SDES_Static::set_default_keyValue( $args, 'warn', true );
		SDES_Static::set_default_keyValue( $args, 'warn_message',
			'<li><a class="text-danger adminmsg adminmsg-menu" style="color: red !important;" data-control-name="nav_menu_locations[%1$s]" '
			. 'href="' . get_site_url() . '/wp-admin/nav-menus.php?action=locations#locations-%1$s">Admin Alert: Missing "%1$s" menu location.</a></li>'
			);
		SDES_Static::set_default_keyValue( $args, 'links_cb', null );

		if ( 1 !== $args['depth'] ) {
			trigger_error( "Calling 'fallback_navbar_list_pages' with a depth that is not 1. The SDES base-theme CSS does not currently support multi-level menus." ); }

		// Priority: links_cb > links > wp_list_pages.
			if ( null !== $args['links_cb'] ) {
				$args['links'] = call_user_func_array(
					$args['links_cb'][0],
					$args['links_cb'][1]
					);
			} else {
				SDES_Static::set_default_keyValue( $args, 'links',
					$wp_list_pages(array(
						'echo' => false,
						'title_li' => '',
						'depth' => ( $args['depth'] ),
						'number' => ( $args['number'] ),
						))
					);
			}

			$shouldWarn = (isset( $shouldWarn )) ? $shouldWarn
			: SDES_Static::Is_UserLoggedIn_Can( 'edit_posts' );

		// Note: caching implications for conditional output on '?preview=true'.
			if ( $args['warn'] && ! $get_query_var('preview') && $shouldWarn ) {
				$args['links'] .= sprintf( $args['warn_message'], $args['theme_location'] );
			}

			$menu_id = $esc_attr($args['menu_id']);
			$menu_class = $esc_attr($args['menu_class']);
		$nav_menu = sprintf( $args['items_wrap'], $menu_id, $menu_class, $args['links'] ); // Match formatting of items_wrap in wp_nav_menu.
		if ( $args['echo'] ) {
			echo $nav_menu;
		}
		return $nav_menu;
	}



	/**
	 * Retrieve data about an SDES department from the SDES Directory CMS.
	 * @param string $directory_cms_acronym  The department 'acronym' to match against.
	 * @param Array  $default_department     Set default values if they are not in the feed.
	 * @param string $uri                    The uri of the Directory CMS's json feed.
	 * @return Array   An associative array representing the department.
	 */
	public static function get_sdes_directory_department( $directory_cms_acronym,
		$default_department = null, $uri = 'http://directory.sdes.ucf.edu/feed/' ) {
		$department = array();
		if ( '' !== $directory_cms_acronym && !ctype_space( $directory_cms_acronym ) ) {
			$json = json_decode( file_get_contents( $uri ), $assoc = true );
			foreach ( $json['departments'] as $idx => $dept ) {
				if ( $directory_cms_acronym === $dept['acronym'] ) {
					$department = array_merge($default_department, $dept);
					break;
				}
			}
		}
		$department = array_merge( 
			array(
				'location' => array( 'building' => '', 'buildingNumber' => '', 'roomNumber' => '', ),
				'name' => '', 'acroynm' => '', 'phone' => '', 'fax' => '', 'email' => '', 'postOfficeBox' => '',
				'image' => '', 'offersPublicServices' => '', 'isDept' => '', 'functionalGroup' => '',
				'websites' => array( array( 'name' => '', 'slug' => '', 'uri' => '', ), ) ,
				'hours' => array( array( 'day' => '', 'open' => '', 'close' => '', ) ) ,
				'staff' => array( array( 'name' => '', 'position' => '', ) ) ,
				'socialNetworks' => array( array( 'name' => '', 'uri' => '', 'uid' => '', ) ) ,
				),
			(array) $default_department,
			(array) $department  // Cast as array to handle null arrays. See: http://stackoverflow.com/a/20132209 for more.
			);
		return $department;
	}

}
