<?php
/**
 * Plugin Name: Extended Icon Support
 * Description: Restores icons for older browsers, such as Firefox Extended Support Release (ESR). Copies CSS rules from https://github.com/WordPress/wordpress-develop/pull/10636 and adds them inline.
 * Version:     0.5
 * Author:      WordPress Contributors
 * Author URI:  https://core.trac.wordpress.org/ticket/64350
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Requires at least: 6.9
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

function esr_icon_support_update_icon_styles() {
	$handles = array(
		// wp-admin styles
		'admin-menu',
		'colors',
		'common',
		'customize-controls',
		'customize-nav-menus',
		'customize-widgets',
		'dashboard',
		'edit',
		'forms',
		'list-tables',
		'media',
		'revisions',
		'site-health',
		'themes',
		'widgets',

		// wp-includes styles
		'admin-bar',
		'editor-buttons', // compare to `wp-includes/css/editor.css`
		'media-views',
		'wp-auth-check',
		'wp-pointer',
		'wp-jquery-ui-dialog', // compare to `wp-includes/css/jquery-ui-dialog.css`
		'thickbox',
	);

	foreach ( $handles as $handle ) {
		$suffix = ( 'thickbox' !== $handle ) ? wp_scripts_get_suffix() : '';
		$css    = file_get_contents( plugin_dir_path( __FILE__ ) . "/css/$handle$suffix.css" );

		if ( 'colors' === $handle ) {
			$color = get_user_option( 'admin_color' );

			/*
			 * This plugin's `colors.css` stylesheet comes from the Blue scheme.
			 * A few color schemes have a different checkbox color.
			 */
			if ( 'coffee' === $color ) {
				$css = str_replace( '7e8993', '59524c', $css );
			} elseif ( 'ectoplasm' === $color ) {
				$css = str_replace( '7e8993', '523f6d', $css );
			} elseif ( 'ocean' === $color ) {
				$css = str_replace( '7e8993', '738e96', $css );
			}
		}

		wp_add_inline_style( $handle, $css );
	}
}

add_action( 'admin_enqueue_scripts', 'esr_icon_support_update_icon_styles', 11 );
add_action( 'login_enqueue_scripts', 'esr_icon_support_update_icon_styles', 11 );
add_action( 'wp_enqueue_scripts', 'esr_icon_support_update_icon_styles', 11 );
