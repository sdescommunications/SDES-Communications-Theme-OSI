<?php
/**
 * Abstract base class for creating custom posttypes.
 * This class is central the a theme's fuctionality, and heavily relies on other files.
 *
 * @package CustomPostType
 * @filesource
 */

namespace SDES;
use \WP_Query;

require_once( get_stylesheet_directory().'/functions/class-sdes-metaboxes.php' );
use SDES\SDES_Metaboxes;
	class_alias( 'SDES\\SDES_Metaboxes', 'SDES_Metaboxes' );  // Allows calling class_exists() without any changes.

	require_once( get_stylesheet_directory().'/functions/class-shortcodebase.php' );
	use SDES\Shortcodes\ShortcodeBase;
	class_alias( 'SDES\\Shortcodes\\ShortcodeBase', 'ShortcodeBase' );

	require_once( get_stylesheet_directory().'/functions/class-sdes-static.php' );
	use SDES\SDES_Static as SDES_Static;

	require_once( get_stylesheet_directory().'/vendor/autoload.php' );
	use Underscore\Types\Object_A;
	use Underscore\Types\Arrays;

/**
 * Abstract class for defining custom post types.
 *
 * @see SDES_Metaboxes::$installed_custom_post_types
 * @see SDES_Metaboxes::show_meta_boxes (calls SDES_Metaboxes::display_meta_box_field)
 * @see SDES_Static::instantiate_and_register_classes()
 * Based on: http://github.com/UCF/Students-Theme/blob/6ca1d02b062b2ee8df62c0602adb60c2c5036867/custom-post-types.php#L1-L242
 **/
abstract class CustomPostType {
	public
	$name           = 'custom_post_type',
	$plural_name    = 'Custom Posts',
	$singular_name  = 'Custom Post',
	$add_new_item   = 'Add New Custom Post',
	$edit_item      = 'Edit Custom Post',
	$new_item       = 'New Custom Post',
	$public         = true,  // I dunno...leave it true
	$use_title      = true,  // Title field
	$use_editor     = true,  // WYSIWYG editor, post content field
	$use_revisions  = true,  // Revisions on post content and titles
	$use_thumbnails = false, // Featured images
	$use_order      = false, // Wordpress built-in order meta data
	$use_metabox    = false, // Enable if you have custom fields to display in admin
	$use_shortcode  = false, // Auto generate a shortcode for the post type
							 // (see also objectsToHTML and toHTML methods).
	$taxonomies     = array( 'post_tag' ),
	$menu_icon      = null,
	$built_in       = false,
	// Optional default ordering for generic shortcode if not specified by user.
	$default_orderby = null,
	$default_order   = null,
	// Interface Columns/Fields.
	$calculated_columns = array(
		array( 'heading' => 'Thumbnail', 'column_name' => '_thumbnail_id', 'order' => 100, 'sortable' => false ),
		// array( 'heading'=>'A Column Heading Text', 'column_name'=>'id_of_the_column', 'order' => 1000 'sortable' => true ),
	), // Calculate values within custom_column_echo_data.
	$sc_interface_fields = null; // Fields for shortcodebase interface (false hides from list, null shows only the default fields).

	/**
	 * Wrapper for get_posts function, that predefines post_type for this
	 * custom post type.  Any options valid in get_posts can be passed as an
	 * option array.
	 
	 * @see http://codex.wordpress.org/Template_Tags/get_posts WP-Codex: get_posts()
	 * @see http://developer.wordpress.org/reference/functions/get_posts/ WP-Ref: get_posts()
	 * @see get_objects_as_options() get_objects_as_options()
	 * @usedby get_objects_as_options()
	 * @param array $options Options to pass to get_posts().
	 * @return WP_Post  Array of post objects.
	 * */
	public function get_objects( $options = array() ) {
		$defaults = array(
			'numberposts'   => -1,
			'orderby'       => 'title',
			'order'         => 'ASC',
			'post_type'     => $this->options( 'name' ),
			);
		$options = array_merge( $defaults, $options );
		$objects = get_posts( $options );
		return $objects;
	}

