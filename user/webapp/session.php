<?php 

session_start();
$_SESSION['emid'] = $_GET['emid'];

header("Location: index.php");

?>