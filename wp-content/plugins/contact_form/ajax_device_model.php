<?php
if (isset($_POST['categoryID']) && !empty($_POST['categoryID'])) {
    include 'dbconnect.php';

    $category_id  = $_POST['categoryID'];
 
    $fetch_products_id = "SELECT * FROM oc_product_to_category WHERE category_id = '$category_id' ";
    $result_products_id = $conn->query($fetch_products_id);

    $product_id = array();
     if ($result_products_id->num_rows > 0) {
        // output data of each row
        while($row = $result_products_id->fetch_assoc()) {
           array_push($product_id, $row['product_id']);
        }
    }
    $product_id = implode(',', $product_id);
    $fetch_products = "SELECT * FROM oc_product WHERE product_id IN  ($product_id) ";
    $result_products = $conn->query($fetch_products);
    
    $devices_models = array();
    if ($result_products->num_rows > 0) {
        // output data of each row
        while($row = $result_products->fetch_assoc()) {
           array_push($devices_models, $row['model']);
        }
    }

    $conn->close();
    echo json_encode($devices_models);
}
