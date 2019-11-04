<?php
/**
 * Add and configure Shortcodes for this theme.
 * Relies on the implementation in ShortcodeBase.
 */

namespace SDES\BaseTheme\Shortcodes;

use \StdClass;
use \Exception;
use \SimpleXMLElement;

require_once( get_stylesheet_directory().'/functions/class-shortcodebase.php' );
use SDES\Shortcodes\ShortcodeBase;

require_once( 'functions/class-sdes-static.php' );
use SDES\SDES_Static as SDES_Static;

require_once( get_stylesheet_directory().'/vendor/autoload.php' );
use Underscore\Types\Arrays;

/**
 * [menuPanel] - Return an in-line menu panel (DIV.panel) using a user-configured Menu.
 * Available attributes:
 * name      => The "Menu Name" of the menu under Appearance>Menus, e.g., Pages
 * heading   => Display an alternate heading instead of the menu Id.
 *
 * Example:
 * [menuPanel name="Other Resources" heading="An Alternate heading"]
 */
class sc_menuPanel extends ShortcodeBase {
	public
	$name = 'Menu Panel',
	$command = 'menuPanel',
	$description = 'Show panelled menu, usually in sidecolumns.',
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true,
	$params      = array(
		array(
			'name'      => 'Menu Name',
			'id'        => 'name',
			'help_text' => 'The menu to display.',
			'type'      => 'text',
			),
		array(
			'name'      => 'Heading',
			'id'        => 'heading',
			'help_text' => 'A heading to display (optional).',
			'type'      => 'text',
			),
	); // The parameters used by the shortcode.

	function __construct() {
		$menus = wp_get_nav_menus();
		$choices = array();
		foreach ( $menus as $menu ) {
			if ( ! is_wp_error( $menu ) && ! array_key_exists( 'invalid_taxonomy', $menu ) && ! empty( $menu ) ) {
				$choices[] = array( 'value' => $menu->slug, 'name' => $menu->name );
			}
		}
		$new_name_param = Arrays::from( $this->params )
		->find( function( $x ) { return 'name' === $x['id']; } )
		->set( 'type', 'dropdown' )
		->set( 'choices', $choices )
		->obtain();
		$other_params = Arrays::from( $this->params )
		->filter( function( $x ) { return 'name' !== $x['id']; } )
		->obtain();
		$this->params = array_merge( array( $new_name_param ), $other_params );
	}

	public static function callback( $attrs, $content = null ) {
		$attrs = shortcode_atts(
			array(
				'name' => 'Pages',
				'heading' => $attrs['name'],
				'style' => 'max-width: 697px;',
				), $attrs
			);
		// Check for errors
		if ( ! is_nav_menu( $attrs['name'] ) ) {
			$error = sprintf( 'Could not find a nav menu named "%1$s"', $attrs['name'] );
			// Output as HTML comment when not logged in or can't edit.
			$format_error =
			( SDES_Static::Is_UserLoggedIn_Can( 'edit_posts' ) )
			? '<p class="bg-danger text-danger">Admin Alert: %1$s</p>'
			: '<!-- %1$s -->';
			$error = sprintf( $format_error, $error );
			return $error;
		}
		// Sanitize input and set context for view.
		$context['heading'] = esc_html( $attrs['heading'] );
		$context['menu_items'] = wp_get_nav_menu_items( esc_attr( $attrs['name'] ) );
		$context['style'] = esc_attr( $attrs['style'] );
		return static::render( $context );
	}

	/**
	 * Render HTML for a "menuPanel" shortcode with a given context.
	 * Context variables:
	 * heading    => The panel-heading.
	 * menu_items => An array of WP_Post objects representing the items in the menu.
	 * style  => Value for the css attribute "style" on the container div.
	 */
	public static function render( $context ) {
			ob_start();
			?>

			<div class="menu">
				<div class="menu-header">
					<?= $context['heading'] ?>
				</div>
				<ul class="list-group menu-right list-unstyled">
					<?php 
					foreach ( (array) $context['menu_items'] as $key => $menu_item ) {
						$title = $menu_item->title;
						$url = SDES_Static::url_ensure_prefix( $menu_item->url );
						//removed as of new theme 8/31/2017
						//$class_names = SDES_Static::Get_ClassNames( $menu_item, 'nav_menu_css_class' );
					?>
						<li><a href="<?= $url ?>" class="list-group-item"><?= $title ?></a></li>
					<?php  } ?>
				</ul>
			</div>
			
			<?php
			return ob_get_clean();
		}
	}

