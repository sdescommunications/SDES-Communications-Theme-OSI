<?php
/**
* Template Name: Billboard
*/
use SDES\SDES_Static as SDES_Static;

get_header(); 

?>

<?= do_shortcode( "[billboard-list tags='". get_post_meta($post->ID, "billboard-meta-box-text", true) ."']" ) ?>

<!-- content area -->
<div class="container site-content" id="content">

	<h1><?= get_the_title() ?></h1>
	<hr>
	<div class="row">
		<br>
		<div class="col-sm-8">
			<?php if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					the_content();
				endwhile;			
			endif;
			wp_reset_query();
			?>
		</div>
		<div class="col-sm-4">
			<?php		

			$sidebar = get_post_meta( $post->ID, 'page_sidecolumn', $single=true );
			echo do_shortcode( $sidebar );
			?>
		</div>	
	</div>

</div> <!-- /DIV.container.site-content -->
<?php
get_footer();