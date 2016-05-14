<?php
include 'database.php';

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'smartzero-opencard';

$database = new DB($dbhost, $dbuser, $dbpass, $dbname);

?>