<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Plugin Name: BuddyPress xProfile Checkout Manager for WooCommerce
 * Plugin URI:  https://themekraft.com/products/wc4bp-checkout-manager/
 * Description: BuddyPress xProfile Checkout Manager for WooCommerce - Add BuddyPress xProfile Fields to the WooCommerce Checkout and remove WooCommerce Fields from the Checkout
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/products/woocommerce-buddypress-integration/
 * Version: 1.3.11
 * Text Domain: wc4bp_xprofile
 * Domain Path: /languages
 * Svn: woocommerce-buddypress-integration-xprofile-checkout-manager
 *
 * ****************************************************************************
 * WC requires at least: 3.0.0
 * WC tested up to: 5.8.0
 * ****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * ***************************************************************************
 */

require_once dirname( __FILE__ ) . '/includes/wc4bp-xprofile-fs-integration.php';
new wc4bp_xprofile_freemius_integration();

class WC4BP_xProfile {

	/**
	 * @var string
	 */
	public $version = '1.3.11';

	/**
	 * @var bool
	 */
	public $active;

	/**
	 * Initiate the class
	 *
	 * @package wc4bp_xprofile
	 * @since 1.0
	 */
	public function __construct() {

		define( 'WC4BP_XPROFILE_VERSION', $this->version );
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-tgm-plugin-activation.php';
		require_once plugin_dir_path( __FILE__ ) . '/includes/wc4bp-xprofile-required.php';
		new WC4BP_Xprofile_Required();
		if ( WC4BP_Xprofile_Required::is_wc4bp_active() ) {
			if ( ! empty( $GLOBALS['wc4bp_loader'] ) ) {
				$wc4bp    = $GLOBALS['wc4bp_loader'];
				$freemius = $wc4bp::getFreemius();
				if ( ! empty( $freemius ) ) {
					if ( WC4BP_Xprofile_Required::is_woocommerce_active() && WC4BP_Xprofile_Required::is_buddypress_active() ) {
						add_action( 'init', array( $this, 'includes' ), 4, 1 );
						add_action( 'init', array( $this, 'load_plugin_textdomain' ), 10, 1 );
						$this->active = true;
					}
				}
			}
		}
	}

	public static function plugin_base_url() {

		return plugin_dir_url( __FILE__ );

	}

	/**
	 * Load the language file
	 *
	 * @since    1.0
	 * @uses    load_plugin_textdomain()
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'wc4bp_xprofile', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	}

	/**
	 * Include files needed by wc4bp_xprofile
	 *
	 * @package wc4bp_xprofile
	 * @since 1.0
	 */
	public function includes() {

		require_once plugin_dir_path( __FILE__ ) . '/includes/wc4bp-xprofile-checkout.php';
		if ( is_admin() ) {
			require_once plugin_dir_path( __FILE__ ) . 'admin/admin-xprofile.php';
			require_once plugin_dir_path( __FILE__ ) . 'admin/admin-xprofile-ajax.php';
		}

	}

}

// If there's not Race Condition
// run the old entry point.
if ( ! isset( $GLOBALS['wc4bp_xprofile'] ) && isset( $GLOBALS['wc4bp_loader'] ) ) {
	$GLOBALS['wc4bp_xprofile'] = new WC4BP_xProfile();
}

// To avoid Race Conditon let's run
// WC4BP_xProfile entry point after the WC4BP init action.
add_action(
	'wc4bp_init',
	function() {
		if ( ! isset( $GLOBALS['wc4bp_xprofile'] ) ) {
			$GLOBALS['wc4bp_xprofile'] = new WC4BP_xProfile();
		}
	}
);
