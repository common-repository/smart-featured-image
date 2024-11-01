<?php

/**
 * Uninstall
 *
 * Checks and actions to perform during plugin
 * uninstallation.
 *
 * @link            http://example.com
 * @since           Smart Featured Images 0.1.0
 *
 * @package         smart-featured-image
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

use GrottoPress\SFI\Helpers;

/**
 * Autoload
 * 
 * @since       Smart Featured Image 0.2.4
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Check capability
 * 
 * @since 		Smart Featured Images 0.1.0
 */
if ( ! current_user_can( 'activate_plugins' ) ) {
	wp_die( esc_html__( 'You are not allowed to perform this action!', 'smart-featured-image' ) );
}

/**
 * Validate plugin to uninstall
 *
 * @since 		Smart Featured Images 0.1.0
 */
if (
	! defined( 'WP_UNINSTALL_PLUGIN' )
	|| 'smart-featured-image/smart-featured-image.php' != WP_UNINSTALL_PLUGIN
) {
	wp_die();
}

/**
 * Remove options
 *
 * @since 		Smart Featured Images 0.1.0
 */

$post_types = Helpers\get_post_types();

if ( is_multisite() ) {
	$get_sites_exists = function_exists( 'get_sites' );

	if ( $get_sites_exists ) {
		$blogs = get_sites( array(
			'number' => -1,
		) );
	} else {
		$blogs = wp_get_sites( array(
			'limit' => -1,
		) );
	}

	foreach ( $blogs as $blog ) {
		if ( $get_sites_exists ) {
			$blog_id = $blog->blog_id;
		} else {
			$blog_id = $blog['blog_id'];
		}

		switch_to_blog( $blog_id );

		foreach ( $post_types as $post_type ) {
			Helpers\delete_option( $post_type->name );
		}
	}

	restore_current_blog();
} else {
	foreach ( $post_types as $post_type ) {
		Helpers\delete_option( $post_type->name );
	}
}
