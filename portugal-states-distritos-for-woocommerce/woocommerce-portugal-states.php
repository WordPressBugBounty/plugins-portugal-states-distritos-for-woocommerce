<?php
/**
 * Plugin Name:          Portugal States (Distritos) for WooCommerce
 * Plugin URI:           https://www.webdados.pt/wordpress/plugins/portugal-states-distritos-woocommerce-wordpress/
 * Description:          This plugin adds the Portuguese "States", known as "Distritos", to WooCommerce and sets the correct address format for Portugal
 * Version:              5.2
 * Author:               Naked Cat Plugins (by Webdados)
 * Author URI:           https://nakedcatplugins.com
 * Text Domain:          portugal-states-distritos-for-woocommerce
 * Requires at least:    5.8
 * Tested up to:         7.0
 * Requires PHP:         7.2
 * WC requires at least: 7.1
 * WC tested up to:      10.7
 * Requires Plugins:     woocommerce
 * License:              GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Initialize the plugin.
 */
function woocommerce_portugal_states_init() {
	if ( class_exists( 'WooCommerce' ) && defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '7.1', '>=' ) ) {
		// Load the class
		$GLOBALS['WC_Webdados_Distritos'] = WC_Webdados_Distritos();
	}
}
add_action( 'plugins_loaded', 'woocommerce_portugal_states_init' );


/**
 * Get the main plugin instance.
 *
 * @return WC_Webdados_Distritos
 */
function WC_Webdados_Distritos() { //phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return WC_Webdados_Distritos::instance();
}

/**
 * Main plugin class.
 */
final class WC_Webdados_Distritos { // phpcs:ignore Universal.Files.SeparateFunctionsFromOO.Mixed, Generic.Classes.OpeningBraceSameLine.ContentAfterBrace

