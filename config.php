<?php
$servername = "localhost";
$username = "root"; // default XAMPP
$password = "";     // default bosh
$dbname = "skincare_platform";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>
