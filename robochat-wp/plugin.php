<?php
/*
Plugin Name: RoboChat WP
Plugin URI: https://github.com/michalicka/robochat
Description: A Wordpress plugin to add AI powered chat to your website.
Author: Jan Michalicka
Author URI: http://www.janmichalicka.com
Version: 1.0
*/

define('RBCH_PLUGIN_DIR', str_replace('\\','/',dirname(__FILE__)));
require_once(RBCH_PLUGIN_DIR.'/options.php');

function rbch_menu() {
  add_options_page('RoboChat', 'RoboChat', 10, __FILE__, 'rbch_options');
}

add_action('admin_menu', 'rbch_menu');

function rbch_scripts() {
    wp_enqueue_script( 'robochat', plugin_dir_url( __FILE__ ) . 'robochat.min.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'rbch_scripts' );
