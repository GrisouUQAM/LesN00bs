
jQuery( document ).ready( function( $ ) {
    $( '#grisou-submit' ).click( function() {
        $.get( mw.config.get( 'wgExtensionAssetsPath' ) + '/Grisou/async/handler.php',
            $( '#grisou-form' ).serialize(), function( data )
            {
                $( '#grisou-result' ).html( data ).append( '<hr />' ).show();
            } );
    } );
	
	$(document).on("click", '.grisouLinks', function(e){
		 e.preventDefault(); 
		 var grisouHref = $(this).attr('href');
		 $.ajax({
			url:  mw.config.get( 'wgExtensionAssetsPath' ) + '/Grisou/async/handler.php?info=' + grisouHref + '&page=load'
			,success: function(response) {
				$('#grisou_' + grisouHref).html(response);
				$('#grisou_' + grisouHref).show();
			}
		 })
		 return false;
	});
} );