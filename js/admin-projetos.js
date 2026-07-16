/* Admin UI for the projetos meta box: colour pickers + icon media uploader. */
( function ( $ ) {
	'use strict';

	$( function () {
		$( '.mage-color' ).wpColorPicker();

		var frame;
		var l10n = window.mageAdmin || {};

		$( '.mage-icon-choose' ).on( 'click', function ( e ) {
			e.preventDefault();

			if ( frame ) {
				frame.open();
				return;
			}

			frame = wp.media( {
				title: l10n.choose || 'Escolher',
				button: { text: l10n.use || 'Usar' },
				library: { type: 'image' },
				multiple: false
			} );

			frame.on( 'select', function () {
				var att = frame.state().get( 'selection' ).first().toJSON();
				var url = ( att.sizes && att.sizes.thumbnail ) ? att.sizes.thumbnail.url : att.url;
				$( '.mage-icon-id' ).val( att.id );
				$( '.mage-icon-preview' ).html(
					'<img src="' + url + '" width="64" height="64" style="object-fit:contain;border-radius:8px;background:#f0f0f1;" />'
				);
				$( '.mage-icon-remove' ).show();
			} );

			frame.open();
		} );

		$( '.mage-icon-remove' ).on( 'click', function ( e ) {
			e.preventDefault();
			$( '.mage-icon-id' ).val( '' );
			$( '.mage-icon-preview' ).empty();
			$( this ).hide();
		} );
	} );
}( jQuery ) );
