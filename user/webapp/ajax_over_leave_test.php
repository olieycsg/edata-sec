<?php 

ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_GET['emid'];
$year = $_GET['year'];
$shift = $_GET['shift'];

$gethly = [];
$getdes = [];
$annual = [];
$leaves = [];
$annually = [];

$ngrade = ['NE1','NE2','NE3','NE4','NE5','NE6'];

$sql1 = "SELECT * FROM eleave_publicholiday WHERE YEAR(dt_holiday) = '$year' OR type = 'fixed' ORDER BY dt_holiday ASC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND (YEAR(DLEAVE) = '$year' OR YEAR(DLEAVE2) = '$year') ORDER BY DLEAVE ASC";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM eleave_leave_type";
$result3 = $conn->query($sql3);

$sql4 = "SELECT * FROM eleave_fxleavea WHERE CNOEE = '$emid'";
$result4 = $conn->query($sql4);

$sql5 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result5 = $conn->query($sql5);

foreach ($result1 as $key1 => $value1) {
  $gethly[] = $year."-".date("m-d", strtotime($value1['dt_holiday']));
  $getdes[] = $year."-".date("m-d", strtotime($value1['dt_holiday']))."|".$value1['description'];
}

foreach ($result2 as $key2 => $value2) {

  foreach ($result3 as $key3 => $value3) {
    if($value2['CCDLEAVE'] == $value3['ID']){
      $leave_type = $value3['leave_type'];
    }
  }

  $start_date = new DateTime($value2['DLEAVE']);
  $end_date = new DateTime($value2['DLEAVE2']);
  $end_date->modify('+1 day');

  $interval = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $interval, $end_date);

  $ghour[$key2] = [];
  $counter = 0;
  foreach ($date_range as $date) {
    if($value2['LADVANCE'] == '0'){
      if ($date->format('N') < 6 && !in_array($date->format('Y-m-d'), $gethly)) {

        $nhours = str_split($value2['NHOURS'], 2);
        foreach ($nhours as $nhour) {
          $ghour[$key2][] = $nhour;
        }

        if(in_array($value2['CCDLEAVE'], ['1','14','15','16'])){
          $leaves[] = $date->format('Y-m-d')."|".$value2['CCDLEAVE']."|".$ghour[$key2][$counter++]."|".$value2['LADVANCE']."|".$value2['MNOTES']."|".$leave_type;
        }else{
          $leaves[] = $date->format('Y-m-d')."|".$value2['CCDLEAVE']."|11|".$value2['LADVANCE']."|".$value2['MNOTES']."|".$leave_type;
        }
      }else{
        $leaves[] = $date->format('Y-m-d')."|".$value2['CCDLEAVE']."|11|".$value2['LADVANCE']."|".$value2['MNOTES']."|".$leave_type;
      }
    }else{
      $nhours = str_split($value2['NHOURS'], 2);
      foreach ($nhours as $nhour) {
        $ghour[$key2][] = $nhour;
      }

      if(in_array($value2['CCDLEAVE'], ['1','14','15','16'])){
        $leaves[] = $date->format('Y-m-d')."|".$value2['CCDLEAVE']."|".$ghour[$key2][$counter++]."|".$value2['LADVANCE']."|".$value2['MNOTES']."|".$leave_type;
      }else{
        $leaves[] = $date->format('Y-m-d')."|".$value2['CCDLEAVE']."|11|".$value2['LADVANCE']."|".$value2['MNOTES']."|".$leave_type;
      }
    }
  }

  if($value2['CCDLEAVE'] == '1' && $year == date("Y", strtotime($value2['DLEAVE']))){
    $annual[] = $value2['NDAYS'];
  }
}

$adjustYear = ($year == date("Y") + 1) ? $year - 1 : $year;
foreach ($result4 as $key4 => $value4) {
  if (date("Y", strtotime($value4['DADJUST'])) == $adjustYear) {
    $bf = $value4['NDAYS'];
  }
}

