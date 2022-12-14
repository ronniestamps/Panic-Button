<?php

/**
 *
 * @link              https://naked-spectrum.com/plugins/panic-button
 * @since             1.0.0
 * @package           Panic_Button
 *
 * @wordpress-plugin
 * Plugin Name:       Panic Button
 * Plugin URI:        https://naked-spectrum.com/plugins/panic-button
 * Description:       This plugin offers a layer of protection and annonimity for users of Domestic Violence (DV) and Sexual Violence (SV) websites. While not a replacement for using an incognito browser window, it does offer a layer of protection for users who are in danger.
 * Version:           1.0.0
 * Author:            Naked Consulting
 * Author URI:        https://naked-spectrum.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       panic-button
 * Domain Path:       /languages
 * @TODO:             Eliminate PHP8 Deprecation Notices.
 */

// Start SESSION so that certain functionality only occurs once per visit.
session_start();

if (!isset($_SESSION["modal"])) {
	$_SESSION["modal"] = 0;
}

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PANIC_BUTTON_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-panic-button-activator.php
 */
function activate_panic_button()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-panic-button-activator.php';
	Panic_Button_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-panic-button-deactivator.php
 */
function deactivate_panic_button()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-panic-button-deactivator.php';
	Panic_Button_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_panic_button');
register_deactivation_hook(__FILE__, 'deactivate_panic_button');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-panic-button.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_panic_button()
{

	$plugin = new Panic_Button();
	$plugin->run();
}
run_panic_button();
