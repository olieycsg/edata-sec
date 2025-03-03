<?php

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../api.php');

if(isset($_POST['add_leave'])){
  if(in_array($_POST['type'], ['1','14','15','16'])) {
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, LADVANCE, CREASON, MNOTES, ICNTEE) VALUES ('".$_POST['add_leave']."', '".$_POST['start']."', '".$_POST['end']."', '".$_POST['type']."', '".$_POST['days']."', '".$_POST['hour']."', '".$_POST['shift']."', '".mysqli_real_escape_string($conn, $_POST['reason'])."', 'approved', 'processed')";
    if ($conn->query($sql) === TRUE) {}
  }else if(in_array($_POST['type'], ['6','7','8','9','11','13'])) {
    if($_POST['type'] == '11'){
      $days = 40;
    }else{
      $days = $_POST['days'];
    }
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, LADVANCE, CREASON, MNOTES, ICNTEE) VALUES ('".$_POST['add_leave']."', '".$_POST['start']."', '".$_POST['end']."', '".$_POST['type']."', '$days', '11', '".$_POST['shift']."', '".mysqli_real_escape_string($conn, $_POST['reason'])."', 'approved', 'processed')";
    if ($conn->query($sql) === TRUE) {}
  }else{
    $days = ($_POST['type'] == '2') ? 98 : (($_POST['type'] == '10') ? 7 : (($_POST['type'] == '12') ? 5 : $_POST['days']));
    $uploadDir = '../../../user/webapp/file/';
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $fileName = uniqid().uniqid().'.'.$extension;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir.$fileName)) {
      $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE, FILE) VALUES ('".$_POST['add_leave']."', '".$_POST['start']."', '".$_POST['end']."', '".$_POST['type']."', '$days', '11', '".mysqli_real_escape_string($conn, $_POST['reason'])."', 'approved', 'processed', '$fileName')";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['update_image'])){
  $uploadDir = '../../../user/webapp/file/';
  $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $fileName = uniqid().uniqid().'.'.$extension;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir.$fileName)) {
    $sql = "UPDATE eleave SET FILE = '$fileName' WHERE id = '".$_POST['update_image']."'";
    if ($conn->query($sql) === TRUE) {}
  }
}

if(isset($_POST['update_leave'])){
  if(in_array($_POST['type'], ['1','14','15','16'])) {
    $sql = "UPDATE eleave SET NDAYS = '".$_POST['days']."', NHOURS = '".$_POST['hour']."', CREASON = '".mysqli_real_escape_string($conn, $_POST['reason'])."' WHERE id = '".$_POST['update_leave']."'";
  }else{
    $sql = "UPDATE eleave SET CREASON = '".mysqli_real_escape_string($conn, $_POST['reason'])."' WHERE id = '".$_POST['update_leave']."'";
  }
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_image'])){
  $sql = "SELECT * FROM eleave WHERE id = '".$_POST['delete_image']."'";
  $result = $conn->query($sql);

  if($row = $result->fetch_assoc()){
    if($row['FILE'] != ''){
      unlink('../../../user/webapp/file/'.$row['FILE']);
      $sql = "UPDATE eleave SET FILE = '' WHERE id = '".$_POST['delete_image']."'";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['delete_leave'])){
  $sql = "SELECT * FROM eleave WHERE id = '".$_POST['delete_leave']."'";
  $result = $conn->query($sql);

  if($row = $result->fetch_assoc()){
    if($row['FILE'] != ''){
      unlink('../../../user/webapp/file/'.$row['FILE']);
    }
    $sql = "DELETE FROM eleave WHERE id = '".$_POST['delete_leave']."'";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE eleave DROP id";
      if ($conn->query($sql) === TRUE) {
        $sql = "ALTER TABLE eleave ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
        if ($conn->query($sql) === TRUE) {}
      }
    }
  }
}

if(isset($_POST['add_holiday'])){

  $sql = "INSERT INTO eleave_publicholiday (dt_holiday, description, type) VALUES ('".$_POST['add_holiday']."', '".mysqli_real_escape_string($conn, $_POST['desc'])."', 'uf')";

  if ($conn->query($sql) === TRUE) {
    echo "success";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

if(isset($_POST['update_descph'])){

  $sql = "UPDATE eleave_publicholiday SET description = '".mysqli_real_escape_string($conn, $_POST['update_descph'])."' WHERE id = '".$_POST['phid']."'";

  if ($conn->query($sql) === TRUE) {
    echo "success";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

if(isset($_POST['update_dateph'])){

  $sql = "UPDATE eleave_publicholiday SET dt_holiday = '".$_POST['update_dateph']."' WHERE id = '".$_POST['phid']."'";

  if ($conn->query($sql) === TRUE) {
    echo "success";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

if(isset($_POST['update_typeph'])){

  $sql = "UPDATE eleave_publicholiday SET type = '".$_POST['update_typeph']."' WHERE id = '".$_POST['phid']."'";

  if ($conn->query($sql) === TRUE) {
    echo "success";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

if(isset($_POST['delete_public_holiday'])){
  
  $sql = "DELETE FROM eleave_publicholiday WHERE id = '".$_POST['delete_public_holiday']."'";
  if ($conn->query($sql) === TRUE) {

    $sql = "ALTER TABLE eleave_publicholiday DROP id";
    if ($conn->query($sql) === TRUE) {

      $sql = "ALTER TABLE eleave_publicholiday ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {

        echo "success";

      }
    }
  }
}

if(isset($_POST['add_adjustment'])){
  $dates = $_POST['dates']."-01-01";
  $sql = "INSERT INTO eleave_fxleavea (CNOEE, DADJUST, CCDLEAVE, CTYPADJUST, NDAYS, NHOURS, NBALANCE) VALUES ('".$_POST['add_adjustment']."', '$dates', '".$_POST['leave']."', '".$_POST['adjust']."', '".$_POST['days']."', '0.00', '".$_POST['balc']."')";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_adjustment'])){
  $dates = date("Y", strtotime($_POST['dates']))."-01-01";
  $sql = "UPDATE eleave_fxleavea SET CNOEE = '".$_POST['edit_adjustment']."', DADJUST = '$dates', CCDLEAVE = '".$_POST['leave']."', CTYPADJUST = '".$_POST['adjust']."', NDAYS = '".$_POST['days']."', NBALANCE = '".$_POST['balc']."' WHERE ID = '".$_POST['id']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_adjustment'])){
  
  $sql = "DELETE FROM eleave_fxleavea WHERE ID = '".$_POST['delete_adjustment']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE eleave_fxleavea DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE eleave_fxleavea ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['leave_restore'])){

  $year = ($_POST['leave_restore'] + 1)."-01-01 00:00:00";
  $sql = "DELETE FROM eleave_fxleavea WHERE DADJUST = '$year'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE eleave_fxleavea DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE eleave_fxleavea ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['leave_closing'])){

  $year = ($_POST['leave_closing'] + 1)."-01-01 00:00:00";
  $closing = $_POST['closing'];

  foreach ($closing as $value) {
    list($cnoee, $balance) = explode(",", $value);
    $sql = "INSERT INTO eleave_fxleavea (CNOEE, DADJUST, CCDLEAVE, CTYPADJUST, NDAYS, NHOURS, MNOTES, NBALANCE) VALUES ('$cnoee', '$year', 1, 1, '$balance', 0, '', 0)";
    if ($conn->query($sql) === TRUE) {}
  }
}

$conn->close();
?>