	/**
	 * Single instance.
	 *
	 * @var WC_Webdados_Distritos|null
	 */
	protected static $instance = null;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		// Hooks
		$this->init_hooks();
	}

	/**
	 * Get the class instance.
	 *
	 * @return WC_Webdados_Distritos
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register hooks.
	 */
	private function init_hooks() {
		// The States/Distritos
		add_filter( 'woocommerce_states', array( $this, 'woocommerce_states' ) );
		// Localization
		add_filter( 'woocommerce_get_country_locale', array( $this, 'woocommerce_get_country_locale' ) );
		// Correct portuguese address format
		add_filter(
			'woocommerce_localisation_address_formats',
			array( $this, 'woocommerce_localisation_address_formats' ),
			apply_filters( 'woocommerce_portugal_localisation_address_formats_priority', -1 )
		);
	}

	/**
	 * Add Portuguese districts to WooCommerce states.
	 *
	 * @param array $states Existing states.
	 * @return array
	 */
	public function woocommerce_states( $states ) {
		$states['PT'] = array(
			'AC' => __( 'Azores', 'portugal-states-distritos-for-woocommerce' ),
			'AV' => __( 'Aveiro', 'portugal-states-distritos-for-woocommerce' ),
			'BJ' => __( 'Beja', 'portugal-states-distritos-for-woocommerce' ),
			'BR' => __( 'Braga', 'portugal-states-distritos-for-woocommerce' ),
			'BG' => __( 'Bragança', 'portugal-states-distritos-for-woocommerce' ),
			'CB' => __( 'Castelo Branco', 'portugal-states-distritos-for-woocommerce' ),
			'CM' => __( 'Coimbra', 'portugal-states-distritos-for-woocommerce' ),
			'EV' => __( 'Évora', 'portugal-states-distritos-for-woocommerce' ),
			'FR' => __( 'Faro', 'portugal-states-distritos-for-woocommerce' ),
			'GD' => __( 'Guarda', 'portugal-states-distritos-for-woocommerce' ),
			'LR' => __( 'Leiria', 'portugal-states-distritos-for-woocommerce' ),
			'LS' => __( 'Lisbon', 'portugal-states-distritos-for-woocommerce' ),
			'MD' => __( 'Madeira', 'portugal-states-distritos-for-woocommerce' ),
			'PR' => __( 'Portalegre', 'portugal-states-distritos-for-woocommerce' ),
			'PT' => __( 'Oporto', 'portugal-states-distritos-for-woocommerce' ),
			'ST' => __( 'Santarém', 'portugal-states-distritos-for-woocommerce' ),
			'SB' => __( 'Setúbal', 'portugal-states-distritos-for-woocommerce' ),
			'VC' => __( 'Viana do Castelo', 'portugal-states-distritos-for-woocommerce' ),
			'VR' => __( 'Vila Real', 'portugal-states-distritos-for-woocommerce' ),
			'VS' => __( 'Viseu', 'portugal-states-distritos-for-woocommerce' ),
		);
		return $states;
	}

	/**
	 * Customize WooCommerce field settings for Portugal.
	 *
	 * @param array $countries Country locale settings.
	 * @return array
	 */
	public function woocommerce_get_country_locale( $countries ) {
		if ( ! isset( $countries['PT'] ) ) {
			$countries['PT'] = array();
		}
		$countries['PT']['postcode'] = array(
			'priority' => apply_filters( 'woocommerce_portugal_postcode_priority', 65 ), // Like Spain
			'class'    => apply_filters( 'woocommerce_portugal_postcode_class', array( 'form-row-first' ) ), // From 3.0 onwards
		);
		$countries['PT']['city']     = array(
			'label' => apply_filters( 'woocommerce_portugal_city_label', __( 'Postcode City', 'portugal-states-distritos-for-woocommerce' ) ),
			'class' => apply_filters( 'woocommerce_portugal_city_class', array( 'form-row-last' ) ), // From 3.0 onwards
		);
		$countries['PT']['state']    = array(
			'label'    => apply_filters( 'woocommerce_portugal_state_label', __( 'District', 'portugal-states-distritos-for-woocommerce' ) ),
			'required' => apply_filters( 'woocommerce_portugal_state_required', true ),
			'hidden'   => false,
		);
		return $countries;
	}

	/**
	 * Set the Portuguese address format.
	 *
	 * @param array $formats Address formats.
	 * @return array
	 */
	public function woocommerce_localisation_address_formats( $formats ) {
		// For Portugal
		$formats['PT'] = "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}";
		// Keep the legacy filter for stores that still include the district.
		if ( apply_filters( 'woocommerce_portugal_address_format_include_state', false ) ) {
			$formats['PT'] = "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}";
		}
		return $formats;
	}
}

// Declare WooCommerce compatibility.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}
);


// Load the Portuguese Postcodes nag when needed.
add_action(
	'admin_init',
	function () {
		if (
		current_user_can( 'manage_woocommerce' )
		&&
		( ! defined( 'WEBDADOS_PORTUGUESE_POSTCODES_NAG' ) )
		&&
		( ! function_exists( '\Webdados\PortuguesePostcodesWooCommerce\init' ) )
		&&
		empty( get_transient( 'webdados_portuguese_postcodes_nag' ) ) // Not used anymore, but kept for backwards compatibility
		&&
		( intval( get_user_meta( get_current_user_id(), 'webdados_portuguese_postcodes_nag_dismissed_until', true ) ) < time() )
		) {
			define( 'WEBDADOS_PORTUGUESE_POSTCODES_NAG', true );
			require_once 'webdados_portuguese_postcodes_nag/webdados_portuguese_postcodes_nag.php';
		}
	}
);

// Load the Ifthenpay recommendation.
if ( ! defined( 'WEBDADOS_RECOMMEND_IFTHENPAY' ) ) {
	require_once 'recommend-ifthenpay/recommend-ifthenpay.php';
}

/* If you’re reading this you must know what you’re doing ;-) Greetings from sunny Portugal! */
