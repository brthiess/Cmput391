$(document).ready(function() {

		$( "#start-date" ).datepicker();

		$( "#end-date" ).datepicker();
		$( "#prescribing-date" ).datepicker();
		$( "#test-date" ).datepicker();
		
		   $( document.body ).on( 'click', '.dropdown-menu li', function( event ) {
				var $target = $( event.currentTarget );
				$target.closest( '.dropdown' )
				.find( '[data-bind="label"]' ).text( $target.text() )
				.end()
				.children( '.dropdown-toggle' ).dropdown( 'toggle' );
				return false;
			});

});