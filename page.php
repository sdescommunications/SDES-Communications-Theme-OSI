<?php
/**
* Template Name: Full Width
*/
use SDES\SDES_Static as SDES_Static;

get_header('second');
?>
<!-- content area -->

<div class="container site-content" id="content">
	
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php if(is_page('News')){ ?>
		<section>
			<aside>
				<?php			
				$args = array(
					'post_type' => array('news'),				
					'tag' => $location, 
					'orderby' => 'date',
					'order'   => 'DESC',
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => esc_sql( 'news_start_date' ),
							'value' => date( 'Y-m-d', time() ),
							'compare' => '<=',
							),
						array(
							'key' => esc_sql( 'news_end_date' ),
							'value' => date( 'Y-m-d', strtotime( '-1 day' ) ), // Datetime is stored as 24 hours before it should expire.
							'compare' => '>',
						)
					),
				);

				$object = new WP_Query($args);			
				?>
					
				<div class="menu">					
					<div class="menu-header">
						Page Navigation
					</div>
					<ul class="list-group menu-right list-unstyled">

						<?php
						if ( $object->have_posts() ) :
							while ( $object->have_posts() ) : $object->the_post();
						?>
						<li><a class="list-group-item" href="#<?= $object->post->ID ?>"><?= the_title() ?></a></li>

						<?php endwhile; wp_reset_postdata(); ?>
							<!-- show pagination here -->
						<?php else : ?>
							<!-- show 404 error here -->
						<?php endif; ?>

					</ul>
				</div>

				<?php wp_reset_query(); ?>
				
			</aside>
			<article>
				<?= do_shortcode( '[news-list show-archives="false" limit="-1" join="or" tags="" categories=""]' ) ?>
			</article>
		</section>

	<?php }else{ ?>

		<h1><?php the_title();?></h1>
		<hr />
		<div class="row">
			<div class="col-sm-12">
				<?php the_content(); ?>
			</div>
		</div>
	<?php } ?>
<?php endwhile;
else: 
	SDES_Static::Get_No_Posts_Message();
endif; ?>

</div> <!-- /DIV.container.site-content -->

<?php
get_footer();
