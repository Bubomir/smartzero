<?php
if (isset($_POST['deviceType']) && !empty($_POST['deviceType'])) {
    include 'simple_html_dom.php';

    $typ        = $_POST['deviceType'];
    $data_fetch = array();
    $data_parse = array();
    $data_json  = array();
    foreach (glob('devices/' . $typ . '.txt') as $filename) {

        $data               = file_get_html($filename);
        $data_fetch['data'] = explode(';', $data);

        foreach ($data_fetch['data'] as $values) {
            list($data_parse['product_id'], $data_parse['model']) = explode('-', $values);
            array_push($data_json, $data_parse);
        }
    }
    echo json_encode($data_json);
}
