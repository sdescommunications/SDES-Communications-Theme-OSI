<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KD9QPBB');</script>
	<!-- End Google Tag Manager -->

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php 
			echo wp_title( '&bull;', true, 'right' ); 
			echo str_replace('&lt;br&gt;', ' ', get_bloginfo('name')); 
		?> 
		&bull; UCF
	</title>	

	<link rel="shortcut icon" href="<?= get_stylesheet_directory_uri(); ?>/images/favicon_black.png" />
	<link rel="apple-touch-icon" href="<?= get_stylesheet_directory_uri(); ?>/images/apple-touch-icon.png" />
	<link rel="stylesheet" href="<?= get_stylesheet_directory_uri(); ?>/style.css" />

	<!--[if lt IE 10]>	
	<link rel="stylesheet" href="css/why.css" media="screen" />
	<![endif]-->	

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" id="ucfhb-script" src="//universityheader.ucf.edu/bar/js/university-header.js"></script>

	<script src="//use.fontawesome.com/48342ef48c.js"></script>
	
	<script type="text/javascript" src="<?= get_stylesheet_directory_uri(); ?>/js/tether.min.js"></script>
	<script type="text/javascript" src="<?= get_stylesheet_directory_uri(); ?>/js/bootstrap.min.js"></script>	
	<script type="text/javascript" src="<?= get_stylesheet_directory_uri(); ?>/js/smoothscroll.js"></script>


	<!--[if lt IE 10]>
	<script type="text/javascript" src="<?= get_stylesheet_directory_uri(); ?>/js/modernizr-custom.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
	<![endif]-->
	<?= wp_head(); ?>
</head>
<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KD9QPBB"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<script>
		jQuery(function($) {
			$('.navbar .dropdown').hover(function() {
				$(this).find('.dropdown-menu').first().stop().fadeIn("fast");

			}, function() {
				$(this).find('.dropdown-menu').first().stop().fadeOut("fast");

			});

			$('.navbar .dropdown > a').click(function(){
				location.href = this.href;
			});

		});

		$(document).ready(function() {
			$("body").tooltip({ selector: '[data-toggle=tooltip]' });
		});			
	</script>
	<!-- header -->
	<header>
		<div class="skip text-center hidden-xs-up" id="skpToContent">
			<a href="#content"><i class="fa fa-lg fa-chevron-down"><span class="sr-only">Skip to Content</span></i></a>
		</div>
		<div class="header-content">
			<div class="container">
				<section class="site-title">			
					<article>
						<a href="<?= site_url() ?>" class="float-left mb-3">
							<?= html_entity_decode(get_bloginfo('name')) ?>
						</a>
						<span class="site-subtitle float-left hidden-md-down">
							<a href="<?= get_option("main_site_url", '') ? get_option("main_site_url", '') : '//www.sdes.ucf.edu' ?>">
								<?= html_entity_decode(get_bloginfo('description')) ?>
							</a>
						</span>				
					</article>
					<aside class="text-lg-right">
						<a class="translate" href="http://it.sdes.ucf.edu/translate/" data-toggle="tooltip" data-placement="right" title="Translate This Page!"><i class="fa fa-globe"></i></a>
					</aside>			
				</section>
			</div>
			<!-- navigation -->
			<nav class="navbar navbar-dept navbar-toggleable-md">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<i class="fa falg fa-bars"></i>
				</button>
				<?php
					$menu = get_post_meta($post->ID, 'submenu', true);

					if (!empty($menu)) {
						wp_nav_menu(array(
						'menu' => $menu, 
						'menu_class' => 'navbar-nav',
						'container_class' => 'collapse navbar-collapse',
						'container_id' => 'navbarSupportedContent',
						'walker' => new Nav_Menu(),
						));
					} else {
						wp_nav_menu(array(
						'theme_location' => 'main-menu', 
						'menu_class' => 'navbar-nav',
						'container_class' => 'collapse navbar-collapse',
						'container_id' => 'navbarSupportedContent',
						'walker' => new Nav_Menu(),
						));
					}
				?>	
			</nav>
		</div>

		<?= do_shortcode( '[alert-list show_all="true"]' ); ?>
		
	</header>