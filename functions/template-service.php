<?php
	$GLOBALS['NUMBEROFCARDS'] = 20;

	function service_meta_box_markup($object)
	{
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");		
		
		$meta_key = 'card_image_';		
		
		$c = 1;
	    while ($c <= $GLOBALS['NUMBEROFCARDS']) {
			$content[] = get_post_meta($object->ID, "service_wysiwyg_".$c);
			$c++;
		}		

		$c = 1;
		while($c <= $GLOBALS['NUMBEROFCARDS']) {
		?>
			<h1>Card <?= $c ?></h1>

			<div class="inside">
				<p>
					<strong>Image</strong> (required)
				</p>
				<p>
					<?= image_uploader_field( $meta_key.$c, get_post_meta($object->ID, $meta_key.$c, true) ) ?>
				</p>
			</div>

			<div class="inside">
				<p>
					Card title
				</p>
				<p>
					<input name="<?= 'service_title_'.$c ?>" type="text" value="<?= get_post_meta($object->ID, "service_title_".$c, true) ?>">
				</p>				
			</div>

			<div class="inside">
				<p>
					<strong>Content</strong> (required)
				</p>
				<p>	          	
					<?= (!empty($content[($c-1)][0])) ? wp_editor( $content[($c-1)][0], 'service_wysiwyg_'. ($c), array('media_buttons' => false, 'textarea_rows'=> 1,) ) : wp_editor( '', 'service_wysiwyg_'. ($c), array('media_buttons' => false, 'textarea_rows'=> 1,) ) ?>
				</p>	          
			</div>

			<div class="inside">
				<p>
					Card Button Text
				</p>
				<p>
					<input name="<?= 'service_btn_text_'.$c ?>" type="text" value="<?= get_post_meta($object->ID, "service_btn_text_".$c, true) ?>">
				</p>	
			</div>
			<div class="inside">
				<p>
					Card Button Url
				</p>
				<p>
					<input name="<?= 'service_btn_url_'.$c ?>" type="text" value="<?= get_post_meta($object->ID, "service_btn_url_".$c, true) ?>">
				</p>	
			</div>
			
			<br><hr><br>	
			<?php
			$c++;
		}

		?>

	    <?php 

	}

	function add_custom_meta_box_service()
	{
	    add_meta_box("service-meta-box", "Cards", "service_meta_box_markup", "page", "normal", "high", null);
	}

	add_action("add_meta_boxes", "add_custom_meta_box_service");

	function save_custom_meta_box_service($post_id, $post, $update)
	{
	    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
	        return $post_id;

	    if(!current_user_can("edit_post", $post_id))
	        return $post_id;

	    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	        return $post_id;

	    $meta_key = 'card_image_';  	 

	    $c = 1;
	    while ($c <= $GLOBALS['NUMBEROFCARDS']) {
	    	update_post_meta($post_id, "service_wysiwyg_".$c, $_POST["service_wysiwyg_".$c]);
	    	update_post_meta( $post_id, $meta_key.$c, $_POST[$meta_key.$c] );
	    	update_post_meta($post_id, "service_title_".$c, $_POST["service_title_".$c]);
	    	update_post_meta($post_id, "service_btn_text_".$c, $_POST["service_btn_text_".$c]);
	    	update_post_meta($post_id, "service_btn_url_".$c, $_POST["service_btn_url_".$c]);
	    	$c++;
	    }

	    update_post_meta($post_id, "billboard-meta-box-text", $_POST["billboard-meta-box-text"]);

	    update_post_meta($post_id, "submenu", $_POST["submenu"]);
	   
	}

	add_action("save_post", "save_custom_meta_box_service", 10, 3);

?>