/**************** SHORTCODE Boilerplate START **********************
 * [myShortcode] - Shortcode description.
 * Available attributes:
 * attr1 => Description of attr1.
 * attr2 => Description of attr2.
 *
 * Example:
 * [myShortcode attr1="SomeValue" attr2="AnotherValue"]
 */
function sc_myShortcode( $attrs, $content = null ) {
	// Default attributes.
	SDES_Static::set_default_keyValue( $attrs, 'attr1', 'SomeValue' );
	SDES_Static::set_default_keyValue( $attrs, 'attr2', 'AnotherValue' );
	// Sanitize input.
	$attrs['attr1'] = esc_attr( $attrs['attr1'] );
	$attrs['attr2'] = esc_attr( $attrs['attr2'] );

	// Shortcode logic.

	// Set context for view.
	$context['disp1'] = $attrs['attr1'];
	$context['disp2'] = $attrs['attr2'];
	// Render HTML.
	return rencer_sc_myShortcode( $context );
}
add_shortcode( 'myShortcode', 'sc_myShortcode' );
/**
 * Render HTML for a "myShortcode" shortcode with a given context.
 * Context variables:
 * disp1 => Description.
 * disp2 => Description.
 */
function render_sc_myShortcode( $context ) {
	ob_start();
	?>
	<div>Some: <?=$context['disp1']?></div>
	<div>Another: <?=$context['disp2']?></div>
	<?php
	return ob_get_clean();
}
/**************** SHORTCODE Boilerplate END   **********************/

require_once( get_stylesheet_directory().'/functions/class-shortcodebase.php' );

/**
 * [events] - Show an events calendar from events.ucf.edu
 */
class sc_events extends ShortcodeBase {
	public
	$name = 'Events', // The name of the shortcode.
	$command = 'events', // The command used to call the shortcode.
	$description = 'Show events calendar from a feed', // The description of the shortcode.
	$callback    = 'callback',
	$render      = false,
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(
			'name'      => 'Event Id',
			'id'        => 'id',
			'help_text' => 'The calendar_id of the events.ucf.edu calendar.',
			'type'      => 'text',
			),
		array(
			'name'      => 'Header',
			'id'        => 'header',
			'help_text' => 'A header for this events calendar.',
			'type'      => 'text',
			'default'   => 'Upcoming Events',
			),
		array(
			'name'      => 'Limit',
			'id'        => 'limit',
			'help_text' => 'Only show this many items.',
			'type'      => 'number',
			'default'   => 6,
			),
	); // The parameters used by the shortcode.

	/**
	 * @see https://github.com/ucf-sdes-it/it-php-template/blob/e88a085401523f78b812ea8b4d9557ba30e40c9f/template_functions_generic.php#L241-L326
	 */
	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'id' => 41, // SDES Events calendar.
				'limit' => 6,
				'header'    => 'Upcoming Events',
				'timezone' => 'America/New_York',
				), $attr
			);
		if ( null === $attr['id'] ) { return true; }
		
		// Open cURL instance for the UCF Event Calendar RSS feed.
		$ch = curl_init( "https://events.ucf.edu/?calendar_id={$attr['id']}&upcoming=upcoming&format=rss" );

		// Set cURL options.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		$rss = curl_exec( $ch );
		curl_close( $ch );
		$rss = @utf8_encode( $rss );
		// Disable libxml errors and allow user to fetch error information as needed.
		libxml_use_internal_errors( true );
		try {
			$xml = new SimpleXMLElement( $rss, LIBXML_NOCDATA );
		} catch ( Exception $e ) { }
		// If there are errors.
		if ( libxml_get_errors() ) {
			ob_start();
			?>
			<li>Failed loading XML</li>
			<?php foreach ( libxml_get_errors() as $error ) : ?>
				<li><?= htmlentities( $error->message ) ?></li>
			<?php endforeach;
			return ob_get_clean();
		}		

		// Set limit if items returned are smaller than limit.
		$count = ( count( $xml->channel->item ) > $attr['limit'] ) ? $attr['limit'] : count( $xml->channel->item );
		ob_start();
		?>
			
			<h2><?= $attr['header'] ?></h2>
			<hr>
			<?= $footer ?>
			
				<?php
					// Check for items.
				if ( 0 === count( $xml->channel->item ) ) : ?>
				<p>Sorry, no events could be found.</p>
				<?php
				else :
						// Loop through until limit.
					for ( $i = 0; $i < $count; $i++ ) {
							// Prepare xml output to html.
						$title = htmlentities( $xml->channel->item[ $i ]->title );
						$title = ( strlen( $title ) > 25) ? substr( $title, 0, 19 ) : $title;
						$loc = htmlentities( $xml->channel->item[ $i ]->children( 'ucfevent', true )->location->children( 'ucfevent', true )->name );
						$map = htmlentities( $xml->channel->item[ $i ]->children( 'ucfevent', true )->location->children( 'ucfevent', true )->mapurl );
						$startTime = new \DateTime( $xml->channel->item[ $i ]->children( 'ucfevent', true )->startdate, new \DateTimeZone( $attr['timezone'] ) );
						$context['datetime'] = $startTime->format( DATE_ISO8601 );
						$context['month'] = $startTime->format( 'M' );
						$context['day'] = $startTime->format( 'j' );
						$context['link'] = htmlentities( $xml->channel->item[ $i ]->link );

						?>    
						<div class="row event">
							<div class="col-sm-3 date">								
								<div class="month"><?= $context['month'] ?></div>
								<div class="day"><?= $context['day'] ?></div>								
							</div>
							<div class="col-sm-8 description">
								<h3 class="event-title">
									<a href="<?= $context['link'] ?>">
									<?= $title ?>
										
									</a>
								</h3>
								<h4 class="location"><a href="<?= $context['link'] ?>"><?= $loc ?></a></h4>			
							</div>
						</div>
						<?php }
						endif; ?>
					
					<p>
						<a class="btn btn-callout float-right" href="//events.ucf.edu/?calendar_id=<?= $attr['id'] ?>&amp;upcoming=upcoming">More Events</a>
					</p>
					<div class="clearfix"></div>
		
				<?php
				return ob_get_clean();
			}
		}

