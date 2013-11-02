
jQuery( document ).ready( function( $ ) {
    $( '#grisou-submit' ).click( function() {
        $.get( mw.config.get( 'wgExtensionAssetsPath' ) + '/Grisou/async/handler.php',
            $( '#grisou-form' ).serialize(), function( data )
            {
                $( '#grisou-result' ).html( data ).append( '<hr />' ).show();
            } );
    } );
} );