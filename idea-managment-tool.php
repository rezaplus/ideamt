<?php
/**
 * Plugin Name:       Idea Managment Tool
 * Plugin URI:        rezahajizade.com
 * Description:       Tools for Managment Idea.
 * Version:           1.0.0
 * Author:            Reza Hajizadeh	
 * Author URI:        rezahajizade.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       IMTPDOMAIN
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Currently plugin version.
 */
define( 'IMTP_VERSION', '1.0.0' );


/**
*include plugin core class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ideaMT.php';

/**
* run plugin core class.
 */
function run_ideaMT_core() {

	$plugin = new ideaMT_core();
	$plugin->run();

}
run_ideaMT_core();