require_once( get_stylesheet_directory().'/custom-posttypes.php' );
use SDES\BaseTheme\PostTypes\Alert;
/**
 * Use code from the Alert class in a shortcode.
 * Extending Alert to add ContextToHTML, assuming responsiblity for sanitizing inputs.
 */
class AlertWrapper extends Alert {
	public static function ContextToHTML( $alert_context ) {
		return static::render_to_html( $alert_context );
	}
}

/**
 * [alert] - Show a single, ad-hoc alert directly in a page's content.
 */
class sc_alert extends ShortcodeBase {
	public
	$name = 'Alert (Ad hoc)', // The name of the shortcode.
	$command = 'alert', // The command used to call the shortcode.
	$description = 'Show an alert on a single page.', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(
			'name'      => 'Is Unplanned',
			'id'        => 'is_unplanned',
			'help_text' => 'Show the alert as red instead of yellow.',
			'type'      => 'checkbox',
			'default'   => true,
			),
		array(
			'name'      => 'Title',
			'id'        => 'title',
			'help_text' => 'A title for the alert (shown in bold).',
			'type'      => 'text',
			'default'   => 'ALERT',
			),
		array(
			'name'      => 'Message',
			'id'        => 'message',
			'help_text' => 'Message text for the alert.',
			'type'      => 'text',
			'default'   => 'Alert',
			),
		array(
			'name'      => 'URL',
			'id'        => 'url',
			'help_text' => 'Make the alert a link.',
			'type'      => 'text',
			'default'   => '',
			),
	); // The parameters used by the shortcode.


	public static function callback( $attr, $content = '' ) {
		$attr = shortcode_atts(
			array(
				'title' => 'ALERT',
				'message' => 'Alert',
				'is_unplanned' => true,
				'url' => null,
				), $attr
			);
		$attr['is_unplanned'] = filter_var( $attr['is_unplanned'], FILTER_VALIDATE_BOOLEAN );

		// Create and sanitize mocks for WP_Post and metadata using the shortcode attributes.
		$alert = new StdClass;
		$alert->post_title = esc_attr( $attr['title'] );
		$alert->post_content = esc_attr( $attr['message'] );
		$metadata_fields = array(
			'alert_is_unplanned' => $attr['is_unplanned'],
			'alert_url' => esc_attr( SDES_Static::url_ensure_prefix( $attr['url'] ) ),
			);
		$ctxt = AlertWrapper::get_render_context( $alert, $metadata_fields );
		return AlertWrapper::ContextToHTML( $ctxt );
	}
}

