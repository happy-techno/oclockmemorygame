<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/joyeux-yohann-36861a97/
 * @since             1.0.0
 * @package           Oclockmemorygame
 *
 * @wordpress-plugin
 * Plugin Name:       OclockMemoryGame
 * Plugin URI:        OclockMemoryGame
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Yohann Joyeux
 * Author URI:        https://www.linkedin.com/in/joyeux-yohann-36861a97/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oclockmemorygame
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0
 */
define( 'OCLOCKMEMORYGAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-oclockmemorygame-activator.php
 */
function activate_oclockmemorygame() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oclockmemorygame-activator.php';
	Oclockmemorygame_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-oclockmemorygame-deactivator.php
 */
function deactivate_oclockmemorygame() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-oclockmemorygame-deactivator.php';
	Oclockmemorygame_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oclockmemorygame' );
register_deactivation_hook( __FILE__, 'deactivate_oclockmemorygame' );

/**
 * The core plugin class that is used to define public-facing site hooks
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-oclockmemorygame.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_oclockmemorygame() {

	$plugin = new Oclockmemorygame();
	$plugin->run();

}
run_oclockmemorygame();
