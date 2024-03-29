<?php
/**
 * Copyright 2015 Nelio Software S.L.
 * This script is distributed under the terms of the GNU General Public
 * License.
 *
 * This script is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    NelioAB
 * @subpackage NelioAB
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 */

/**
 * Plugin Name: Nelio A/B Testing
 * Description: Optimize your site based on data, not opinions. With this plugin, you will be able to perform A/B testing (and more) on your WordPress site.
 * Version: 4.7.6
 * Author: Nelio Software
 * Author URI: http://neliosoftware.com
 * Plugin URI: https://neliosoftware.com/testing/
 * Text Domain: nelio-ab-testing
 * Domain Path: /languages
 */

// PLUGIN VERSION.
define( 'NELIOAB_PLUGIN_VERSION', '4.7.6' );

// Plugin dir name...
define( 'NELIOAB_PLUGIN_NAME', 'Nelio A/B Testing' );
define( 'NELIOAB_PLUGIN_ID', 'nelio-ab-testing/nelio-ab-testing.php' );
define( 'NELIOAB_PLUGIN_DIR_NAME', basename( dirname( __FILE__ ) ) );

// Defining a few important directories.
define( 'NELIOAB_ROOT_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'NELIOAB_DIR', NELIOAB_ROOT_DIR . '/includes' );

define( 'NELIOAB_ADMIN_DIR', NELIOAB_DIR . '/admin' );
define( 'NELIOAB_EXP_CONTROLLERS_DIR', NELIOAB_DIR . '/experiment-controllers' );
define( 'NELIOAB_UTILS_DIR', NELIOAB_DIR . '/utils' );
define( 'NELIOAB_MODELS_DIR', NELIOAB_DIR . '/models' );

// Some URLs...
define( 'NELIOAB_BACKEND_VERSION', 6 );
define( 'NELIOAB_BACKEND_NAME', 'nelioabtesting' );
define( 'NELIOAB_BACKEND_DOMAIN', NELIOAB_BACKEND_NAME . '.appspot.com' );
define( 'NELIOAB_BACKEND_URL', 'https://' . NELIOAB_BACKEND_DOMAIN . '/_ah/api/nelioab/v' . NELIOAB_BACKEND_VERSION );
define( 'NELIOAB_BACKEND_SERVLET_URL', 'https://' . NELIOAB_BACKEND_DOMAIN . '/v' . NELIOAB_BACKEND_VERSION );

// CPT.
define( 'NELIOAB_SHOW_LOCAL_EXPS', false );

include_once( NELIOAB_ROOT_DIR . '/ajax/iesupport.php' );

// Including Settings classes.
require_once( NELIOAB_MODELS_DIR . '/settings.php' );
require_once( NELIOAB_MODELS_DIR . '/account-settings.php' );

// Including basic functions (helpers and user information).
require_once( NELIOAB_UTILS_DIR . '/essentials.php' );

// Updater codes.
require_once( NELIOAB_UTILS_DIR . '/updater-4.4.0.php' );

// Including base controllers.
require_once( NELIOAB_DIR . '/controller.php' );
require_once( NELIOAB_ADMIN_DIR . '/admin-controller.php' );
require_once( NELIOAB_ADMIN_DIR . '/woocommerce/woocommerce-support.php' );

// Making sure all alternatives are hidden when the plugin is deactivated.
register_activation_hook( __FILE__, 'nelioab_activate_plugin' );
register_deactivation_hook( __FILE__, 'nelioab_deactivate_plugin' );

// Making sure everything is up-to-date when the plugin is updated.
add_action( 'plugins_loaded', 'nelioab_update_plugin_info_if_required' );

/**
 * Loads all Nelio A/B Testing internationalization strings.
 */
function nelioab_i18n() {
	load_plugin_textdomain( 'nelio-ab-testing', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}//end nelioab_i18n()
add_action( 'plugins_loaded', 'nelioab_i18n' );

/**
 * This is an AJAX callback function, triggered by the action
 * <code>dismiss_upgrade_notice</code>. This action occurs when an admin user
 * dismisses an upgrade notice.
 */
function dismiss_upgrade_notice_callback() {
	NelioABSettings::hide_upgrade_message();
	echo '0';
	die();
}//end dismiss_upgrade_notice_callback()

add_action( 'wp_ajax_dismiss_upgrade_notice', 'dismiss_upgrade_notice_callback' );
include_once( NELIOAB_ROOT_DIR . '/includes/utils/local-exps.php' );

// Disable publication of alternatives
add_action( 'save_post', 'disable_alternative_publication' );
function disable_alternative_publication( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! get_post_meta( $post_id, '_is_nelioab_alternative', true ) ) {
		return;
	}

	remove_action( 'save_post', 'disable_alternative_publication' );
	wp_update_post( array(
		'ID'          => $post_id,
		'post_status' => 'draft',
		'post_name'   => 'nelioab-' . $post_id,
	) );

	add_action( 'save_post', 'disable_alternative_publication' );
}
