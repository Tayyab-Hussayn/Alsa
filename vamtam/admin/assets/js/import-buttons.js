/*
 VamTam Import Buttons
 */

/*global jQuery*/

(function( $ ) {
	'use strict';

	$( function() {
		$( 'body' ).on( 'click', '.vamtam-import-button', function( e ) {
			e.preventDefault();

			var button = $( this );

			if ( ! button.hasClass( 'disabled' ) ) {
				button.addClass( 'disabled' );

				var spinner = $( '<span></span>' ).addClass( 'spinner' ).css( {
					visibility: 'visible',
					float: 'none',
					'vertical-align': 'top'
				} );

				button.siblings( '.import-success' ).remove();

				button.after( spinner );

				$.get( button.attr( 'href' ) )
					.done( function( result ) {
						spinner.remove();

						var result_wrap = $( '<span />' );

						if ( result.match( /all done\./i ) ) {
							//Update result msg
							result_wrap.html( button.data( 'success-msg' ) ).addClass( 'import-success' );
							//Update btn text
							button.text( 'Imported' ).addClass( 'done' );
							//Enable widget btn and update message
							var $widgetBtn = $('a#widget-import-button');
							if ( $widgetBtn.hasClass('disabled') && $($widgetBtn).siblings('span.spinner').length === 0 ) {
								$widgetBtn.removeClass('disabled');
								var $widgetDesc = $widgetBtn.parent().siblings('p.vamtam-description');
								$widgetDesc.addClass('warning').text($widgetDesc.data('warning-msg'));
							}

							if ( button.attr( 'id' ) === 'content-import-button' ) {
								button.closest( '.form-table' ).find( '.disabled.content-disabled' ).removeClass( 'disabled content-disabled' );
								start_polling( result_wrap );
								button.after( result_wrap );
							}

						} else {
							result_wrap.html( button.data( 'error-msg' ).replace( '{fullimport}', button.attr( 'href' ) ) ).addClass( 'import-fail' );
							button.closest( '.vamtam-box-wrap' ).addClass( 'import-fail' );
							button.after( result_wrap );
						}
					} )
					.fail( function( result ) {
						spinner.remove();

						var result_wrap = $( '<span />' );

						if ( result.status === 500 || result.status === 408 ) {
							result_wrap.html( button.data( 'timeout-msg' ).replace( '{fullimport}', button.attr( 'href' ) ) ).addClass( 'import-fail' );
						} else if ( result.status !== 404 ) {
							result_wrap.html( button.data( 'fail-msg' ).replace( '{fullimport}', button.attr( 'href' ) ).replace( '{statuscode}', result.status ) ).addClass( 'import-fail' );
						} else {
							result_wrap.html( button.data( 'error-msg' ).replace( '{fullimport}', button.attr( 'href' ) ) ).addClass( 'import-fail' );
						}

						button.closest( '.vamtam-box-wrap' ).addClass( 'import-fail' );
						button.after( result_wrap );
					} );
			}
		} );

		var alreadyImported = $( '#content-import-button[data-content-imported=1]' );

		if ( alreadyImported.length ) {
			var result_wrap = $( '<span />' );

			result_wrap.html( alreadyImported.data( 'success-msg' ) ).addClass( 'import-success' );

			start_polling( result_wrap );

			alreadyImported.after( result_wrap );
		}
	} );

	var start_polling = function( wrap ) {

		const pollTimer = setInterval( () => {
			$.ajax( {
				type: "POST",
				url: window.vamtamImportButtonsVars.ajaxurl,
				data: {
					action: 'vamtam_attachment_progress',
					_ajax_nonce: window.vamtamImportButtonsVars.nonce
				},
				success: function ( resp ) {
					// Update progress text.
					if ( resp.vamtam_attachment_import_progress ) {
						wrap.find( '.vamtam-image-import-progress' ).text( resp.vamtam_attachment_import_progress );
					}
					// Exit.
					if ( resp.done === true ) {
						clearInterval( pollTimer );
					}
				}
			} );
		}, 2000 );
	};
})( jQuery );