class sc_contactblock extends ShortcodeBase{
	public
	$name = 'Contact', // The name of the shortcode.
	$command = 'contactblock', // The command used to call the shortcode.
	$description = 'Show the contact information box without custumizer or cms.', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(			
			'name'      => 'Contact Block',
			'id'        => 'contactname',
			'help_text' => 'This is the Title of the Contact block you would like to display.',
			'type'      => 'text',
			),
		array(
			'name'      => 'Footer',
			'id'        => 'is_footer',
			'help_text' => 'Is this the footer',
			'type'      => 'text',
			'default'	=> 'this is a test'
			),
		);

	public static function callback( $attr, $content = '' ) {
		//get the post_id based on title given by user
		$id = get_posts(array(
			'post_type' => 'contact',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			), 'OBJECT');

		if(!empty($id)){
			foreach($id as $item){			
				if(strtolower($item->post_title) == strtolower($attr['contactname'])){	
					return static::render( $item->ID, $attr['is_footer'] );
				}	//end of if		
			}	//end of for
		}else{
			return '<div class="alert alert-danger">Go to contact and add a contact named Main.</div>';
		}	//end of ifelse

		return '<div class="alert alert-danger">No contact block exists with this name.</div>';
		
	}

	public static function render ( $attr, $footer = null ){
		
		$data = get_post_meta($attr);

		ob_start();

		if(empty($footer)){
		?>	
		
		<table class="table table-hover">
			<tbody>
				<?php if(!empty($data['contact_Hours'][0])) { ?>
				<tr>
					<th scope="row">
						<i class="fa fa-lg fa-fw fa-clock-o"><span class="sr-only">Phone</span></i>
					</th>
					<td><?= $data['contact_Hours'][0] ?></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_phone'][0])) { ?>
				<tr>
					<th scope="row">
						<i class="fa fa-lg fa-fw fa-phone"><span class="sr-only">Phone</span></i>
					</th>
					<td><a href="tel:<?= $data['contact_phone'][0] ?>"><?= $data['contact_phone'][0] ?></a></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_fax'][0])) { ?>
				<tr>
					<th scope="row">
						<i class="fa fa-lg fa-fw fa-fax"><span class="sr-only">Fax</span></i>
					</th>
					<td><?= $data['contact_fax'][0] ?></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_email'][0])) { ?>
				<tr>
					<th scope="row">
						<i class="fa fa-lg fa-fw fa-envelope"><span class="sr-only">Email</span></i>
					</th>
					<td><a href="mailto:<?= $data['contact_email'][0] ?>"> <?= $data['contact_email'][0] ?></a></td>
				</tr>
				<?php } ?>
				<?php if(!empty($data['contact_room'][0]) && !empty($data['contact_building'][0]) && !empty($data['contact_room'][0])) { ?>
				<tr>
					<th scope="row">
						<i class="fa fa-lg fa-fw fa-map-marker"><span class="sr-only">Location</span></i>
					</th>
					<td><a href="http://map.ucf.edu/?show=<?= $data['contact_map_id'][0] ?>" class="external"><?=	$data['contact_building'][0] ?>, Room <?= $data['contact_room'][0]?></a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
		<?php
			} else{
		?> 
			<P>
				<?php if(!empty($data['contact_phone'][0])) { ?>
				
					<i class="fa fa-lg fa-fw fa-phone"><span class="sr-only">Phone</span></i>
					<a href="tel:<?= $data['contact_phone'][0] ?>"><?= $data['contact_phone'][0] ?></a><br />
				<?php } ?>
				<?php if(!empty($data['contact_email'][0])) { ?>
				
					<i class="fa fa-lg fa-fw fa-envelope"><span class="sr-only">Email</span></i>
					<a href="mailto:<?= $data['contact_email'][0] ?>"> <?= $data['contact_email'][0] ?></a><br />
				<?php } ?>
				<?php if(!empty($data['contact_room'][0]) && !empty($data['contact_building'][0]) && !empty($data['contact_room'][0])) { ?>
				
					<i class="fa fa-lg fa-fw fa-map-marker"><span class="sr-only">Location</span></i>
					<a href="http://map.ucf.edu/?show=<?= $data['contact_map_id'][0] ?>" class="external"><?=	$data['contact_building'][0] ?>, Room <?= $data['contact_room'][0]?></a>
				<?php } ?>
			</P>


		<?php
			}
		return ob_get_clean();
	}
}

