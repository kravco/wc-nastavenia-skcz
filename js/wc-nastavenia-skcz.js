jQuery( document ).ready( function() {
	jQuery( '#wc_nastavenia_skcz_billing_as_company' ).change( function( duration ) { 
		var element_set = jQuery( '#billing_company_field, #wc_nastavenia_skcz_billing_company_vat_id_field, #wc_nastavenia_skcz_billing_company_id_field, #wc_nastavenia_skcz_billing_company_tax_id_field' );
		if ( jQuery( this ).is( ':checked' ) ) {
			element_set.fadeIn( duration ).first().focus();
		}
		else {
			element_set.fadeOut( duration ).val( '' );
		}
	} ).triggerHandler( 'change', [ 0 ] );

	jQuery( '#billing_country' ).change( function() {
		var customizations = {
			'SK': [ 'IČ DPH', 'IČO', 'DIČ' ],
			'CZ': [ 'DIČ', 'IČO', null ],
			'other': [ 'VAT ID', null, null ]
		}

		var country = jQuery( this ).val();
		var cust = customizations[ country in customizations ? country : 'other' ];
		var names = [ 'vat_id', 'id', 'tax_id' ];
		for ( var pos = cust.length - 1; pos >= 0; pos-- ) {
			var id = 'wc_nastavenia_skcz_billing_company_' + names[ pos ];
			var field = jQuery( '#' + id );
			var label = jQuery( 'label[for="' + id + '"]' );
			if ( cust[ pos ] ) {
				field.show();
				label.text( cust[ pos ] ).show();
			}
			else {
				field.hide().val( '' );
				label.hide();
			}
		}
	} ).triggerHandler( 'change' );
} );
