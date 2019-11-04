<?php
/**
 * Extend the TinyMCE editor in Admin Dashboard (/wp-admin/).
 */

namespace SDES\Admin;

/**
 * @see https://github.com/UCF/Students-Theme/blob/87dca3074cb48bef5d811789cf9a07c9eac55cd1/functions/admin.php#L74-L101
 */
/**
 * Modifies the default stylesheets associated with the TinyMCE editor.
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_css WP-Codex: mce_css
 * @return string
 * @author Jared Lang
 * */
function editor_styles( $css ) {
	$css   = array_map( 'trim', explode( ',', $css ) );
	$css   = implode( ',', $css );
	return $css;
}
add_filter( 'mce_css', __NAMESPACE__.'\editor_styles' );
/**
 * Edits second row of buttons in tinyMCE editor. Removing/adding actions.
 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4 WP-Codex: mce_buttons_2
 * @return array
 * @author Jared Lang
 * */
function editor_format_options( $row ) {
	$found = array_search( 'underline', $row );
	if ( false !== $found ) {
		unset( $row[ $found ] );
	}
	return $row;
}
add_filter( 'mce_buttons_2', __NAMESPACE__.'\editor_format_options' );




/**
 * Add styleselect button to the TinyMCE advanced toolbar.
 * @see https://github.com/UCF/Students-Theme/blob/87dca3074cb48bef5d811789cf9a07c9eac55cd1/functions/admin.php#L130-L268
 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4 WP-Codex: mce_buttons_2
 */
function add_advanced_styles_button( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
add_filter( 'mce_buttons_2', __NAMESPACE__.'\add_advanced_styles_button' );

/**
 * Define styles for the styleselect ("Formats") dropdown.
 * @see http://codex.wordpress.org/TinyMCE_Custom_Styles WP-Codex: TinyMCE_Custom_Styles
 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/tiny_mce_before_init WP-Codex: tiny_mce_before_init
 * @see https://developer.wordpress.org/reference/functions/wp_json_encode/ WP-Codex: wp_json_encode
 */
function add_editor_styles( $init_array ) {
	$style_formats = array(
		array(
			'title' => 'Text Transforms',
			'items' => array(
				array(
					'title'    => 'Uppercase Text',
					'selector' => 'h1,h2,h3,h4,h5,p',
					'classes'  => 'text-uppercase',
					),
				array(
					'title'    => 'Lowercase Text',
					'selector' => 'h1,h2,h3,h4,h5,p',
					'classes'  => 'text-lowercase',
					),
				array(
					'title'    => 'Capitalize Text',
					'selector' => 'h1,h2,h3,h4,h5,p',
					'classes'  => 'text-capitalize',
					),
				),
			),
		array(
			'title' => 'List Styles',
			'items' => array(
				array(
					'title'    => 'Unstyled List',
					'selector' => 'ul,ol',
					'classes'  => 'list-unstyled',
					),
				array(
					'title'    => 'Horizontal List',
					'selector' => 'ul,ol',
					'classes'  => 'list-inline',
					),
				array(
					'title'    => 'Bullets',
					'selector' => 'ul,ol',
					'classes'  => 'bullets',
					),
				),
			),
		array(
			'title' => 'Buttons',
			'items' => array(
				array(
					'title' => 'Button Sizes',
					'items' => array(
						array(
							'title'    => 'Large Button',
							'selector' => 'a,button',
							'classes'  => 'btn btn-lg',
							),
						array(
							'title'    => 'Default Button',
							'selector' => 'a,button',
							'classes'  => 'btn',
							),
						array(
							'title'    => 'Small Button',
							'selector' => 'a,button',
							'classes'  => 'btn btn-sm',
							),
						array(
							'title'    => 'Extra Small Button',
							'selector' => 'a,button',
							'classes'  => 'btn btn-xs',
							),
						),
					),
				array(
					'title' => 'Button Styles',
					'items' => array(
						array(
							'title'    => 'Default',
							'selector' => 'a.btn,button.btn',
							'classes'  => 'btn-default',
							),
						array(
							'title'    => 'UCF Gold',
							'selector' => 'a.btn,button.btn',
							'classes'  => 'btn-ucf',
							),
						array(
							'title'    => 'Primary',
							'selector' => 'a.btn,button.btn',
							'classes'  => 'btn-primary',
							),
						array(
							'title'    => 'Success',
							'selector' => 'a.btn,button.btn',
							'classes'  => 'btn-success',
							),
						array(
							'title'    => 'Info',
							'selector' => 'a.btn,button.btn',
							'classes'  => 'btn-info',
							),
						array(
							'title'    => 'Warning',
							'selector' => 'a.btn,button.btn',
							'classes'  => 'btn-warning',
							),
						array(
							'title'    => 'Danger',
							'selector' => 'a.btn,button.btn',
							'classes'  => 'btn-danger',
							),
						),
					),
				),
			),
		array(
			'title'    => 'Lead',
			'selector' => 'p',
			'classes'  => 'lead',
			),
		array(
			'title'    => 'Bullets',
			'selector' => 'h1,h2,h3,ul,ol',
			'classes'  => 'bullets',
			),
		array(
			'title'    => 'Page Header',
			'selector' => 'h1,h2,h3',
			'classes'  => 'page-header',
			),
		array(
			'title'    => 'Nonresponsive Image',
			'selector' => 'img',
			'classes'  => 'img-nonresponsive',
			),
		);
	$init_array['style_formats'] = wp_json_encode( $style_formats );
	return $init_array;
}
add_filter( 'tiny_mce_before_init', __NAMESPACE__.'\add_editor_styles' );

// /**
// * Add a stylesheet to apply to the editor itself.
// * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_css WP-Codex: mce_css
// */
// function add_mce_stylesheet( $url ) {
// if ( ! empty( $url ) ) {
// $url .= ',';
// }
// $url .= THEME_CSS_URL . '/style.min.css';
// return $url;
// }
// add_filter( 'mce_css', __NAMESPACE__.'\add_mce_stylesheet' );
?>
