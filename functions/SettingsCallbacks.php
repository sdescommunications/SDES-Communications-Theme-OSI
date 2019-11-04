<?php
/**
 * Display the Settings fields for this theme (admin settings for IT staff).
 */
namespace SDES\BaseTheme\Settings;
/**
 * Display the "Field" (labels and input areas) for a Setting/Option.
 * Corresponds to the "Control"s in Theme Customizer.
 */

require_once('class-sdes-static.php');
use SDES\SDES_Static as SDES_Static;

function section_one_callback() {
    echo 'Some help text goes here.';
}

function google_analytics_id_callback() {
    $sdes_theme_settings_ga_id = esc_attr( get_option('sdes_theme_settings_ga_id', '') );
    echo "<input type='text' name='sdes_theme_settings_ga_id' value='$sdes_theme_settings_ga_id' />";
}

function javascript_callback() {
    $sdes_theme_settings_js = esc_attr( get_option('sdes_theme_settings_js', '') );
    echo "<textarea style='width: 560px; height: 150px;' name='sdes_theme_settings_js' >$sdes_theme_settings_js</textarea>";
}

function javascript_libraries_callback() {
    $sdes_theme_settings_js_lib = esc_attr( get_option('sdes_theme_settings_js_lib', '') );
    echo "<input type='text' name='sdes_theme_settings_js_lib' value='$sdes_theme_settings_js_lib' style='width: 560px;' />";
}

function css_callback() {
    $sdes_theme_settings_css = esc_attr( get_option('sdes_theme_settings_css', '') );
    echo "<textarea style='width: 560px; height: 150px;' name='sdes_theme_settings_css'>$sdes_theme_settings_css'</textarea>";
}

function css_libraries_callback() {
    $sdes_theme_settings_css_lib = esc_attr( get_option('sdes_theme_settings_css_lib', '') );
    echo "<input type='text' name='sdes_theme_settings_css_lib' value='$sdes_theme_settings_css_lib' style='width: 560px;' />";
}

function directory_cms_acronym_callback() {
    $sdes_theme_settings_dir_acronym = esc_attr( get_option('sdes_theme_settings_dir_acronym', '') );
    echo "<input type='text' name='sdes_theme_settings_dir_acronym' value='$sdes_theme_settings_dir_acronym' />";
}

function footer_content_left_callback() {
    $footer_content_left
    = wp_kses( get_option('sdes_rev_2015-footer_content-left', ''),
        wp_kses_allowed_html( 'post' ), null );
    echo "<textarea style='width: 560px; height: 150px;' name='sdes_rev_2015-footer_content-left'>$footer_content_left</textarea>";
}

function footer_content_center_callback() {
    $footer_content_center
    = wp_kses( get_option('sdes_rev_2015-footer_content-center', ''),
        wp_kses_allowed_html( 'post' ), null );
    echo "<textarea style='width: 560px; height: 150px;' name='sdes_rev_2015-footer_content-center'>$footer_content_center</textarea>";
}

function footer_content_right_callback() {
    $footer_content_right
    = wp_kses( get_option('sdes_rev_2015-footer_content-right', ''),
        wp_kses_allowed_html( 'post' ), null );
    echo "<textarea style='width: 560px; height: 150px;' name='sdes_rev_2015-footer_content-right'>$footer_content_right</textarea>";
}


function sdes_settings_render() {
    ?>
    <!-- Hello from sdes_settings_render(). -->
    <div class="wrap">
        <h2>SDES Theme Settings</h2>
        <?php
            // settings_errors( $setting, $sanitize, $hide_on_update );
        settings_errors(); ?>
        <form action="options.php" method="POST">
            <?php settings_fields( 'sdes_setting_group' ); ?>
            <?php do_settings_sections( 'sdes_settings' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <!-- Bye from sdes_settings_render(). -->
    <?php
}
