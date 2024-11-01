<?php

if (!defined('ABSPATH'))
    exit;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if (!class_exists('WPCCCOD_menu')) {
    class WPCCCOD_menu {
        protected static $instance;
        function WPCCCOD_admin_menu() {
           
            add_menu_page( 
                
                __( 'COD Price', 'wpcccod' ), 
                __( 'COD Price', 'wpcccod' ),
                'manage_options', 
                'cod_price_list',
                array($this,'WPCCCOD_cod_price_list'),
                'dashicons-money-alt',
             15
            );
            add_submenu_page( 
                'cod_price_list', 
                __( 'Add New Price', 'wpcccod' ), 
                __( 'Add New Price', 'wpcccod' ),
                'manage_options', 
                'add-cod_amount',
                array($this,'WPCCCOD_add_cod_amount')
            );

            add_submenu_page( 
                'cod_price_list', 
                __( 'COD Setting', 'wpcccod' ),  
                __( 'COD Setting', 'wpcccod' ),
                'manage_options', 
                'wpcc-cod-charges',
                array($this,'WPCCCOD_setting')
            );
            add_submenu_page( 
                'cod_price_list', 
                __( 'Import COD Price List', 'wpcccod' ), 
                __( 'Import COD Price List', 'wpcccod' ),
                'manage_options', 
                'import-cod-pricelist',
                array($this,'WPCCCOD_import_cod_pricelist')
            );
        }


        function WPCCCOD_add_cod_amount() {
            global $wpdb;
            $tablename=$wpdb->prefix.'wpcc_cashondelivery';

            if(isset($_REQUEST['action']) && $_REQUEST['action'] == "oc_edit") {
                $pincode = sanitize_text_field($_REQUEST['id']);
                $cntSQL = "SELECT * FROM {$tablename} where id='".$pincode."'";
                $record = $wpdb->get_results($cntSQL, OBJECT);
                ?>
                    <div class="wrap">
                        <div class="wpcc_container">
                            <h2>Update COD Price</h2>

                            <?php
                            if(isset($_GET['update']) && $_GET['update'] == 'exists') {
                                echo "<div class='wpcc_notice_error'><p>Sorry, pincode already exists in records.</p></div>";
                            }

                            if(isset($_GET['update']) && $_GET['update'] == 'success') {
                                echo "<div class='wpcc_notice_success'><p>Pincode updated successfully.</p></div>";
                            }

                            ?>

                            <form method="post">
                                <?php wp_nonce_field( 'WPCC_update_cod_price_action', 'WPCC_update_cod_price_field' ); ?>
                                <table class="wpcc_table">
                                    <tr>
                                        <td>Pincode</td>
                                        <td>
                                            <input type="text" name="txtpincode" value="<?php echo $record[0]->wpcc_pincode; ?>" required="">
                                            <input type="hidden" name="txtid" value="<?php echo $record[0]->id; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>City</td>
                                        <td><input type="text" name="txtcity" value="<?php echo $record[0]->wpcc_city; ?>" required=""></td>
                                    </tr>
                                    <tr>
                                        <td>State</td>
                                        <td><input type="text" name="txtstate" value="<?php echo $record[0]->wpcc_state; ?>" required=""></td>
                                    </tr>
                                   
                                     <tr id="codavailable">
                                        <td>Cash on Delivery Amount</td>
                                        <td>
                                            <input type="number" name="txtcodamount" min=0 step="0.10" value="<?php echo $record[0]->wpcc_cod_amount; ?>" >
                                            <td><strong>Note : If COD option is enable then COD amount will count on cart and checkout page</strong><td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="action" value="wpcc_update_cod_price">
                                            <input type="submit" name="wpcc_update_cod_price" value="Update">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                <?php
            } else {
                ?>
                    <div class="wrap">
                        <div class="wpcc_container">
                            <h2>Add COD Price</h2>
                            
                            <?php
                            if(isset($_GET['add']) && $_GET['add'] == 'exists') {
                                echo "<div class='wpcc_notice_error'><p>Sorry, pincode already exists in records.</p></div>";
                            }

                            if(isset($_GET['add']) && $_GET['add'] == 'success') {
                                echo "<div class='wpcc_notice_success'><p>Pincode added successfully.</p></div>";
                            }

                            ?>

                            <form method="post">
                                <?php wp_nonce_field( 'WPCC_add_cod_price_action', 'WPCC_add_cod_price_field' ); ?>

                                <table class="wpcc_table">
                                    <tr>
                                        <td>Pincode</td>
                                        <td><input type="text" name="txtpincode" <?php if(isset($_GET['txtpincode']) && $_GET['txtpincode'] != '') { echo 'value='.esc_attr( $_GET['txtpincode'] ); } ?> required=""></td>
                                    </tr>
                                    <tr>
                                        <td>City</td>
                                        <td><input type="text" name="txtcity" <?php if(isset($_GET['txtcity']) && $_GET['txtcity'] != '') { echo 'value='.esc_attr( $_GET['txtcity'] ); } ?> required=""></td>
                                    </tr>
                                    <tr>
                                        <td>State</td>
                                        <td><input type="text" name="txtstate" <?php if(isset($_GET['txtstate']) && $_GET['txtstate'] != '') { echo 'value='.esc_attr( $_GET['txtstate'] ); } ?> required=""></td>
                                    </tr>
                                   
                                       <tr id="codavailable">
                                        <td>Cash on Delivery Amount</td>
                                        <td>
                                            <input type="number" name="txtcodamount" min=0 step="0.10" <?php if(isset($_GET['txtcodamount']) && $_GET['txtcodamount'] != '') { echo 'value='.esc_attr( $_GET['txtcodamount'] ); } ?>>
                                            <td><strong>Note : If COD option is enable then COD amount will count on cart and checkout page</strong><td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                          <input type="hidden" name="action" value="wpcc_add_cod_price">
                                          <input type="submit" name="wpcc_add_cod_price" value="Add">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                <?php
            }
        }


        function WPCCCOD_import_cod_pricelist() {
            ?>
            <div class="wrap">
                <div class="wpcc_container">
                    <h2>Bulk Import COD Price List</h2>

                    <?php
                    if(isset($_GET['import']) && $_GET['import'] == 'error') {
                        echo "<div class='wpcc_notice_error'><p>Import failed, invalid file extension or something bad happened.</p></div>";
                    }

                    if(isset($_GET['import']) && $_GET['import'] == 'success') {
                        $records = '';
                        if(isset($_GET['records']) && $_GET['records'] != '') {
                            $records = sanitize_text_field($_GET['records']);
                        }
                        echo "<div class='wpcc_notice_success'><p>Total Records inserted: ".$records."</p></div>";
                    }

                    ?>

                    <form method='post' enctype='multipart/form-data' class="wpcc_import">
                        <?php wp_nonce_field( 'WPCC_import_cod_price_action', 'WPCC_import_cod_price_field' ); ?>
                        <div class="wpcc_importbox">
                            <h3>Bulk import COD Price via csv</h3>
                            <input type="file" name="import_file" required="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            <input type="hidden" name="action" value="wpcc_import_cod_price">
                            <input type="submit" name="butimport" value="Import">
                        </div>
                        <a href="<?php echo WPCCCOD_PLUGIN_DIR.'/wpcc_cod_price_sample.csv'; ?>" download='sample_cod_charge.csv' class="wpcc_demo_file">Download sample file</a>
                        <p class="description">This is the sample file of COD price for csv import.</p>
                        </form>
                </div>
            </div>
            <?php
        }


        function WPCCCOD_cod_price_list() {
            $exampleListTable = new WPCCCOD_List_Table();
            $exampleListTable->prepare_items();
            ?>
            <div class="wrap">
                <div class="wpcc_container">
                    <h2>COD Price List</h2>

                    <?php
                    if(isset($_GET['delete']) && $_GET['delete'] == 'success') {
                        echo "<div class='wpcc_notice_success'><p>Record deleted successfully.</p></div>";
                    }                    
                    ?>

                    <form  method="post" class="wpcc_list_postcode">
                        <a href="?page=add-cod_amount"  class="button wpcc_add_postcode" style="background-color:#2271b1;border-color:#2271b1;color:#fff;">Add New COD Price</a>
                        <a href="?page=import-cod-pricelist"  class="button wpcc_import_bulk" style="background-color:#3f51b5;border-color:#3f51b5;color:#fff;">Import Bulk COD Price</a>
                        <input type="submit" name="all_record_delete" class="button wpcc_all_delete" onclick="return confirm('Are you sure you want to delete this item?');" value="Delete All COD Price" style="background-color:red;color:#fff;border-color:red;">
                        <?php
                            $page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
                            $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );

                            printf( '<input type="hidden" name="page" value="%s" />', $page );
                            printf( '<input type="hidden" name="paged" value="%d" />', $paged ); 
                        ?>
                        <?php $exampleListTable->display(); ?>
                    </form>
                </div>
            </div>
            <?php
        }


        function WPCCCOD_setting() {
            ?>
            <div class="wrap">
                <div class="wpcc_container">
                    <form method="post" class="oc_wpcc">
                        <?php wp_nonce_field( 'wpcc_nonce_action', 'wpcc_nonce_field' ); ?>
                        <table class="wpcc_table">
                            <h2>Basic Settings</h2>
                            <tr>
                                <td>Enable COD on checkout page</td>
                                <td>
                                    <input type="checkbox" name="wpcc_enable_checkcodststus" <?php if( get_option('wpcc_enable_checkcodststus', 'on') == 'on' ) { echo 'checked'; } ?>>
                                </td>
                            </tr>
                                 <tr>
                                <td>COD Price Term</td>
                                <td>
                                    <input type="radio" name="wpcc_enable_cod_price" value="pincodewise" <?php if( get_option('wpcc_enable_cod_price', 'pincodewise') == 'pincodewise' ) { echo 'checked'; } ?>> Apply COD Price Based On Customer Shipping/ Billing Pincode
                                    <br>
                                      <input type="radio" name="wpcc_enable_cod_price" value="fixed" <?php if( get_option('wpcc_enable_cod_price', 'fixed') == 'fixed' ) { echo 'checked'; } ?>> Apply Fixed COD Price for All
                               
                                </td>
                            </tr>
                           
                            <tr class="wpcc_nosrvtxt">
                                <td>COD Fixed Price</td>
                                <td>
                                    <input type="number" name="wpcc_cod_fixed_price" step="0.10" value="<?php echo get_option('wpcc_cod_fixed_price', ''); ?>">
                                </td>
                            </tr>
                            <tr class="wpcc_nosrvtxt">
                                <td>COD label Text on Checkout Page</td>
                                <td>
                                    <input type="text" name="wpcc_cod_label_txt" value="<?php echo get_option('wpcc_cod_label_txt', 'Cash on Delivery Charge'); ?>" >
                                  
                                </td>
                            </tr>
                        </table>
                
                       
                       
                        <input type="hidden" name="action" value="WPCCCOD_settings_save">
                        <input type="submit" name="wpcc_txtadd_design" value="Save">
                    </form>
                </div>
            </div>
            <?php
        }

        function WPCCCOD_save_options() {
            if( current_user_can('administrator') ) { 
                global $wpdb;
                $tablename=$wpdb->prefix.'wpcc_cashondelivery';

                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'WPCCCOD_settings_save') {
                    if(!isset( $_POST['wpcc_nonce_field'] ) || !wp_verify_nonce( $_POST['wpcc_nonce_field'], 'wpcc_nonce_action' )) {
                        
                        echo 'Sorry, your nonce did not verify.';
                        exit;

                    } else {

                        if(isset($_REQUEST['wpcc_enable_cod_price']) && !empty($_REQUEST['wpcc_enable_cod_price'])) {
                            $wpcc_enable_cod_price = sanitize_text_field( $_REQUEST['wpcc_enable_cod_price'] );
                            update_option( 'wpcc_enable_cod_price', $wpcc_enable_cod_price); 
                        }
                        
                        if(isset($_REQUEST['wpcc_enable_checkcodststus']) && !empty($_REQUEST['wpcc_enable_checkcodststus'])) {
                            $wpcc_enable_checkcodststus = sanitize_text_field( $_REQUEST['wpcc_enable_checkcodststus'] );
                           
                        }else{
                            $wpcc_enable_checkcodststus="off";
                            
                        }
                       

                        update_option( 'wpcc_enable_checkcodststus', $wpcc_enable_checkcodststus); 
                       update_option( 'wpcc_cod_fixed_price', sanitize_text_field( $_REQUEST['wpcc_cod_fixed_price'])); 
                        update_option( 'wpcc_cod_label_txt', sanitize_text_field( $_REQUEST['wpcc_cod_label_txt'])); 
                    
                     
                    }
                }


                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'wpcc_add_cod_price') {
                    if(!isset( $_POST['WPCC_add_cod_price_field'] ) || !wp_verify_nonce( $_POST['WPCC_add_cod_price_field'], 'WPCC_add_cod_price_action' )) {
                        
                        echo 'Sorry, your nonce did not verify.';
                        exit;

                    } else {

                        $pincode = sanitize_text_field( $_REQUEST['txtpincode']);
                        $city = sanitize_text_field( $_REQUEST['txtcity']);
                        $state = sanitize_text_field( $_REQUEST['txtstate']);
                        $cod_amount = sanitize_text_field( $_REQUEST['txtcodamount']);
                   
                        $cntSQL = "SELECT count(*) as count FROM {$tablename} where wpcc_pincode='".$pincode."'";
                        $record = $wpdb->get_results($cntSQL, OBJECT);
                        
                        if($record[0]->count == 0) {
                            if(!empty($pincode) && !empty($city) && !empty($state)  ) {
                                $data=array(
                                    'wpcc_pincode'  => $pincode,
                                    'wpcc_city'     => $city, 
                                    'wpcc_state'    => $state,
                                    'wpcc_cod_amount'      => $cod_amount

                                );
                                $wpdb->insert( $tablename, $data);
                                wp_redirect(admin_url('admin.php?page=add-cod_amount&add=success'));
                                exit;
                            }
                        } else {
                            wp_redirect(admin_url('admin.php?page=add-cod_amount&add=exists&txtpincode='.$pincode.'&txtcity='.$city.'&txtstate='.$state.'&txtcodamount='.$cod_amount));
                            exit;
                        }
                    }
                }


                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'wpcc_update_cod_price') {
                    if(!isset( $_POST['WPCC_update_cod_price_field'] ) || !wp_verify_nonce( $_POST['WPCC_update_cod_price_field'], 'WPCC_update_cod_price_action' )) {
                        
                        echo 'Sorry, your nonce did not verify.';
                        exit;

                    } else {

                        $pincode_exists = 'false';
                        $id = sanitize_text_field( $_REQUEST['txtid']);
                        $pincode = sanitize_text_field( $_REQUEST['txtpincode']);
                        $city = sanitize_text_field( $_REQUEST['txtcity']);
                        $state = sanitize_text_field( $_REQUEST['txtstate']);
                        $cod_amount = sanitize_text_field( $_REQUEST['txtcodamount']);
                        
                        $cntSQL = "SELECT * FROM {$tablename} where id='".$id."'";
                        $record = $wpdb->get_results($cntSQL, OBJECT);

                        $cntSQL_new = "SELECT count(*) as count FROM {$tablename} where wpcc_pincode='".$pincode."'";
                        $record_new = $wpdb->get_results($cntSQL_new, OBJECT);

                        $current_pincode = $record[0]->wpcc_pincode;
                        $count_new = $record_new[0]->count;
                        
                        if($pincode != $current_pincode) {
                            if($count_new > 0 ) {
                                $pincode_exists = 'true';
                            }
                        }


                        if($pincode_exists == 'false') {
                            if(!empty($pincode) && !empty($city) && !empty($state)  ) {
                                $data=array(
                                    'wpcc_pincode'  => $pincode,
                                    'wpcc_city'     => $city, 
                                    'wpcc_state'    => $state,
                                    'wpcc_cod_amount'      => $cod_amount

                                );
                                $condition=array(
                                    'id'=>$id
                                );

                                $wpdb->update($tablename, $data, $condition);
                                wp_redirect(admin_url('admin.php?page=cod_price_list&action=oc_edit&id='.$id.'&update=success'));
                                exit;
                            }
                        } else {
                            wp_redirect(admin_url('admin.php?page=cod_price_list&action=oc_edit&id='.$id.'&update=exists'));
                            exit;
                        }
                    }
                }


                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'wpcc_import_cod_price') {
                    if(!isset( $_POST['WPCC_import_cod_price_field'] ) || !wp_verify_nonce( $_POST['WPCC_import_cod_price_field'], 'WPCC_import_cod_price_action' )) {
                        
                        echo 'Sorry, your nonce did not verify.';
                        exit;

                    } else {

                        if(isset($_POST['butimport'])) {

                            // File extension
                            $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

                            // If file extension is 'csv'
                            if(!empty($_FILES['import_file']['name']) && $extension == 'csv') {

                                $totalInserted = 0;
                         
                                // Open file in read mode
                                $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
                                fgetcsv($csvFile); // Skipping header row

                                // Read file
                                while(($csvData = fgetcsv($csvFile)) !== FALSE) {
                                    $csvData = array_map("utf8_encode", $csvData);

                                    // Assign value to variables
                                    $pincode    = trim($csvData[0]);
                                    $city       = trim($csvData[1]);
                                    $state      = trim($csvData[2]);
                                    $cod_amount        = trim($csvData[3]);
                              
                                    $cntSQL = "SELECT count(*) as count FROM {$tablename} where wpcc_pincode='".$pincode."'";
                                    $record = $wpdb->get_results($cntSQL, OBJECT);

                                    if($record[0]->count == 0) {

                                        // Check if variable is empty or not
                                        if($pincode!="" && $city!="" && $state!="" ) {
                                            // Insert Record
                                            $wpdb->insert($tablename, array(
                                               'wpcc_pincode'   => $pincode,
                                               'wpcc_city'      => $city,
                                               'wpcc_state'     => $state,
                                               'wpcc_cod_amount'      => $cod_amount
                                            ));
                                            if($wpdb->insert_id > 0) {
                                               $totalInserted++;
                                            }
                                        }
                                    }
                                }
                                wp_redirect(admin_url('admin.php?page=import-cod-pricelist&import=success&records='.$totalInserted));
                                exit;
                            } else {
                                wp_redirect(admin_url('admin.php?page=import-cod-pricelist&import=error'));
                                exit;
                            }
                        }
                    }
                }


                if (isset($_REQUEST['action']) && $_REQUEST['action'] == "wpcc_delete") {
                    if( wp_verify_nonce( $_GET['_wpnonce'], 'my_nonce' ) ) {
                        $pincode = sanitize_text_field($_REQUEST['id']);
                        $sql = "DELETE FROM $tablename WHERE id='".$pincode."'";
                        $wpdb->query($sql);
                        wp_redirect(admin_url('admin.php?page=cod_price_list&delete=success'));
                        exit;
                    } else {
                        echo 'Sorry, your nonce did not verify.';
                        exit;
                    }
                }

                if(isset($_REQUEST['all_record_delete']) ){
                        $sql = "DELETE FROM $tablename";
                        $wpdb->query($sql);
                        wp_redirect(admin_url('admin.php?page=cod_price_list&delete=success'));
                        exit;
                }
            }
        }

        function WPCCCOD_support_and_rating_notice() {
            $screen = get_current_screen();
           //  print_r($screen);
            if( 'cod_price_list' == $screen->parent_base) {
                ?>
                <div class="wpcc_mainnn_rantiong">
                   
                    <div class="wpcc_support_notice">
                        <div class="wpcc_rtusnoti_left">
                            <h3>Having Issues?</h3>
                            <label>You can contact us at work@codevyne.com</label>
                           
                            </a>
                        </div>
                       
                    </div>
                </div>
                <div class="wpcc_donate_main">
                   <img src="<?php echo WPCCCOD_PLUGIN_DIR;?>/includes/images/coffee.svg">
                   <h3>Buy me a Coffee !</h3>
                   <p>If you like this plugin, buy me a coffee and help support this plugin !</p>
                   <div class="wpcc_donate_form">
                      <a class="button button-primary ocwg_donate_btn" href="https://www.paypal.com/paypalme/pradeepku041/" data-link="https://www.paypal.com/paypalme/pradeepku041/" target="_blank">Buy me a coffee !</a>
                   </div>
                </div>
                <?php
            }
        }

        function init() {
            add_action( 'admin_menu', array($this, 'WPCCCOD_admin_menu') );
            add_action( 'init', array($this, 'WPCCCOD_save_options') );
            add_action( 'admin_notices', array($this, 'WPCCCOD_support_and_rating_notice' ));
        }


        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }
    }
    WPCCCOD_menu::instance();
}


