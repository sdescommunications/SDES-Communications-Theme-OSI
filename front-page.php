<?php
/**
 * Display the Front Page of the site, per the WordPress Template Hierarchy.
 */
use SDES\SDES_Static as SDES_Static;

$headerlink = strtolower(get_post_meta($post->ID, "billboard-meta-box-text", true));

(!empty($headerlink)&&$headerlink != 'home') ? get_header('third') : get_header();

?>
	<?= (empty($headerlink)||$headerlink = 'home') ? do_shortcode( "[billboard-list tags='home']" ) : null ?>
	<br>
	<div class="container">
		<h1><?= get_the_title() ?></h1>
		<hr />

		<section>
			<aside>
				<?= wpautop( do_shortcode( get_post_meta( $post->ID, 'page_sidecolumn', $single=true )) ); ?>
			</aside>
			<article>
				<?php if (have_posts()) :
					while (have_posts()) : the_post();
					the_content();
					endwhile;
					else:
						
					endif; 
				?>
			</article>
		</section>
	</div>
	<?php 
		if (get_posts('post_type=news&tag=home')){
	?>
	<div class="yellow">
		<div class="container">
			<div class="row">
				<?php if(has_nav_menu( 'home-resource-menu' )){ ?>
				<div class="col-lg-4 col-md-12">
					<div class="menu menu-left">
						<div class="menu-header">
							Other Resources
						</div>
						<?= 
						wp_nav_menu(array(
							'theme_location' => 'home-resource-menu',
							'menu_class' => 'list-group list-unstyled', 
							'walker' => new Side_Menu(),
						)) 
						?>
					</div>
				</div>
				<div class="col-lg-8 col-md-12">
					<?php } else { ?>
					<div class="col-md-12">
						<?php } ?>						
						<?= 
						do_shortcode( '[news-list show-archives="false" limit="3" join="or" tags="home" categories=""]' ) 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
		}else{}
	?>
	
<?php
get_footer();
