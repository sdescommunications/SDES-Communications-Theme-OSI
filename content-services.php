<?php
/**
* Template Name: Cards
*/
use SDES\SDES_Static as SDES_Static;

$c = 1;
while ($c <= $GLOBALS['NUMBEROFCARDS']) {
	$titles[] 	= get_post_meta($post->ID, "service_title_".$c, true);
	$contents[] = get_post_meta($post->ID, "service_wysiwyg_".$c, true);
	$texts[] 	= get_post_meta($post->ID, "service_btn_text_".$c, true);
	$urls[] 	= get_post_meta($post->ID, "service_btn_url_".$c, true);
	$images[] 	= get_post_meta($post->ID, "card_image_".$c, true);
	$c++;
}
get_header('second');
?>
<!-- content area -->
<div class="container site-content" id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><?= get_the_title() ?></h1>
		<hr>
		<div class="row">
			<div class="col-sm-12 ">
				<?php the_content(); ?>
				<br>				
				<div class="card-deck mt-3">
					<?php
					$c=1;					
					foreach ($contents as $key => $content) {					
						?>
						<?php 
						if (!empty($contents[$key]) && !empty($images[$key])) { 
							?>
							<div class="card">					
								<?= image_uploader_field( 'card_image_'.$c, $images[$key], 2 ) ?>							
								<div class="card-block">
									<?=
									(!empty($titles[$key])) ?
									'<h4 class="card-title">' . $titles[$key] . '</h4>
									<hr>' : null
									?>												
									<div class="card-text">
										<?= wpautop($contents[$key]) ?>
									</div>																				
								</div>
								<?= 
									(!empty($urls[$key]) && !empty($texts[$key])) ? 
									'<div class="card-img-bottom">
										<a class="btn btn-callout btn-block" href="' . $urls[$key] . '">' . $texts[$key] . '</a>
									</div>': null
								?>						
						</div>
					<?php } //end of if
						if ((($key+1) % 4) == 0){ 
					?>
				</div>							
				<div class="card-deck mt-3">
					<?php					 
							}//end of if
						}//end of foreach					
					?>
				</div>							
			</div>
		</div>
	<?php endwhile;
	else: 
		SDES_Static::Get_No_Posts_Message();
	endif; ?>
</div> <!-- /DIV.container.site-content -->
<?php
get_footer();