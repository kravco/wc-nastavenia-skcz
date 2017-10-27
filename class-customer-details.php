<?php

namespace Webikon\Woocommerce_Plugin\WC_Nastavenia_SKCZ;

// protect against direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Customer_Details {
	private $billing_as_company;
	private $company_vat_id;
	private $company_id;
	private $company_tax_id;

	public function __construct( $billing_as_company, $company_vat_id = '', $company_id = '', $company_tax_id = '' ) {
		$this->billing_as_company = boolval( $billing_as_company );
		$this->company_vat_id = strval( $company_vat_id );
		$this->company_id = strval( $company_id );
		$this->company_tax_id = strval( $company_tax_id );
	}

	/** convenient alias */
	public function is_billing_as_company() {
		return $this->get_billing_as_company();
	}

	public function get_billing_as_company() {
		return $this->billing_as_company;
	}

	public function get_company_vat_id() {
		return $this->company_vat_id;
	}

	public function get_company_id() {
		return $this->company_id;
	}

	public function get_company_tax_id() {
		return $this->company_tax_id;
	}
}
