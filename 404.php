<?php @header( 'HTTP/1.1 404 Not found', true, 404 ); ?>

<?php get_header(); ?>

<div class="container site-content" id="content">
	<div class="row">
		<div class="col-xs-12">
			<h1>404 Page Not Found</h1>
			<hr />
			<p class="lead">We're sorry! It looks like the page you are trying to visit no longer exists.</p>
			<p>You may have stumbled upon this page in error, or the content you are looking for may no longer be up to date. Please return to our <a href="<?= esc_url( home_url( '/' ) ) ?>">home page</a> or search in the UCF header bar to browse our website.</p>
		</div>
		
	</div>
</div> <!-- /DIV.container.site-content -->

<?php get_footer(); ?>