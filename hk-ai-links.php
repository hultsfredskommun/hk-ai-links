<?php
/*
Plugin Name: HK AI Links
Plugin URI: http://wordpress.org/extend/plugins/hk-ai-links/
Description:
Author: jonashjalmarsson
Version: 0.1
Author URI: https://www.hultsfred.se
*/

/*  Copyright 2019 Jonas Hjalmarsson (email: jonas.hjalmarsson@hultsfred.se)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


$hkail_tbl_user_clicks = 'hkail_user_clicks';


/* include */
include( plugin_dir_path( __FILE__ ) . 'hk-ai-links-options.php');
include( plugin_dir_path( __FILE__ ) . 'hk-ai-links-ajax.php');
include( plugin_dir_path( __FILE__ ) . 'hk-ai-links-shortcode.php');
include( plugin_dir_path( __FILE__ ) . 'classes/hk-ai-links-class.php');

add_action('init', 'hkail_plugin_init');
add_action('wp_head', 'hkail_head_function');
add_action('wp_enqueue_scripts', 'hkail_enqueue_script');
add_action('admin_enqueue_scripts', 'hkail_enqueue_admin_script');
register_activation_hook( __FILE__, 'hkail_plugin_activate' );

$hkail_obj = NULL;

/* init plugin */
function hkail_plugin_init() {
  global $hkail_obj;
  load_plugin_textdomain( 'hkail', false, plugin_dir_path( __FILE__ ) . '/languages' );
  if ($hkail_obj == NULL) {
    $hkail_obj = new HKAILinks;
  }
}


/* add html head function */
function hkail_head_function() {
  global $hkail_obj;
  echo "<div style='display:none'>test head ";
  //print_r(wp_get_current_user());
  print_r(get_user_meta(get_current_user_id()));
  echo "</div>";
}


/* enqueue script */
function hkail_enqueue_script() {
	$options = get_option('hkail_options');

	wp_enqueue_style( 'hkail_style', plugin_dir_url( __FILE__ ) . 'css/hk-ai-links.css');
  wp_register_script( 'hkail_script', plugin_dir_url( __FILE__ ) . 'js/hk-ai-links.js' , array('jquery'));

	$hkail_array = array(
    //'test_text' => __($options['hkail_test_text'], 'hkail' ),
    'user' => __( get_current_user_id(), 'hkail' ),
    'title' => __( 'Hultsfreds kommun', 'hkail' ),
    'ajaxurl' => admin_url( 'admin-ajax.php' )    
	);
	wp_localize_script( 'hkail_script', 'hkail_js_object', $hkail_array );
	wp_enqueue_script( 'hkail_script' );
}

/* enqueue admin script */
function hkail_enqueue_admin_script() {
	$options = get_option('hkail_options');

	wp_enqueue_style( 'hkail_style', plugin_dir_url( __FILE__ ) . 'css/hk-ai-links-admin.css');
  wp_register_script( 'hkail_script', plugin_dir_url( __FILE__ ) . 'js/hk-ai-links-admin.js' , array('jquery'));

	$hkail_array = array(
    'ajaxurl' => admin_url( 'admin-ajax.php' )
	);
	wp_localize_script( 'hkail_script', 'hkail_admin_js_object', $hkail_array );
	wp_enqueue_script( 'hkail_script' );
}






/* do one time when plugin is activated  */
function hkail_plugin_activate()
{
  global $hkail_obj;
  if ($hkail_obj == NULL) {
    $hkail_obj = new HKAILinks;
  }
  $hkail_obj->createDatabases();
}


?>
