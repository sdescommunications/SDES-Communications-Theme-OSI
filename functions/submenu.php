<?php
	
	function submenu_meta_box_markup($object){
		
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");		
		
		$meta_key = 'submenu';

		$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		
		?>

		<div class="inside">
			<p>
				<strong>
					Sub Menu Name
				</strong>
			</p>
			<select name="<?= $meta_key ?>">
				<option value=""></option>
				<?php

					$option = get_post_meta($object->ID, $meta_key, true);
					foreach ($menus as $menu) {
						$selected = ($menu->slug == $option) ? 'selected' : NULL;
						echo '<option value="'.$menu->slug.'" '.$selected.'>'.$menu->name.'</option>';
					}

				?>
			</select>
			<p>
				<em>
					Place the menu you would like to override the main menu for. Leave blank if you want to use use default Main Menu functionality.
				</em>
			</p>	
		</div>
			
	    <?php 

	}

	function add_custom_meta_box_submenu()
	{
	    add_meta_box("submenu-meta-box", "Sub Menu", "submenu_meta_box_markup", "page", "side", "high", null);
	}
	add_action("add_meta_boxes", "add_custom_meta_box_submenu");

?>