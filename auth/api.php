<?php

$servername = "localhost";
$username = "root";
$password = "#A@min*2024#";
$dbname = "admin";
$hrname = "offsysdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$hris = new mysqli($servername, $username, $password, $hrname);

if ($hris->connect_error) {
  die("Connection failed: " . $hris->connect_error);
}

?>