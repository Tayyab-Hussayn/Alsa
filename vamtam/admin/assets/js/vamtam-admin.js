(function($, undefined) {
	"use strict";

	window.VAMTAM = window.VAMTAM || {};

	$(function() {
		$(document).on('change select', '[data-field-filter]', function() {
			var prefix = $(this).attr('data-field-filter');
			var selected = $(':checked', this).val();

			var others = $(this).closest('.vamtam-config-group').find('.' + prefix).filter(':not(.hidden)');
			others.show().filter(':not(.' + prefix + '-' + selected + ')').hide();
		});

		$('[data-field-filter]').change();

		$(document).on('change', '.num_shown', function() {
			var wrap = $(this).closest('p').siblings('.hidden_wrap');
			wrap.children('div').hide();
			$('.hidden_el:lt(' + $(this).val() + ')', wrap).show();
		});

		$('.metabox').each(function() {
			var meta_tabs = $('<ul>').addClass('vamtam-meta-tabs');

			$('.config-separator:first', this).before(meta_tabs);
			$('.config-separator', this).each(function() {
				var id = $(this).text().replace(/[\s\n]+/g, '').toLowerCase();
				$(this).nextUntil('.config-separator').wrapAll('<div class="vamtam-meta-part" id="tab-' + id + '"></div>');
				$(this).css('cursor', 'pointer');
				if ($(this).next().is('.vamtam-meta-part')) {
					meta_tabs.append('<li class="vamtam-meta-tab '+$(this).attr('data-tab-class')+'"><a href="#tab-' + id + '" title="">' + $(this).text() + '</a></li>');
				}
				$(this).remove();
			});

			if(meta_tabs.children().length > 1) {
				meta_tabs.closest('.metabox').tabs();
			} else {
				meta_tabs.hide();
			}
		});

		$('#vamtam-config').tabs({
			activate: function(event, ui) {
				var hash = ui.newTab.context.hash;
				var element = $(hash);
				element.attr('id', '');
				window.location.hash = hash;
				element.attr('id', hash.replace('#', ''));

				$('.save-vamtam-config').show();
				if (ui.newTab.hasClass('nosave')) $('.save-vamtam-config').hide();
			},
			create: function(event, ui) {
				if (ui.tab.hasClass('nosave')) $('.save-vamtam-config').hide();
			}
		});

		$('body').on('click', '.info-wrapper > a', function(e) {
			var other = $(this).attr('data-other');
			$(this).attr('data-other', $(this).text()).text(other);
			$(this).siblings('.desc').slideToggle(200);
			e.preventDefault();
		});

		// Asynchronously posts a given form using the default Settings API approach (options.php).
		function save_options_ajax( $formToSave, disableForm ) {
			$formToSave = $formToSave || $( 'form[method="post"][action="options.php"]' );
			if ( $formToSave ) {
				$formToSave.unbind( 'submit' );
				$formToSave.on( 'submit', function () {
					$('#vamtam-post-result span.spinner').addClass( 'is-active' );
					var b =  $(this).serialize();

					// Disable here so serialize can work properly.
					disableForm && $formToSave.find( ':input' ).attr("disabled", true);

					$.post( 'options.php', b )
						.error( function() {
							$('#vamtam-post-result .vamtam-post-msg-failure').show();
							$('#vamtam-post-result').show('slow');
							$('#vamtam-post-result span.spinner').removeClass( 'is-active' );
						})
						.success( function() {
							$('#vamtam-post-result .vamtam-post-msg-success').show();
							$('#vamtam-post-result').show('slow');
							$('#vamtam-post-result span.spinner').removeClass( 'is-active' );
						})
						.done( function () {
							setTimeout( function () {
								$('#vamtam-post-result').hide('slow');
								$('#vamtam-post-result > p').hide();
							}, 3000 );
							disableForm && $formToSave.find( ':input' ).attr("disabled", false);
						} );
						return false;
				});
				$formToSave.submit();
			}
		}

		$( 'input#vamtam-envato-elements-cb' ).on( 'change', function () {
			const $table = $(this).closest( 'table.form-table' ),
				$th = $table.find( 'tr:first > th' );

			if ( $(this).is( ':checked' ) ) {
				$( '#vamtam-code-help' ).addClass( 'hidden' );
				$( '#vamtam-code-help-elements' ).removeClass( 'hidden' );
				$th.text( window.VAMTAM_ADMIN.elementsTxt );
			} else {
				$( '#vamtam-code-help-elements' ).addClass( 'hidden' );
				$( '#vamtam-code-help' ).removeClass( 'hidden' );
				$th.text( window.VAMTAM_ADMIN.tfPcTxt );
			}
		} );


		$( document ).ready(function() {

			//help page, enable status gathering radios
			$( '#vamtam-ts-help form input[type="radio"]' ).each( function () {
				$(this).on( 'change', function() {
					save_options_ajax( $(this).closest( 'form' ), true );
				});
			} );

			//dashboard register copy button
			$( 'button#vamtam-check-license' ).on( 'click', function () {
				save_options_ajax( $(this).closest( 'form' ) );
			} );
		});
	});

})(jQuery);
