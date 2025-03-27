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

  $sql1 = "SELECT * FROM eleave_publicholiday";
  $result1 = $conn->query($sql1);
  $publicHolidays = $result1->fetch_all(MYSQLI_ASSOC);

  $sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
  $result2 = $conn->query($sql2);
  $employees = [];
  while ($row = $result2->fetch_assoc()) {
    $employees[$row['CNAME']] = $row['CNOEE'];
  }

  $sql3 = "SELECT * FROM eleave";
  $result3 = $conn->query($sql3);
  $leaves = $result3->fetch_all(MYSQLI_ASSOC);

  $sql4 = "SELECT * FROM eleave_leave_type";
  $result4 = $conn->query($sql4);
  $leaveTypes = [];
  foreach ($result4->fetch_all(MYSQLI_ASSOC) as $lt) {
    $leaveTypes[$lt['ID']] = ['acry' => $lt['acry'], 'leave_type' => $lt['leave_type']];
  }

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

    $cnoee = $employees[$name] ?? null;

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
        foreach ($leaves as $leave) {
          if ($edate >= $leave['DLEAVE'] && $edate <= $leave['DLEAVE2'] && $cnoee == $leave['CNOEE']) {
            $leaveType = $leaveTypes[$leave['CCDLEAVE']] ?? ['leave_type' => 'Unknown'];
            $absent = 1;
            $type = 'Absent';
            $stat = $leaveType['leave_type'];
            break;
          }
        }

        if ($absent !== 1) {
          $type = 'Absent';
          $stat = 'No Clock IN/OUT';
        }
      }

      if ($val['cint'] != '00:00:00' && $val['cout'] != '00:00:00') {
        foreach ($leaves as $leave) {
          if ($cnoee == $leave['CNOEE']) {
            $startDate = new DateTime($leave['DLEAVE']);
            $endDate = new DateTime($leave['DLEAVE2']);
            $leaveType = $leaveTypes[$leave['CCDLEAVE']] ?? ['acry' => '', 'leave_type' => ''];

            while ($startDate <= $endDate) {
              if ($edate == $startDate->format('Y-m-d')) {
                if ($leave['NDAYS'] == '0.50') {
                  if ($leave['NHOURS'] == '10') {
                    $status = 1;
                    $type = "Attend";
                    $stat = 'Halfday AM (' . $leaveType['acry'] . ')';
                    $late = strtotime($val['cint']) > strtotime("13:00") ? gmdate("H:i:s", strtotime($val['cint']) - strtotime("13:00")) : '';
                    $early = strtotime($val['cout']) < strtotime("15:00") ? gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("15:00"))) : '';
                  } elseif ($leave['NHOURS'] == '01') {
                    $status = 1;
                    $type = "Attend";
                    $stat = 'Halfday PM (' . $leaveType['acry'] . ')';
                    $late = strtotime($val['cint']) > strtotime("08:00") ? gmdate("H:i:s", strtotime($val['cint']) - strtotime("08:00")) : '';
                    $early = strtotime($val['cout']) < strtotime("12:00") ? gmdate("H:i:s", abs(strtotime($val['cout']) - strtotime("12:00"))) : '';
                  }
                } elseif ($leave['NDAYS'] > '0.50' && strlen($leave['NHOURS']) >= 2) {
                  if (strpos($leave['NHOURS'], '11') !== false) {
                    $status = 1;
                    $type = "Absent";
                    $stat = $leaveType['leave_type'];
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
