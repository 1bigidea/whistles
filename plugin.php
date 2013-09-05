<?php
/**
 * Plugin Name: Whistles
 * Plugin URI: https://github.com/justintadlock/whistles
 * Description: Tabs, accordions, and all that other jazz.
 * Version: 0.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * Long Description
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   Whistles
 * @version   0.1.0
 * @since     0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2013, Justin Tadlock
 * @link      https://github.com/justintadlock/whistles
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

final class Tabs_And_Jazz {

	/**
	 * PHP5 constructor method.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function setup() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( __CLASS__, 'constants' ), 1 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( __CLASS__, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( __CLASS__, 'includes' ), 3 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( __CLASS__, 'admin' ), 4 );

		/* Enqueue scripts and styles. */
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		/* Print scripts in the header. */
		add_action( 'wp_head', array( __CLASS__, 'print_scripts' ) );

		/* Register activation hook. */
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function constants() {

		/* Set constant path to the plugin directory. */
		define( 'TAJ_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the plugin directory URI. */
		define( 'TAJ_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the includes directory. */
		define( 'TAJ_INCLUDES', TAJ_DIR . trailingslashit( 'includes' ) );

		/* Set the constant path to the admin directory. */
		define( 'TAJ_ADMIN', TAJ_DIR . trailingslashit( 'admin' ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function includes() {

		require_once( TAJ_INCLUDES . 'post-types.php' );
		require_once( TAJ_INCLUDES . 'taxonomies.php' );
		require_once( TAJ_INCLUDES . 'class-tabs-bells-whistles.php' );
		require_once( TAJ_INCLUDES . 'shortcodes.php' );
		require_once( TAJ_INCLUDES . 'widgets.php' );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function i18n() {

		/* Load the translation of the plugin. */
	//	load_plugin_textdomain( 'tabs-and-jazz', false, 'tabs-and-jazz/languages' );
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function admin() {

		if ( is_admin() )
			require_once( TAJ_ADMIN . 'admin.php' );
	}

	public static function enqueue_scripts() {

		/* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
		$suffix = ''; // ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		/* Enqueue the stylesheet. */
		wp_enqueue_style(
			'tabs-and-jazz',
			trailingslashit( plugin_dir_url( __FILE__ ) ) . "css/tabs-and-jazz$suffix.css",
			null,
			'20130123'
		);

		/* Load the jQuery UI Tabs and Accordion scripts. */
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-accordion' );
	}

	/**
	 * Enables tabs and toggles using jQuery UI.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function print_scripts() { ?>
		<script>
		jQuery( document ).ready(
			function() {
				jQuery( '.taj-tabs' ).tabs();
				jQuery( '.taj-toggle' ).accordion();
			}
		);
		</script>
	<?php }

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function activation() {

		/* Get the administrator role. */
		$role = get_role( 'administrator' );

		/* If the administrator role exists, add required capabilities for the plugin. */
		if ( !empty( $role ) ) {

			$role->add_cap( 'manage_tabs' );
			$role->add_cap( 'create_tabs' );
			$role->add_cap( 'edit_tabs'   );
		}
	}
}

Tabs_And_Jazz::setup();

?>