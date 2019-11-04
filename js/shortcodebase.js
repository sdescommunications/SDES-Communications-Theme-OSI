/* global window, tinymce, jQuery, WebcomAdmin */
if ( 'undefined' === typeof WebcomAdmin) { WebcomAdmin = {}; }

/** @see https://github.com/UCF/Students-Theme/blob/d56183079c70836adfcfaa2ac7b02cb4c935237d/src/js/admin.js#L256-L334 */
WebcomAdmin.shortcodeInterfaceTool = function( $ ) {
  var $cls                   = WebcomAdmin.shortcodeInterfaceTool;
  $cls.shortcodeForm         = $( '#select-shortcode-form' );
  $cls.shortcodeButton       = $cls.shortcodeForm.find( 'button' );
  $cls.shortcodeSelect       = $cls.shortcodeForm.find( '#shortcode-select' );
  $cls.shortcodeEditors      = $cls.shortcodeForm.find( '#shortcode-editors' );
  $cls.shortcodeDescriptions = $cls.shortcodeForm.find( '#shortcode-descriptions' );

  $cls.shortcodeInsert = function( shortcode, parameters, enclosingText, showClosingTag ) {
    var key, text = '[' + shortcode;
    if ( parameters ) {
      for ( key in parameters ) {
        text += ' ' + key + '="' + parameters[key] + '"';
      }
    }
    text += ']';

    if ( enclosingText ) {
      text += enclosingText;
    }
    if ( '1' == showClosingTag ) {
      text += '[/' + shortcode + ']';
    }

    // From media-upload.js, e.g., https://core.trac.wordpress.org/browser/trunk/src/wp-admin/js/media-upload.js?rev=36286
    window.send_to_editor( text );
  };

  $cls.shortcodeAction = function() {
    var editor, parameters, dummyText, highlightedWysiwigText, enclosingText, showClosingTag,
    $selected = $cls.shortcodeSelect.find( ':selected' );
    if ( $selected.length < 1 || '' === $selected.val() ) {
      return;
    }

    editor = $cls.shortcodeEditors.find( 'li.shortcode-' + $cls.shortcodeSelected ),
    dummyText = $selected.attr( 'data-enclosing' ) || null,
    highlightedWysiwigText = tinymce.activeEditor ? tinymce.activeEditor.selection.getContent() : null,
    enclosingText = ( highlightedWysiwigText ) ? highlightedWysiwigText : dummyText,
    showClosingTag = $selected.attr( 'data-showclosingtag' ) || '1';

    parameters = {};
    if ( 1 === editor.length ) {
      editor.children().each(function() {

        // IDEA: Add debug/warning flag.
        // IDEA: If in debug mode, show warning for elems where `$formElement.prop( 'tagName' )` is not an 'INPUT', 'TEXTAREA', or 'SELECT'.
        var $formElement = $( this ),
        dataParmeter = $formElement.attr( 'data-parameter' );
        switch ( $formElement.prop( 'type' ) ) {
          case 'checkbox':
          parameters[ dataParmeter ] = String( $formElement.prop( 'checked' ) );
          break;

          // Convert datetime from ISO8601 to MySQL datetime for consumption by WordPress.
          case 'datetime-local':
          parameters[ dataParmeter ] = $formElement.val().replace( 'T', ' ' );
          break;

          default:
          if ( undefined !== dataParmeter ) {
            parameters[ dataParmeter ] = $formElement.val();
          }
          break;
        }
      });
    }

    $cls.shortcodeInsert( $selected.val(), parameters, enclosingText, showClosingTag );
  };

  $cls.shortcodeSelectAction = function() {
    $cls.shortcodeSelected = $cls.shortcodeSelect.val();
    $cls.shortcodeEditors.find( 'li' ).hide();
    $cls.shortcodeDescriptions.find( 'li' ).hide();
    $cls.shortcodeEditors.find( '.shortcode-' + $cls.shortcodeSelected ).show();
    $cls.shortcodeDescriptions.find( '.shortcode-' + $cls.shortcodeSelected ).show();
  };

  $cls.shortcodeSelectAction();

  $cls.shortcodeSelect.change( $cls.shortcodeSelectAction );

  $cls.shortcodeButton.click( $cls.shortcodeAction );

};

jQuery(function() {
  WebcomAdmin.shortcodeInterfaceTool( jQuery );
});