class social_media extends ShortcodeBase{
	public
	$name = 'Social Media', // The name of the shortcode.
	$command = 'social_media', // The command used to call the shortcode.
	$description = '', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(			
			'name'      => 'Facebook Url',
			'id'        => 'facebook',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Flickr Url',
			'id'        => 'flickr',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Google+ Url',
			'id'        => 'google_plus',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Instagram Url',
			'id'        => 'instagram',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'LinkedIn Url',
			'id'        => 'linkedin',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Pinterest Url',
			'id'        => 'pinterest',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Twitter Url',
			'id'        => 'twitter',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Tumblr Url',
			'id'        => 'tumblr',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Vimeo Url',
			'id'        => 'vimeo',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Youtube Url',
			'id'        => 'youtube',
			'help_text' => '',
			'type'      => 'text',
			),
		);

	public static function callback( $attr, $content = '' ) {

		ob_start();
		?>

		<div class="card-columns social mt-3">
			<?php if (!empty($attr['facebook'])) { ?>
			<div class="card">
				<a class="btn btn-block facebook text-lg-center" href="<?= $attr['facebook'] ?>"><span class="fa fa-facebook-official fa-fw fa-lg"></span><span class="sr-only">&emsp;Facebook</span></a>
			</div>
			<?php } if (!empty($attr['flickr'])) { ?>
			<div class="card">
				<a class="btn btn-block flickr text-lg-center" href="<?= $attr['flickr'] ?>"><span class="fa fa-flickr fa-fw fa-lg"></span><span class="sr-only">&emsp;Flickr</span></a>
			</div>
			<?php } if (!empty($attr['google_plus'])) { ?>
			<div class="card">
				<a class="btn btn-block gplus text-lg-center" href="<?= $attr['google_plus'] ?>"><span class="fa fa-google-plus fa-fw fa-lg"></span><span class="sr-only">&emsp;Google+</span></a>
			</div>
			<?php } if (!empty($attr['instagram'])) { ?>
			<div class="card">
				<a class="btn btn-block instagram text-lg-center" href="<?= $attr['instagram'] ?>"><span class="fa fa-instagram fa-fw fa-lg"></span><span class="sr-only">&emsp;Instagram</span></a>
			</div>
			<?php } if (!empty($attr['linkedin'])) { ?>
			<div class="card">
				<a class="btn btn-block linkedin text-lg-center" href="<?= $attr['linkedin'] ?>"><span class="fa fa-linkedin fa-fw fa-lg"></span><span class="sr-only">&emsp;LinkedIn</span></a>
			</div>
			<?php } if (!empty($attr['pinterest'])) { ?>
			<div class="card">
				<a class="btn btn-block pinterest text-lg-center" href="<?= $attr['pinterest'] ?>"><span class="fa fa-pinterest fa-fw fa-lg"></span><span class="sr-only">&emsp;Pinterest</span></a>
			</div>
			<?php } if (!empty($attr['twitter'])) { ?>
			<div class="card">
				<a class="btn btn-block twitter text-lg-center" href="<?= $attr['twitter'] ?>"><span class="fa fa-twitter fa-fw fa-lg"></span><span class="sr-only">&emsp;Twitter</span></a>
			</div>
			<?php } if (!empty($attr['tumblr'])) { ?>
			<div class="card">
				<a class="btn btn-block tumblr text-lg-center" href="<?= $attr['tumblr'] ?>"><span class="fa fa-tumblr fa-fw fa-lg"></span><span class="sr-only">&emsp;Tumblr</span></a>
			</div>
			<?php } if (!empty($attr['vimeo'])) { ?>
			<div class="card">
				<a class="btn btn-block vimeo text-lg-center" href="<?= $attr['vimeo'] ?>"><span class="fa fa-vimeo fa-fw fa-lg"></span><span class="sr-only">&emsp;Vimeo</span></a>
			</div>
			<?php } if (!empty($attr['youtube'])) { ?>
			<div class="card">
				<a class="btn btn-block youtube text-lg-center" href="<?= $attr['youtube'] ?>"><span class="fa fa-youtube fa-fw fa-lg"></span><span class="sr-only">&emsp;YouTube</span></a>
			</div>
			<?php } ?>
		</div>		
		
		<?php
		return ob_get_clean();
	}
	
}