	/**
	 * Similar to get_objects, but returns array of key values mapping post
	 * title to id if available, otherwise it defaults to id=>id.
	 
	 * @see get_objects() get_objects()
	 * @uses get_objects(), options(), $use_title
	 * @param array $options Options to pass to get_posts().
	 * @return array Objects as post ids.
	 **/
	public function get_objects_as_options( $options ) {
		$objects = $this->get_objects( $options );
		$opt     = array();
		foreach ( $objects as $o ) {
			switch ( true ) {
				case $this->options( 'use_title' ):
				$opt[ $o->post_title ] = $o->ID;
				break;
				default:
				$opt[ $o->ID ] = $o->ID;
				break;
			}
		}
		return $opt;
	}

	/**
	 * Return the instances values defined by $key.
	 * @param string|mixed $key The instance value key.
	 * @return The value stored at the given key.
	 * */
	public function options( $key ) {
		$vars = get_object_vars( $this );
		return $vars[ $key ];
	}

	/**
	 * Additional fields on a custom post type may be defined by overriding this
	 * method on an descendant object.
	 * @see SDES_Metaboxes::display_metafield() SDES_Metaboxes::display_metafield() contains field types.
	 * @return array Specifications of the metadata fields for this post_type (to be displayed in metaboxes).
	 * */
	public function fields() {
		return array();
	}

	/**
	 * Using instance variables defined, returns an array defining what this
	 * custom post type supports.
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type#supports WP-Codex: register_post_type()
	 * @see http://codex.wordpress.org/Function_Reference/add_post_type_support WP-Codex: add_post_type_support()
	 * @return array Array of what the post_type supports.
	 * */
	public function supports() {
		// Default support array.
		$supports = array();
		if ( $this->options( 'use_title' ) ) {
			$supports[] = 'title';
		}
		if ( $this->options( 'use_order' ) ) {
			$supports[] = 'page-attributes';
		}
		if ( $this->options( 'use_thumbnails' ) ) {
			$supports[] = 'thumbnail';
		}
		if ( $this->options( 'use_editor' ) ) {
			$supports[] = 'editor';
		}
		if ( $this->options( 'use_revisions' ) ) {
			$supports[] = 'revisions';
		}
		return $supports;
	}

	/**
	 * Creates labels array, defining names for admin panel.
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type#labels WP-Codex: register_post_type()
	 * @return array The labels array used by register_post_type().
	 * */
	public function labels() {
		return array(
			'name'          => __( $this->options( 'plural_name' ) ),
			'singular_name' => __( $this->options( 'singular_name' ) ),
			'add_new_item'  => __( $this->options( 'add_new_item' ) ),
			'edit_item'     => __( $this->options( 'edit_item' ) ),
			'new_item'      => __( $this->options( 'new_item' ) ),
			);
	}

	/**
	 * Creates metabox array for custom post type. Override method in
	 * descendants to add or modify metaboxes.
	 * @return array The metaboxes for this post_type.
	 * */
	public function metabox() {
		if ( $this->options( 'use_metabox' ) ) {
			return array(
				'id'       => 'custom_'.$this->options( 'name' ).'_metabox',
				'title'    => __( $this->options( 'singular_name' ).' Fields' ),
				'page'     => $this->options( 'name' ),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => $this->fields(),
				);
		}
		return null;
	}

	/**
	 * Registers metaboxes defined for custom post type.
	 * @see SDES_Metaboxes::show_meta_boxes() SDES_Metaboxes::show_meta_boxes()
	 * @see SDES_Metaboxes::display_meta_box_field() SDES_Metaboxes::display_meta_box_field()
	 * */
	public function register_metaboxes() {
		if ( $this->options( 'use_metabox' ) ) {
			$metabox = $this->metabox();
			add_meta_box(
				$metabox['id'],
				$metabox['title'],
				'SDES_Metaboxes::show_meta_boxes',
				$metabox['page'],
				$metabox['context'],
				$metabox['priority']
				);
		}
	}

	/**
	 * Show metaboxes that have the context 'after_title'.
	 * @see do_meta_boxes_after_title() do_meta_boxes_after_title()
	 * @see http://developer.wordpress.org/reference/hooks/edit_form_after_title/ WP-Ref: edit_form_after_title
	 */
	public static function register_meta_boxes_after_title() {
		add_action( 'edit_form_after_title', __CLASS__.'::do_meta_boxes_after_title' );
	}

