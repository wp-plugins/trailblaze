<?php

/*
Plugin Name: Trailblaze
Plugin URI: http://www.wearepixel8.com/
Description: Add breadcrumb navigation to your post, pages and custom post types with a template tag.
Version: 1.0.0
Author: We Are Pixel8
Author URI: http://www.wearepixel8.com
License:
	Copyright 2013 We Are Pixel8 <hello@wearepixel8.com>
	
	This program is free software; you can redistribute it and/or modify it under
	the terms of the GNU General Public License, version 2, as published by the Free
	Software Foundation.
	
	This program is distributed in the hope that it will be useful, but WITHOUT ANY
	WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
	PARTICULAR PURPOSE. See the GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software Foundation, Inc.,
	51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

/*-----------------------------------------------------------------------------------*/
/* Constants
/*-----------------------------------------------------------------------------------*/

define( 'WAP8TRAILBLAZE', plugin_dir_path( __FILE__ ) );

/*-----------------------------------------------------------------------------------*/
/* Includes
/*-----------------------------------------------------------------------------------*/

include( WAP8TRAILBLAZE . 'includes/trailblaze-options.php' );     // load plugin options
include( WAP8TRAILBLAZE . 'includes/trailblaze-breadcrumbs.php' ); // load breadcrumbs function

/*-----------------------------------------------------------------------------------*/
/* Trailblaze Settings Link
/*-----------------------------------------------------------------------------------*/

add_filter( 'plugin_action_links', 'wap8_trailblaze_settings_link', 10, 2 );

/**
 * Trailblaze Settings Link
 *
 * Add a shortcut link to the Trailblaze Settings page from the plugin management
 * screen.
 *
 * @param $links
 * @param $file
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Erik Ford for We Are Pixel8 <@notdivisible>
 *
 */

function wap8_trailblaze_settings_link( $links, $file ) {
	
	if ( $file == plugin_basename( __FILE__ ) && current_user_can( 'manage_options' ) ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=wap8-trailblaze-options' ) . '">' . __( 'Settings', 'wap8plugin-i18n' ) . '</a>';
	}
	
	return $links;
	
}

/*-----------------------------------------------------------------------------------*/
/* Plugin Text Domain
/*-----------------------------------------------------------------------------------*/

add_action( 'plugins_loaded', 'wap8_plugin_text_domain', 10 );

/**
 * Plugin Text Domain
 *
 * Load the text domain for internationalization.
 *
 * @package Trailblaze
 * @version 1.0.0
 * @since 1.0.0
 * @author Erik Ford for We Are Pixel8 <@notdivisible>
 *
 */

function wap8_plugin_text_domain() {
	
	load_plugin_textdomain( 'wap8plugin-i18n', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	
}