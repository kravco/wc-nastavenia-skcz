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
} );