	/**
	 * Callback function to print metaboxes used by add_action('edit_form_after_title').
	 * @see register_meta_boxes_after_title() register_meta_boxes_after_title()
	 * @see http://codex.wordpress.org/Function_Reference/do_meta_boxes WP-Codex: do_meta_boxes()
	 * @see http://codex.wordpress.org/Function_Reference/get_current_screen WP-Codex: get_current_screen()
	 * @see http://codex.wordpress.org/Global_Variables WP-Codex: Global variables $wp_meta_boxes, $post
	 * @param WP_Post $post The post object, passed to the metabox's callback function (defined with add_meta_box() in $this->register_metaboxes().
	 */
	public static function do_meta_boxes_after_title( $post ) {
		do_meta_boxes( get_current_screen(), 'after_title', $post ); // Output meta boxes for the 'after_title' context.
	}


	/**
	 * Get an HTML img element representing an image attachment for this post.
	 * @param int $post_id The ID of the current post.
	 * @see custom_column_echo_data() custom_column_echo_data()
	 * @see http://developer.wordpress.org/reference/functions/wp_get_attachment_image/ WP-Ref: wp_get_attachment_image()
	 * @see http://developer.wordpress.org/reference/functions/get_post_meta/ WP-Ref: get_post_meta()
	 * @see http://codex.wordpress.org/Function_Reference/get_children WP-Codex: get_children()
	 * @usedby custom_column_echo_data()
	 * @return string An html IMG element or default text.
	 */
	public static function get_thumbnail_or_attachment_image( $post_id ) {
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		if ( $thumbnail_id ) {
			$html = wp_get_attachment_image( $thumbnail_id, 'thumbnail', true );
		} else {
			$attachments = get_children(
				array( 'post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image' )
				);
			if ( $attachments ) {
				$last_attachment_id = end( $attachments );
				$html = wp_get_attachment_image( $last_attachment_id, 'thumbnail', true );
			}
		}
		if ( ! isset( $html )  || ! $html ) { $html = __( 'None' ); }
		return $html;
	}

	/**
	 * Get all custom columns for this post type. Autogenerates columns for $this->fields() with a 'custom_column_order' key (note: the value must not be 0).
	 * @see custom_columns_set_headings() custom_columns_set_headings()
	 * @see http://anahkiasen.github.io/underscore-php/#Arrays-filter Underscore-php: filter
	 * @see http://anahkiasen.github.io/underscore-php/#Arrays-each Underscore-php: each
	 * @usedby custom_columns_set_headings(), custom_columns_make_sortable() custom_columns_sort_orderby()
	 * @return Array Collection of Columns, each with the keys: 'heading', 'metafield', 'order', 'sortable', 'orderby'.
	 */
	public function custom_columns_get_all() {
		$calculated_columns = $this->options( 'calculated_columns' );
		foreach ( $calculated_columns as $calculated_column ) {
			$calculated_column = array_merge( array( 'sortable' => false, 'orderby' => 'meta_value' ), $calculated_column );
		}
		// Filter and Map fields to custom columns.
		$field_columns = Arrays::from( $this->fields() )
		->filter( function( $x ) { return array_key_exists( 'custom_column_order', $x ) && $x['custom_column_order']; } )
		->each( function( $field ) {
			$order = isset( $field['custom_column_order'] ) ? $field['custom_column_order'] : 1000;
			$sortable = isset( $field['custom_column_sortable'] ) ? $field['custom_column_sortable'] : true;
			$orderby = isset( $field['custom_column_orderby'] ) ? $field['custom_column_orderby'] : 'meta_value';
			return array(
				'heading' => $field['name'],
				'column_name' => $field['id'],
				'order' => $order,
				'sortable' => $sortable,
				'orderby' => $orderby,
				);
		})->obtain();
		$columns = Arrays::sort( array_merge( $calculated_columns, $field_columns ), 'order' );
		return $columns;
	}

	/**
	 * Add headers for custom columns to an index page (built-in listing of posts with this post_type).
	 * @see custom_columns_get_all() custom_columns_get_all()
	 * @see http://anahkiasen.github.io/underscore-php/#Arrays-group Underscore-php: group
	 * @uses custom_columns_get_all()
	 * @param array $columns An array of column name ? label. The name is passed to functions to identify the column. The label is shown as the column header.
	 * @return array The updated column names and labels.
	 */
	public function custom_columns_set_headings( $columns ) {
		foreach ( $this->custom_columns_get_all() as $custom_column ) {
			$columns[ $custom_column['column_name'] ] = $custom_column['heading'];
		}
		return $columns;
	}

