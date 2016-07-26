<?php

/*
Plugin Name: Contact Form
Description: shortcode [contact_form]
Version: 1.0
Author: Biros, Igonda
 */

/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */

/**
 * Enqueue plugin style-file
 */

function wptuts_styles_with_the_lot()
{
    // Register the style like this for a plugin:
    if (!is_admin()) {

        wp_register_style('contact_style', plugins_url('/css/style.css', __FILE__), array(), '', 'all');
        wp_enqueue_style('contact_style');
        
        wp_register_script('bootstrap_js', plugins_url('/js/bootstrap.min.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('bootstrap_js');

        wp_register_script('contact_form_js', plugins_url('/js/contact_form.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('contact_form_js');

        wp_register_script('md5', plugins_url('/js/md5.js', __FILE__), array('jquery'), '', true);
        wp_enqueue_script('md5');
        // For either a plugin or a theme, you can then enqueue the style:
    }

}
add_action('wp_enqueue_scripts', 'wptuts_styles_with_the_lot');

function html_form_code()
{
    include 'template.php';
}

function get_product_type_parse()
{   
    require_once WP_PLUGIN_DIR.'/contact_form/dbconnect.php';
   
    $result = $database->select('oc_category_description',array('*'),null, array('name' => 'ASC'));

    $html_select = "<select id='id-device_type-0' name='cf-device_type-0' class='form-control' required autocomplete='off'> <option value='null' >-- Vyberte si jednu z možností --</option>";
    if ($result) {
        // output data of each row
        foreach ($result as $row) {
            $html_select .= "<option value=".$row["category_id"]." label=" . $row["name"] . ">" . $row["name"] . "</option>";
        }
    }
    $html_select .= "</select>";
   // $database->disconnect();
    return $html_select;
}

function insert_to_database()
{
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'smartzero-opencart';
    $database = new DB($dbhost, $dbuser, $dbpass, $dbname);
   
    // if the submit button is clicked, insert date to the database
    if (isset($_POST['cf-submitted'])) {
        $SK = 'Slovenská republika';
        $CZ = 'Česká republika';
        
        $data = array();
        $data['control_sum']     = $_POST["cf-control_sum"];
        $data['firstname']       = sanitize_text_field($_POST["cf-firstname"]);
        $data['surname']         = sanitize_text_field($_POST["cf-surname"]);
        $data['email']           = sanitize_text_field($_POST["cf-email"]);
        $data['phone']           = sanitize_text_field($_POST["cf-phone"]);
        $data['country']         = sanitize_text_field($_POST["cf-country"]);
        $data['city']            = sanitize_text_field($_POST["cf-city"]);
        $data['street']          = sanitize_text_field($_POST["cf-street"]);
        $data['zip']             = sanitize_text_field($_POST["cf-zip"]);
        $data['payment_method']  = 'Platba na dobierku';
        $data['total_price']     = $_POST["cf-total_price"];
        $data['currency_code']   = 'EUR';
        $data['date']            = date('Y-m-d H:i:s');
        $data['order_status_id'] = '1'; // 1 = Pending more in openCart DB table oc_order_status
        // $data['device_type']     = sanitize_text_field($_POST["cf-device_type"]);
        // $data['device_model']    = sanitize_text_field($_POST["cf-device_model"]);
        $data['counter']         = $_POST['cf-counter'];
        $data['device_quantity'] = array();
        $data['tax']             = '0';
        $data['store_url']       = 'http://www.smartzero-opencart.dev/';
        $country_ceck = false;
        switch ($data['country']) {
            case $SK:
                $data['shipping_price']  = 0;
                $data['shipping_code']   = 'xshipping.xshipping1';
                $data['sub_total_price'] = $data['total_price'];
                $country_ceck = true;
                break;
            case $CZ:
                $data['shipping_price']  = 2;
                $data['shipping_code']   = 'xshipping.xshipping2';
                $data['sub_total_price'] = $data['total_price']-$data['shipping_price'];
                $country_ceck = true;
                break;
            default:
                $country_ceck = false;
                break;
        }      

        $data['store_name']      = 'SmartZero';
        $data['product_id']      = array();

        $hash = md5($data['counter'].$data['total_price']);
        
        if ($data['control_sum'] == $hash && $country_ceck == true) {
            
            for ($i=0; $i <= $data['counter']; $i++) { 
                array_push($data['device_quantity'], sanitize_text_field($_POST["cf-device_quantity-".$i]));
                array_push($data['product_id'], $_POST["cf-device_model-".$i]);
            }
            
            $orders_data = array(
                'firstname' 			=> $data['firstname'],
                'lastname' 				=> $data['surname'],
                'email' 				=> $data['email'],
                'telephone' 			=> $data['phone'],
                'payment_firstname' 	=> $data['firstname'],
                'payment_lastname' 		=> $data['surname'],
                'payment_country' 		=> $data['country'],
                'payment_city' 			=> $data['city'],
                'payment_address_1' 	=> $data['street'],
                'payment_postcode'		=> $data['zip'],
                'payment_method'		=> $data['payment_method'],
                'shipping_firstname'	=> $data['firstname'],
                'shipping_lastname' 	=> $data['surname'],
                'shipping_city' 		=> $data['city'],
                'shipping_address_1'	=> $data['street'],
                'shipping_country' 		=> $data['country'],
                // 'shipping_postcode'  => $data['zip'],        asi ostane takto
                // 'shiping_company'	=> $data['company'],    treba doprogramovat
                // 'payment_address_2'  => $data['ICO'],        treba doprogramovat
                // 'shipping_address_2' => $data['ICO'],        treba doprogramovat
                'total' 				=> $data['total_price'],
                'currency_code'			=> $data['currency_code'],
                'date_added' 			=> $data['date'],
                'date_modified'			=> $data['date'],
                'order_status_id' 		=> $data['order_status_id'],
                'store_url' 			=> $data['store_url'],
                'shipping_code'			=> $data['shipping_code'],
                'store_name' 			=> $data['store_name']
            );

            foreach ($orders_data as $idx=>$order_data) {
                $escaped_values[$idx] = "'".$order_data."'";
            }
            $columns_order_data = implode(", ", array_keys($escaped_values));
            $values_order_data  = implode(", ", $escaped_values);
            
            $insert_oc_order = $database->insert('oc_order',$orders_data);
            $last_id_product_order = array();
           
            if ($insert_oc_order) {
                $last_id = $insert_oc_order;

                for ($i=0; $i < $data['counter']; $i++) { 
                    $where_oc_product = array( 'product_id' => $data['product_id'][$i]);

                    $result_products_names = $database->select('oc_product_description', array('*'), $where_oc_product);
                   
                  
                    $product_nameSK;
                    $product_nameEN;
                    if ($result_products_names) {
                        // output data of each row
                        foreach ($result_products_names as $row) {
                            $product_nameSK = $row['name'];
                            $product_nameEN = "Tempered glass ".$row['meta_title'];
                        }
                    }

                 
                    $result_products_price =  $database->select('oc_product', array('*'), $where_oc_product);
                    if ($result_products_price) {
                        // output data of each row
                        foreach ($result_products_price as $row) {
                            $product_price = $row['price'];
                        }
                    }
                    $product_price_total = $product_price * $data['device_quantity'][$i];

                    $order_data_products = array(
                        'order_id' => $last_id,
                        'product_id' => $data['product_id'][$i],
                        'name' => $product_nameSK,
                        'model' => $product_nameEN,
                        'quantity' =>  $data['device_quantity'][$i],
                        'price' => $product_price,
                        'total' => $product_price_total,
                        'tax' => $data['tax']
                    );

                    foreach ($order_data_products as $idx=>$order_data_product) {
                        $escaped_values_product[$idx] = "'".$order_data_product."'";
                    }

                    $columns_order_data_product = implode(", ", array_keys($escaped_values_product));
                    $values_order_data_product  = implode(", ", $escaped_values_product);

                    $insert_order_product = $database->insert('oc_order_product', $order_data_products);

                   
                    if($insert_order_product){
                        array_push($last_id_product_order, $insert_order_product);  
                    }              
                }
                
            } else {
                echo "Error insert: ";
            }
            $oc_order_total_1 = array(
                'order_id' => $last_id,
                'code' => 'sub_total',
                'title' => 'Sub-Total',
                'value' => $data['sub_total_price'],
                'sort_order' => '1'
            );
            $insert_result = $database->insert('oc_order_total',$oc_order_total_1);
            if(!$insert_result){
                echo 'error insert ';
            }
          
            $oc_order_total_2 = array(
                'order_id' => $last_id,
                'code' => 'total',
                'title' => 'Doprava',
                'value' => $data['shipping_price'],
                'sort_order' => '3'
            );
            $insert_result = $database->insert('oc_order_total',$oc_order_total_2);
            if(!$insert_result){
                echo 'error insert ';
            }

            $oc_order_total_3 = array(
                'order_id' => $last_id,
                'code' => 'total',
                'title' => 'Total',
                'value' => $data['total_price'],
                'sort_order' => '9'
            );
            $insert_result = $database->insert('oc_order_total',$oc_order_total_3);
            if(!$insert_result){
                echo 'error insert ';
            }
            
            $oc_order_history = array(
                'order_id' => $last_id,
                'order_status_id' => $data['order_status_id'],
                'notify' => '0',
                'date_added' => $data['date']
            );
            $insert_result = $database->insert('oc_order_history', $oc_order_history);
           
            if(!$insert_result){
                echo 'error insert ';
            }
            $last_id_product_order = implode(', ', $last_id_product_order);
                        
            $sql = "SELECT * FROM oc_order_product WHERE order_product_id IN (".$last_id_product_order.") "; 
            $order_products = $database->custom_sql_select($sql);
                      	
            ob_start();
            include WP_PLUGIN_DIR.'/contact_form/template_mail.php';
            $mail_message = ob_get_clean();
            deliver_mail($data['email'], $last_id, $mail_message);
        }
        else{
            echo 'Narušená integrita dát';
        }
    }
    $database->disconnect();
}

function deliver_mail($email, $order_number, $mail_message)
{    
    add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

    $multiple_recipients = array('bubomirxxx@gmail.com', $email);    //objednavka@smartzero.sk
    $subject   = 'SmartZero.sk - objednávka '.$order_number;
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Smartzero.sk <smartzero@smartzero.sk>" . "\r\n";
   
    // If email has been process for sending, display a success message
    if (wp_mail($multiple_recipients, $subject, $mail_message, $headers)) {
    	//redirect on other web site because of re-sending form in firefox
        echo "<script type='text/javascript'>document.location.href='formular-uspesne-odoslany';</script>";
    } else{
    	echo 'Pri objednávke sa vyskytla chyba prosím kontaktujte administrátora';
    }

    // Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
    remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
 
}
function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}

function cf_shortcode()
{
    ob_start();
    html_form_code();
    insert_to_database();
    return ob_get_clean();
}

add_shortcode('contact_form', 'cf_shortcode');
