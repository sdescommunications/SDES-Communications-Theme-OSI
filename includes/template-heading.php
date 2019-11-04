<?php
/** Display the H1 heading and navpills at the top of a template. */
require_once(  get_stylesheet_directory() . '/functions/class-sdes-helper.php' );
	use SDES\BaseTheme\SDES_Helper as SDES_Helper;
?>

<div class="page-header">
	<?php 
		// Re-register location for Page's navpill menu, in case it was not registered yet (e.g., moment-of-creation).
		$locKey = SDES_Helper::the_locationKey_navpills();
		$locValue = SDES_Helper::the_locationValue_navpills();
		register_nav_menu($locKey, $locValue);
		// Display navpill menu
		wp_nav_menu( array(
			'theme_location' => $locKey
			, 'depth' => 1
			, 'container' => 'ul'
			, 'menu_class' => 'nav nav-pills pull-xs-right'
			, 'fallback_cb' => 'SDES\\BaseTheme\\SDES_Helper::fallback_navpills_warning'
		));
	?>
	<h1 id="content-top"><?=the_title();?></h1>
</div>
