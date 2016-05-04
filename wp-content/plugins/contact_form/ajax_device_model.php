<?php
if (isset($_POST['categoryID']) && !empty($_POST['categoryID'])) {
    include 'dbconnect.php';

    $category_id  = $_POST['categoryID'];
 
    $where = array( 'category_id' => $category_id);
    $result_products_id = $database->select('oc_product_to_category',array('product_id'),$where);
    
    $product_id = array();
     if ($result_products_id) {
        // output data of each row
        foreach ($result_products_id as $row) {
            array_push($product_id, $row['product_id']);
        }
    }
    $product_id = implode(',', $product_id);
    

    $sql = "SELECT * FROM oc_product WHERE product_id IN  ($product_id) ";
    $result_products = $database->custom_sql_select($sql);
           
    $devices_models = array();
    if ($result_products) {
        // output data of each row
        foreach ($result_products as $row) {
            array_push($devices_models, $row);
        }
    }

    $database->disconnect();
    echo json_encode($devices_models);
}