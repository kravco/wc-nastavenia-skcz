jQuery( function( $ ) {
	$( '#wc_nastavenia_skcz_billing_as_company' ).change( function( duration ) {
		var element_set = $( '#billing_company_field, #wc_nastavenia_skcz_billing_company_vat_id_field, #wc_nastavenia_skcz_billing_company_id_field, #wc_nastavenia_skcz_billing_company_tax_id_field' );
		if ( $( this ).is( ':checked' ) ) {
			element_set.fadeIn( duration ).first().focus();
		}
		else {
			element_set.fadeOut( duration ).val( '' );
		}
	} ).triggerHandler( 'change', [ 0 ] );

	$( '#billing_country' ).change( function() {
		var country = $( this ).val();
		if ( ! ( country in wc_nastavenia_skcz_localized_field_names ) ) {
			country = 'other';
		}
		var names = wc_nastavenia_skcz_localized_field_names[ country ];
		var ids = [ 'vat_id', 'id', 'tax_id' ];
		for ( var pos = names.length - 1; pos >= 0; pos-- ) {
			var id = 'wc_nastavenia_skcz_billing_company_' + ids[ pos ];
			var field = $( '#' + id );
			var label = $( 'label[for="' + id + '"]' );
			if ( names[ pos ] ) {
				field.show();
				label.text( names[ pos ] ).show();
			}
			else {
				field.hide().val( '' );
				label.hide();
			}
		}
	} ).triggerHandler( 'change' );
} );
