<?php
/**
 *  Contributors: MooveAgency
 *  Plugin Name: GDPR Cookie Compliance
 *  Plugin URI: https://wordpress.org/plugins/gdpr-cookie-compliance/
 *  Description: Our plugin is useful in preparing your site for the following data protection and privacy regulations: GDPR, CCPA, PIPEDA, AAP, LGPD and others.
 *  Version: 5.0.1
 *  Author: Moove Agency
 *  Domain Path: /languages
 *  Author URI: https://www.mooveagency.com
 *  License: GPLv3
 *  Text Domain: gdpr-cookie-compliance
 *
 *  @package gdpr-cookie-compliance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

define( 'MOOVE_GDPR_VERSION', '5.0.1' );
if ( ! defined( 'MOOVE_SHOP_URL' ) ) :
	define( 'MOOVE_SHOP_URL', 'https://shop.mooveagency.com' );
endif;

register_activation_hook( __FILE__, 'moove_gdpr_activate' );
register_deactivation_hook( __FILE__, 'moove_gdpr_deactivate' );

/**
 * Functions on plugin activation, create relevant pages and defaults for settings page.
 */
function moove_gdpr_activate() {
	delete_option( 'gdpr_cc_db_created' );
}

/**
 * Function on plugin deactivation. It removes the pages created before.
 */
function moove_gdpr_deactivate() {
	try {
		if ( class_exists( 'Moove_GDPR_License_Manager' ) ) :
			$gdpr_default_content = new Moove_GDPR_Content();
			$option_key           = $gdpr_default_content->moove_gdpr_get_key_name();
			$gdpr_key             = $gdpr_default_content->gdpr_get_activation_key( $option_key );

			if ( $gdpr_key && isset( $gdpr_key['key'] ) && isset( $gdpr_key['activation'] ) ) :
				$license_manager  = new Moove_GDPR_License_Manager();
				$validate_license = $license_manager->validate_license( $gdpr_key['key'], 'gdpr', 'deactivate' );
				if ( $validate_license && isset( $validate_license['valid'] ) && true === $validate_license['valid'] ) :
					update_option(
						$option_key,
						array(
							'key'          => $gdpr_key['key'],
							'deactivation' => strtotime( 'now' ),
						)
					);
				endif;
			endif;
		endif;
	} catch ( Exception $e ) {
		echo esc_html( $e->getMessage() );
	}
}

/**
 * Loading Text Domain - for translations & localizations
 */
add_action( 'plugins_loaded', 'moove_gdpr_load_textdomain' );
/**
 * Loading text domain
 */
function moove_gdpr_load_textdomain() {
	load_plugin_textdomain( 'gdpr-cookie-compliance', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

/**
 * Loading Core files after all the plugins are loaded!
 */
add_action( 'plugins_loaded', 'gdpr_cookie_compliance_load_libs' );

/**
 * Star rating on the plugin listing page
 */
if ( ! function_exists( 'gdpr_cookie_add_plugin_meta_links' ) ) {
	/**
	 * Meta links visible in plugins page
	 *
	 * @param array  $meta_fields Meta fields.
	 * @param string $file Plugin path.
	 */
	function gdpr_cookie_add_plugin_meta_links( $meta_fields, $file ) {
		if ( plugin_basename( __FILE__ ) === $file ) :
			$plugin_url    = 'https://wordpress.org/support/plugin/gdpr-cookie-compliance/reviews/?rate=5#new-post';
			$meta_fields[] = "<a href='" . esc_url( $plugin_url ) . "' target='_blank' title='" . esc_html__( 'Rate', 'gdpr-cookie-compliance' ) . "'>
					<i class='gdpr-plugin-star-rating'>"
			. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			. "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			. '</i></a>';

		endif;
		return $meta_fields;
	}
}
add_filter( 'plugin_row_meta', 'gdpr_cookie_add_plugin_meta_links', 10, 2 );
/**
 * Loading assets
 */
function gdpr_cookie_compliance_load_libs() {
	if ( current_user_can( 'edit_posts' ) && isset( $_GET['elementor-preview'] ) && intval( $_GET['elementor-preview'] ) ) : // phpcs:ignore
		/**
		 * Prevent loading the GDPR plugin in Elementor previews
		 */
	else :
		/**
		 * Database Controller
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'class-moove-gdpr-db-controller.php';

		/**
		 * View
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-gdpr-view.php';

		/**
		 * Modules View
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-gdpr-modules-view.php';
		/**
		 * Modules
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-gdpr-modules.php';

		/**
		 * Content
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-moove-gdpr-content.php';

		/**
		 * Options page
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-moove-gdpr-options.php';

		/**
		 * Controllers
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'class-moove-gdpr-controller.php';
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'class-moove-gdpr-license-manager.php';
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'class-moove-gdpr-review.php';

		/**
		 * Custom Functions
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'gdpr-functions.php';

		/**
		 * Actions
		 */
		include_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'class-moove-gdpr-actions.php';
	endif;
}
