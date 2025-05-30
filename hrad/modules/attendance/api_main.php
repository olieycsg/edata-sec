<?php

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../api.php'); 

if (isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == 0) {
  $filename = $_FILES["csvFile"]["tmp_name"];
  if (($handle = fopen($filename, "r")) !== FALSE) {
    $bom = fread($handle, 3);
    if ($bom !== "\xEF\xBB\xBF") {
      rewind($handle);
    }

    $row = 0;

    while (($data = fgetcsv($handle, 10000000, ",")) !== FALSE) {
      $row++;
      if ($row < 3) continue;

      $time = explode("-", $data[5]);
      $name = trim($data[1]);
      $date = date("Y-m-d", strtotime(str_replace("/", "-", $data[4])));
      $cint = $time[0];
      $cout = $time[1];
      $rang = $data[7];

      $words = explode(' ', $name);
      $likeConditions = array_map(fn($word) => "CNAME LIKE '%$word%'", $words);
      $whereClause = implode(' AND ', $likeConditions);

      $sql1 = "SELECT CNOEE, CDIVISION FROM employees_demas WHERE ($whereClause) AND DATE_FORMAT(DHIRE, '%Y-%m-%d') < DATE_FORMAT('$date', '%Y-%m-%d') AND DRESIGN = '0000-00-00'";
      $result1 = $conn->query($sql1);
      if ($row1 = $result1->fetch_assoc()) {
        $cnoee = $row1['CNOEE'];
        $cdivi = $row1['CDIVISION'];
        $sql = "INSERT INTO attendance (cnoee, cdivi, edate, cint, cout, rang, type, remarks) VALUES ('$cnoee', '$cdivi', '$date', '$cint', '$cout', '$rang', '', '')";
        $conn->query($sql);
      }
    }
    fclose($handle);
  }
}

/*if (isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == 0) {
    $filename = $_FILES["csvFile"]["tmp_name"];

    if (($handle = fopen($filename, "r")) !== FALSE) {
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $row = 0;
        $processedNames = []; // Track names that have been processed
        $processedCdivi = []; // Track divisions for processed names

        while (($data = fgetcsv($handle, 10000000, ",")) !== FALSE) {
            $row++;
            if ($row < 3) continue; // Skip the first two rows

            $time = explode("-", $data[5]);
            $name = trim($data[1]);
            $date = date("Y-m-d", strtotime(str_replace("/", "-", $data[4])));
            $cint = $time[0];
            $cout = $time[1];

            if (!isset($processedNames[$name])) {
                $sql1 = "SELECT CNOEE, CDIVISION FROM employees_demas WHERE CNAME LIKE '%$name%' AND DRESIGN = '0000-00-00'";
                $result1 = $conn->query($sql1);
                if ($row1 = $result1->fetch_assoc()) {
                    $cnoee = $row1['CNOEE'];
                    $cdivi = $row1['CDIVISION'];
                } else {
                    $cnoee = ''; // Default if no match found
                    $cdivi = '';
                }
                $processedNames[$name] = $cnoee;
                $processedCdivi[$name] = $cdivi;
            } else {
                $cnoee = '';
                $cdivi = '';
            }

            $sql = "INSERT INTO attendance (cnoee, cdivi, edate, cint, cout, type, remarks) 
                    VALUES ('$cnoee', '$cdivi', '$date', '$cint', '$cout', '', '')";
            $conn->query($sql);
        }
        fclose($handle);
    }
}*/

/*if (isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == 0) {
  $filename = $_FILES["csvFile"]["tmp_name"];
  if (($handle = fopen($filename, "r")) !== FALSE) {
    $bom = fread($handle, 3);
    if ($bom !== "\xEF\xBB\xBF") {
      rewind($handle);
    }

    $row = 0;
    $dataList = [];
    $datesArray = [];

    while (($data = fgetcsv($handle, 10000000, ",")) !== FALSE) {
      $row++;
      if ($row < 3) continue;

      $name = trim($data[1]);
      $date = date("Y-m-d", strtotime(str_replace("/", "-", $data[4])));
      $cint = isset($data[6]) ? trim($data[6]) : '';
      $cout = isset($data[7]) ? trim($data[7]) : '';

      if (!empty($name)) {
        $dataList[] = [
          'name' => $name,
          'date' => $date,
          'cint' => $cint,
          'cout' => $cout
        ];
        $datesArray[] = $date;
      }
    }
    fclose($handle);

    if (!empty($datesArray)) {
      sort($datesArray);
      $firstDetectedDate = new DateTime(reset($datesArray));
      $year = $firstDetectedDate->format("Y");

      $startDate = new DateTime("$year-01-01");
      $endDate = new DateTime(end($datesArray));
    }

    $allDates = [];
    while ($startDate <= $endDate) {
      $allDates[] = $startDate->format("Y-m-d");
      $startDate->modify("+1 day");
    }

    $groupedData = [];
    foreach ($dataList as $entry) {
      $groupedData[$entry['name']][$entry['date']] = [
        'cint' => $entry['cint'],
        'cout' => $entry['cout']
      ];
    }

    foreach ($groupedData as $name => $dates) {
      foreach ($allDates as $date) {
        if (!isset($dates[$date])) {
          $dates[$date] = ['cint' => '', 'cout' => ''];
        }
      }

      ksort($dates);
      foreach ($dates as $date => $times) {
        $safe_name = $conn->real_escape_string($name);
        $safe_date = $conn->real_escape_string($date);
        $safe_cint = $conn->real_escape_string($times['cint']);
        $safe_cout = $conn->real_escape_string($times['cout']);

        $sql1 = "SELECT CNOEE, CDIVISION FROM employees_demas WHERE REPLACE(CNAME, '@', '') LIKE '%$safe_name%' AND DRESIGN = '0000-00-00'";
        $result1 = $conn->query($sql1);

        if ($row1 = $result1->fetch_assoc()) {
          $cnoee = $conn->real_escape_string($row1['CNOEE']);
          $cdivi = $conn->real_escape_string($row1['CDIVISION']);

          $sql2 = "INSERT INTO attendance (cnoee, cdivi, edate, cint, cout, type, remarks) VALUES ('$cnoee', '$cdivi', '$safe_date', '$safe_cint', '$safe_cout', '', '')";
          $conn->query($sql2);
        }
      }
    }
  }
}*/

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