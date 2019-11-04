<?php
/**
* Template Name: Left Sidebar
*/
use SDES\SDES_Static as SDES_Static;

get_header('second');
?>
<!-- content area -->
<div class="container site-content" id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><?= get_the_title() ?></h1>
		<hr>
		<div class="row">
			<div class="col-sm-4 col-xs-12">
				<?php
					$prefix = SDES_Static::get_post_type( get_the_ID() ).'_';

					echo do_shortcode(wpautop(get_post_meta(get_the_ID(),  $prefix.'sidecolumn', true )  )); 

				?>
			</div>
			<div class="col-sm-8 col-xs-12">
				<?php the_content(); ?>
			</div>			
		</div>
	<?php endwhile;
	else: 
		SDES_Static::Get_No_Posts_Message();
	endif; ?>

</div> <!-- /DIV.container.site-content -->
<?php
get_footer();