class sc_iframe extends ShortcodeBase{
	public
	$name = 'IFrame', // The name of the shortcode.
	$command = 'iframe', // The command used to call the shortcode.
	$description = '', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(			
			'name'      => 'IFrame Url',
			'id'        => 'if_url',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Width',
			'id'        => 'if_width',
			'help_text' => '',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Height',
			'id'        => 'if_height',
			'help_text' => '',
			'type'      => 'text',
			),
		
		);

	public static function callback( $attr, $content = '' ) {

		ob_start();
		?>
			<?php
				if (strpos($attr['if_url'], 'youtube') !== false) {
			?>

			<iframe src="<?= $attr['if_url'] ?>" width="<?= $attr['if_width'] ?>" height="<?= $attr['if_height'] ?>" frameborder="0" scrolling="no" allowfullscreen></iframe>

			<?php
				} else {
			?>
				<iframe src="<?= $attr['if_url'] ?>" width="<?= $attr['if_width'] ?>" height="<?= $attr['if_height'] ?>" frameborder="0" scrolling="no" ></iframe>
			<?php
				}
			?>

		<?php
		return ob_get_clean();
	}

}

class sc_redirect extends ShortcodeBase{
	public
	$name = 'Redirect', // The name of the shortcode.
	$command = 'redirect', // The command used to call the shortcode.
	$description = '', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = true, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(			
			'name'      => 'Redirect Url',
			'id'        => 'redirect_url',
			'help_text' => '',
			'type'      => 'text',
			),
		
		);

	public static function callback( $attr, $content = null) {

		echo 'Seems like you have JavaScript turned off please go to <a href="'.$attr['redirect_url'].'">'.$attr['redirect_url'].'</a>';		
		
		echo '<script type="text/javascript">
           		window.location = "'.$attr['redirect_url'].'"
      		</script>';
      				
	}

}

class sc_countdown extends ShortcodeBase{
	public
	$name = 'Countdown', // The name of the shortcode.
	$command = 'Countdown', // The command used to call the shortcode.
	$description = '', // The description of the shortcode.
	$callback    = 'callback',
	$render      = 'render',
	$closing_tag = false,
	$wysiwyg     = false, // Whether to add it to the shortcode Wysiwyg modal.
	$params      = array(
		array(			
			'name'      => 'Countdown Date',
			'id'        => 'countdown_date',
			'help_text' => 'Format: Jan 5, 2021',
			'type'      => 'text',
			),
		array(			
			'name'      => 'Countdown Title',
			'id'        => 'countdown_title',
			'help_text' => '',
			'type'      => 'text',
			),
		
		);

	public static function callback( $attr, $content = null) {

		?>

		<script>
			// Set the date we're counting down to
			var countDownDate = new Date("<?= $attr['countdown_date'] ?>").getTime();

			// Update the count down every 1 second
			var x = setInterval(function() {

				// Get todays date and time
				var now = new Date().getTime();

				// Find the distance between now and the count down date
				var distance = countDownDate - now;

				// Time calculations for days, hours, minutes and seconds
				var days = Math.floor(distance / (1000 * 60 * 60 * 24 ));
				var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);

				// Display the result in the element with id="countdown"
				document.getElementById("countdown").innerHTML = "<div class='col day'><span class='num'>" + days + "</span><span class='word'>Days</span></div>" + "<div class='col hour'><span class='num'>" + hours + "</span><span class='word'>Hrs</span></div>" + "<div class='col min'><span class='num'>" + minutes + "</span><span class='word'>Mins</span></div>" + "<div class='col sec'><span class='num'>" + seconds + "</span><span class='word'>Secs</span></div>";

				// If the count down is finished, write some text 
				if (distance < 0) {
					clearInterval(x);
					document.getElementById("countdown").innerHTML = "<h3 style='color: white; text-align: center;'>We are now open</h3>";
					}
			}, 1000);
		</script>		
		
		<?php

		echo '<h3 style="text-align: center;color:white;background: black;margin-bottom: 0px;padding-top: 10px;">'.$attr["countdown_title"] .'</h3><div id="countdown" class="countdown"></div>';
      				
	}

}

function register_shortcodes() {
	ShortcodeBase::Register_Shortcodes(array(
		__NAMESPACE__.'\sc_alert',		
		__NAMESPACE__.'\sc_menuPanel',
		__NAMESPACE__.'\sc_events',
		__NAMESPACE__.'\sc_contactBlock',
		__NAMESPACE__.'\social_media',
		__NAMESPACE__.'\sc_iframe',
		__NAMESPACE__.'\sc_redirect',
		__NAMESPACE__.'\sc_countdown',
		));
}
add_action( 'init', __NAMESPACE__.'\register_shortcodes' );
