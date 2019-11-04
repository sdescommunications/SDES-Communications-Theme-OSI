(function( $ ) {
	$(function() {

		// Template Function
		// wp.customize( 'theme_customizer_setting_name', function( value ) {
		// 	value.bind( function( to ) {
		// 		// Bind dynamically using javascript when using Theme Customizer inferace.
		//		// This will match anywhere that you use get_theme_mod() for this setting.
		// 	});
		// });

		wp.customize( 'sdes_rev_2015-hours', function( value ) {
			value.bind( function( to ) {
				$( '#contactBlock td:eq(0)' ).html( to );
			});
		});

		wp.customize( 'sdes_rev_2015-phone', function( value ) {
			value.bind( function( to ) {
				to = ( '' !== to ) ? to : '407-823-4625';
				$( '#contactBlock td:eq(1)' ).html( to );
			});
		});

		wp.customize( 'sdes_rev_2015-fax', function( value ) {
			value.bind( function( to ) {
				to = ( '' !== to ) ? to : '407-823-2969';
				$( '#contactBlock td:eq(2)' ).html( to );
			});
		});

		wp.customize( 'sdes_rev_2015-email', function( value ) {
			value.bind( function( to ) {
				$( '#contactBlock td:eq(3)' ).html( to );
			});
		});

		wp.customize( 'sdes_rev_2015-taglineURL', function( value ) {
			value.bind( function( to ) {
				$( 'div.site-subtitle a' ).attr( 'href', to );
			});
		});
	});
}( jQuery ) );
