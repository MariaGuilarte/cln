<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://espartadevs.website/portafolio
 * @since             1.0.0
 * @package           Cln_custom_plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Cln
 * Plugin URI:        cln_custom_plugin
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Maria
 * Author URI:        https://espartadevs.website/portafolio
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cln_custom_plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wpdb;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cln_custom_plugin-activator.php
 */
function activate_cln_custom_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cln_custom_plugin-activator.php';
	Cln_custom_plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cln_custom_plugin-deactivator.php
 */
function deactivate_cln_custom_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cln_custom_plugin-deactivator.php';
	Cln_custom_plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cln_custom_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_cln_custom_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cln_custom_plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cln_custom_plugin() {

	$plugin = new Cln_custom_plugin();
	$plugin->run();

}

run_cln_custom_plugin();

// Crear tabla de registro de descuentos del plugin cln en la bd
global $cln_db_version;
$cln_db_version = '1.0';

function cln_create_db_table(){
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $cln_db_version;

  $table_name = $wpdb->prefix . 'cln_discount_register';
  
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    cln_rate int NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  dbDelta( $sql );
  add_option( 'cln_db_version', $cln_db_version );
}

// function cln_install_data(){
  // global $wpdb;
  // global $pagenow;
   
  // if ( is_admin() && $pagenow !== 'plugins.php' ){
    // $descuento = '20%';
    // $table_name = $wpdb->prefix . 'opts_cln_admin_panel';
    
    // $wpdb->insert(
      // $table_name, 
      // array( 
        // 'time' => current_time( 'mysql' ),
        // 'descuento' => $descuento
      // ) 
    // );
  // }
// }

register_activation_hook (__FILE__, 'cln_create_db_table'); 
// register_activation_hook (__FILE__, 'cln_install_data');


// Hooks de los menús de administración
add_action('admin_menu', 'cln_admin_menu');
add_action('admin_menu', 'cln_admin_submenu_1');

// Creación del Menus de administración
function cln_admin_menu(){
  add_menu_page(
    'Titulo de pagina', //Titulo pagina
    'Club de la Nación', //Titulo menu
    'manage_options', //Capacidad
    'cln-admin-menu', //Slug
    'cln_form', //funcion
    'dashicons-admin-plugins' //url icon
  );
}

// add_submenu_page( $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' );
function cln_admin_submenu_1(){
  add_submenu_page( 
    'cln-admin-menu', //parent_slug
    'reportes', //Titulo pagina
    'Reportes', //Titulo menu
    'manage_options', //Capacidad
    'cln-admin-submenu-1', //Slug
    'cln_form_submenu_1' //funcion
  );
}
// Fin definición de Menús de administración

// Handlers de los menús de administración
function cln_form(){
  include "includes/cln_admin_form.php";
}

function cln_form_submenu_1(){


}
// Fin Handlers de administración

// add_action('woocommerce_review_order_after_order_total', 'add_btn_descuento_cln');

// function add_btn_descuento_cln(){
  // echo '
    // <form action="llamarAlaApi" method="get">
      // <div id="cln-btn" style="display:inline-block;">
        // <label for="cln_cod" style="display:block;">Código de descuento CLN</label>
        // <input type="text" name="cln_cod" id="cln_cod">
      // </div>
      // <div style="display:inline-block;">
        // <button type="submit">Aplicar descuento</button>
      // </div>
    // </form>
  // ';
// }

