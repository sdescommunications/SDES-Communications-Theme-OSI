<!-- footers -->
<?php wp_footer(); ?>
<footer class="site-footer-container">
	<!-- main footer -->
	<div class="site-footer">
		<div class="container"> 
			<div class="row">
				<div class="col-lg-3 col-md-12">					
					<?= do_shortcode( '[events id="'.get_option('calendar_id', '').'" header="Upcoming Events" limit="2" ]' ) ?>	
					<div class="clearfix"></div>
				</div>
				<div class="col-lg-4 col-md-12 offset-lg-1 offset-md-0">
					<h2><?= has_nav_menu( 'footer-menu' ) ? 'Quick Links' : 'Page Navigation' ?></h2>
					<hr />
					<div class="row">
						<div class="col-sm-12 col-xs-12">
							<?php 
								$menu = get_post_meta($post->ID, 'submenu', true);

								if (has_nav_menu( 'footer-menu' )) {
									wp_nav_menu(array(
										'theme_location' => 'footer-menu',
										'menu_class' => 'list-unstyled', 
									));
								} elseif (!empty($menu)) {
									wp_nav_menu(array(
										'menu' => $menu, 
										'menu_class' => 'list-unstyled',
										'depth' => 1,
									));
								} else{
									wp_nav_menu(array(
										'theme_location' => 'main-menu',
										'menu_class' => 'list-unstyled',
										'depth' => 1, 
									));
								}
								
							?>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-12 offset-lg-1 offset-md-0">
					<h2 id="contact">Contact Us</h2>
					<hr />
					<h3 class="site-title"><?= html_entity_decode(get_bloginfo('name')) ?></h3>
					<p>
						<?= do_shortcode( '[contactblock contactname="main" is_footer="true"]' ) ?>
					</p>
				</div>
			</div>
		</div>
	</div>
	<!-- sub footer -->
	<div class="site-sub-footer">
		<div class="container">
			<p class="text-center"><a class="footer-title" href="http://www.ucf.edu">University of Central Florida</a></p>	
			<br />
							
			<div class="screen-only">
				<ul class="list-unstyled list-inline text-center">
					<li class="list-inline-item"><a href="https://www.ucf.edu/azindex/">A-Z Index</a></li>
					<li class="list-inline-item"><a href="https://www.ucf.edu/about-ucf/">About UCF</a></li>
					<li class="list-inline-item"><a href="https://www.ucf.edu/contact/">Contact UCF</a></li>
					<li class="list-inline-item"><a href="https://www.ucf.edu/internet-privacy-policy/">Internet Privacy Policy</a></li>
					<li class="list-inline-item"><a href="http://www.ucf.edu/online">Online Degrees</a></li>
					<li class="list-inline-item"><a href="http://www.ucf.edu/pegasus">Pegasus</a></li>
					<li class="list-inline-item"><a href="http://policies.ucf.edu" target="_blank" >Policies</a></li>
					<li class="list-inline-item"><a href="http://regulations.ucf.edu/" target="_blank" >Regulations</a></li>
					<li class="list-inline-item"><a href="https://www.ucf.edu/news/" target="_blank" >UCF News</a></li>
				</ul>			
			</div>
			<p class="ucf-footer-address text-center">
				4000 Central Florida Blvd. Orlando, Florida 32816 | <a href="tel:4078232000">407.823.2000</a> | <a href="http://www.sdes.ucf.edu/accessibility"><i class="fa fa-universal-access" aria-hidden="true"></i> Accessibility Statement</a>				<br>
				© <a href="http://www.ucf.edu/">University of Central Florida</a></p>



		</div>
	</div>
</footer>