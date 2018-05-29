<?php

namespace Webikon\Woocommerce_Plugin\WC_Nastavenia_SKCZ;

// protect against direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {
	const PREFIX = 'wc_nastavenia_skcz_';
	const BILLING_AS_COMPANY_KEY = 'wc_nastavenia_skcz_billing_as_company';

	private static $instance;

	/** @singleton */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static;
		}
		return static::$instance;
	}

	/** @constructor */
	protected function __construct() {
		add_action( 'init', [ $this, 'init' ], 20, 0 );
		add_action( 'admin_init', [ $this, 'admin_init' ], 20, 0 );
	}

	/**
	 * Method to run at 'init' action.
	 * Register actions and filters required at frontend.
	 */
	public function init() {
		load_plugin_textdomain( 'wc-nastavenia-skcz', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		add_filter( 'woocommerce_billing_fields', [ $this, 'filter_billing_fields' ], 20, 1 );
		add_filter( 'woocommerce_shipping_fields', [ $this, 'filter_shipping_fields' ], 20, 1 );
		add_filter( 'woocommerce_localisation_address_formats', [ $this, 'filter_country_address_formats' ], 20, 1 );
		add_filter( 'woocommerce_formatted_address_replacements', [ $this, 'filter_billing_address_replacements' ], 20, 2 );
		add_filter( 'woocommerce_order_formatted_billing_address', [ $this, 'filter_order_billing_address' ], 20, 2 );
		add_filter( 'woocommerce_order_formatted_shipping_address', [ $this, 'filter_order_shipping_address' ], 20, 2 );
		add_filter( 'woocommerce_my_account_my_address_formatted_address', [ $this, 'filter_user_account_address' ], 20, 3 );

		add_action( 'woocommerce_checkout_create_order', [ $this, 'save_order_billing_fields' ], 20, 2 );
		add_action( 'woocommerce_checkout_update_customer', [ $this, 'save_customer_billing_fields' ], 20, 2 );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_billing_fields_scripts' ], 20, 0 );
	}

	/**
	 * Method to run at 'admin_init' action.
	 * Register actions and filters required at backend.
	 */
	public function admin_init() {
		add_filter( 'woocommerce_admin_billing_fields', [ $this, 'filter_order_admin_billing_fields' ], 20, 1 );
		add_filter( 'woocommerce_customer_meta_fields', [ $this, 'filter_customer_admin_billing_fields' ], 20, 1 );
	}

	/**
	 * Filter billing fields displayed at checkout.
	 * Inserts checkbox for customer to choose whether they want to shop as a private person or a company.
	 * Additionally moves country selection in front of this checkbox, to allow for country specific company detail fields.
	 */
	public function filter_billing_fields( $fields ) {
		if ( ! isset( $fields['billing_company'] ) || ! is_array( $fields['billing_company'] ) ) {
			return $fields;
		}
		$insert = [
			static::BILLING_AS_COMPANY_KEY => [
				'type' => 'checkbox',
				'label' => __( 'Buy as a company', 'wc-nastavenia-skcz' ),
				'class' => [ 'form-row-wide' ],
				'priority' => 44,
			],
			'billing_company' => [ 'priority' => 45 ] + $fields['billing_company'],
		];
		foreach ( static::get_additional_fields() as $key => $info ) {
			$insert[ $key ] = [
				'type' => 'text',
				'label' => $info['label'], // this label gets replaced by javascript
				'class' => [ $info['class'] ],
				'priority' => $info['priority'],
			];
		}
		unset( $fields['billing_company'] );
		return static::associative_array_insert( $fields, 'billing_country', $insert, false );
	}

	/**
	 * Filter shipping fields displayed at my account page.
	 * Move country selection in front of company field to match billing field order.
	 */
	public function filter_shipping_fields( $fields ) {
		if ( ! isset( $fields['shipping_company'] ) || ! is_array( $fields['shipping_company'] ) ) {
			return $fields;
		}
		$insert = [ 'shipping_company' => [ 'priority' => 45 ] + $fields['shipping_company'] ];
		unset( $fields['shipping_company'] );
		return static::associative_array_insert( $fields, 'shipping_country', $insert, false );
	}

	/**
	 * Save additional order billing fields at checkout.
	 */
	public function save_order_billing_fields( $order, $data ) {
		if ( empty( $data[ static::BILLING_AS_COMPANY_KEY ] ) ) {
			$order->update_meta_data( '_' . static::BILLING_AS_COMPANY_KEY, '0' );
			foreach ( static::get_additional_fields() as $key => $info ) {
				$order->update_meta_data( "_$key", '' );
			}
			return;
		}
		$order->update_meta_data( '_' . static::BILLING_AS_COMPANY_KEY, '1' );
		foreach ( static::get_additional_fields() as $key => $info ) {
			$order->update_meta_data( "_$key", $data[ $key ] );
		}
	}

	/**
	 * Save additional customer billing fields at checkout.
	 */
	public function save_customer_billing_fields( $customer, $data ) {
		if ( empty( $data[ static::BILLING_AS_COMPANY_KEY ] ) ) {
			$customer->update_meta_data( static::BILLING_AS_COMPANY_KEY, '0' );
			foreach ( static::get_additional_fields() as $key => $info ) {
				$customer->update_meta_data( $key, '' );
			}
			return;
		}
		$customer->update_meta_data( static::BILLING_AS_COMPANY_KEY, '1' );
		foreach ( static::get_additional_fields() as $key => $info ) {
			$customer->update_meta_data( $key, $data[ $key ] );
		}
	}

	/**
	 * Enqueue scripts required at checkout page and account page.
	 */
	public function enqueue_billing_fields_scripts() {
		if ( is_checkout() || is_account_page() ) {
			wp_enqueue_script( 'wc-nastavenia-skcz', plugin_dir_url( __FILE__ ) . 'js/wc-nastavenia-skcz.js', [ 'jquery' ], '1.1.0' );
		}
	}

	/**
	 * Add placeholder for custom company information into country address template.
	 * For now it supports only two countries: SK and CZ (Slovakia and Czech Republic).
	 */
	public function filter_country_address_formats( $formats ) {
		foreach ( WC()->countries->get_european_union_countries( 'eu_vat' ) as $code ) {
			if ( ! isset( $formats[ $code ] ) ) {
				$formats[ $code ] = $formats['default'];
			}
			$formats[ $code ] .= "\n{" . static::BILLING_AS_COMPANY_KEY . "}";
		}
		return $formats;
	}

	/**
	 * Add values for custom replacement for company information?
	 */
	public function filter_billing_address_replacements( $replacements, $args ) {
		if ( isset( $args[ static::BILLING_AS_COMPANY_KEY ] ) ) {
			$replacements[ '{' . static::BILLING_AS_COMPANY_KEY . '}' ] = $args[ static::BILLING_AS_COMPANY_KEY ];
		}
		return $replacements;
	}

	/**
	 * Add values for custom replacement for company information in billing address.
	 */
	public function filter_order_billing_address( $args, $order ) {
		if ( $order->get_meta( '_' . static::BILLING_AS_COMPANY_KEY ) ) {
			$country = $order->get_billing_country();
			$additional_info = [];
			foreach ( static::get_additional_fields() as $key => $info ) {
				$value = $order->get_meta( "_$key" );
				if ( ! empty( $value ) ) {
					$additional_info[] = static::get_additional_field_info( $country, $info, $value );
				}
			}
			$args[ static::BILLING_AS_COMPANY_KEY ] = implode( "\n", $additional_info );
		}
		else {
			$args['company'] = '';
			$args[ static::BILLING_AS_COMPANY_KEY ] = '';
		}
		return $args;
	}

	/**
	 * Add dummy replacement for company information in shipping address.
	 * Billing company information should not be displayed in shipping address.
	 */
	public function filter_order_shipping_address( $args, $order ) {
		if ( ! $order->get_meta( '_' . static::BILLING_AS_COMPANY_KEY ) ) {
			$args['company'] = '';
		}
		$args[ static::BILLING_AS_COMPANY_KEY ] = '';
		return $args;
	}

	/**
	 * Add values for custom replacements for company information in addresses at account page.
	 */
	public function filter_user_account_address( $args, $user_id, $address_type ) {
		$customer = new \WC_Customer( $user_id );
		if ( 'billing' === $address_type ) {
			if ( $customer->get_meta( static::BILLING_AS_COMPANY_KEY ) ) {
				$country = $customer->get_billing_country();
				$additional_info = [];
				foreach ( static::get_additional_fields() as $key => $info ) {
					$value = $customer->get_meta( $key );
					if ( ! empty( $value ) ) {
						$additional_info[] = static::get_additional_field_info( $country, $info, $value );
					}
				}
				$args[ static::BILLING_AS_COMPANY_KEY ] = implode( "\n", $additional_info );
			}
			else {
				$args[ 'company' ] = '';
				$args[ static::BILLING_AS_COMPANY_KEY ] = '';
			}
		}
		if ( 'shipping' === $address_type ) {
			if ( ! $customer->get_meta( static::BILLING_AS_COMPANY_KEY ) ) {
				$args[ 'company' ] = '';
			}
			$args[ static::BILLING_AS_COMPANY_KEY ] = '';
		}
		return $args;
	}

	/**
	 * Filter order billing fields in order administration and my account page.
	 */
	public function filter_order_admin_billing_fields( $fields ) {
		if ( ! isset( $fields['company'] ) || ! is_array( $fields['company'] ) ) {
			return $fields;
		}
		$insert = [
			static::BILLING_AS_COMPANY_KEY => [
				'id' => '_' . static::BILLING_AS_COMPANY_KEY,
				'label' => '',
				'show' => false,
				'type' => 'select',
				'wrapper_class' => 'form-field-wide',
				'options' => static::get_billing_as_company_select_values(),
			],
			'company' => $fields['company'],
		];
		foreach ( static::get_additional_fields() as $key => $info ) {
			$insert[ $key ] = [
				'id' => "_$key",
				'label' => $info['label'],
				'wrapper_class' => $info['wrapper_class'],
				'show' => false,
			];	
		}
		return static::associative_array_insert( $fields, 'company', $insert );
	}

	/**
	 * Filter customer billing fields in user profile administration.
	 */
	public function filter_customer_admin_billing_fields( $fields ) {
		if ( ! isset( $fields['billing'] ) || ! isset( $fields['billing']['fields'] ) ) {
			return $fields;
		}
		$bf = $fields['billing']['fields'];
		if ( ! isset( $bf['billing_company'] ) || ! is_array( $bf['billing_company'] ) ) {
			return $fields;
		}
		$insert = [
			static::BILLING_AS_COMPANY_KEY => [
				'label' => __( 'Type of buying', 'wc-nastavenia-skcz' ),
				'description' => '',
				'type' => 'select',
				'options' => static::get_billing_as_company_select_values(),
			],
			'billing_company' => $bf['billing_company'],
		];
		foreach ( static::get_additional_fields() as $key => $info ) {
			$insert[ $key ] = [
				'label' => $info['label'],
				'description' => '',
			];	
		}
		$fields['billing']['fields'] = static::associative_array_insert( $bf, 'billing_company', $insert );
		return $fields;
	}

	public function get_customer_details( $order ) {
		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}
		if ( ! $order instanceof \WC_Abstract_Order ) {
			return null;
		}

		$billing_as_company = $order->get_meta( '_' . static::BILLING_AS_COMPANY_KEY );
		$company_vat_id = $order->get_meta( '_' . static::PREFIX . 'billing_company_vat_id' );
		$company_id = $order->get_meta( '_' . static::PREFIX . 'billing_company_id' );
		$company_tax_id = $order->get_meta( '_' . static::PREFIX . 'billing_company_tax_id' );

		if ( '' === "$billing_as_company$company_vat_id$company_id$company_tax_id" ) {
			// try fallback meta fields from SuperFaktura/Webikon Invoice plugin
			$company_vat_id = $order->get_meta( 'billing_company_wi_vat' );
			$company_id = $order->get_meta( 'billing_company_wi_id' );
			$company_tax_id = $order->get_meta( 'billing_company_wi_tax' );
			// this field is not saved, we have to guess it
			$billing_as_company = '' !== "$company_vat_id$company_id$company_tax_id";
		}

		$billing = apply_filters( 'wc_nastavenia_skcz_customer_details_billing', [
			'company_vat_id' => $company_vat_id,
			'company_id' => $company_id,
			'company_tax_id' => $company_tax_id,
		], $order );

		require_once __DIR__ . '/class-customer-details.php';

		return new Customer_Details( $billing_as_company, $billing['company_vat_id'], $billing['company_id'], $billing['company_tax_id'] );
	}

	/**
	 * Get list of additional fields in company details.
	 */
	protected static function get_additional_fields() {
		return [
			static::PREFIX . 'billing_company_vat_id' => [
				'name' =>  'VAT ID',
				'label' => __( 'VAT ID', 'wc-nastavenia-skcz' ),
				'class' => 'form-row-wide',
				'wrapper_class' => '_billing_company_field',
				'priority' => 46,
			],
			static::PREFIX . 'billing_company_id' => [
				'name' => 'ID',
				'label' => __( 'ID', 'wc-nastavenia-skcz' ),
				'class' => 'form-row-first',
				'wrapper_class' => '_billing_first_name_field',
				'priority' => 47,
			],				
			static::PREFIX . 'billing_company_tax_id' => [
				'name' => 'Tax ID',
				'label' => __( 'Tax ID', 'wc-nastavenia-skcz' ),
				'class' => 'form-row-last',
				'wrapper_class' => '_billing_last_name_field',
				'priority' => 48,
			],
		];	
	}

	/**
	 * Get list of additional field info to append to address.
	 */
	protected static function get_additional_field_info( $country, $info, $value ) {
		if ( is_admin() ) {
			$label = $info['label'];
		}
		else {
			$label = static::get_country_field_label( $country, $info['name'] );
		}
		return "$label: $value";
	}

	/**
	 * Get list of additional field name for country.
	 */
	protected static function get_country_field_label( $country, $field ) {
		$fields = [ 'VAT ID' => 'VAT_ID', 'ID' => 'ID', 'Tax ID' => 'Tax ID' ];
		switch ( $country ) {
		case 'SK':
			$fields = [ 'VAT ID' => 'IČ DPH', 'ID' => 'IČO', 'Tax ID' => 'DIČ' ];
			break;
		case 'CZ':
			$fields = [ 'VAT ID' => 'DIČ', 'ID' => 'IČO' ];
			break;
		}
		return isset( $fields[ $field ] ) ? $fields[ $field ] : null;
	}

	/**
	 * Get possible values for customer type.
	 */
	protected static function get_billing_as_company_select_values() {
		return [
			'0' => __( 'Buy as a private person', 'wc-nastavenia-skcz' ),
			'1' => __( 'Buy as a company', 'wc-nastavenia-skcz' ),
		];
	}

	/** @internal */
	protected static function associative_array_insert( $array, $index, $insert, $replace = true ) {
		$numeric_index = array_search( $index, array_keys( $array ) );
		return array_slice( $array, 0, $numeric_index + ( $replace ? 0 : 1 ) ) + $insert + array_slice( $array, $numeric_index + 1 );
	}
}
