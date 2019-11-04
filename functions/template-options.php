<?php
	add_action('admin_enqueue_scripts', 'admin_template_view');
	function admin_template_view()
	{
		global $post;
		
	    wp_enqueue_script('show_hide_bill_view', get_bloginfo('template_url').'/js/template-option.js', array('jquery'));
	    wp_localize_script( 'show_hide_bill_view', 'data', array(
			'postID' => $post->ID,
			'isFrontPage' =>  get_option('page_on_front')));
	}

	add_filter('admin_init', 'add_google_id'); 
	function add_google_id()
	{
	    register_setting('general', 'google_id', 'esc_attr');
	    add_settings_field('google_id', '<label for="google_id">'.__('Google ID' , 'google_id' ).'</label>' , 'google_id_setting_html', 'general');
	}
	 
	function google_id_setting_html()
	{
	    $value = get_option( 'google_id', '' );
	    echo '<input type="text" id="google_id" name="google_id" value="' . $value . '" />';
	}

	add_filter('admin_init', 'add_calendar_id'); 
	function add_calendar_id()
	{
	    register_setting('general', 'calendar_id', 'esc_attr');
	    add_settings_field('calendar_id', '<label for="calendar_id">'.__('Calendar ID' , 'calendar_id' ).'</label>' , 'calendar_id_setting_html', 'general');
	}
	 
	function calendar_id_setting_html()
	{
	    $value = get_option( 'calendar_id', '' );
	    echo '<input type="text" id="calendar_id" name="calendar_id" value="' . $value . '" />';
	}

	add_filter('admin_init', 'add_breaker_id'); 
	function add_breaker_id()
	{
	    register_setting('general', 'breaker_id', 'esc_attr');
	    add_settings_field('breaker_id', '<label for="breaker_id">'.__('Breaker ID' , 'breaker_id' ).'</label>' , 'breaker_id_setting_html', 'general');
	}
	 
	function breaker_id_setting_html()
	{
	    $value = get_option( 'breaker_id', '' );
	    echo '<input type="text" id="breaker_id" name="breaker_id" value="' . $value . '" />';
	}

	add_filter('admin_init', 'add_main_site_url'); 
	function add_main_site_url()
	{
	    register_setting('general', 'main_site_url', 'esc_attr');
	    add_settings_field('main_site_url', '<label for="main_site_url">'.__('Main URL' , 'main_site_url' ).'</label>' , 'main_site_url_setting_html', 'general');
	}
	 
	function main_site_url_setting_html()
	{
	    $value = get_option( 'main_site_url', '' );
	    echo '<input type="text" id="main_site_url" name="main_site_url" value="' . $value . '" />';
	}
?>