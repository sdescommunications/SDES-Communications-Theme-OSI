<?php
/**
 * Drop-in file to encapsulate shortcode creation, adds a UI for adding shortcodes.
 * Can autogenerate a shortcode for a custom posttype (display a listing of items of that posttype).
 */

namespace SDES\Shortcodes;
/**
 * @see js/shortcodebase.js
 * @see includes/shortcodebase-interface.php
 * @see includes/theme-help.php (Has section with documentation shortcodes.)
 */

define( 'SDES\Shortcodes\SHORTCODE_JS_URI', get_template_directory_uri().'/js/shortcodebase.js' );
define( 'SDES\Shortcodes\SHORTCODE_INTERFACE_PATH', get_stylesheet_directory().'/includes/shortcodebase-interface.php' );

/**
 * Register the javascript and PHP components required for ShortcodeBase to work.
 */
class ShortcodeBase_Loader {
	private static $isLoaded = false;
	public static $enable_fontawesome = false;

	public static function Load() {
		if ( ! self::$isLoaded ) {
			add_action( 'admin_enqueue_scripts', __CLASS__.'::enqueue_shortcode_script' );
			add_action( 'media_buttons', __CLASS__.'::add_shortcode_interface' );
			add_action( 'admin_footer', __CLASS__.'::add_shortcode_interface_modal' );
			self::$isLoaded = true;
		}
	}

	public static function enqueue_shortcode_script() {
		wp_enqueue_script( 'shortcode-script', SHORTCODE_JS_URI );
	}

	// @see https://github.com/UCF/Students-Theme/blob/d56183079c70836adfcfaa2ac7b02cb4c935237d/functions/admin.php#L3-L11
	public static function add_shortcode_interface() {
		$js_id = 'select-shortcode-form';
		$icon_classes = (self::$enable_fontawesome) ? 'fa fa-code' : 'dashicons dashicons-editor-code';
		$icon_styles  = (self::$enable_fontawesome) ? '' : 'margin-top: 3px;';
		ob_start();
		?>
		<a href="#TB_inline?width=600&height=700&inlineId=<?= $js_id ?>" class="thickbox button" id="add-shortcode" title="Add Shortcode">
			<span class="<?= $icon_classes ?>" style=" <?= $icon_styles ?>"></span> Add Shortcode
		</a>
		<?php
		echo ob_get_clean();
	}

	// @see https://github.com/UCF/Students-Theme/blob/d56183079c70836adfcfaa2ac7b02cb4c935237d/functions/admin.php#L13-L20
	public static function add_shortcode_interface_modal() {
		$page = basename( $_SERVER['PHP_SELF'] );
		if ( in_array( $page, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) ) ) {
			include_once( SHORTCODE_INTERFACE_PATH );
		}
	}
}
ShortcodeBase_Loader::Load();

/**
 * Functions used to display a shortcode in Shortcode-Interface.php.
 */
interface IShortcodeUI {
	/** Friendly name of the shortcode in the interface dropdown. */
	public function get_option_markup();
	/** Description of the shortcode. */
	public function get_description_markup();
	/** Options fields shown in the interface form. */
	public function get_form_markup();
}

	/**
	 * Base Shortcode class.
	 * @see https://github.com/UCF/Students-Theme/blob/d56183079c70836adfcfaa2ac7b02cb4c935237d/shortcodes.php#L2-L111
	 **/
abstract class ShortcodeBase implements IShortcodeUI {
	public static $installed_custom_post_types = array();
	public static $installed_shortcodes = array();
	public
		$name        = 'Shortcode', // The name of the shortcode.
		$command     = 'shortcode', // The command used to call the shortcode.
		$description = 'This is the description of the shortcode.', // The description of the shortcode.
		$params      = array(), // The parameters used by the shortcode.
		$callback    = 'callback',
		$render      = 'render',
		$closing_tag = true,
		$wysiwyg     = true; // Whether to add it to the shortcode Wysiwyg modal.

