<?php
/**
 * Static functions to display HTML.
 * @package Rev2015 WordPress Prototype
 */
namespace SDES\BaseTheme;

require_once( 'class-sdes-static.php' );
use SDES\SDES_Static as SDES_Static;

/**
 * Container for HTML template functions.
 * This should only include static functions (i.e., the same parameters should always return the same output).
 */
class Render_Template
{
	/**
	 * HTML for contact block in the footer.
	 * @see Render_Template::echo_and_validate
	 * @see SDES_Static::set_default_keyValue()
	 * @param Array $context Array containing key-value pairs to insert into the HTML template.
	 *  Context variables:
	 *   header => The H2 header for this block (defaults to "Contact").
	 *   departmentName => The department name.
	 *   phone => The department phone number.
	 *   email => The department email.
	 *   buildingNumber => The department building number used by map.ucf.edu.
	 *   buildingName => The name of the building.
	 * @param Array $args  Any additional arguments for this function.
	 *  Bool   echo          Flag to whether to echo or return text (defaults to true).
	 *  String validate_with Specify how to validate before echoing.
	 * @return null|String  Echo the html or return as a string.
	 */
	public static function footer_contact( $context, $args = null ) {
		SDES_Static::set_default_keyValue( $context, 'header', 'Contact' );

		SDES_Static::set_default_keyValue( $args, 'echo', true );
		SDES_Static::set_default_keyValue( $args, 'validate_with', 'wp_kses' );

		$phone_block = ( '' !== $context['phone']) ? "Phone: {$context['phone']} &bull; " : '';
		ob_start();
		?>
		<h2><?= $context['header'] ?></h2>
		<p>
			<?= $context['departmentName'] ?><br />
			<?= $phone_block ?>Email: <a href="mailto:<?= $context['email'] ?>"><?= $context['email'] ?></a><br />
			<?php if( '' !== $context['buildingNumber'] && '' !== $context['buildingName'] ) : ?>
				Location: <a href="http://map.ucf.edu/?show=<?= $context['buildingNumber'] ?>">
				<?= $context['buildingName'] . ' ' . $context['roomNumber'] ?>
			</a>
		<?php else: ?>
			<a href="http://www.ucf.edu/phonebook/">UCF Phonebook</a> &bull;
			<a href="http://events.ucf.edu/">UCF Events</a> &bull;
			<a href="http://map.ucf.edu/">UCF Map</a> &bull;
			<a href="http://ucf.custhelp.com/">Ask UCF</a>
		<?php endif; ?>
	</p>
	<?php
	if ( false === $args['echo'] ) {
		return ob_get_clean();
	} else {
		Render_Template::echo_and_validate( ob_get_clean(), $args );
	}
}

	/**
	 * Render HTML for footer header with a given context.
	 * @see Render_Template::echo_and_validate
	 * @see SDES_Static::set_default_keyValue()
	 * @param Array $context Array containing key-value pairs to insert into the HTML template.
	 *  Context variables:
	 *   header => The H2 header for this block of footer links.
	 * @param Array $args  Any additional arguments for this function.
	 *  Bool   echo          Flag to whether to echo or return text (defaults to true).
	 *  String validate_with Specify how to validate before echoing.
	 * @return null|String  Echo the html or return as a string.
	 */
	public static function footer_header( $context, $args = null ) {
		SDES_Static::set_default_keyValue( $args, 'echo', true );
		SDES_Static::set_default_keyValue( $args, 'validate_with', 'wp_kses' );
		ob_start();
		?>
		<h2><?= $context['header'] ?></h2>
		<?php
		if ( false === $args['echo'] ) {
			return ob_get_clean();
		} else {
			Render_Template::echo_and_validate( ob_get_clean(), $args );
		}
	}

	/**
	 * Render HTML for footer links with a given context.
	 * @see Render_Template::echo_and_validate
	 * @see SDES_Static::set_default_keyValue()
	 * @param Array $context Array containing key-value pairs to insert into the HTML template.
	 *  Context variables:
	 *   anchors => Array of anchors, where each anchor has the keys: 'link', 'title'.
	 * @param Array $args  Any additional arguments for this function.
	 *  Bool   echo          Flag to whether to echo or return text (defaults to true).
	 *  String validate_with Specify how to validate before echoing.
	 * @return null|String  Echo the html or return as a string.
	 */
	public static function footer_links( $context, $args = null ) {
		SDES_Static::set_default_keyValue( $args, 'echo', true );
		SDES_Static::set_default_keyValue( $args, 'validate_with', 'wp_kses' );
		ob_start();
		?>
		<ul>
			<?php foreach ( $context['anchors'] as $item ) : ?>
				<li><a href="<?= $item['link']?>"><?= $item['title'] ?></a></li>
			<?php endforeach ?>
		</ul>
		<?php
		if ( false === $args['echo'] ) {
			return ob_get_clean();
		} else {
			Render_Template::echo_and_validate( ob_get_clean(), $args );
		}
	}

	/**
	 * Wrapper function to echo some text after running data validation on it.
	 * @see SDES_Static::set_default_keyValue()
	 * @see http://codex.wordpress.org/Data_Validation
	 * @see http://codex.wordpress.org/Function_Reference/wp_kses
	 * @param string $output  The string to be echoed.
	 * @param Array  $args 	  Any additonal arguments for this function.
	 *  String validate_with    Specify how to validate before echoing.
	 *  Array  kses_allowd_html Allow HTML elements (defaults to wp_kses_allowed_html('post')).
	 *  Array  kses_protocols   Optional listing of allowed protocols.
	 * @return void
	 */
	public static function echo_and_validate( $output, $args ) {
		switch ( $args['validate_with'] ) {
			case 'wp_kses':
			default:
			SDES_Static::set_default_keyValue( $args, 'kses_allowd_html', wp_kses_allowed_html( 'post' ) );
			SDES_Static::set_default_keyValue( $args, 'kses_protocols', null );
			echo wp_kses( $output, $args['kses_allowd_html'], $args['kses_protocols'] );
			break;
		}
	}
}
