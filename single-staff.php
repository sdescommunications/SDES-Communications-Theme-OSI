<?php

	$image_url 	= has_post_thumbnail( $post->ID ) ?
	wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID), 'full', false ) : null;

	if ( $image_url ) {
		$image_url = $image_url[0];
	}

	$position	= get_post_meta( $post->ID, 'staff_position_title', true);
	$phone 		= get_post_meta( $post->ID, 'staff_phone', true );
	$email 		= get_post_meta( $post->ID, 'staff_email', true );

	get_header('second'); the_post();

?>

<div class="container">
	<br>
	<h1>
		<?=
			get_the_title();
		?>
	</h1>
	<hr>
	<section>
		<article class="full-width">
			<div class="staff">
				<img src="<?= !empty($image_url) ? $image_url : get_stylesheet_directory_uri() . '/images/blank.png' ?>" class="img-fluid">
				<div class="staff-content">
					<h3 class="staff-title"><?= $position ?></h3>
					<h4 class="staff-phone"><?= $phone ?></h4>
					<h4 class="staff-email">
						<a href="mailto:<?= $email ?>"><?= $email ?></a>
					</h5>
					<div class="staff-details"><?= the_content() ?></div>
				</div>
			</div>
			<a class="btn btn-callout float-right mt-3" href="<?= wp_get_referer() ?>">&lt; Back</a>
		</article>
	</section>
</div>
<?php get_footer(); ?>