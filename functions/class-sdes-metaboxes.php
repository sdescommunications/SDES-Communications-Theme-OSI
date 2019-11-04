<?php
/**
 * Add metabox functionality to a custom posttype.
 * Relies on implementations in SDES\Metafields.
 */

namespace SDES;

require_once( 'classes-metabox-metafields.php' );
use SDES\Metafields\IMetaField as IMetafield;
use SDES\Metafields\MetaField as Metafield;
use SDES\Metafields\ChoicesMetaField as ChoicesMetaField;
use SDES\Metafields\TextMetaField as TextMetaField;
use SDES\Metafields\DatePickerMetaField as DatePickerMetaField;
use SDES\Metafields\PasswordMetaField as PasswordMetaField;
use SDES\Metafields\TextareaMetaField as TextareaMetaField;
use SDES\Metafields\SelectMetaField as SelectMetaField;
use SDES\Metafields\MultiselectMetaField as MultiselectMetaField;
use SDES\Metafields\RadioMetaField as RadioMetaField;
use SDES\Metafields\CheckboxListMetaField as CheckboxListMetaField;
use SDES\Metafields\FileMetaField as FileMetaField;
use SDES\Metafields\EditorMetaField as EditorMetaField;

require_once( 'class-sdes-static.php' );
use SDES\SDES_Static as SDES_Static;

require_once( get_stylesheet_directory().'/vendor/autoload.php' );
use Underscore\Types\Arrays;

use \Exception as Exception;

/**
 * POST DATA HANDLERS and META BOX FUNCTIONS
 *
 * Functions that display and save custom post types and their meta data.
 *
 * @see custom-post-types.php\register_custom_posttypes()
 * @see CustomPostType::register_metaboxes
 * @see SDES_Static::get_post_type()
 * Based on:
 * https://github.com/UCF/Students-Theme/blob/6ca1d02b062b2ee8df62c0602adb60c2c5036867/functions/base.php#L1203-L1215
 * https://github.com/UCF/Students-Theme/blob/6ca1d02b062b2ee8df62c0602adb60c2c5036867/functions/base.php#L1218-L1371
 */
class SDES_Metaboxes {
	/**
	 * Array containing instances of the custom post types classes.
	 * @see custom-post-types.php\register_custom_posttypes() register_custom_posttypes().
	 */
	public static $installed_custom_post_types = null;

	private static function check_installed_custom_post_types() {
		if ( null === static::$installed_custom_post_types ) {
			throw new Exception('Instances of metaboxes were not set in ' .
				'SDES_Metaboxes::$installed_custom_post_types. Cannot retrieve metaboxes.', 1);
		}
	}

	/**
	 * Registers all metaboxes for install custom post types
	 *
	 * @return void
	 * @author Jared Lang
	 * */
	public static function register_meta_boxes() {
		static::check_installed_custom_post_types();
		// Register custom post types metaboxes.
		foreach ( static::$installed_custom_post_types as $custom_post_type ) {
			$custom_post_type->register_metaboxes();
		}
	}

	/**
	 * Returns a custom post type's metabox data.
	 **/
	public static function get_post_meta_box( $post_id ) {
		static::check_installed_custom_post_types();
		$meta_box = null;
		foreach ( static::$installed_custom_post_types as $custom_post_type ) {
			if ( SDES_Static::get_post_type( $post_id ) === $custom_post_type->options( 'name' ) ) {
				$meta_box = $custom_post_type->metabox();
				break;
			}
		}
		return $meta_box;
	}

	/**
	 * Saves the data for a given post type
	 *
	 * @author Jared Lang
	 * */
	public static function save_meta_data( $post_id ) {
		$meta_box = static::get_post_meta_box( $post_id );
		// Verify nonce.
		$nonce = isset( $_POST['meta_box_nonce'] ) ? $_POST['meta_box_nonce'] : null;
		if ( ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
			return $post_id;
		}
		// Check autosave.
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
			return $post_id;
		}
		// Check permissions.
		if ( 'page' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		if ( $meta_box ) {
			foreach ( $meta_box['fields'] as $field ) {
				static::save_default( $post_id, $field );
			}
		}
	}

	public static function save_default( $post_id, $field ) {
		$old = get_post_meta( $post_id, $field['id'], true );
		$new = isset( $_POST[ $field['id'] ]) ? $_POST[ $field['id'] ] : null;
		if ( $new !== '' and $new !== null and $new != $old ) {
			// Update if new is not empty and is not the same value as old.
			update_post_meta( $post_id, $field['id'], $new );
		} elseif ( ( $new === '' or is_null( $new ) ) and $old ) {
			// Delete if we're sending a new null value and there was an old value.
			delete_post_meta( $post_id, $field['id'], $old );
		}
		// Otherwise we do nothing, field stays the same.
		return;
	}

	/**
	 * Displays the metaboxes for a given post type
	 *
	 * @return void
	 * @author Jared Lang
	 * */
	public static function show_meta_boxes( $post ) {
		$meta_box = static::get_post_meta_box( $post );
		ob_start();
		?>
		<input type="hidden" name="meta_box_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>">
		<table class="form-table">
			<?php foreach ( $meta_box['fields'] as $field ) {
				static::display_metafield( $post->ID, $field );
			} ?>
		</table>
		<?php
		$hasDateField = Arrays::matchesAny(
			$meta_box['fields'],
			function( $x ) { return ('date' === $x['type']); }
			);
			if ( $hasDateField ) : ?>
			<script>
				jQuery(document).ready(function(){
					jQuery('.date').datepicker({
						minDate: '0d',
						dateFormat : 'yy-mm-dd'
					});
				});
			</script>
		<?php endif;
		echo ob_get_clean();
	}

	/**
	 * Displays metafields with current or default values.
	 * */
	public static function display_metafield( $post_id, $field ) {
		$field_obj = null;
		$field['value'] = get_post_meta( $post_id, $field['id'], true );
		switch ( $field['type'] ) {
			case 'text':
			$field_obj = new TextMetaField( $field );
			break;
			case 'date':
			$field_obj = new DatePickerMetaField( $field );
			break;
			case 'textarea':
			$field_obj = new TextareaMetaField( $field );
			break;
			case 'select':
			$field_obj = new SelectMetaField( $field );
			break;
			case 'multiselect':
			$field_obj = new MultiselectMetaField( $field );
			break;
			case 'radio':
			$field_obj = new RadioMetaField( $field );
			break;
			case 'checkbox':
			case 'checkbox_list':
			$field_obj = new CheckboxListMetaField( $field );
			break;
			case 'file':
			$field['post_id'] = $post_id;
			$field_obj = new FileMetaField( $field );
			break;
			case 'editor':
			$field_obj = new EditorMetaField( $field );
			break;
			default:
			break;
		}
		$markup = '';
		if ( null !== $field_obj && $field_obj instanceof IMetafield ) {
			ob_start();
			?>
			<tr>
				<th><?php echo $field_obj->label_html(); ?></th>
				<td>
					<?php echo $field_obj->description_html(); ?>
					<?php echo $field_obj->input_html(); ?>
				</td>
			</tr>
			<?php
			$markup = ob_get_clean();
		} else {
			$markup = '<tr><th></th><td>Don\'t know how to handle field of type '. $field['type'] .'</td></tr>';
		}
		echo $markup;
	}

	/**
	 * Register with the WordPress actions, 'do_meta_boxes' and 'save_post'.
	 */
	public static function register() {
		add_action( 'do_meta_boxes', __CLASS__ . '::register_meta_boxes' );
		add_action( 'save_post', __CLASS__ . '::save_meta_data' );
	}
}

SDES_Metaboxes::register();