		/*
		* Register the shortcode.
		* @since v0.0.1
		* @author Jim Barnes
		* @return void
		*/
		public function register_shortcode() {
			add_shortcode( $this->command, array( $this, $this->callback ) );
		}

		/*
		* Returns the html option markup.
		* @since v0.0.1
		* @author Jim Barnes
		* @return string
		*/
		public function get_option_markup() {
			return sprintf('<option value="%s" data-showClosingTag="%b">%s</option>',
			$this->command, $this->closing_tag, $this->name);
		}

		/*
		* Returns the description html markup.
		* @since v0.0.1
		* @author Jim Barnes
		* @return string
		*/
		public function get_description_markup() {
			return sprintf( '<li class="shortcode-%s">%s</li>', $this->command, $this->description );
		}

		/*
		* Returns the form html markup.
		* @since v0.0.1
		* @author Jim Barnes
		* @return string
		*/
		public function get_form_markup() {
			ob_start();
			?>
			<li class="shortcode-<?php echo $this->command; ?>">
			<h3><?php echo $this->name; ?> Options</h3>
			<?php
			foreach ( $this->params as $param ) {
				echo $this->get_field_input( $param, $this->command );
			}
			?>
			</li>
			<?php
			return ob_get_clean();
		}

		/*
		* Returns the appropriate markup for the field.
		* @since v0.0.1
		* @author Jim Barnes
		* return string
		*/
		protected function get_field_input( $field, $command ) {
			$name      = isset( $field['name'] ) ? $field['name'] : '';
			$id        = isset( $field['id'] ) ? $field['id'] : '';
			$help_text = isset( $field['help_text'] ) ? $field['help_text'] : '';
			$type      = isset( $field['type'] ) ? $field['type'] : 'text';
			$default   = isset( $field['default'] ) ? $field['default'] : '';
			$template  = isset( $field['template'] ) ? $tempalte['template'] : '';

			$retval = '<h4>' . $name . '</h4>';
			if ( $help_text ) {
				$retval .= '<p class="help">' . $help_text . '</p>';
			}
			switch ( $type ) {
				case 'text':
				case 'date':
				case 'datetime':
				case 'datetime-local':
				case 'time':
				case 'month':
				case 'week':
				case 'range':
				case 'search':
				case 'tel':
				case 'email':
				case 'url':
				case 'number':
				case 'color':
					$retval .= '<input type="' . $type . '" name="' . $command . '-' . $id . '" value="'.$default.'" default-value="' . $default . '" data-parameter="' . $id . '">';
					break;
				case 'dropdown':
					$choices = is_array( $field['choices'] ) ? $field['choices'] : array();
					$retval .= '<select type="text" name="' . $command . '-' . $id . '" value="" default-value="' . $default . '" data-parameter="' . $id . '">';
					foreach ( $choices as $choice ) {
						$retval .= '<option value="' . $choice['value'] . '">' . $choice['name'] . '</option>';
					}
					$retval .= '</select>';
					break;
				case 'checkbox':
					$checked = ( filter_var( $default, FILTER_VALIDATE_BOOLEAN ) ) ? 'checked' : '';
					$retval .= '<input id="'.$command.'-'.$id.'" type="checkbox" name="' . $command . '-' . $id . '" data-parameter="' . $id . '"'. $checked .'>';
					$retval .= '<label for="'.$command.'-'.$id.'">'.$name.'</label>';
					break;
			}

			return $retval;
		}

		public static function callback( $attrs, $content = '' ) {
			$attrs = shortcode_atts( array(
				'text' => '',
				), $attrs
			);

			$ctxt = array();
			foreach ( $attrs as $attr ) {
				$ctx[] = esc_attr( $attr );
			}
			return static::render( $ctxt );
		}

		public static function render( $context ) {
			ob_start();
			?>
			<div>Text: <?= $context['text'] ?></div>
		<?php
		return ob_get_clean();
		}