	/**
	 * Enable sorting on columns by setting the column's 'orderby' to its name.
	 * @see custom_columns_sort_orderby() custom_columns_sort_orderby() implements the sorting.
	 * @see custom_columns_get_all() custom_columns_get_all()
	 * @see http://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/ WP-Ref: manage_{$this->screen->id}_sortable_columns
	 * @tutorial http://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095 Quick Tip: Make Your Custom Column Sortable
	 * @param array $columns An array of sortable columns.
	 */
	public function custom_columns_make_sortable( $columns ) {
		foreach ( $this->custom_columns_get_all() as $custom_column ) {
			if ( $custom_column['sortable'] ) {
				$columns[ $custom_column['column_name'] ] = $custom_column['column_name'];
			} else {
				unset( $columns[ $custom_column['column_name'] ] );
			}
		}
		return $columns;
	}

	/**
	 * Implement sorting for Dashboard queries where the $query's 'orderby' matches the custom_column's 'column_name'.
	 * @see custom_columns_make_sortable() custom_columns_make_sortable()
	 * @see custom_columns_get_all() custom_columns_get_all()
	 * @uses is_admin(), custom_columns_get_all()
	 * @tutorial http://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095 Quick Tip: Make Your Custom Column Sortable
	 * @see http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts WP-Codex: pre_get_posts
	 * @see http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters WP-Codex: WP_Query Orderby_Parameters
	 * @see http://anahkiasen.github.io/underscore-php/#Arrays-filter Underscore-php: filter
	 * @see http://anahkiasen.github.io/underscore-php/#Arrays-first Underscore-php: first
	 * @param WP_Query $query The query object, which is used for sorting columns.
	 * @return void Edits the $query object in-place.
	 */
	public function custom_columns_sort_orderby( $query ) {
		if ( ! is_admin() ) { return; }
		$orderby = $query->get( 'orderby' );
		$match = Arrays::from( $this->custom_columns_get_all() )
		->filter( function( $x ) use ( $orderby ) { return $orderby === $x['column_name']; } )
		->obtain();
		$count = count( $match );
		if ( 1 < $count ) {
			echo 'Admin Msg: column names must be unique. Please edit the following columns:';
			var_dump( $match );
		} elseif ( 1 === $count ) {
			$match = Arrays::first( $match );
			$query->set( 'meta_key', $match['column_name'] );
			$query->set( 'orderby', $match['orderby'] );
		}
		return;
	}

	/**
	 * Show the data for a single row ($post_id) of a column.
	 * @see get_thumbnail_or_attachment_image() get_thumbnail_or_attachment_image()
	 * @see http://developer.wordpress.org/reference/functions/get_post_meta/ WP-Ref: get_post_meta()
	 * @see http://codex.wordpress.org/Function_Reference/wp_kses_post WP-Codex: wp_kses_post()
	 * @see http://developer.wordpress.org/reference/functions/esc_attr/ WP-Ref: esc_attr()
	 * @uses get_thumbnail_or_attachment_image()
	 * @param string $column  The name of the column to display.
	 * @param int    $post_id The ID of the current post.
	 * @return void  Echos the data for this column.
	 */
	public function custom_column_echo_data( $column, $post_id ) {
		switch ( $column ) {
			case '_thumbnail_id':
			echo wp_kses_post( static::get_thumbnail_or_attachment_image( $post_id ) );
			break;
			default:
			echo esc_attr( get_post_meta( $post_id, $column, true ) );
			break;
		}
		return;
	}

	/**
	 * Add columns to an index page (built-in listing of posts with this post_type).
	 * @see custom_columns_set_headings() custom_columns_set_headings()
	 * @see custom_columns_make_sortable() custom_columns_make_sortable()
	 * @see custom_column_echo_data() custom_column_echo_data()
	 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns WP-Codex: manage_$post_type_posts_columns
	 * @see http://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/ WP-Ref: manage_{$this->screen->id}_sortable_columns
	 * @see http://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column WP-Codex: manage_$post_type_posts_custom_column
	 * @uses options(), $name.
	 */
	public function register_custom_columns() {
		$posttype = $this->options( 'name' );
		// Column Header.
		add_filter( "manage_{$posttype}_posts_columns", array( $this, 'custom_columns_set_headings' ) );
		// Sorting and Order By.
		add_filter( "manage_edit-{$posttype}_sortable_columns", array( $this, 'custom_columns_make_sortable' ) );
		add_action( 'pre_get_posts', array( $this, 'custom_columns_sort_orderby' ) );
		// Show data in rows.
		/* add_action( $tag, $function_to_add, $priority, $accepted_args ); // Signature. */
		add_action( "manage_{$posttype}_posts_custom_column" , array( $this, 'custom_column_echo_data' ), 10, 2 );
	}

