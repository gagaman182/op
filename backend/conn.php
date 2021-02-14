<?php

$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "jhcisdb";
$port = "3333";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn -> set_charset("utf8");

?>