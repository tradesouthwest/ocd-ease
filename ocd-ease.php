<?php 
/**
 * Plugin Name: OCDEase Workspace
 * Author: Saeed Taheri
 * Version: 1.0
 * Author uri: http://#
 * Text Domain : ocd-ease
 * Domain path: /languages
 */
if(!defined('ABSPATH')){
    die(); // no direct access
}

/**
 * define needed constants
 */
if(!defined('OCDEASEPATH')){
    define('OCDEASEPATH', plugin_dir_path(__FILE__));
}
if(!defined('OCDEASEURL')){
    define('OCDEASEURL', plugin_dir_url(__FILE__));
}

// including main file
require_once OCDEASEPATH . 'plugin.php';

/**
 * run on plugin activate 
 */
function ocd_ease_plugin_activate(){
    $cpt_obj = \OcdEase\CPT\cpt::instance();
    $cpt_obj->register_cpt();
    $cpt_obj->register_tax();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__ , 'ocd_ease_plugin_activate' );
/**
 * run on plugin deactivate
 */
function ocd_ease_plugin_deactivate(){
    unregister_post_type( 'ocd_ease' );
    unregister_taxonomy('ocd_cats');
    unregister_taxonomy('ocd_tags');
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__ , 'ocd_ease_plugin_deactivate' );