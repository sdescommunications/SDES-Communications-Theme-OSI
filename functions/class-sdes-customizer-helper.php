<?php
/**
 * Encapsulate adding Theme Customizer options for any theme (non-admin settings).
 */

namespace SDES\CustomizerControls;
use \WP_Customize_Control;
require_once( 'class-sdes-static.php' );
	use SDES\SDES_Static as SDES_Static;

/**
 * Helper methods for working with the Theme Customizer.
 * @see SDES_Static::set_default_keyValue()	Set default value for an args array.
 * @see https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
 * @see https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
 * @see https://developer.wordpress.org/themes/advanced-topics/customizer-api/
 * @see https://codex.wordpress.org/Theme_Customization_API
 */
class SDES_Customizer_Helper
{
	/**
	 * Switch on the control type.
	 * @see SDES_Customizer_Helper::add_setting_and_control__WP_Customize_Control()
	 */
	public static function add_setting_and_control( $controlType, $wp_customizer, $id, $label, $section, $args ) {
		switch ( $controlType ) {
			case 'Textarea_CustomControl':

				break;

			// ...
			case 'WP_Customize_Control':
			default:
				SDES_Customizer_Helper::add_setting_and_control__WP_Customize_Control( $wp_customizer, $id, $label, $section, $args );
				break;
		}
	}

	/**
	 * Add a setting and control for a WP_Customize_Control.
	 * @see http://codex.wordpress.org/Class_Reference/WP_Customize_Control
	 * @see SDES_Customizer_Helper::add_setting_and_control
	 */
	public static function add_setting_and_control__WP_Customize_Control( $wp_customizer, $id, $label, $section, $args ) {
		SDES_Static::set_default_keyValue( $args, 'default', '' );
		SDES_Static::set_default_keyValue( $args, 'transport', 'refresh' );
		SDES_Static::set_default_keyValue( $args, 'sanitize_callback', '' );
		SDES_Static::set_default_keyValue( $args, 'sanitize_js_callback', '' );
		SDES_Static::set_default_keyValue( $args, 'capability', 'edit_theme_options' );
		SDES_Static::set_default_keyValue( $args, 'setting_type', 'theme_mod' );
		SDES_Static::set_default_keyValue( $args, 'theme_supports', '' );
		SDES_Static::set_default_keyValue( $args, 'control_type', 'text' );
		SDES_Static::set_default_keyValue( $args, 'choices', array() );
		SDES_Static::set_default_keyValue( $args, 'description', '' );


		$wp_customizer->add_setting(
			$id,
			array(
				'default'    => $args['default'],
				'transport'  => $args['transport'],  // Refresh (default) or postMessage.
				'sanitize_callback' => $args['sanitize_callback'],
				'sanitize_js_callback' => $args['sanitize_js_callback'],
				'capability' => $args['capability'],
				'type' => $args['setting_type'], // Either 'theme_mod' or 'option'.
				'theme_supports' => $args['theme_supports'], // Rarely needed.
			)
		);
		$wp_customizer->add_control(
			new WP_Customize_Control(
				$wp_customizer,
				$id,
				array(
					'label'    => $label,
					'description' => $args['description'],
					'section'  => $section,
					'settings' => $id,
					'type' => $args['control_type'],
					'choices' => $args['choices'],
				)
			)
		);
	}
}
