<?php
/**
 * Implements the interface of the Shortcode UI. Appends the contents of the 'Add Shortcode' Thickbox to a page.
 */
namespace SDES\Shortcodes;
require_once( get_stylesheet_directory().'/functions/class-shortcodebase.php' );
	use SDES\Shortcodes\ShortcodeBase;
	use SDES\Shortcodes\IShortcodeUI;
	use SDES\Shortcodes\Shortcode_CustomPostType_Wrapper;
/**
 * Shortcode admin interface
 * @see https://github.com/UCF/Students-Theme/blob/d56183079c70836adfcfaa2ac7b02cb4c935237d/includes/shortcode-interface.php
 **/
 $shortcodes = array();
 // Add shortcodes registered in ShortcodeBase.
 foreach (ShortcodeBase::$installed_shortcodes as $sc) {
 	if( class_exists($sc) ) {
 		$instance = new $sc;
 		if( $instance instanceof IShortcodeUI) $shortcodes[] = new $instance;
 	}
 }
 // Add shortcodes for custom posttypes registered in ShortcodeBase.
 foreach ( ShortcodeBase::$installed_custom_post_types as $sc_post ) {
	if ( false !== $sc_post->options('sc_interface_fields') && $sc_post->options('use_shortcode') ) {
		$shortcodes[] = new Shortcode_CustomPostType_Wrapper($sc_post);
 	}
 }
?>
<div id="select-shortcode-form" style="display:none">
	<div id="select-shortcode-form-inner">
		<h2>Select a shortcode:</h2>
		<p>
			This shortcode will be inserted into the text editor when you click the "Insert into Post" button.
		</p>
		<div class="cols">
			<div class="col-left">
				<select name="shortcode-select" id="shortcode-select">
					<option value="">--Choose Shortcode--</option>
					<?php foreach( $shortcodes as $shortcode ) {
                        echo $shortcode->get_option_markup();
                    } ?>
				</select>
			</div>
			<div class="col-right">
				<ul id="shortcode-descriptions">
					<?php foreach( $shortcodes as $shortcode ) {
                        echo $shortcode->get_description_markup();
                    } ?>
				</ul>
			</div>
		</div>
		<ul id="shortcode-editors">
			<?php foreach( $shortcodes as $shortcode ) {
                echo $shortcode->get_form_markup();
            } ?>
		</ul>
		<button class="button-primary">Insert into Post</button>
	</div>
</div>
