<?php

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

/*if(isset($_POST['sync'])){

  $sql = "SELECT * FROM attendance";
  $result = $conn->query($sql);

  foreach ($result as $key => $row) {
    $edate = $row['edate'];
    $name = $row['name'];

    $sql1 = "SELECT * FROM eleave_publicholiday";
    $result1 = $conn->query($sql1);

    $sql2 = "SELECT * FROM employees_demas WHERE CNAME LIKE '%$name%' AND DRESIGN = '0000-00-00'";
    $result2 = $conn->query($sql2);

    if($row = $result2->fetch_assoc()){
      $cnoee = $row['CNOEE'];
    }

    $sql3 = "SELECT * FROM eleave WHERE DATE_FORMAT(DLEAVE, '%Y-%m') = '$edate' OR DATE_FORMAT(DLEAVE2, '%Y-%m') = '$edate'";
    $result3 = $conn->query($sql3);

    $sql4 = "SELECT * FROM eleave_leave_type";
    $result4 = $conn->query($sql4);

    foreach ($result as $key => $val) {

      $id = $val['id'];
      $type = "Attend";
      $stat = '';
      $late = '';
      $early = '';
      $status = 0;
      $absent = 0;

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
                  $absent = 1;
                  $type = 'Absent';
                  $stat = $val4['leave_type'];
                  $late = '';
                  $early = '';
                }
              }
            }
          }

          if($absent !== 1){
            $type = 'Absent';
            $stat = 'No Clock IN/OUT';
            $late = '';
            $early = '';
          }
        }

        if ($val['cint'] != '00:00:00' && $val['cout'] != '00:00:00') {
          
          foreach ($result3 as $val3) {
            if ($cnoee == $val3['CNOEE']) {

              $startDate = new DateTime($val3['DLEAVE']);
              $endDate = new DateTime($val3['DLEAVE2']);

              foreach ($result4 as $val4) {
                if($val3['CCDLEAVE'] == $val4['ID']){
                  $statx = $val4['acry'];
                  $staty = $val4['leave_type'];
                }
              }

              while ($startDate <= $endDate) {
                $leaveDate = $startDate->format('Y-m-d');
                if ($val['edate'] == $leaveDate) {
                  if ($val3['NDAYS'] == '0.50' && $val3['NHOURS'] == '10') {
                    $status = 1;
                    $type = "Attend";
                    $stat = 'Halfday AM ('.$statx.')';

                    if (strtotime($val['cint']) > strtotime("13:00")){
                      $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("13:00"));
                    }else{
                      $late = '';
                    }

                    if (strtotime($val['cout']) < strtotime("15:00")){
                      $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("15:00")));
                    }else{
                      $early = '';
                    }

                  } else if ($val3['NDAYS'] == '0.50' && $val3['NHOURS'] == '01') {
                    $status = 1;
                    $type = "Attend";
                    $stat = 'Halfday PM ('.$statx.')';

                    if (strtotime($val['cint']) > strtotime("08:00")){
                      $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("08:00"));
                    }else{
                      $late = '';
                    }

                    if (strtotime($val['cout']) < strtotime("12:00")){
                      $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("12:00")));
                    }else{
                      $early = '';
                    }

                  } else if ($val3['NDAYS'] > '0.50' && strlen($val3['NHOURS']) >= 2) {
                    $nhoursParts = str_split($val3['NHOURS'], 2);
                    foreach ($nhoursParts as $part) {
                      if ($part == '10') {
                        $status = 1;
                        $type = "Attend";
                        $stat = 'Halfday AM ('.$statx.')';

                        if (strtotime($val['cint']) > strtotime("13:00")){
                          $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("13:00"));
                        }else{
                          $late = '';
                        }

                        if (strtotime($val['cout']) < strtotime("15:00")){
                          $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("15:00")));
                        }else{
                          $early = '';
                        }

                      } else if ($part == '01') {
                        $status = 1;
                        $type = "Attend";
                        $stat = 'Halfday PM ('.$statx.')';

                        if (strtotime($val['cint']) > strtotime("08:00")){
                          $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("08:00"));
                        }else{
                          $late = '';
                        }

                        if (strtotime($val['cout']) < strtotime("12:00")){
                          $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("12:00")));
                        }else{
                          $early = '';
                        }

                      } else if ($part == '11') {
                        $status = 1;
                        $type = "Absent";
                        $stat = $staty;
                        $late = '';
                        $early = '';
                      }
                    }
                  }
                }
                $startDate->modify('+1 day');
              }
            }
          }

          if($status !== 1){
            if (strtotime($val['cint']) > strtotime("09:00") && strtotime($val['cout']) >= strtotime("16:30")){
              $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("09:00"));
              $type = 'Incomplete';
              $stat = 'No Clock-IN';
            }

            if (strtotime($val['cint']) <= strtotime("09:00") && strtotime($val['cout']) < strtotime("16:30")){
              $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("16:30")));
              $type = 'Incomplete';
              $stat = 'No Clock-OUT';
            }

            if (strtotime($val['cint']) > strtotime("09:00") && strtotime($val['cout']) < strtotime("16:30")){
              $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("09:00"));
              $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("16:30")));
              $type = 'Incomplete';
              $stat = 'No Clock IN/OUT';
            }
          }
        }
      }

      $sql = "UPDATE attendance SET late = '$late', early = '$early', type = '$type', remarks = '$stat' WHERE id = '$id' AND name LIKE '%$name%'";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}*/

$conn->close();
?>