	/**
	 * Registers the custom post type and any other ancillary actions that are
	 * required for the post to function properly.
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type WP-Codex: register_post_type()
	 * @see http://codex.wordpress.org/Function_Reference/add_shortcode WP-Codex: add_shortcode()
	 * @uses labels(), supports(), options(), custom_columns_get_all(), register_custom_columns()
	 * @uses $public, $taxonomies, $built_in, $use_order, $name, $use_shortcode
	 * @param Array $args Override the registration args passed to register_post_type.
	 * */
	public function register( $args = array() ) {
		// Register the post type.
		$registration = array(
			'labels'     => $this->labels(),
			'supports'   => $this->supports(),
			'public'     => $this->options( 'public' ),
			'taxonomies' => $this->options( 'taxonomies' ),
			'_builtin'   => $this->options( 'built_in' ),
			'menu_icon'  => $this->options( 'menu_icon' ),
			);
		if ( $this->options( 'use_order' ) ) {
			$registration = array_merge( $registration, array( 'hierarchical' => true ) );
		}
		$registration = array_merge( $registration, $args );
		register_post_type( $this->options( 'name' ), $registration );
		// Register custom columns.
		$custom_columns = $this->custom_columns_get_all();
		if ( null !== $custom_columns && isset( $custom_columns[0] ) ) { $this->register_custom_columns(); }
		// Add shortcode.
		if ( $this->options( 'use_shortcode' ) ) {
			add_shortcode( $this->options( 'name' ).'-list', array( $this, 'shortcode' ) );
		}
	}

	/**
	 * Shortcode callback for this custom post type.  Can be overridden for descendants.
	 * Defaults to just outputting a list of objects outputted as defined by
	 * toHTML method.
	 * @see CustomPostType::sc_object_list sc_object_list()
	 * @see CustomPostType::objectsToHTML objectsToHTML()
	 * @see CustomPostType::toHTML toHTML()
	 * @uses CustomPostType::sc_object_list() sc_object_list()
	 * @param array $attr The attributes for this shortcode.
	 * @return string Return the html output for this shortcode.
	 * */
	public function shortcode( $attr ) {
		$default = array(
			'type' => $this->options( 'name' ),
			);
		if ( is_array( $attr ) ) {
			$attr = array_merge( $default, $attr );
		} else {
			$attr = $default;
		}
		$args = array( 'classname' => __CLASS__ );
		return self::sc_object_list( $attr, $args );  // Prevent override by child class - use self instead of static.
	}

	/**
	 * Static method that tries to call the correct instance method of objectsToHTML.
	 * @param WP_Post $objects    Iterable of the post objects to display.
	 * @param string  $css_classes List of css classes for the objects container.
	 * @param string  $classname   Override the classname to instantiate the class.
	 */
	public static function tryObjectsToHTML( $objects, $css_classes, $classname = '' ) {
		if ( count( $objects ) < 1 ) { return (WP_DEBUG) ? '<!-- No objects were provided to objectsToHTML. -->' : '';}
		$classname = ( '' === $classname ) ? $objects[0]->post_type : $classname;
		if ( class_exists( $classname ) ) {
			$class = new $classname;
			return $class->objectsToHTML( $objects, $css_classes );
		} else { return ''; }
	}

	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 * @param WP_Post $objects Iterable of the post objects to display.
	 * @param string  $css_classes List of css classes for the objects container.
	 * @see render_objects_to_html() render_objects_to_html()
	 * @uses render_objects_to_html()
	 * @see http://php.net/manual/en/language.oop5.late-static-bindings.php
	 * @see Always prefer `static::` over `self::` (in PHP 5.3+): http://stackoverflow.com/a/6807615
	 * */
	public function objectsToHTML( $objects, $css_classes ) {
		if ( count( $objects ) < 1 ) { return (WP_DEBUG) ? '<!-- No objects were provided to objectsToHTML. -->' : '';}
		$context['objects'] = $objects;
		$context['css_classes'] = ( $css_classes ) ? $css_classes : $this->options( 'name' ).'-list';
		return static::render_objects_to_html( $context );
	}

