$(function() {

    // Open external links and PDF files in new tabs.
    $( 'a.external, a[href$=".pdf"]' ).click( function( e ) {
        window.open( this.href );
        e.preventDefault();
    });

    /* Link to Theme Customizer instead of nav-menus.php if Javascript is enabled.
     * @see https://developer.wordpress.org/themes/advanced-topics/customizer-api/#focusing
     */
    $( '.adminmsg-menu' ).each( function( idx, elem ) {
        var $this = $( this );
        $this.attr( 'href', '../wp-admin/customize.php?autofocus[control]=' + $this.data( 'control-name' ) );
    });
});
