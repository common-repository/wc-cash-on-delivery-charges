<?php

if (!defined('ABSPATH'))
    exit;

if (!class_exists('WPCCCOD_front')) {

    class WPCCCOD_front {

        protected static $instance;
     

    function WPCCCOD_pincode_change_checkout() {
        
            if(isset($_REQUEST['pincode']) && $_REQUEST['pincode'] != '') {
                
              $pincode = sanitize_text_field($_REQUEST['pincode']);
              
                $expiry = strtotime('+7 day');
                setcookie('wpcc_cashondelivery', $pincode, $expiry , COOKIEPATH, COOKIE_DOMAIN);
                
                global $wpdb;
                $tablename=$wpdb->prefix.'wpcc_cashondelivery';
                $cntSQL = "SELECT * FROM {$tablename} where wpcc_pincode='".$pincode."'";
                $record = $wpdb->get_results($cntSQL, OBJECT);
                $totalrec = count($record);
               
                $this->WPCCCOD_woo_add_cart_fee();

            }else{
                 $this->WPCCCOD_woo_add_cart_fee();
            }
        }


        
        function WPCCCOD_woo_add_cart_fee() {
         
            global $woocommerce;
            
            $codlabel=get_option('wpcc_cod_label_txt', 'Cash on Delivery Charge');
            $chosen_payment_id = WC()->session->get('chosen_payment_method');
             $checkCODStatus=get_option('wpcc_enable_checkcodststus', 'on');
            if(get_option('wpcc_enable_cod_price', 'fixed') =='fixed' && $checkCODStatus=='on'){
                
                  if( get_option('wpcc_cod_fixed_price') !==''){
                      
                       $codamount=get_option('wpcc_cod_fixed_price');
                       
                       if ( empty( $chosen_payment_id ) ){
                        return;
                        
                       }else{
                           if($chosen_payment_id=='cod'){
                        $woocommerce->cart->add_fee( __($codlabel, 'woocommerce'), $codamount);
                           }
                       }
                        }
            }
            
            if(get_option('wpcc_enable_cod_price', 'pincodewise') =='pincodewise' && $checkCODStatus=='on'){
      
                if(isset($_COOKIE['wpcc_cashondelivery'])) {
                    global $wpdb;
                  
                    $tablename=$wpdb->prefix.'wpcc_cashondelivery';
                    $cntSQL = "SELECT * FROM {$tablename} where wpcc_pincode='".sanitize_text_field($_COOKIE['wpcc_cashondelivery'])."'";
                    $record = $wpdb->get_results($cntSQL, OBJECT);
                 //  print_r( $record[0]);

                    if($record && $record[0]->wpcc_cod_amount != 0 && !empty($record[0]->wpcc_cod_amount)){
                        
                       if ( empty( $chosen_payment_id ) ){
                        return;
                        
                       }else{
                           if($chosen_payment_id=='cod'){
                        $woocommerce->cart->add_fee( __($codlabel, 'woocommerce'), $record[0]->wpcc_cod_amount);
                           }
                       }
                        
                       }
                    }
        
            
            }
        }
       
       
        function init() {

              
                add_action( 'woocommerce_cart_calculate_fees', array($this,'WPCCCOD_woo_add_cart_fee'));
                add_action( 'wp_ajax_WPCCCOD_pincode_change_checkout', array($this,'WPCCCOD_pincode_change_checkout' ));
                add_action( 'wp_ajax_nopriv_WPCCCOD_pincode_change_checkout', array($this,'WPCCCOD_pincode_change_checkout' ));
               
              
        }
        
  
        public static function instance() {

            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }
    }
    WPCCCOD_front::instance();
}