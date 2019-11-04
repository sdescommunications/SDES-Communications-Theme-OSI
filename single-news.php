<?php
	get_header('second'); the_post();

	$image_url 	= has_post_thumbnail( $post->ID ) ?
	wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full', false ) : null;

	if ( $image_url ) {
		$image_url = $image_url[0];
	}

	$url		= get_post_meta( $post->ID, 'news_url', true);
	$strapline 	= get_post_meta( $post->ID, 'news_strapline', true );
?>
<br />
<div class="container">
	<h1>
		<?= (!empty($url)) ? '<a href="' . $url . '">'. get_the_title() . '</a>' : get_the_title(); ?>
	</h1>
	<hr />
	<section>
		<article class="full-width">
			<div class="news">
				<img src="<?= !empty($image_url)? $image_url :  get_stylesheet_directory_uri() . '/images/blank.png' ?>" class="img-fluid">
				<div class="news-content">
					<h3 class="news-strapline"><?= $strapline ?></h3>
					<p class="datestamp">Posted <?= get_the_date( 'l, F j, Y @ g:i A', $object->post->ID ); ?></p>
					<p>
						<?= the_content(); ?>
					</p>
				</div>
			</div>
			<a class="btn btn-callout float-right mt-3" href="<?= wp_get_referer(); ?>"><i class="fa fa-chevron-left"></i> Back</a>
		</article>
	</section>
</div>
<?php get_footer(); ?>
