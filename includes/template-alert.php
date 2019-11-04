<?php
/** A template part for displaying a list of sitewide Alerts.
 * This should be called by all templates except front-page. */
if ( 'alert' !== get_query_var('post_type') ) {
	echo do_shortcode( "[alert-list]" );
}