		public static function Register_Shortcodes( $shortcodes ) {
			ShortcodeBase::$installed_shortcodes
			= array_merge( ShortcodeBase::$installed_shortcodes, $shortcodes );

			$shortcode_instances = array();
			foreach ( $shortcodes as $sc ) {
				if ( class_exists( $sc ) ) {
					$instance = new $sc;
					$instance->register_shortcode();
					$shortcode_instances[] = $instance;
				}
			}
			return $shortcode_instances;
		}
}

/**
 * Generate shortcodes for classes that extend CustomPostType.
 * @see CustomPostType::$sc_interface_fields
 */
class Shortcode_CustomPostType_Wrapper extends ShortcodeBase implements IShortcodeUI {
	public
		$name        = 'Shortcode CPT Wrapper', // The name of the shortcode.
		$command     = 'shortcode-cpt-wrapper', // The command used to call the shortcode.
		$description = 'Show list of cpt items.', // The description of the shortcode.
		$closing_tag = false,
		$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
		$params      = array( // The parameters used by the shortcode.
			array(
				'name' => 'limit (number)',
				'id' => 'limit',
				'help_text' => 'Only show this many items (defaults to "-1", no limit).',
				'default' => -1,
				'type' => 'number',
			),
		);

		function __construct( $cpt_instance ) {
			$this->name = $cpt_instance->options( 'singular_name' ).' List';
			$this->command = $cpt_instance->options( 'name' ).'-list';
			$this->description = 'Show list of '.$cpt_instance->options( 'plural_name' ).' items.';

			if ( isset( $cpt_instance->sc_interface_fields ) && ! empty( $cpt_instance->sc_interface_fields ) ) {
				$this->params = array_merge( $cpt_instance->sc_interface_fields, $this->params );
			}

			// Add taxonomy params.
			if ( count( $cpt_instance->taxonomies ) > 1 ) {
				$this->params[] = array(
				'name' => "join ('and', 'or')",
				'id' => 'join',
				'help_text' => 'Join multiple taxonomies.',
				'type' => 'dropdown',
				'choices' => array(
					array( 'value' => 'or', 'name' => '' ),
					array( 'value' => 'and', 'name' => 'and' ),
					array( 'value' => 'or', 'name' => 'or' ),
					),
				);
			}
			foreach ( $cpt_instance->taxonomies as $tax ) {
				$choices = array( array( 'value' => '', 'name' => '' ) );
				$terms = get_terms( $tax );
				// if( 'staff' == $cpt_instance->options('name') && 'org_groups' == $tax ) wp_die(var_dump($terms));
				foreach ( $terms as $term ) {
					if ( ! is_wp_error( $term ) && ! array_key_exists( 'invalid_taxonomy', $term ) && ! empty( $term ) ) {
						// wp_die(var_dump($terms));
						// var_dump($term);
						$choices[] = array( 'value' => $term->slug, 'name' => $term->name );
					}
				}
				// Friendlier text.
				switch ( $tax ) {
					case 'post_tag':
						$tax = 'tags';
						break;
					case 'category':
						$tax = 'categories';
						break;
				}
				// Add field params.
				$this->params[] = array(
				'name' => $tax,
				'id' => $tax,
				'help_text' => 'Only show items in the custom taxonomy "'.$cpt_instance->options( 'name' ).'".',
				'type' => 'dropdown',
				'choices' => $choices,
				);
			}
		}

		public function get_option_markup() {
			return sprintf( '<option value="%s" data-showClosingTag="%b">%s</option>',
			$this->command, $this->closing_tag, $this->name );
		}

		public function get_description_markup() {
			return sprintf( '<li class="shortcode-%s">%s</li>', $this->command, $this->description );
		}

		public function get_form_markup() {
			ob_start();
			?>
			<li class="shortcode-<?php echo $this->command; ?>">
			<h3><?php echo $this->name; ?> Options</h3>
			<?php
			foreach ( $this->params as $param ) {
				echo $this->get_field_input( $param, $this->command );
			}
			?>
			</li>
			<?php
			return ob_get_clean();
		}
}
