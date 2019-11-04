<?php
/**
 * Display a single Post or Page, per the WordPress Template Hierarchy.
 */ 
get_header('second');
?>
<!-- content area -->
<div class="container site-content" id="content">

	<?php if (have_posts()) :
	while (have_posts()) : the_post();
	?>
	<h1><?= get_the_title() ?></h1>
	<hr>
	<?php
	the_content();
	endwhile;
	else:
		echo "No posts were found.";
	endif; ?>

</div> <!-- /DIV.container.site-content -->
<?php
get_footer();
