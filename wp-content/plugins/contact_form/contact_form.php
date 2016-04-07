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

        // For either a plugin or a theme, you can then enqueue the style:
    }

}
add_action('wp_enqueue_scripts', 'wptuts_styles_with_the_lot');

function html_form_code()
{
    include 'template.php';
}
function parser_include()
{
    if (!function_exists('file_get_html')) {
        require_once WP_CONTENT_DIR . '/plugins/contact_form/simple_html_dom.php';
    }
}

function get_product_type_parse()
{   
    include 'wp-content/plugins/contact_form/dbconnect.php';

    $fetch = "SELECT * FROM oc_category_description ORDER BY name ASC";
    $result = $conn->query($fetch);

    $html_select = "<select id='id-device_type' name='cf-device_type' class='form-control' required autocomplete='off'> <option value='null' >-- Vyberte si jednu z možností --</option>";
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $html_select .= "<option value=".$row["category_id"]." label=" . $row["name"] . ">" . $row["name"] . "</option>";
        }
    }
    $html_select .= "</select>";
    $conn->close();
    return $html_select;
}

function insert_to_database()
{
    include 'wp-content/plugins/contact_form/dbconnect.php';

    // if the submit button is clicked, insert date to the database
    if (isset($_POST['cf-submitted'])) {
        $data = array();

        $data['firstname']       = sanitize_text_field($_POST["cf-firstname"]);
        $data['surname']         = sanitize_text_field($_POST["cf-surname"]);
        $data['email']           = sanitize_text_field($_POST["cf-email"]);
        $data['phone']           = sanitize_text_field($_POST["cf-phone"]);
        $data['country']         = sanitize_text_field($_POST["cf-country"]);
        $data['city']            = sanitize_text_field($_POST["cf-city"]);
        $data['street']          = sanitize_text_field($_POST["cf-street"]);
        $data['zip']             = sanitize_text_field($_POST["cf-zip"]);
        $data['payment_method']  = 'Platba na dobierku';
        $data['total_price']     = '10';
        $data['currency_code']   = 'EUR';
        $data['date']            = date('Y-m-d H:i:s');
        $data['order_status_id'] = '1'; // 1 = Pending more in openCart DB table oc_order_status
        // $data['device_type']     = sanitize_text_field($_POST["cf-device_type"]);
        //$data['device_model']    = sanitize_text_field($_POST["cf-device_model"]);
        $data['device_quantity'] = sanitize_text_field($_POST["cf-device_quantity"]);
        $data['tax']             = '0';
        $data['store_url']       = 'http://www.smartzero-opencart.dev/';
        $data['shipping_code']   = '[]';

        $data['product_id'] = $_POST["cf-device_model"];
        
        foreach ($data as $value) {

            if ($value == null) {
                echo 'prazdne ' . $value . '<br>';
            }
            //echo $value . '<br>';
        }

        $orders_data = array(
            'firstname' => $data['firstname'],
            'lastname' => $data['surname'],
            'email' => $data['email'],
            'telephone' => $data['phone'],
            'payment_firstname' => $data['firstname'],
            'payment_lastname' => $data['surname'],
            'payment_country' => $data['country'],
            'payment_city' => $data['city'],
            'payment_address_1' => $data['street'],
            'payment_postcode' => $data['zip'],
            'payment_method' => $data['payment_method'],
            'shipping_firstname' => $data['firstname'],
            'shipping_lastname' => $data['surname'],
            'shipping_city' => $data['city'],
            'shipping_address_1' => $data['street'],
            'shipping_country' => $data['country'],
            'total' => $data['total_price'],
            'currency_code' => $data['currency_code'],
            'date_added' => $data['date'],
            'date_modified' => $data['date'],
            'order_status_id' => $data['order_status_id'],
            'store_url' => $data['store_url'],
            'shipping_code' => $data['shipping_code']
        );

        foreach ($orders_data as $idx=>$order_data) {
            $escaped_values[$idx] = "'".$order_data."'";
        }
        $columns_order_data = implode(", ", array_keys($escaped_values));
        $values_order_data  = implode(", ", $escaped_values);

        $sql_order = "INSERT INTO oc_order ($columns_order_data) VALUES ($values_order_data)";

        
        if ($conn->query($sql_order) === true) {
            $last_id = $conn->insert_id;

            $fetch_devices_names = "SELECT * FROM oc_product_description WHERE product_id = ".$data['product_id']." "; 
            $result_products_names = $conn->query($fetch_devices_names);

            $product_nameSK;
            $product_nameEN;
             if ($result_products_names->num_rows > 0) {
                // output data of each row
                while($row = $result_products_names->fetch_assoc()) {
                    $product_nameSK = $row['name'];
                    $product_nameEN = "Tempered glass ".$row['meta_title'];
                }
            }

            $order_data_products = array(
                'order_id' => $last_id,
                'product_id' => $data['product_id'],
                'name' => $product_nameSK,
                'model' => $product_nameEN,
                'quantity' =>  $data['device_quantity'],
                'price' => $data['total_price'],
                'total' => $data['total_price'],
                'tax' => $data['tax']
            );

            foreach ($order_data_products as $idx=>$order_data_product) {
                $escaped_values_product[$idx] = "'".$order_data_product."'";
            }

            $columns_order_data_product = implode(", ", array_keys($escaped_values_product));
            $values_order_data_product  = implode(", ", $escaped_values_product);

            $sql_order_product = "INSERT INTO oc_order_product ($columns_order_data_product ) VALUES ($values_order_data_product)";
            if (!$conn->query($sql_order_product) === true) {
                echo "Error: " . $sql_order_product . "<br>" . $conn->error;
            }

        } else {
            echo "Error: " . $sql_order . "<br>" . $conn->error;
        }

        $sql_order_total = "INSERT INTO oc_order_total (order_id, code, title, value, sort_order ) VALUES ('".$last_id."', 'sub_total', 'Sub-Total' , '".$data['total_price']."', '1')";

        if (!$conn->query($sql_order_total) === true) {
            echo "Error: " . $sql_order_total . "<br>" . $conn->error;
        } 
         $sql_order_total = "INSERT INTO oc_order_total (order_id, code, title, value, sort_order ) VALUES ('".$last_id."', 'total', 'Total' , '".$data['total_price']."', '9')";
        
        if (!$conn->query($sql_order_total) === true) {
            echo "Error: " . $sql_order_total . "<br>" . $conn->error;
        }
       
    }
    $conn->close();
}

function cf_shortcode()
{
    ob_start();

    html_form_code();
    insert_to_database();

    return ob_get_clean();
}

add_shortcode('contact_form', 'cf_shortcode');