	/**
	 * Render HTML for a collection of objects.
	 * @param Array $context An array of sanitized variables to display with this view.
	 * @uses toHTML() toHTML()
	 * @usedby render_objects_to_html()
	 */
	protected static function render_objects_to_html( $context ) {
		ob_start();
		?>
		<ul class="<?= $context['css_classes'] ?>">
			<?php foreach ( $context['objects'] as $o ) : ?>
				<li>
					<?= static::toHTML( $o ) ?>
				</li>
			<?php endforeach;?>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}

	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 * @param WP_Post $object The post object to display.
	 * @see render_to_html() render_to_html()
	 * */
	public static function toHTML( $object ) {
		$context['permalink'] = get_permalink( $object->ID );
		$context['title'] = $object->post_title;
		return static::render_to_html( $context );
	}

	/**
	 * Render HTML for a single object.
	 * @param Array $context An array of sanitized variables to display with this view.
	 * @usedby toHTML()
	 */
	protected static function render_to_html( $context ) {
		ob_start();
		?>
		<a href="<?= $context['permalink'] ?>"><?= $context['post_title'] ?></a>
		<?php
		$html = ob_get_clean();
		return $html;
	}


	/**
	 * Fetches objects defined by arguments passed, outputs the objects according
	 * to the objectsToHTML method located on the object. Used by the auto
	 * generated shortcodes enabled on custom post types. See also:
	 *
	 * CustomPostType::objectsToHTML
	 * CustomPostType::toHTML
	 *
	 * @param array $attrs Search params mapped to WP_Query args.
	 * @param array $args  Override values/behaviors of this function.
	 *  $args['classname'] string - Override the classname to instantiate the class.
	 * @return string
	 * @author Jared Lang
	 * @see https://github.com/UCF/Students-Theme/blob/6ca1d02b062b2ee8df62c0602adb60c2c5036867/functions/base.php#L780-L903
	 **/
	public static function sc_object_list( $attrs, $args = array() ) {
		if ( ! is_array( $attrs ) ) {return '';}

		$default_args = array(
			'default_content' => null,
			'sort_func' => null,
			'objects_only' => false,
			'classname' => '',
			);

		// Make keys into variable names for merged $default_args/$args.
		extract( array_merge( $default_args, $args ) );

		// Set defaults and combine with passed arguments.
		$default_attrs = array(
			'type'    => null,
			'limit'   => -1,
			'join'    => 'or',
			'class'   => '',
			'orderby' => 'menu_order title',
			'meta_key' => null,
			'order'   => 'ASC',
			'offset'  => 0,
			'meta_query' => array(),
			);
		$params = array_merge( $default_attrs, $attrs );
		$classname = ( '' === $classname ) ? $params['type'] : $classname;

		$params['limit']  = intval( $params['limit'] );
		$params['offset'] = intval( $params['offset'] );

		// Verify inputs.
		if ( null === $params['type'] ) {
			return '<p class="error">No type defined for object list.</p>';
		}
		if ( ! in_array( strtoupper( $params['join'] ), array( 'AND', 'OR' ) ) ) {
			return '<p class="error">Invalid join type, must be one of "and" or "or".</p>';
		}
		if ( ! class_exists( $classname ) ) {
			return '<p class="error">Invalid post type or classname.</p>';
		}

		$class = new $classname;

		// Use post type specified ordering?
		if ( ! isset( $attrs['orderby'] ) && ! is_null( $class->default_orderby ) ) {
			$params['orderby'] = $class->orderby;
		}
		if ( ! isset( $attrs['order'] ) && ! is_null( $class->default_order ) ) {
			$params['order'] = $class->default_order;
		}

		// Get taxonomies and translation.
		$translate = array(
			'tags' => 'post_tag',
			'categories' => 'category',
			'org_groups' => 'org_groups',
			);
		$taxonomies = array_diff( array_keys( $attrs ), array_keys( $default_attrs ) );

		// Assemble taxonomy query.
		$tax_queries = array();
		$tax_queries['relation'] = strtoupper( $params['join'] );

		foreach ( $taxonomies as $tax ) {
			$terms = $params[ $tax ];
			$terms = trim( preg_replace( '/\s+/', ' ', $terms ) );
			$terms = explode( ' ', $terms );
			if ( '' === $terms[0] ) { continue; } // Skip empty taxonomies.

			if ( array_key_exists( $tax, $translate ) ) {
				$tax = $translate[ $tax ];
			}

			foreach ( $terms as $idx => $term ) {
				if ( in_array( strtolower( $term ), array( 'none', 'null', 'empty' ) ) ) {
					unset( $terms[ $idx ] );
					$terms[] = '';
				}
			}

			$tax_queries[] = array(
				'taxonomy' => $tax,
				'field' => 'slug',
				'terms' => array_unique( $terms ),
				);
		}

		// Perform query.
		$query_array = array(
			'tax_query'      => $tax_queries,
			'post_status'    => 'publish',
			'post_type'      => $params['type'],
			'posts_per_page' => $params['limit'],
			'orderby'        => $params['orderby'],
			'order'          => $params['order'],
			'offset'         => $params['offset'],
			'meta_query'     => $params['meta_query'],
			);

		$query = new \WP_Query( $query_array );

		global $post;
		$objects = array();
		while ( $query->have_posts() ) {
			$query->the_post();
			$objects[] = $post;
		}

		// Custom sort if applicable.
		if ( null !== $sort_func ) {
			usort( $objects, $sort_func );
		}

		wp_reset_postdata();

		if ( $objects_only ) {
			return $objects;
		}

		if ( count( $objects ) ) {
			$html = $class->objectsToHTML( $objects, $params['class'] );
		} else {
			if ( isset( $tax_queries['terms'] ) ) {
				$default_content .= '<!-- No results were returned for: ' . $tax_queries['taxonomy'] . '. Does this attribute need to be unset()? -->';
			}
			$html = $default_content;
		}
		return $html;
	}

