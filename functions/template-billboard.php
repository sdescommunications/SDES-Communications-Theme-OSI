<?php

	function billboard_meta_box_markup($object)
	{
	    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

	    ?>
	        <div class="inside">
	          <p>
	            <strong>Billboard <span id="billTag">Tag</span><span id="billUrl">URL</span></strong>
	          </p>
	          <p>
	            <label class="screen-reader-text" for="billboard-meta-box-text">Billboard Tag</label>
	            <input name="billboard-meta-box-text" type="text" value="<?php echo get_post_meta($object->ID, "billboard-meta-box-text", true); ?>">
	          </p>
	          <p>
	            <em>Place the <span id="billTagb">tag</span><span id="billUrlb">URL</span> that you want to appear in this billboard.</em>
	          </p>
	        </div>
	    <?php  
	}

	function add_custom_meta_box()
	{
	    add_meta_box("billboard-meta-box", "Billboard", "billboard_meta_box_markup", "page", "side", "high", null);
	}

	add_action("add_meta_boxes", "add_custom_meta_box");

?>