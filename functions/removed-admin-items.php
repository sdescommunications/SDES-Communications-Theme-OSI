<?php
// Allow editors to see Appearance menu
$role_object = get_role( 'editor' );
$role_object->add_cap( 'edit_theme_options' );

/**
*   Removed admin items not needed to be seen my users.
*/
function remove_menus(){
	$user = wp_get_current_user();
	if ( in_array( 'author', (array) $user->roles ) || in_array( 'editor', (array) $user->roles ) ) {
		    remove_menu_page( 'edit-comments.php' );          //Comments
		    remove_menu_page( 'edit.php' );                   //Posts
		    remove_menu_page( 'plugins.php' );                //Plugins
	 		remove_menu_page( 'tools.php' );                  //Tools

	  		// Hide theme selection page
		    remove_submenu_page( 'themes.php', 'themes.php' );
		 
		    // Hide widgets page
		    remove_submenu_page( 'themes.php', 'widgets.php' );
		 
		    // Hide customize page
		    global $submenu;
		   	unset($submenu['themes.php'][6]);
	  	}
	  }

add_action( 'admin_menu', 'remove_menus' );

?>