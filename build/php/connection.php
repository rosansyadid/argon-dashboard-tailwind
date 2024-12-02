<?php

$hostname = "127.0.0.1";
$username = "root";
$password = "";
$database = "e-report";

$connection = mysqli_connect($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>