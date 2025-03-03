<?php

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);
*/
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../api.php');

if(isset($_POST['check_code'])){
  $sql = "SELECT * FROM sys_general_dctype WHERE CTYPE = '".$_POST['check_code']."'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) { echo "1"; }
}

if(isset($_POST['add_code'])){
  $sql = "INSERT INTO sys_general_dctype (CTYPE, CDESC) VALUES ('".$_POST['add_code']."', '".mysqli_real_escape_string($conn, $_POST['desc'])."')";
  if ($conn->query($sql) === TRUE) { echo $conn->insert_id; }
}

if(isset($_POST['edit_code'])){
  $sql = "UPDATE sys_general_dctype SET CDESC = '".$_POST['desc']."' WHERE ID = '".$_POST['edit_code']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_code'])){
  $sql = "DELETE FROM sys_general_dcmisc WHERE CTYPE = '".$_POST['delete_code']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE sys_general_dcmisc DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE sys_general_dcmisc ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {
        $sql = "DELETE FROM sys_general_dctype WHERE CTYPE = '".$_POST['delete_code']."'";
        if ($conn->query($sql) === TRUE) {
          $sql = "ALTER TABLE sys_general_dctype DROP ID";
          if ($conn->query($sql) === TRUE) {
            $sql = "ALTER TABLE sys_general_dctype ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
            if ($conn->query($sql) === TRUE) {}
          }
        }
      }
    }
  }
}

if(isset($_POST['add_subcode'])){
  $sql = "INSERT INTO sys_general_dcmisc (CTYPE, CCODE, CDESC, CLABEL) VALUES ('".$_POST['code']."', '".$_POST['add_subcode']."', '".$_POST['subdesc']."', '".$_POST['sublabel']."')";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_subcode'])){
  $sql = "UPDATE sys_general_dcmisc SET CCODE = '".$_POST['subcode']."', CDESC = '".$_POST['subdesc']."', CLABEL = '".$_POST['sublabel']."' WHERE ID = '".$_POST['edit_subcode']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_subcode'])){
  $sql = "DELETE FROM sys_general_dcmisc WHERE ID = '".$_POST['delete_subcode']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE sys_general_dcmisc DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE sys_general_dcmisc ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_workflow'])){
  $module = $_POST['add_workflow'];
  $division = $_POST['division'];
  $department = $_POST['department'];
  $job = $_POST['job'];
  $status = $_POST['status'];
  $cross = $_POST['cross'];
  $description = mysqli_real_escape_string($conn, $_POST['description']);

  $sql = "INSERT INTO sys_workflow (CJOB, CDIVISION, CDEPARTMEN, MODULE, ACTION, CROSDIVACC, REMARKS) VALUES ('$job', '$division', '$department', '$module', '$status', '$cross', '$description')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_workflow'])){

  $edesc = mysqli_real_escape_string($conn, $_POST['edesc']);
  $sql = "UPDATE sys_workflow SET MODULE = '".$_POST['emode']."', CDIVISION = '".$_POST['edivi']."', CDEPARTMEN = '".$_POST['edept']."', CJOB = '".$_POST['ejob']."', ACTION = '".$_POST['estat']."', CROSDIVACC = '".$_POST['cross']."', REMARKS = '$edesc' WHERE ID = '".$_POST['edit_workflow']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_workflow'])){

  $sql = "DELETE FROM sys_workflow WHERE ID = '".$_POST['delete_workflow']."'";
  if ($conn->query($sql) === TRUE) {

    $sql = "ALTER TABLE sys_workflow DROP ID";
    if ($conn->query($sql) === TRUE) {

      $sql = "ALTER TABLE sys_workflow ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_head'])){
  $division = $_POST['add_head'];
  $sql = "INSERT INTO sys_workflow_divisional (CREFF, CDIVISION, DMODIFIED) VALUES ('DEFAULT', '$division', '".date('Y-m-d H:i:s')."')";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_head'])){
  $sql = "UPDATE sys_workflow_divisional SET CJOB = '".$_POST['jb']."', CNOEE = '".$_POST['cd']."', DMODIFIED = '".date('Y-m-d H:i:s')."' WHERE CDIVISION = '".$_POST['edit_head']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_head'])){

  $sql = "DELETE FROM sys_workflow_divisional WHERE ID = '".$_POST['delete_head']."'";
  if ($conn->query($sql) === TRUE) {

    $sql = "ALTER TABLE sys_workflow_divisional DROP ID";
    if ($conn->query($sql) === TRUE) {

      $sql = "ALTER TABLE sys_workflow_divisional ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_secretary'])){
  $division = $_POST['add_secretary'];
  $sql = "INSERT INTO sys_workflow_divisional_access (CREFF, CDIVISION, DMODIFIED) VALUES ('DEFAULT', '$division', '".date('Y-m-d H:i:s')."')";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_secretary'])){
  $sql = "UPDATE sys_workflow_divisional_access SET CJOB = '".$_POST['jb']."', CNOEE = '".$_POST['ce']."', DMODIFIED = '".date('Y-m-d H:i:s')."' WHERE CDIVISION = '".$_POST['edit_secretary']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_secretary_backup'])){
  $sql = "UPDATE sys_workflow_divisional_access SET SJOB = '".$_POST['jb']."', SNOEE = '".$_POST['ce']."', DMODIFIED = '".date('Y-m-d H:i:s')."' WHERE CDIVISION = '".$_POST['edit_secretary_backup']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_secretary'])){

  $sql = "DELETE FROM sys_workflow_divisional_access WHERE ID = '".$_POST['delete_secretary']."'";
  if ($conn->query($sql) === TRUE) {

    $sql = "ALTER TABLE sys_workflow_divisional_access DROP ID";
    if ($conn->query($sql) === TRUE) {

      $sql = "ALTER TABLE sys_workflow_divisional_access ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

$conn->close();
?>