	/**
	 * Register the custom post types wiht WordPress and auxilliary classes (should be called from custom-posttypes.php).
	 * @param Array $custom_posttypes Names of custom post types classes to register.
	 * @return  Array Array of instantiated posttype classes (array of arrays). Each item has the keys: 'classname', 'instance'.
	 * @see SDES_Static::instantiate_and_register_classes()
	 */
	public static function Register_Posttypes( $custom_posttypes ) {
		$posttype_instances = SDES_Static::instantiate_and_register_classes( $custom_posttypes );
		foreach ( $posttype_instances as $registered_posttype ) {
			if ( class_exists( 'SDES_Metaboxes' ) ) {
				SDES_Metaboxes::$installed_custom_post_types[] = $registered_posttype['instance'];
			}
			if ( class_exists( 'ShortcodeBase' ) ) {
				ShortcodeBase::$installed_custom_post_types[] = $registered_posttype['instance'];
			}
		}
		static::Register_Thumbnails_Support( $posttype_instances );
		return $posttype_instances;
	}

	/**
	 * Register theme support for any custom post_types with $this->use_thumbnails set to true.
	 * @param Array $instances Instantiated classes for Custom Post Types.
	 * @see http://codex.wordpress.org/Function_Reference/add_theme_support Codex: add_theme_support
	 * @return void
	 */
	public static function Register_Thumbnails_Support( $instances ) {
		// If the key $instances[0]['instance'] exists.
		if ( Arrays::has( Object_A::unpack( $instances ), 'instance' ) ) {
			$instances = Arrays::pluck( $instances, 'instance' );
		}

		$thumbnail_posttypes
		= Arrays::from( $instances )
		->filter( function( $x ) { return true === $x->use_thumbnails; } )
		->pluck( 'name' )
		->obtain();
		add_theme_support( 'post-thumbnails', $thumbnail_posttypes );

		/** Scale thumbnails by default. */ define( 'SDES\\SCALE', false );
		/** Crop thumbnails by default. */  define( 'SDES\\CROP', true );
		// For cropping behavior see `add_image_size`, e.g.: http://core.trac.wordpress.org/browser/tags/4.4.2/src/wp-includes/media.php#L228 .
		set_post_thumbnail_size( 125, 125, CROP );
		// $crop_from = array( 'top', 'left');
		// set_post_thumbnail_size( 125, 125, $crop_from );
	}
}

CustomPostType::register_meta_boxes_after_title();