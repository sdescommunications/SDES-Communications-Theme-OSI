<?php
/**
 * Create custom controls to use with Theme Customizer by extending WP_Customize_Control.
 */
namespace SDES\CustomizerControls;
require_once( ABSPATH . WPINC . '/class-wp-customize-control.php' );
use \WP_Customize_Control;

/**
 * Display a TEXTAREA element within Theme Customizer.
 */
class Textarea_CustomControl extends WP_Customize_Control {
	public $type = 'textarea';
	
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>
		<?php
	}
}

/**
 * Display a field for a phone number within Theme Customizer.
 * Adds CSS classes: .customize-phoneLabel, .customize-phoneInput
 */
class Phone_CustomControl extends WP_Customize_Control {
	public $type = 'text';
	
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title customize-phoneLabel"><?php echo esc_html( $this->label ); ?></span>
			<input type="text" class="customize-phoneInput" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		</label>
		<?php
	}
}