class WPCCCOD_List_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct(
            array(
                'singular' => 'singular_form',
                'plural'   => 'plural_form',
                'ajax'     => false
            )
        );
    }


    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
        $this->process_bulk_action();
    }
   

    public function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'pincode'     => 'Pincode',
            'city'        => 'City',
            'state'       => 'State',
            'cod_amount'        => 'COD Amount',
        );
        return $columns;
    }
   

    public function get_hidden_columns() {
        return array();
    }
  

    public function get_sortable_columns() {
        return array('pincode' => array('pincode', false));
    }


    private function table_data() {
        $data = array();
        global $wpdb;
        $tablename = $wpdb->prefix.'wpcc_cashondelivery';
        $wpcc_records = $wpdb->get_results( "SELECT * FROM $tablename" );
        foreach ($wpcc_records as $wpcc_record) {

          

            $data[] = array(
                'id'          => $wpcc_record->id,
                'pincode'     => $wpcc_record->wpcc_pincode,
                'city'        => $wpcc_record->wpcc_city,
                'state'       => $wpcc_record->wpcc_state,
                'cod_amount'   => $wpcc_record->wpcc_cod_amount,
            );
        }
        return $data;
    }
   

    public function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'id':
                return $item['id'];
            case 'pincode':
                return $item['pincode'];
            case 'city':
                return $item['city'];
            case 'state':
                return $item['state'];
            case 'cod_amount':
                return $item['cod_amount'];
            default:
                return print_r( $item, true ) ;
        }
    }


    private function sort_data( $a, $b ) {
        // Set defaults
        $orderby = 'pincode';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby'])) {
            $orderby = sanitize_text_field($_GET['orderby']);
        }
        // If order is set use this as the order
        if(!empty($_GET['order'])) {
            $order = sanitize_text_field($_GET['order']);
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc') {
            return $result;
        }
        return -$result;
    }


    public function get_bulk_actions() {
        return array(
            'delete' => __( 'Delete', 'wpcc' ),
        );
    }


    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item['id']
        );    
    }

    function WPCC_recursive_sanitize_text_field($array) {
         
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = $this->WPCC_recursive_sanitize_text_field($value);
            }else{
                $value = sanitize_text_field( $value );
            }
        }
        return $array;
    }



    public function process_bulk_action() {
        global $wpdb;
        $tablename = $wpdb->prefix.'wpcc_cashondelivery';
        // security check!
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );
        }

        $action = $this->current_action();
        switch ( $action ) {

            case 'delete':
                $ids = isset($_REQUEST['id']) ? $this->WPCC_recursive_sanitize_text_field($_REQUEST['id']) : array();
                if (is_array($ids)) $ids = implode(',', $ids);

                    if (!empty($ids)) {
                        $wpdb->query("DELETE FROM $tablename WHERE id IN($ids)");
                    }

                wp_redirect( $_SERVER['HTTP_REFERER'] );

                break;

            default:
                // do nothing or something else
                return;
                break;
        }
        return;
    }


    function column_pincode($item) {

        $delete_url = wp_nonce_url( admin_url().'?page=import-cod-pricelist&action=wpcc_delete&id='.$item['id'], 'my_nonce' );
        
        $actions = array(
            'edit'      => sprintf('<a href="?page=add-cod_amount&action=%s&id=%s">Edit</a>','oc_edit',$item['id']),
            'delete'    => '<a href="'.$delete_url.'">Delete</a>',
        );

        return sprintf('%1$s %2$s', $item['pincode'], $this->row_actions($actions) );
    }
}