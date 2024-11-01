<?php
/**
* Plugin Name: Cash on Delivery Charges for WooCommerce
* Description: Woocommerce function to apply cash on delivery changes on specific pincode or all over the world.
*  Author: Codevyne Creatives
*  Author URI: https://www.codevyne.com/contact-us/
* Donate link: https://www.paypal.com/paypalme/pradeepku041/
* Contributors: Pradeep Maurya
* *Contributors: Codevyne Creatives
* Tested up to: 5.9
* Stable tag: 2.2.2
* Version: 2.2.2
* Copyright: 2021
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
  die('-1');
}

if (!defined('WPCCCOD_PLUGIN_NAME')) {
  define('WPCCCOD_PLUGIN_NAME', 'Cash on Delivery Charges for WooCommerce');
}
if (!defined('WPCCCOD_PLUGIN_VERSION')) {
  define('WPCCCOD_PLUGIN_VERSION', '2.2.2');
}
if (!defined('WPCCCOD_PLUGIN_FILE')) {
  define('WPCCCOD_PLUGIN_FILE', __FILE__);
}
if (!defined('WPCCCOD_PLUGIN_DIR')) {
  define('WPCCCOD_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('WPCCCOD_BASE_NAME')) {
    define('WPCCCOD_BASE_NAME', plugin_basename(WPCCCOD_PLUGIN_FILE));
}
if (!defined('WPCCCOD_DOMAIN')) {
  define('WPCCCOD_DOMAIN', 'wpcccod');
}


if (!class_exists('WPCCCOD')) {

    class WPCCCOD {

        protected static $WPCCCOD_instance;
        function __construct() {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            add_action('admin_init', array($this, 'WPCCCOD_check_plugin_state'));
        }


        function WPCCCOD_check_plugin_state(){
            if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
                set_transient( get_current_user_id() . 'wqrerror', 'message' );
            }
        }


        function init() {
            add_action( 'admin_notices', array($this, 'WPCCCOD_show_notice'));
            add_action( 'admin_enqueue_scripts', array($this, 'WPCCCOD_load_admin'));
            add_action( 'wp_enqueue_scripts',  array($this, 'WPCCCOD_load_front'));
            add_filter( 'plugin_row_meta', array( $this, 'WPCCCOD_plugin_row_meta' ), 10, 2 );
        }


        function WPCCCOD_show_notice() {
            if ( get_transient( get_current_user_id() . 'wqrerror' ) ) {

                deactivate_plugins( plugin_basename( __FILE__ ) );
                delete_transient( get_current_user_id() . 'wqrerror' );
                echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';
            }
        }


       	
        function WPCCCOD_plugin_row_meta( $links, $file ) {
            if ( WPCCCOD_BASE_NAME === $file ) {
                $row_meta = array(
                    'rating'    =>  ' <a href="https://www.codevyne.com/contact-us/?utm_source=aj_plugin&utm_medium=plugin_support&utm_campaign=aj_support&utm_content=aj_wordpress" target="_blank">Support</a>',
                );
                return array_merge( $links, $row_meta );
            }
            return (array) $links;
        }


        function WPCCCOD_load_admin() {
            wp_enqueue_style( 'WPCC_admin_style', WPCCCOD_PLUGIN_DIR . '/includes/css/wpcc_back_style.css', false,WPCCCOD_PLUGIN_VERSION );
          
        }


        function WPCCCOD_load_front() {
            wp_enqueue_style( 'WPCCCOD_front_style', WPCCCOD_PLUGIN_DIR . '/includes/css/wpcc_front_style.css', array(), WPCCCOD_PLUGIN_VERSION,'all' );
          wp_enqueue_script( 'WPCCCOD_front_script', WPCCCOD_PLUGIN_DIR . '/includes/js/wpcc_front_script.js', array( 'jquery' ), WPCCCOD_PLUGIN_VERSION, false);
          wp_localize_script( 'WPCCCOD_front_script', 'wpcccod_ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php') )  );
           $translation_array = array('plugin_url'=>WPCCCOD_PLUGIN_DIR);
           wp_localize_script( 'WPCCCOD_front_script', 'wpcc_plugin_url', $translation_array );
           
        }


        public static function WPCCCOD_create_table() {
            
               global $table_prefix, $wpdb;
              $charset_collate = $wpdb->get_charset_collate();
            $tablename = $table_prefix.'wpcc_cashondelivery';
            
           if($wpdb->get_var( "show tables like '{$tablename}'" ) != $tablename) 
             {
          
            $sql = "CREATE TABLE $tablename (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                wpcc_pincode TEXT NOT NULL,
                wpcc_city TEXT NOT NULL,
                wpcc_state TEXT NOT NULL,
                wpcc_cod_amount TEXT NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            }
        }

        
        function includes() {
         include_once('admin/wpcc_admin.php');
         include_once('front/wpcc_front.php');
        }


        public static function WPCCCOD_instance() {
            if (!isset(self::$WPCCCOD_instance)) {
                self::$WPCCCOD_instance = new self();
                self::$WPCCCOD_instance->init();
                self::$WPCCCOD_instance->includes();
            }
            return self::$WPCCCOD_instance;
        }

    }

    register_activation_hook( WPCCCOD_PLUGIN_FILE, array('WPCCCOD', 'WPCCCOD_create_table' ));
    add_action('plugins_loaded', array('WPCCCOD', 'WPCCCOD_instance'));
}