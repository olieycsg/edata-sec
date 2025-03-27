<?php

session_start();
$_SESSION["progress"] = 0;
session_write_close();

for ($i = 0; $i <= 100; $i += 10) {
  session_start();
  $_SESSION["progress"] = $i;
  session_write_close();
  sleep(1);
}

session_start();
$_SESSION["progress"] = 100;
session_write_close();

date_default_timezone_set("Asia/Kuala_Lumpur");

include "../../../api.php";

if (isset($_POST["sync_all"])) {
  $sql = "SELECT * FROM attendance";
  $result = $conn->query($sql);
  $attendances = $result->fetch_all(MYSQLI_ASSOC);

  $getDate = [];
  foreach ($attendances as $valDate) {
    $getDate[] = date("Y-m", strtotime($valDate['edate']));
  } 

  $sql1 = "SELECT * FROM eleave_publicholiday";
  $result1 = $conn->query($sql1);
  $publicHolidays = $result1->fetch_all(MYSQLI_ASSOC);

  $sql3 = "SELECT * FROM eleave WHERE DATE_FORMAT(DLEAVE, '%Y-%m') = '$getDate[0]' OR DATE_FORMAT(DLEAVE2, '%Y-%m') = '$getDate[0]'";
  $result3 = $conn->query($sql3);
  $leaves = $result3->fetch_all(MYSQLI_ASSOC);

  $sql4 = "SELECT * FROM eleave_leave_type";
  $result4 = $conn->query($sql4);
  $etypes = $result4->fetch_all(MYSQLI_ASSOC);

  foreach ($attendances as $val) {
    $id = $val['id'];
    $edate = $val['edate'];
    $name = $val['name'];

    $type = "Attend";
    $stat = '';
    $late = '';
    $early = '';
    $status = 0;
    $absent = 0;


    $sql2 = "SELECT * FROM employees_demas WHERE CNAME LIKE '%$name%' AND DRESIGN = '0000-00-00'";
    $result2 = $conn->query($sql2);

    if($row2 = $result2->fetch_assoc()){
      $cnoee = $row2['CNOEE'];
    }

    foreach ($publicHolidays as $holiday) {
      if ($holiday['dt_holiday'] == $edate || (date("m-d", strtotime($holiday['dt_holiday'])) == date("m-d", strtotime($edate)) && $holiday['type'] == 'fixed')) {
        $type = "Public Holiday";
        $stat = '';
        break;
      }
    }

    if ($type !== "Public Holiday" && in_array(date('N', strtotime($edate)), [6, 7])) {
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
        foreach ($leaves as $val3) {
          if ($edate >= $val3['DLEAVE'] && $edate <= $val3['DLEAVE2'] && $cnoee == $val3['CNOEE']) {
            foreach ($etypes as $val4) {
              if($val3['CCDLEAVE'] == $val4['ID']){
                $absent = 1;
                $type = 'Absent';
                $stat = $val4['leave_type'];
              }
            }
          }
        }
        if ($absent !== 1) {
          $type = 'Absent';
          $stat = 'No Clock IN/OUT';
        }
      }

      if ($val['cint'] != '00:00:00' && $val['cout'] != '00:00:00') {
        foreach ($result3 as $leave) {
          if ($cnoee == $leave['CNOEE']) {
            $startDate = new DateTime($leave['DLEAVE']);
            $endDate = new DateTime($leave['DLEAVE2']);

            foreach ($etypes as $val4) {
              if($leave['CCDLEAVE'] == $val4['ID']){
                $acry = $val4['acry'];
                $getType = $val4['leave_type'];
              }
            }

            while ($startDate <= $endDate) {
              if ($edate == $startDate->format('Y-m-d')) {
                if ($leave['NDAYS'] == '0.50') {
                  if ($leave['NHOURS'] == '10') {
                    $status = 1;
                    $type = "Attend";
                    $stat = 'Halfday AM (' .$acry. ')';
                    $late = strtotime($val['cint']) > strtotime("13:00") ? gmdate("H:i:s", strtotime($val['cint']) - strtotime("13:00")) : '';
                    $early = strtotime($val['cout']) < strtotime("15:00") ? gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("15:00"))) : '';
                  } elseif ($leave['NHOURS'] == '01') {
                    $status = 1;
                    $type = "Attend";
                    $stat = 'Halfday PM (' .$acry. ')';
                    $late = strtotime($val['cint']) > strtotime("08:00") ? gmdate("H:i:s", strtotime($val['cint']) - strtotime("08:00")) : '';
                    $early = strtotime($val['cout']) < strtotime("12:00") ? gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("12:00"))) : '';
                  }
                } elseif ($leave['NDAYS'] > '0.50' && strlen($leave['NHOURS']) >= 2) {
                  if (strpos($leave['NHOURS'], '11') !== false) {
                    $status = 1;
                    $type = "Absent";
                    $stat = $getType;
                  }
                }
              }
              $startDate->modify('+1 day');
            }
          }
        }

        if ($status !== 1) {
          if (strtotime($val['cint']) > strtotime("09:00") && strtotime($val['cout']) >= strtotime("16:30")) {
            $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("09:00"));
            $type = 'Incomplete';
            $stat = 'No Clock-IN';
          }

          if (strtotime($val['cint']) <= strtotime("09:00") && strtotime($val['cout']) < strtotime("16:30")) {
            $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("16:30")));
            $type = 'Incomplete';
            $stat = 'No Clock-OUT';
          }

          if (strtotime($val['cint']) > strtotime("09:00") && strtotime($val['cout']) < strtotime("16:30")) {
            $late = gmdate("H:i:s", strtotime($val['cint']) - strtotime("09:00"));
            $early = gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("16:30")));
            $type = 'Incomplete';
            $stat = 'No Clock IN/OUT';
          }
        }
      }
    }

    $sqlUpdate = "UPDATE attendance SET late = '$late', early = '$early', type = '$type', remarks = '$stat' WHERE id = '$id'";
    if (!$conn->query($sqlUpdate)) {}
  }
}

$conn->close();
?>
