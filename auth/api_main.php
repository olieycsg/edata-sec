<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

if($_SESSION['uid'] == ""){
  
  header('location: index');
  
}else{
  
include('api.php');

if(isset($_POST['add_access'])){
  $sql = "INSERT INTO access (sid, email, status) VALUES ('".$_POST['add_access']."', '".$_POST['eml']."', '".$_POST['sts']."')";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['offline_access'])){
  $sql = "UPDATE access SET status = '0' WHERE id = '".$_POST['offline_access']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['online_access'])){
  $sql = "UPDATE access SET status = '1' WHERE id = '".$_POST['online_access']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_access'])){
  $sql = "DELETE FROM access WHERE id = '".$_POST['delete_access']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE access DROP id";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE access ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_module'])){
  foreach ($_POST['mod'] as $module) {
    $sql = "INSERT INTO module (sid, module, status) VALUES ('".$_POST['add_module']."', '$module', '".$_POST['sts']."')";
    if ($conn->query($sql) === TRUE) {}
  }
}

if(isset($_POST['offline_module'])){
  $sql = "UPDATE module SET status = '0' WHERE id = '".$_POST['offline_module']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['online_module'])){
  $sql = "UPDATE module SET status = '1' WHERE id = '".$_POST['online_module']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_module'])){
  $sql = "DELETE FROM module WHERE id = '".$_POST['delete_module']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE module DROP id";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE module ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['delete_module_all'])){
  $sql = "DELETE FROM module";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE module DROP id";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE module ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

$conn->close();
}
?>