if(date("Y") + 1 != $year){
  foreach ($result5 as $key5 => $value5) {

    $hire = $value5['DHIRE'];
    $staff = $value5['CNAME'];
    $superior = $value5['CSUPERIOR'];
    $supervis = $value5['CSUPERVISO'];
    $hireDate = new DateTime($hire);

    if($superior == $supervis){
      $direct = 1;
    }else{
      $direct = 0;
    }

    $date1 = new DateTime($value5['DHIRE']);
    $date2 = new DateTime();
    $date3 = new DateTime(($year + 1).'-01-01');

    $interval1 = $date1->diff($date2);
    $interval2 = $date1->diff($date3);

    if($year == date('Y', strtotime($value5['DHIRE']))){
      if(date('d', strtotime($value5['DHIRE'])) == '01'){
        $month = 13 - date('m', strtotime($value5['DHIRE'])); 
      }else{
        $finalMonth = date('m') - date('m', strtotime($value5['DHIRE']));
        if($finalMonth > 0){
          $month = $finalMonth - date('m', strtotime($value5['DHIRE']));
        }else{
          $month = 0;
        }
      }
    }else if($year == date('Y')){
      $month = ($interval1->y * 12 + $interval1->m);
    }else{
      $month = $interval2->y * 12 + $interval2->m;
    }

    if(!in_array($value5['CGRADE'], $ngrade)){
      if($month >= 60){
        if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (30/12), 2, '.', ',');
        }else{
          if ($month > 59 && $month < 71) {
            $new = ($month - 60) * (30/12);
            $old = (12 - ($month - 60)) * (24/12);
            $entitle = number_format((float)$new + $old, 2, '.', ',');
          }else{
            $entitle = 30;
          }
        }
      }else{
        if($year == date('Y', strtotime($value5['DHIRE']))){
          $entitle = number_format((float)$month * (24/12), 2, '.', ',');
        }else if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (24/12), 2, '.', ',');
        }else{
          $entitle = 24;
        }
      }
    }else if(in_array($value5['CGRADE'], $ngrade)){
      if($month >= 60){
        if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (21/12), 2, '.', ',');
        }else{
          if ($month > 59 && $month < 71) {
            $new = ($month - 60) * (21/12);
            $old = (12 - ($month - 60)) * (14/12);
            $entitle = number_format((float)$new + $old, 2, '.', ',');
          }else{
            $entitle = 21;
          }
        }
      }else{
        if($year == date('Y', strtotime($value5['DHIRE']))){
          $entitle = number_format((float)$month * (14/12), 2, '.', ',');
        }else if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (14/12), 2, '.', ',');
        }else{
          $entitle = 14;
        }
      }
    }
  }
}else{
  foreach ($result5 as $key5 => $value5) {

    $hire = $value5['DHIRE'];
    $staff = $value5['CNAME'];
    $superior = $value5['CSUPERIOR'];
    $supervis = $value5['CSUPERVISO'];
    $hireDate = new DateTime($hire);

    if($superior == $supervis){
      $direct = 1;
    }else{
      $direct = 0;
    }

    if(($year - 1) != date("Y")){
      $currentDate = new DateTime(($year + 1)."-01-01");
    }else{
      $currentDate = new DateTime(date("Y-m-d"));
    }

    $interval = $hireDate->diff($currentDate);

    $years = $interval->y;
    $months = $interval->m;
    $days = $interval->d;

    if($year == date('Y', strtotime($value5['DHIRE']))){
      if(date('d', strtotime($value5['DHIRE'])) == '01'){
        $month = 13 - date('m', strtotime($value5['DHIRE'])); 
      }else{
        $finalMonth = date('m') - date('m', strtotime($value5['DHIRE']));
        if($finalMonth > 0){
          $month = $finalMonth - date('m', strtotime($value5['DHIRE']));
        }else{
          $month = 0;
        }
      }
    }else if($year == date('Y')){
      $month = ($interval1->y * 12 + $interval1->m);
    }else{
      $month = $interval2->y * 12 + $interval2->m;
    }

    if(!in_array($value5['CGRADE'], $ngrade)){
      if($month >= 60){
        if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (30/12), 2, '.', ',');
        }else{
          if ($month > 59 && $month < 71) {
            $new = ($month - 60) * (30/12);
            $old = (12 - ($month - 60)) * (24/12);
            $entitle = number_format((float)$new + $old, 2, '.', ',');
          }else{
            $entitle = 30;
          }
        }
      }else{
        if($year == date('Y', strtotime($value5['DHIRE']))){
          $entitle = number_format((float)$month * (24/12), 2, '.', ',');
        }else if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (24/12), 2, '.', ',');
        }else{
          $entitle = 24;
        }
      }
    }else if(in_array($value5['CGRADE'], $ngrade)){
      if($month >= 60){
        if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (21/12), 2, '.', ',');
        }else{
          if ($month > 59 && $month < 71) {
            $new = ($month - 60) * (21/12);
            $old = (12 - ($month - 60)) * (14/12);
            $entitle = number_format((float)$new + $old, 2, '.', ',');
          }else{
            $entitle = 21;
          }
        }
      }else{
        if($year == date('Y', strtotime($value5['DHIRE']))){
          $entitle = number_format((float)$month * (14/12), 2, '.', ',');
        }else if($year == date('Y')){
          $currMonth = date("m") - 1;
          $entitle = number_format((float)$currMonth * (14/12), 2, '.', ',');
        }else{
          $entitle = 14;
        }
      }
    }
  }
}

$sql6 = "SELECT employees.EmailAddress AS email, employees_demas.CNAME AS cname, employees_demas.CNOEE AS cnoee FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CJOB = '$superior' AND employees_demas.DRESIGN = '0000-00-00'";
$result6 = $conn->query($sql6);

$sql7 = "SELECT employees_demas.CNOEE AS cnoee FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CJOB = '$supervis' AND employees_demas.DRESIGN = '0000-00-00'";
$result7 = $conn->query($sql7);

if($row6 = $result6->fetch_assoc()){
  $email = $row6['email'];
  $cname = $row6['cname'];
  $mnoee = $row6['cnoee'];
}

if($row7 = $result7->fetch_assoc()){
  $fnoee = $row7['cnoee'];
}

$sql8 = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND (YEAR(DLEAVE) = ".($year - 1)." OR YEAR(DLEAVE2) = ".($year - 1).") ORDER BY DLEAVE ASC"; 
$result8 = $conn->query($sql8);

foreach ($result8 as $key8 => $value8) {
  if ($value8['CCDLEAVE'] == '1') {
    $leaveYear = date("Y", strtotime($value8['DLEAVE']));
    if (($year == $leaveYear && $year != date("Y") + 1) || ($year - 1 == $leaveYear && $year == date("Y") + 1)) {
      $annually[] = $value8['NDAYS'];
    }
  }
}

if(date("Y") + 1 != $year){
  $filter = number_format(($bf + $entitle) - array_sum($annual), 2);
}else{
  $filter = number_format(($bf + $entitle) - (array_sum($annual) + array_sum($annually)), 2);
}

?>