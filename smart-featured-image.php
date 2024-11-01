<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin.
 *
 * @link              #
 * @since             Smart Featured Image 0.1.0
 * @package           smart-featured-image
 *
 * @wordpress-plugin
 * Plugin Name:       Smart Featured Image
 * Plugin URI:        https://www.grottopress.com/tutorials/smart-featured-image-wordpress-plugin/
 * Description:       Automagically add featured image to posts using images inserted into post content. Displays a configurable default image if no image found.
 * Version:           0.2.4
 * Author:            GrottoPress.com
 * Author URI:        https://www.grottopress.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       smart-featured-image
 * Domain Path:       /languages
 * 
 * This plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * This plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with this plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
 */

namespace GrottoPress\SFI;

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Autoload
 * 
 * @since       Smart Featured Image 0.1.0
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Activation hook
 * 
 * @since       Smart Featured Image 0.1.0
 */
register_activation_hook( __FILE__, '\GrottoPress\SFI\Setup\activation_checks' );

/**
 * Deactivation hook
 *
 * @since       Smart Featured Image 0.1.0
 */
register_deactivation_hook( __FILE__, '\GrottoPress\SFI\Setup\deactivation_checks' );

/**
 * Run plugin
 * 
 * @since       Smart Featured Images 0.1.0
 */
add_action( 'plugins_loaded', '\GrottoPress\SFI\Setup\run', 0 );
