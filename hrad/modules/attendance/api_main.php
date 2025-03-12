<?php

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../api.php'); 

if (isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == 0) {
  $filename = $_FILES["csvFile"]["tmp_name"];
  if (($handle = fopen($filename, "r")) !== FALSE) {
    $bom = fread($handle, 3);
    if ($bom !== "\xEF\xBB\xBF") {
      rewind($handle);
    }

    while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
      $time = explode("-", $data[4]);
      $name = $data[0]; 
      $date = date("Y-m-d", strtotime(str_replace("/", "-", $data[3])));
      $cint = $time[0];
      $cout = $time[1];

      $sql = "INSERT INTO attendance (name, edate, cint, cout, type, remarks) VALUES ('$name', '$date', '$cint', '$cout', '', '')";
      $conn->query($sql);
    }
    fclose($handle);
  }
}

if(isset($_POST['delete'])){
  $sql = "DELETE FROM attendance";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE attendance DROP id";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE attendance ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['sync'])){

  $name = $_POST['sync'];

  $sql = "SELECT * FROM attendance";
  $result = $conn->query($sql);

  $sql1 = "SELECT * FROM eleave_publicholiday";
  $result1 = $conn->query($sql1);

  $sql2 = "SELECT * FROM employees_demas WHERE CNAME LIKE '%$name%'";
  $result2 = $conn->query($sql2);

  if($row = $result2->fetch_assoc()){
    $cnoee = $row['CNOEE'];
  }

  $sql3 = "SELECT * FROM eleave";
  $result3 = $conn->query($sql3);

  $sql4 = "SELECT * FROM eleave_leave_type";
  $result4 = $conn->query($sql4);

  foreach ($result as $key => $val) {

    $id = $val['id'];
    $type = "Attend";
    $stat = '';
    $late = '';
    $early = '';

    foreach ($result1 as $val1) {
      if ($val1['dt_holiday'] == $val['edate'] || date("m-d", strtotime($val1['dt_holiday'])) == date("m-d", strtotime($val['edate'])) && $val1['type'] == 'fixed') {
        $type = "Public Holiday";
        $stat = '';
      }
    }

    if ($type !== "Public Holiday" && in_array(date('N', strtotime($val['edate'])), [6, 7])) {
      $type = "OFF";
      $stat = '';
    }

    if ($type === "Attend") {
      if ($val['cint'] == '00:00:00' && $val['cout'] != '00:00:00') {
        $type = 'Incomplete';
        $stat = 'No Clock-IN';
      }

      if ($val['cint'] != '00:00:00' && $val['cout'] == '00:00:00') {
        $type = 'Incomplete';
        $stat = 'No Clock-OUT';
      }

      if ($val['cint'] == '00:00:00' && $val['cout'] == '00:00:00') {
        foreach ($result3 as $val3) {
          if ($val['edate'] >= $val3['DLEAVE'] && $val['edate'] <= $val3['DLEAVE2'] && $cnoee == $val3['CNOEE']){
            foreach ($result4 as $val4) {
              if($val3['CCDLEAVE'] == $val4['ID']){
                $type = 'Leave';
                $stat = $val4['leave_type'];
              }
            }
          }
        }
      }

      if ($val['cint'] != '00:00:00' && $val['cout'] != '00:00:00') {
        
        /*foreach ($result3 as $val3) {
          if ($val['edate'] >= $val3['DLEAVE'] && $val['edate'] <= $val3['DLEAVE2'] && $cnoee == $val3['CNOEE']){
            if($val3['NDAYS'] == '0.50' && $val3['NHOURS'] == '10'){
              if (strtotime($val['cint']) < strtotime("17:00") && strtotime($val['cout']) > strtotime("12:00")) {
                $type = "Attend";
                $stat = 'Halfday AM';
                $early = ''; //$early = gmdate("H:i:s", abs(strtotime("16:30") - strtotime($val['cout'])));
              }
            }
          }
        }*/

        if (strtotime($val['cint']) >= strtotime("07:30") && strtotime($val['cint']) <= strtotime("09:00") && strtotime($val['cout']) >= strtotime("16:30") && strtotime($val['cout']) <= strtotime("18:00")) {
          $type = "Attend";
          $stat = '';
        }

        /*if (strtotime($val['cint']) >= strtotime("07:30") && strtotime($val['cint']) <= strtotime("09:00") && strtotime($val['cout']) < strtotime("16:30")) {
          if($half == 1){
            $type = "Attend";
            $stat = 'Halfday PM';
            $early = gmdate("H:i:s", abs(strtotime("16:30") - strtotime($val['cout'])));
          }else{
            $type = "Incomplete";
            $stat = 'Early Clock-OUT';
            $early = gmdate("H:i:s", abs(strtotime("16:30") - strtotime($val['cout'])));
          }
        }

        if (strtotime($val['cint']) < strtotime("07:30") && strtotime($val['cint']) <= strtotime("09:00") && strtotime($val['cout']) < strtotime("16:30")) {
          if($half == 1){
            $type = "Attend";
            $stat = 'Halfday PM';
            $early = gmdate("H:i:s", abs(strtotime("16:30") - strtotime($val['cout'])));
          }else{
            $type = "Incomplete";
            $stat = 'Early Clock-OUT';
            $early = gmdate("H:i:s", abs(strtotime("16:30") - strtotime($val['cout'])));
          }
        }

        if (strtotime($val['cint']) >= strtotime("09:00") && strtotime($val['cout']) >= strtotime("16:30") && strtotime($val['cout']) <= strtotime("18:00")) {
          if($half == 1){
            $type = "Attend";
            $stat = 'Halfday AM';
            $late = gmdate("H:i:s", abs(strtotime($val['cint']) - strtotime("09:00")));
          }else{
            $type = "Incomplete";
            $stat = 'Late Clock-IN';
            $late = gmdate("H:i:s", abs(strtotime($val['cint']) - strtotime("09:00")));
          }
        }

        if (strtotime($val['cint']) >= strtotime("09:00") && strtotime($val['cout']) >= strtotime("16:30") && strtotime($val['cout']) > strtotime("18:00")) {
          if($half == 1){
            $type = "Attend";
            $stat = 'Halfday AM';
            $late = gmdate("H:i:s", abs(strtotime($val['cint']) - strtotime("09:00")));
          }else{
            $type = "Incomplete";
            $stat = 'Late Clock-IN';
            $late = gmdate("H:i:s", abs(strtotime($val['cint']) - strtotime("09:00")));
          }
        }

        if (strtotime($val['cint']) > strtotime("09:00") && strtotime($val['cout']) < strtotime("16:30")) {
          if($half == 1){
            $type = "Attend";
            $stat = '';
            $late = gmdate("H:i:s", abs(strtotime($val['cint']) - strtotime("09:00")));
            $early = gmdate("H:i:s", abs(strtotime("16:30") - strtotime($val['cout'])));
          }else{
            $type = "Incomplete";
            $stat = 'Late IN / Early OUT';
            $late = gmdate("H:i:s", abs(strtotime($val['cint']) - strtotime("09:00")));
            $early = gmdate("H:i:s", abs(strtotime("16:30") - strtotime($val['cout'])));
          }
        }*/
      }
    }

    $sql = "UPDATE attendance SET late = '$late', early = '$early', type = '$type', remarks = '$stat' WHERE id = '$id' AND name LIKE '%$name%'";
    if ($conn->query($sql) === TRUE) {}
  }
}

$conn->close();
?>