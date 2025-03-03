<?php 

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_POST['emid'];
$year = $_POST['year'];
$shift = $_POST['shift'];

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
<style type="text/css">
  .floating-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
  }

  .floating-button:hover {
    background-color: #2980b9;
  }
</style>
<div class="calendar-container">
  <?php for($month = 1; $month <= 12; $month++) { ?>
  <div id="month-<?php echo $month; ?>" class="month-container">
    <div class="calendar">
      <h3><?php echo date("F Y", strtotime($year."-".$month."-01")); ?></h3>
      <table class="table-responsive">
        <tr>
          <th class="day-header">Mon</th>
          <th class="day-header">Tue</th>
          <th class="day-header">Wed</th>
          <th class="day-header">Thu</th>
          <th class="day-header">Fri</th>
          <th class="day-header weekend">Sat</th>
          <th class="day-header weekend">Sun</th>
        </tr>
        <tr>
          <?php

          $firstDayOfMonth = new DateTime("$year-$month-01");
          $daysInMonth = $firstDayOfMonth->format('t');
          $firstDayOfWeek = $firstDayOfMonth->format('N');

          for ($i = 1; $i < $firstDayOfWeek; $i++) {
            echo "<td></td>";
          }

          $currdate = 1;
          while ($currdate <= $daysInMonth) {

            $dayDate = $year."-".sprintf("%02d", $month)."-".sprintf("%02d", $currdate);

            //weekend
            $dayOfWeek = ($firstDayOfWeek + $currdate - 1) % 7;
            $css = ($dayOfWeek == 6 || $dayOfWeek == 0) ? 'class="weekend '.(($shift == '1') ? 'get_date" style="font-weight: bold"' : '').'" data-apply="'.$dayDate.'" data-read="'.$dayDate.'"' : '';

            //today
            if (date("Y-m-d") == $dayDate) {
              $today = ' blue-gradient text-white';
            }else{
              $today = '';
            }

            //apply leave
            if ($dayOfWeek != 0 && $dayOfWeek != 6) {
              $css = 'class="get_date'.$today.'" style="font-weight: bold" data-apply="'.$dayDate.'" data-read="'.$dayDate.'"';
            }

            //holiday
            foreach ($getdes as $getdec) {
              list($hlDate, $hlDesc) = explode("|", $getdec);
              if ($dayDate == $hlDate) {
                $ph[$currdate] = $hlDate;
                $css = "class='success-color text-white holiday".(($shift == '1') ? ' get_date' : '')."' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$hlDesc}' data-apply='{$dayDate}' data-read='{$dayDate}'";
              }
            }

            //leave
            foreach ($leaves as $leave) {
              list($leaveDate, $leaveCCD, $leaveHour, $leaveAdvance, $leaveStatus, $showCCD) = explode("|", $leave);
              if ($dayDate == $leaveDate) {
                if($leaveStatus == 'approved') {
                  if($leaveAdvance == '0') {
                    if (date('N', strtotime($leaveDate)) < 6 && $leaveDate != $ph[$currdate]) {
                      switch ($leaveCCD) {
                        case 1:
                          if($leaveHour == '11'){
                            $css = "class='amber-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                          }else if($leaveHour == '10'){
                            $css = "style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                          }else if($leaveHour == '01'){
                            $css = "style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(255,179,0) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                          }
                          break;
                        case 14:
                          if($leaveHour == '11'){
                            $css = "class='danger-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                          }else if($leaveHour == '10'){
                            $css = "style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                          }else if($leaveHour == '01'){
                            $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                          }
                          break;
                        case 15:
                          if($leaveHour == '11'){
                            $css = "class='calamity-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                          }else if($leaveHour == '10'){
                            $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                          }else if($leaveHour == '01'){
                            $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                          }
                          break;
                        case 16:
                          if($leaveHour == '11'){
                            $css = "class='other-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                          }else if($leaveHour == '10'){
                            $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                          }else if($leaveHour == '01'){
                            $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                          }
                          break;
                        default:
                          break;
                      }
                    }
                  }else{
                    switch ($leaveCCD) {
                      case 1:
                        if($leaveHour == '11'){
                          $css = "class='amber-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                        }else if($leaveHour == '10'){
                          $css = "style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                        }else if($leaveHour == '01'){
                          $css = "style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(255,179,0) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                        }
                        break;
                      case 14:
                        if($leaveHour == '11'){
                          $css = "class='danger-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                        }else if($leaveHour == '10'){
                          $css = "style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                        }else if($leaveHour == '01'){
                          $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                        }
                        break;
                      case 15:
                        if($leaveHour == '11'){
                          $css = "class='calamity-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                        }else if($leaveHour == '10'){
                          $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                        }else if($leaveHour == '01'){
                          $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                        }
                        break;
                      case 16:
                        if($leaveHour == '11'){
                          $css = "class='other-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                        }else if($leaveHour == '10'){
                          $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                        }else if($leaveHour == '01'){
                          $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                        }
                        break;
                      default:
                        break;
                    }
                  }
                  switch ($leaveCCD) {
                    case 2:
                      $css = "class='yellow-color text-dark' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 3:
                      $css = "class='info-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 4:
                      $css = "class='pink-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 5:
                      $css = "class='cyan-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 6:
                      $css = "class='brown-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 7:
                      $css = "class='grey-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 8:
                      $css = "class='lime-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 9:
                      $css = "class='black-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 10:
                      $css = "class='light-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 11:
                      $css = "class='purple-gradient text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 12:
                      $css = "class='green-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 13:
                      $css = "class='peach-gradient text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      break;
                    case 14:
                      if($leaveHour == '11'){
                        $css = "class='danger-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      }else if($leaveHour == '10'){
                        $css = "style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                      }else if($leaveHour == '01'){
                        $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                      }
                      break;
                    case 15:
                      if($leaveHour == '11'){
                        $css = "class='calamity-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      }else if($leaveHour == '10'){
                        $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                      }else if($leaveHour == '01'){
                        $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                      }
                      break;
                    case 16:
                      if($leaveHour == '11'){
                        $css = "class='other-color text-white' style='font-weight: bold' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD}'";
                      }else if($leaveHour == '10'){
                        $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Morning)'";
                      }else if($leaveHour == '01'){
                        $css = "class='text-white' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%); font-weight: bold;' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-custom-class='custom-tooltip' title='{$showCCD} (Afternoon)'";
                      }
                      break;
                    default:
                      break;
                  }
                }else{
                  switch ($leaveCCD) {
                    case 1:
                      if($leaveHour == '11'){
                        $css = "class='secondary-color text-white delete_leave' data-leave='".$dayDate."' style='font-weight: bold'";
                      }else if($leaveHour == '10'){
                        $css = "class='delete_leave' data-leave='".$dayDate."' style='color: white; background: linear-gradient(180deg, rgb(153,51,204) 0%, rgba(254,254,254,1) 100%); font-weight: bold;'";
                      }else if($leaveHour == '01'){
                        $css = "class='delete_leave' data-leave='".$dayDate."' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(153,51,204) 100%); font-weight: bold;'";
                      }
                      break;
                    case 14:
                      if($leaveHour == '11'){
                        $css = "class='danger-color text-white delete_leave' data-leave='".$dayDate."' style='font-weight: bold'";
                      }else if($leaveHour == '10'){
                        $css = "class='delete_leave' data-leave='".$dayDate."' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%); font-weight: bold;'";
                      }else if($leaveHour == '01'){
                        $css = "class='text-white delete_leave' data-leave='".$dayDate."' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%); font-weight: bold;'";
                      }
                      break;
                    default:
                      break;
                  }
                }
              }
            }

            echo "<td $css>$currdate</td>";

            if ($dayOfWeek == 0) {
              echo "</tr><tr>";
            }

            $currdate++;
          }
          ?>
        </tr>
      </table>
    </div>
  </div>
  <?php } ?>
</div>
<button class="floating-button bg-primary load" id="apply">
  <em class="icon ni ni-save-fill"></em>
</button>
<input id="start" hidden>
<input id="end" hidden>
<input id="final" hidden>
<input id="check" hidden>
<script type="text/javascript">
function formatDate(dateString) {
  var date = new Date(dateString);
  var day = date.getDate();
  var month = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(date);
  var year = date.getFullYear();
  return `${day} ${month} ${year}`;
}

$(document).ready(function(){
  var clickCount = 0;
  var maxClicks = 2;
  $(".get_date").click(function(){
    var get = $(this).attr("data-apply");
    if (clickCount < maxClicks) {
      if (clickCount === 0) {
        $("#start").val(get);
        $(".get_date").removeClass("blue-gradient text-white");
        $(this).addClass("secondary-color text-white");
        $(".holiday").addClass("text-white");
      } else if (clickCount === 1) {
        var end = $("#end").val(get);
        date_run();
      }
      clickCount++;
    }else if(get > $("#end").val()){
      clickCount = 0;
      Swal.fire({
        title: 'RELOADING',
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });
      location.reload();
    }
  });

  function date_run() {
    var dates = new Date($("#start").val());
    var dated = new Date($("#end").val());
    var get_final = []; 
    $(".get_date").each(function () {
      var dateValue = $(this).attr("data-read");
      var dateObject = new Date(dateValue);

      if (dateObject >= dates && dateObject <= dated) {
        $(this).addClass("secondary-color text-white");
        get_final.push(dateValue);
      }
    });
    $("#final").val(get_final.join(','));
    $("#check").val(get_final.length);
  }
});

$(document).ready(function(){
  $("#apply").click(function(){
    var start = $("#start").val();
    var end = $("#end").val();
    var final = $("#final").val();
    var apply = parseInt($("#check").val(), 10);
    var direct = '<?php echo $direct; ?>';
    var balance = '<?php echo $filter; ?>';
    var entitle = '<?php echo $entitle; ?>';
    if (start === '') {
      Swal.fire("Select Date");
    }else{
      if(balance > 0){
        if($("#check").val() == ''){
          Swal.fire({
            title: "Apply Annual Leave?<br>"+formatDate(start),
            html: '<div class="btn-group" role="group" style="margin-bottom: 15px;">' +
                    '<input type="radio" class="btn-check" name="leave" id="leave1" value="10">' +
                    '<label class="btn btn-outline-primary" for="leave1">Morning</label>' +
                    '<input type="radio" class="btn-check" name="leave" id="leave2" value="11" checked>' +
                    '<label class="btn btn-outline-primary" for="leave2">Full Day</label>' +
                    '<input type="radio" class="btn-check" name="leave" id="leave3" value="01">' +
                    '<label class="btn btn-outline-primary" for="leave3">Afternoon</label>' +
                  '</div>' +
                  '<input type="text" class="form-control reason" placeholder="Reason">',
            showCancelButton: true,
            confirmButtonText: 'Proceed',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: function() {
              var add_leave_one = $("input[name='leave']:checked").val();
              var reason = $(".reason").val();
              var cnoee = '<?php echo $emid; ?>';
              var cname = '<?php echo $cname; ?>';
              var staff = '<?php echo $staff; ?>';
              var email = '<?php echo $email; ?>';
              var mnoee = '<?php echo $mnoee; ?>';
              var fnoee = '<?php echo $fnoee; ?>';
              return $.ajax({
                url: "api_main",
                type: 'POST',
                data: {add_leave_one:add_leave_one,start:start,reason:reason,cnoee:cnoee,cname:cname,staff:staff,email:email,mnoee:mnoee,fnoee:fnoee,direct:direct},
                beforeSend: function(){    
                  Swal.fire({
                    title: 'PROCESSING',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                      Swal.showLoading();
                    },
                  });
                },
                success: function(response) {
                  window.location.href = 'leave_pending';
                }
              });
            }
          });
        }else{
          if (apply <= balance) {
            Swal.fire({
              title: 'PROCESSING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
            var form = $('<form action="apply_over_leave?emid=<?php echo $emid; ?>" method="post"></form>');
            var leaveInput = $('<input type="hidden" name="leave" value="' + (end === '' ? start : final) + '">');
            form.append(leaveInput);
            form.appendTo('body').submit();
          }else if(apply > balance){
            Swal.fire({
              title: 'Insufficient Leave',
              html: '<strong>Current Balance: ' + balance + ' Days</strong>',
              didClose: () => {
                Swal.fire({
                  title: 'RELOADING',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
                location.reload();
              },
            });
          }
        }
      }else{
        Swal.fire({
          title: 'Insufficient Leave',
          html: '<strong>Current Balance: ' + balance + ' Days</strong>',
          didClose: () => {
            Swal.fire({
              title: 'RELOADING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
            location.reload();
          },
        });
      }
    }
  });
});

$(document).ready(function(){
  $(".delete_leave").click(function(){
    window.location.href = 'leave_pending';
  });
});

$(document).ready(function(){
  $(".delete_bulk_leave").click(function(){
    window.location.href = 'leave_pending';
  });
});

$(document).ready(function() {
  var choicesItem = $('.choices__item');
  choicesItem.data('deletable', null);
  choicesItem.find('.choices__button').remove();
});
</script>