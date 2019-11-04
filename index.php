<?php
/**
 * Default layout, per the WordPress Template Hierarchy.
 * This is a page of last resort, and should be overriden in most cases.
 */

require_once( 'functions/class-sdes-static.php' );
use SDES\SDES_Static;

get_header('second');
?>
<!-- content area -->
<div class="container site-content" id="content">
	<?= get_template_part( 'includes/template', 'alert' ); ?>

	<?php if (have_posts()) :
	while (have_posts()) : the_post();
	the_content();
	endwhile;
	else:
		SDES_Static::Get_No_Posts_Message();
	endif; ?>

</div> <!-- /DIV.container.site-content -->
<?php
get_footer();
