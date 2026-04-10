<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Show the Portuguese Postcodes admin notice.
add_action( 'admin_notices', 'webdados_portuguese_postcodes_nag' );

/**
 * Render the Portuguese Postcodes admin notice.
 */
function webdados_portuguese_postcodes_nag() {
	?>
		<script type="text/javascript">
		jQuery(function($) {
			$( document ).on( 'click', '#webdados_portuguese_postcodes_nag .notice-dismiss', function () {
				// Store the dismissal for a few months.
				$.ajax( ajaxurl, {
					type: 'POST',
					data: {
						action: 'dismiss_webdados_portuguese_postcodes_nag',
					}
				});
			});
		});
		</script>
		<div id="webdados_portuguese_postcodes_nag" class="notice notice-info is-dismissible">
			<p style="line-height: 1.4em;">
				<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'icon-portuguese-postcodes.svg' ); ?>" width="70" height="70" style="float: left; max-width: 70px; height: auto; margin-right: 1em;"/>
				<strong><?php esc_html_e( 'Do your customers still write the full address manually on the checkout?', 'portugal-states-distritos-for-woocommerce' ); ?></strong>
				<br/>
			<?php
				printf(
					/* translators: %1$s and %2$s are opening and closing anchor tags respectively. */
					esc_html__( 'Activate the automatic filling of the address details at the checkout, including street name and neighbourhood, based on the postal, avoiding incorrect data at the time of shipping, with our plugin %1$sPortuguese Postcodes for WooCommerce%2$s', 'portugal-states-distritos-for-woocommerce' ),
					'<a href="https://nakedcatplugins.com/product/portuguese-postcodes-for-woocommerce-technical-support/" target="_blank">',
					'</a>'
				);
			?>
				<br/>
				<?php echo wp_kses_post( __( 'Use the coupon <strong>webdados</strong> for 10% discount!', 'portugal-states-distritos-for-woocommerce' ) ); ?>
			</p>
		</div>
		<?php
}

add_action( 'wp_ajax_dismiss_webdados_portuguese_postcodes_nag', 'dismiss_webdados_portuguese_postcodes_nag' );

/**
 * Persist the notice dismissal.
 */
function dismiss_webdados_portuguese_postcodes_nag() {
	$days                 = 120;
	$expiration_timestamp = time() + ( $days * DAY_IN_SECONDS );
	update_user_meta( get_current_user_id(), 'webdados_portuguese_postcodes_nag_dismissed_until', $expiration_timestamp );
	wp_die();
}