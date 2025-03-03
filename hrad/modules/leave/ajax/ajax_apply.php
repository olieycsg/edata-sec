<?php 

include('../../../../api.php');
date_default_timezone_set("Asia/Kuala_Lumpur");

$emid = $_POST['emid'];
$year = $_POST['year'];

$gethly = [];
$getdes = [];
$geturl = [];
$leaves = [];
$types = [];
$count_url = [];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM eleave_publicholiday WHERE YEAR(dt_holiday) = '$year' OR type = 'fixed' ORDER BY dt_holiday ASC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND (YEAR(DLEAVE) = '$year' OR YEAR(DLEAVE2) = '$year') ORDER BY DLEAVE ASC";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM eleave_leave_type";
$result3 = $conn->query($sql3);

if($row = $result->fetch_assoc()){
  $cname = $row['CNAME'];
  $dhire = $row['DHIRE'];
  $cshift = $row['CSHIFT'];
  $division = $row['CDIVISION'];

  if($row['CSEX'] == 'M'){
    $csex = '2';
  }else{
    $csex = '10';
  }
}

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

  if($value2['CCDLEAVE'] == '14'){
    $count_url[] = $value2['NDAYS'];
  }
}

foreach ($result3 as $key3 => $value3) {
  $types[] = $value3['ID']."|".$value3['leave_type'];
}

foreach ($gethly as $urlval) {
  if(date("N", strtotime($urlval)) == 6) {
    $geturl[] = $urlval;
  }
  $sfturl[] = $urlval;
}

sort($geturl);
sort($sfturl);

?>
<style type="text/css">
  .text-left{
    text-align: left;
  }
  .text-right{
    text-align: right;
  }
  .text-center{
    text-align: center;
  }
  .pointer{
    cursor: cursor;
  }
  .amber-color {
    background-color: #ffb300!important;
  }

  .yellow-color {
    background-color: #ff0!important;
  }

  .info-color {
    background-color: #33b5e5!important;
  }

  .pink-color {
    background-color: #e91e63!important;
  }

  .cyan-color {
    background-color: #006064!important;
  }

  .brown-color{
    background-color: #3e2723!important;
  }

  .grey-color{
    background-color: #424242!important;
  }

  .lime-color{
    background-color: #aeea00!important;
  }

  .black-color{
    background-color: #000000!important;
  }

  .light-color{
    background-color: #01579b!important;
  }

  .purple-gradient {
    background: linear-gradient(40deg,#ff6ec4,#7873f5) !important;
  }

  .green-color{
    background-color: #00e676!important;
  }

  .peach-gradient {
    background: linear-gradient(40deg,#ffd86f,#fc6262) !important;
  }

  .danger-color {
    background-color: #ff3547!important;
  }

  .success-color {
    background-color: #00c851!important;
  }

  .secondary-color {
    background-color: #93c!important;
  }

  .blue-gradient {
    background: linear-gradient(40deg,#45cafc,#303f9f)!important;
  }

  .weekend {
    color: #ff3547!important;
  }

  .calamity-color{
    background-color: #0b1952!important;
  }

  .other-color{
    background-color: #cc08c5!important;
  }

  #tbl_public_holiday th, td {
    white-space: nowrap;
    padding: 5px!important;
  }

  .day:nth-child(7n), .day:nth-child(7n+1){
    background: #ffe5e5;
  }

  .day:nth-child(7n+1){
    color: red;
  }

  .daytitle:nth-child(7n+1){
    color: red!important;
  }

  .new_holiday:hover{
    background: blue;
    color: white;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 overflow-x-scroll">
            <table class="table table-sm table-hover table-striped no-wrap">
              <thead class="bg-primary">
                <tr class="text-center">
                  <th></th>
                  <?php
                  $headings = ["S","M","T","W","T","F","S"];
                  for($x = 1; $x <= 37; $x++) { 
                    $title = ($headings[($x % 7) ]);
                    echo "<td class='daytitle text-center'><b>{$title}</b></td>";
                  }
                  ?>
                </tr>
              </thead>
              <?php
              for($month = 1; $month <= 12; $month++) {
                $thisMonth   = new DateTime("{$year}-{$month}");
                $daysInMonth = $thisMonth->format("t");
                $monthName   = $thisMonth->format("M");
              ?>
              <tr class="text-center">
                <?php

                echo "<td style='text-transform:uppercase'><b>{$monthName}</b></td>";
                $dayOffsetArray = ["Monday" => 0, "Tuesday" => 1, "Wednesday" => 2, "Thursday" => 3, "Friday" => 4, "Saturday" => 5, "Sunday" => 6];

                $offset = $dayOffsetArray[$thisMonth->format("l")];
                for ($i = 0; $i < $offset; $i++){
                  echo "<td class='day'></td>";
                }

                for ($day = 1; $day <= 37 - $offset; $day++) {
                  
                  $dayNumber = ($day <= $daysInMonth) ? $day : "";
                  $currdate = $year."-".sprintf("%02d", $month)."-".sprintf("%02d", $dayNumber);

                  $style = "class='day text-center zoom pointer sec-tooltip update_setup' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.edit_setup' data-start='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='START DATE'";

                  //today
                  if ($currdate == date("Y-m-d")) {
                    $style = "class='day text-center blue-gradient text-white zoom pointer sec-tooltip update_setup' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.edit_setup' data-start='{$currdate}' style='font: 700 15px system-ui;' data-mdb-tooltip-init data-mdb-placement='top' title='TODAY'";
                  }

                  //holiday
                  foreach ($getdes as $getdec) {
                    list($hlDate, $hlDesc) = explode("|", $getdec);
                    if ($currdate == $hlDate) {
                      $ph[$currdate] = $hlDate;
                      $style = "class='day text-center success-color text-white zoom pointer sec-tooltip update_setup' style='font: 700 15px system-ui;' data-mdb-tooltip-init data-mdb-placement='top' title='{$hlDesc}' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.edit_setup' data-start='{$hlDate}'";
                    }
                  }

                  //leave
                  foreach ($leaves as $leave) {
                    list($leaveDate, $leaveCCD, $leaveHour, $leaveAdvance, $leaveStatus, $showCCD) = explode("|", $leave);
                    if ($currdate == $leaveDate) {
                      if($leaveStatus == 'approved') {
                        if($leaveAdvance == '0') {
                          if (date('N', strtotime($leaveDate)) < 6 && $leaveDate != $ph[$currdate]) {
                            switch ($leaveCCD) {
                              case 1:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk day amber-color text-white zoom pointer sec-tooltip update_leave' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(255,179,0) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                                }
                                break;
                              case 14:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave bg-danger text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                                }
                                break;
                              case 15:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave calamity-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                                }
                                break;
                              case 16:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave other-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                                }
                                break;
                              default:
                                break;
                            }
                          }else{
                            switch ($leaveCCD) {
                              case 1:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk day amber-color text-white zoom pointer sec-tooltip update_leave' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(255,179,0) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                                }
                                break;
                              case 14:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave bg-danger text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                                }
                                break;
                              case 15:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave calamity-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                                }
                                break;
                              case 16:
                                if($leaveHour == '11'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave other-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                                }else if($leaveHour == '10'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                                }else if($leaveHour == '01'){
                                  $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
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
                                $style = "class='delete_bulk day amber-color text-white zoom pointer sec-tooltip update_leave' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                              }else if($leaveHour == '10'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                              }else if($leaveHour == '01'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(255,179,0) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                              }
                              break;
                            case 14:
                              if($leaveHour == '11'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave bg-danger text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                              }else if($leaveHour == '10'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                              }else if($leaveHour == '01'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                              }
                              break;
                            case 15:
                              if($leaveHour == '11'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave calamity-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                              }else if($leaveHour == '10'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                              }else if($leaveHour == '01'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                              }
                              break;
                            case 16:
                              if($leaveHour == '11'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave other-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                              }else if($leaveHour == '10'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                              }else if($leaveHour == '01'){
                                $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                              }
                              break;
                            default:
                              break;
                          }
                        }
                        switch ($leaveCCD) {
                          case 2:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave yellow-color text-dark' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 3:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave info-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 4:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave pink-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 5:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave cyan-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 6:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave brown-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 7:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave grey-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 8:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave lime-color text-dark' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 9:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave black-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 10:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave light-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 11:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave purple-gradient text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 12:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave green-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 13:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave peach-gradient text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            break;
                          case 14:
                            if($leaveHour == '11'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave bg-danger text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            }else if($leaveHour == '10'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                            }else if($leaveHour == '01'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                            }
                            break;
                          case 15:
                            if($leaveHour == '11'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave calamity-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            }else if($leaveHour == '10'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                            }else if($leaveHour == '01'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                            }
                            break;
                          case 16:
                            if($leaveHour == '11'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave other-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD}'";
                            }else if($leaveHour == '10'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (MORNING)'";
                            }else if($leaveHour == '01'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='{$showCCD} (EVENING)'";
                            }
                            break;
                          default:
                            break;
                        }
                      }else{
                        switch ($leaveCCD) {
                          case 1:
                            if($leaveHour == '11'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave secondary-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            }else if($leaveHour == '10'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(153,51,204) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (MORNING)'";
                            }else if($leaveHour == '01'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(153,51,204) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (EVENING)'";
                            }
                            break;
                          case 2:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave yellow-color text-dark' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 3:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave info-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 4:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave pink-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 5:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave cyan-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 6:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave brown-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 7:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave grey-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 8:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave lime-color text-dark' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 9:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave black-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 10:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave light-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 11:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave purple-gradient text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 12:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave green-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 13:
                            $style = "class='delete_bulk pointer sec-tooltip update_leave peach-gradient text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            break;
                          case 14:
                            if($leaveHour == '11'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave bg-danger text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            }else if($leaveHour == '10'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (MORNING)'";
                            }else if($leaveHour == '01'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (EVENING)'";
                            }
                            break;
                          case 15:
                            if($leaveHour == '11'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave calamity-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            }else if($leaveHour == '10'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (MORNING)'";
                            }else if($leaveHour == '01'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (EVENING)'";
                            }
                            break;
                          case 16:
                            if($leaveHour == '11'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave other-color text-white' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD}'";
                            }else if($leaveHour == '10'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (MORNING)'";
                            }else if($leaveHour == '01'){
                              $style = "class='delete_bulk pointer sec-tooltip update_leave' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%);' data-mdb-ripple-init data-mdb-modal-init data-mdb-target='.leave_setup' data-delete='{$currdate}' data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION : {$showCCD} (EVENING)'";
                            }
                            break;
                          default:
                            break;
                        }
                      }
                    }
                  }

                  echo "<td {$style}>{$dayNumber}</td>";
                } 
                ?>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade edit_setup" data-mdb-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <b><i class="fas fa-user-tie"></i> <?php echo $cname; ?></b>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4" style="padding: 10px;">
            <select id="leave_type" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result3 as $row3){ ?>
              <option value="<?php echo $row3['ID']; ?>" data-mdb-icon="../img/icon.png" <?php if($row3['ID'] == $csex){ echo "disabled"; } ?>>
                <?php echo $row3['leave_type']; ?>
              </option>
              <?php } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Leave Type</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="start" type="date" class="form-control active" disabled>
              <label class="form-label text-primary">
                <b>Start Date</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="end" type="date" class="form-control active">
              <label class="form-label text-primary">
                <b>End Date</b>
              </label>
            </div>
          </div>
          <div class="col-md-8" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="reason" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Reason</b>
              </label>
            </div>
          </div>
          <div class="col-md-4 view_shift" style="padding: 10px;">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="shift" <?php echo $division != 'ENG' ? "disabled" : ""; ?>>
              <label class="form-check-label" for="shift">Shift Worker (Energy Only)</label>
            </div>
          </div>
          <div class="col-md-4 view_upload" style="padding: 10px;">
            <input type="file" class="form-control control" id="upload_file">
          </div>
          <div class="col-md-4 view_url" style="padding: 10px;">
            <select id="url_type" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
              <option value="" disabled selected>- Select -</option>
              <?php

              if($cshift == '0'){
                foreach($geturl as $urlkey => $url){
                  foreach ($result1 as $key1 => $value1) {
                    if(date("Y-m-d", strtotime($url)) == $year."-".date("m-d", strtotime($value1['dt_holiday']))){
                      $urldesc = $value1['description'];
                    }
                  }

              ?>
                <option value="<?php echo $url; ?>" data-mdb-icon="../img/icon.png" <?php if(($urlkey + 1) == array_sum($count_url)){ echo "disabled"; } if($url > date("Y-m-d")){ echo "disabled"; } ?>>
                  <?php echo $urldesc; ?>
                </option>
              <?php 
                } 
              }else{ 
                foreach($sfturl as $sftkey => $sft){
                  foreach ($result1 as $key1 => $value1) {
                    if(date("Y-m-d", strtotime($sft)) == $year."-".date("m-d", strtotime($value1['dt_holiday']))){
                      $sftdesc = $value1['description'];
                    }
                  }
              ?>
                <option value="<?php echo $sft; ?>" data-mdb-icon="../img/icon.png" <?php if(($sftkey + 1) <= array_sum($count_url)){ echo "disabled"; } if($sft > date("Y-m-d")){ echo "disabled"; } ?>>
                  <?php echo $sftdesc; ?>
                </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>URL Record</b>
            </label>
          </div>
          <span id="alert_upload"></span>
        </div>
        <div class="row text-center apply_loader" style="margin-top: 10px;">
          <div class="col-md-12">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
          </div>
        </div>
        <span id="show_status"></span>
      </div>
      <div class="modal-footer">
        <button id="apply_leave" class="btn btn-primary" disabled>
          <b><i class="fas fa-floppy-disk"></i> Apply</b>
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade leave_setup" data-mdb-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <b><i class="fas fa-user-tie"></i> <?php echo $cname; ?></b>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span id="show_update"></span>
      </div>        
      <div class="modal-footer">
        <button id="update_leave" class="btn btn-success">
          <b><i class="fas fa-floppy-disk"></i> Update Leave</b>
        </button>
        <button id="delete_leave" class="btn btn-danger">
          <b><i class="fas fa-trash-can"></i> Delete Leave</b>
        </button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(".apply_loader").hide();
$(".view_url").hide();
$(".view_shift").hide();
$(".view_upload").hide();

/*Leave Modal*/
$(document).ready(function() {
  $(".update_setup").click(function(){
    var target = $(this).data('mdb-target');
    $(target).modal('show');
    $("#appy_leave").attr('disabled', true);
    $("#start").val($(this).data('start'));
    $("#end").val($(this).data('start'));
    $('#end').attr('min', $(this).data('start'));
  });
});

/*Leave Type*/
$(document).ready(function() {
  $("#leave_type").change(function(){
    var type = $(this).val();
    if(type == '1'){

      $(".view_url").hide();
      $(".view_shift").show();
      $(".view_upload").hide();
      $("#alert_upload").hide();
      var shift = $("#shift").is(":checked") ? 1 : 0;
      $("#end").val($("#start").val()).attr('disabled', false);
    
    }else if(type == '2'){

      $(".view_url").hide();
      $(".view_shift").hide();
      $(".view_upload").show();
      $("#alert_upload").html('<span class="badge badge-danger"><b><i class="fas fa-exclamation"></i> Upload Birth Certificate (jpg/jpeg/png)</b></span>').show();

      var start = new Date($("#start").val());
      start.setDate(start.getDate() + 97);
      var end = start.toISOString().split('T')[0];
      $("#end").val(end).attr('disabled', true);

    }else if(type == '10'){

      $(".view_url").hide();
      $(".view_shift").hide();
      $(".view_upload").show();
      $("#alert_upload").html('<span class="badge badge-danger"><b><i class="fas fa-exclamation"></i> Upload Birth Certificate (jpg/jpeg/png)</b></span>').show();

      var start = new Date($("#start").val());
      start.setDate(start.getDate() + 6);
      var end = start.toISOString().split('T')[0];
      $("#end").val(end).attr('disabled', true);

    }else if(type == '11'){

      $(".view_url").hide();
      $(".view_shift").hide();
      $(".view_upload").hide();
      $("#alert_upload").hide();

      var start = new Date($("#start").val());
      start.setDate(start.getDate() + 39);
      var end = start.toISOString().split('T')[0];
      $("#end").val(end).attr('disabled', true);

    }else if(type == '12'){

      $(".view_url").hide();
      $(".view_shift").hide();
      $(".view_upload").show();
      $("#alert_upload").html('<span class="badge badge-danger"><b><i class="fas fa-exclamation"></i> Upload Marriage Certificate (jpg/jpeg/png)</b></span>').show();

      var start = new Date($("#start").val());
      start.setDate(start.getDate() + 4);
      var end = start.toISOString().split('T')[0];
      $("#end").val(end).attr('disabled', true);

    }else if(type == '3' || type == '4' || type == '5'){

      $(".view_url").hide();
      $(".view_shift").hide();
      $(".view_upload").show();
      $("#alert_upload").html('<span class="badge badge-danger"><b><i class="fas fa-exclamation"></i> Upload Medical Certificate (jpg/jpeg/png)</b></span>').show();
      $("#end").val($("#start").val()).attr('disabled', false);

    }else if(type == '14'){

      $(".view_url").show();
      $(".view_shift").hide();
      $(".view_upload").hide();
      $("#alert_upload").hide();
      $("#end").val($("#start").val()).attr('disabled', true);

    }else if(type == '15' || type == '16'){

      $(".view_url").hide();
      $(".view_shift").hide();
      $(".view_upload").hide();
      $("#alert_upload").hide();
      $("#end").val($("#start").val()).attr('disabled', false);

    }else{

      $(".view_url").hide();
      $(".view_shift").hide();
      $(".view_upload").hide();
      $("#alert_upload").hide();
      $("#end").val($("#start").val()).attr('disabled', false);
    }

    $.ajax({
      url: "modules/leave/ajax/ajax_check",
      type: "POST",
      data: {
        type: type,
        start: $("#start").val(),
        end: $("#end").val(),
        url: $("#url_type").val(),
        emid: '<?php echo $emid; ?>',
        year: '<?php echo $year; ?>',
        shift: shift
      },
      beforeSend: function() {    
        $(".apply_loader").show();
        $("#show_status").hide();
      },
      success: function(response) {
        $(".apply_loader").hide();
        $("#show_status").html(response).show();
      },
    });
  });
});

/*URL Leave*/
$(document).ready(function() {
  $("#url_type").change(function(){
    var url = $(this).val();
    var start = $("#start").val();
    var end = $("#end").val();

    if(url > start){
      var currentDate = new Date(url);
      currentDate.setDate(currentDate.getDate() + 1);
      var formattedDate = currentDate.toISOString().split('T')[0];
      $("#start").val(formattedDate).attr("min", formattedDate).attr('disabled', false);
      $("#end").val(formattedDate);
    }else{
      var currentDate = new Date(url);
      currentDate.setDate(currentDate.getDate() + 1);
      var formattedDate = currentDate.toISOString().split('T')[0];
      $("#start").attr("min", formattedDate).attr('disabled', false);
    }

    $.ajax({
      url: "modules/leave/ajax/ajax_check",
      type: "POST",
      data: {
        type: $("#leave_type").val(),
        start: $("#start").val(),
        end: $("#end").val(),
        url: $("#url_type").val(),
        cnt: '<?php echo array_sum($count_url); ?>',
        emid: '<?php echo $emid; ?>',
        year: '<?php echo $year; ?>'
      },
      beforeSend: function() {    
        $(".apply_loader").show();
        $("#show_status").hide();
      },
      success: function(response) {
        $(".apply_loader").hide();
        $("#show_status").html(response).show();
      },
    });
  });
});

/*Start Date*/
$(document).ready(function() {
  $("#start").blur(function(){
    $(".apply_loader").hide();
    $("#end").val($(this).val());
    $.ajax({
      url: "modules/leave/ajax/ajax_check",
      type: "POST",
      data: {
        type: $("#leave_type").val(),
        start: $(this).val(),
        end: $("#end").val(),
        url: $("#url_type").val(),
        emid: '<?php echo $emid; ?>',
        year: '<?php echo $year; ?>'
      },
      beforeSend: function() {    
        $(".apply_loader").show();
        $("#show_status").hide();
      },
      success: function(response) {
        $(".apply_loader").hide();
        $("#show_status").html(response).show();
      },
    });
  });
});

/*End Date*/
$(document).ready(function() {
  $("#end").blur(function(){
    $(".apply_loader").hide();
    var shift = $("#shift").is(":checked") ? 1 : 0;
    $.ajax({
      url: "modules/leave/ajax/ajax_check",
      type: "POST",
      data: {
        type: $("#leave_type").val(),
        start: $("#start").val(),
        end: $(this).val(),
        emid: '<?php echo $emid; ?>',
        year: '<?php echo $year; ?>',
        shift: shift
      },
      beforeSend: function() {    
        $(".apply_loader").show();
        $("#show_status").hide();
      },
      success: function(response) {
        $(".apply_loader").hide();
        $("#show_status").html(response).show();
      },
    });
  });
});

/*Sec Tooltip*/
$(document).ready(function() {
  $(".sec-tooltip").click(function(){
    $(".apply_loader").hide();
    var type = $("#leave_type").val();
    if(type == '1'){
      $(".view_shift").show();
      $(".view_upload").hide();
      $("#alert_upload").hide();
      var shift = $("#shift").is(":checked") ? 1 : 0;
      $("#end").val($("#start").val()).attr('disabled', false);
      $.ajax({
        url: "modules/leave/ajax/ajax_check",
        type: "POST",
        data: {
          type: type,
          start: $("#start").val(),
          end: $("#end").val(),
          emid: '<?php echo $emid; ?>',
          year: '<?php echo $year; ?>',
          shift: shift
        },
        beforeSend: function() {    
          $(".apply_loader").show();
          $("#show_status").hide();
        },
        success: function(response) {
          $(".apply_loader").hide();
          $("#show_status").html(response).show();
        },
      });
    }
  });
});

/*Update Leave Class*/
$(document).ready(function() {
  $(".update_leave").click(function(){
    $(".apply_loader").hide();
    $.ajax({
      url: "modules/leave/ajax/ajax_update",
      type: "POST",
      data: {
        run_update: $(this).attr('data-delete'),
        emid: '<?php echo $emid; ?>',
        year: '<?php echo $year; ?>',
        divi: '<?php echo $division; ?>'
      },
      beforeSend: function() {    
        $(".apply_loader").show();
        $("#show_update").hide();
      },
      success: function(response) {
        $(".apply_loader").hide();
        $("#show_update").html(response).show();
      },
    });
  });
});

/*Shift Worker*/
$(document).ready(function() {
  $("#shift").click(function(){
    var shift = $(this).is(":checked") ? 1 : 0;
    $.ajax({
      url: "modules/leave/ajax/ajax_check",
      type: "POST",
      data: {
        type: $("#leave_type").val(),
        start: $("#start").val(),
        end: $("#end").val(),
        emid: '<?php echo $emid; ?>',
        year: '<?php echo $year; ?>',
        shift: shift
      },
      beforeSend: function() {    
        $(".apply_loader").show();
        $("#show_status").hide();
      },
      success: function(response) {
        $(".apply_loader").hide();
        $("#show_status").html(response).show();
      },
    });
  });
});

/*Apply Leave*/
$(document).ready(function() {
  $("#apply_leave").click(function(){
    var type = $("#leave_type").val();
    if(type == '1' || type == '14' || type == '15' || type == '16'){
      var shift = $("#shift").is(":checked") ? 1 : 0;
      $.ajax({
        url: "modules/leave/api_main",
        type: "POST",
        data: {
          add_leave: '<?php echo $emid; ?>',
          type: $("#leave_type").val(),
          start: $("#start").val(),
          end: $("#end").val(),
          hour: $("#get_hour").val(),
          days: $("#get_days").val(),
          reason: $("#reason").val(),
          shift: shift
        },
        beforeSend: function() {    
          $(".apply_loader").show();
          $("#show_status").hide();
        },
        success: function(response) {
          $.ajax({
            url: "modules/leave/ajax/ajax_apply",
            type: "POST",
            data: {
              emid: $("#employee").val(),
              year: $("#year").val()
            },
            beforeSend: function() { 
              $(".main_loader").show();
              $(".no_data").hide();
            },
            success: function(response) {
              Swal.close();
              sec_remove();
              $(".no_data").hide();
              $(".main_loader").hide();
              $("#show_leave").html(response).show();
            },
          });
        },
      });
    }else if(type == '6' || type == '7' || type == '8' || type == '9' || type == '11' || type == '13'){
      $.ajax({
        url: "modules/leave/api_main",
        type: "POST",
        data: {
          add_leave: '<?php echo $emid; ?>',
          type: $("#leave_type").val(),
          start: $("#start").val(),
          end: $("#end").val(),
          days: $("#get_days").val(),
          reason: $("#reason").val()
        },
        beforeSend: function() {    
          $(".apply_loader").show();
          $("#show_status").hide();
        },
        success: function(response) {
          $.ajax({
            url: "modules/leave/ajax/ajax_apply",
            type: "POST",
            data: {
              emid: $("#employee").val(),
              year: $("#year").val()
            },
            beforeSend: function() { 
              $(".main_loader").show();
              $(".no_data").hide();
            },
            success: function(response) {
              Swal.close();
              sec_remove();
              $(".no_data").hide();
              $(".main_loader").hide();
              $("#show_leave").html(response).show();
            },
          });
        },
      });
    }else{
      var file = $("#upload_file").val();
      if(file == ''){
        Swal.fire("Upload File");
      }else{

        var formData = new FormData();
        var file = $('#upload_file')[0].files[0];

        var img = new Image();
        img.onload = function () {
          var canvas = document.createElement('canvas');
          var ctx = canvas.getContext('2d');
          var MAX_WIDTH = 768;
          var MAX_HEIGHT = 1024;
          var width = img.width;
          var height = img.height;

          var scaleFactor = Math.min(MAX_WIDTH / width, MAX_HEIGHT / height);
          width *= scaleFactor;
          height *= scaleFactor;

          canvas.width = MAX_WIDTH;
          canvas.height = MAX_HEIGHT;

          ctx.fillStyle = 'rgb(255,255,255)';
          ctx.fillRect(0, 0, canvas.width, canvas.height);

          var x = (MAX_WIDTH - width) / 2;
          var y = (MAX_HEIGHT - height) / 2;

          ctx.drawImage(img, x, y, width, height);
          canvas.toBlob(function (blob) {

            formData.append('add_leave', '<?php echo $emid; ?>');
            formData.append('type', type);
            formData.append('start', $("#start").val());
            formData.append('end', $("#end").val());
            formData.append('days', $("#get_days").val());
            formData.append('reason', $("#reason").val());
            formData.append('file', file);

            $.ajax({
              url: "modules/leave/api_main",
              type: 'POST',
              data: formData,
              contentType: false,
              processData: false,
              beforeSend: function () {
                Swal.fire({
                  title: 'SAVING',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
              },
              success: function(response) {
                $.ajax({
                  url: "modules/leave/ajax/ajax_apply",
                  type: "POST",
                  data: {
                    emid: $("#employee").val(),
                    year: $("#year").val()
                  },
                  beforeSend: function() { 
                    $(".main_loader").show();
                    $(".no_data").hide();
                  },
                  success: function(response) {
                    Swal.close();
                    sec_remove();
                    $(".no_data").hide();
                    $(".main_loader").hide();
                    $("#show_leave").html(response).show();
                  },
                });
              }
            });
          });
        };
        img.src = URL.createObjectURL(file);
      }
    }
  });
});

/*File Check*/
$(document).ready(function() {
  $("#upload_file").change(function(){
    var file = $(this)[0].files[0];
    if (file) {
      var fileType = file.type;
      var validExtensions = ['image/jpg', 'image/jpeg', 'image/png'];
      if ($.inArray(fileType, validExtensions) == -1) {
        Swal.fire('Only jpg/jpeg/png allowed');
        $(this).val('');
      }
    }
  });
});

/*DB Update Leave*/
$(document).ready(function() {
  $("#update_leave").click(function(){
    $.ajax({
      url: "modules/leave/api_main",
      type: "POST",
      data: {
        update_leave: $("#edit_id").val(),
        reason: $("#ereason").val(),
        type: $("#edit_type").val(),
        hour: $("#edit_hour").val(),
        days: $("#edit_days").val()
      },
      beforeSend: function() {    
        Swal.fire({
          title: 'PLEASE WAIT',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function(response) {
        $.ajax({
          url: "modules/leave/ajax/ajax_apply",
          type: "POST",
          data: {
            emid: $("#employee").val(),
            year: $("#year").val()
          },
          beforeSend: function() {
            $(".main_loader").show();
            $(".no_data").hide();
            Swal.fire({
              title: 'UPDATING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response) {
            Swal.close();
            sec_remove();
            $(".no_data").hide();
            $(".main_loader").hide();
            $("#show_leave").html(response).show();
          },
        });
      },
    });
  });
});

/*Delete Leave*/
$(document).ready(function() {
  $("#delete_leave").click(function(){
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<strong>YOU WON'T BE ABLE TO REVERT THIS</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "modules/leave/api_main",
          type: "POST",
          data: {
            delete_leave: $("#edit_id").val()
          },
          beforeSend: function() {    
            Swal.fire({
              title: 'PLEASE WAIT',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response) {
            $.ajax({
              url: "modules/leave/ajax/ajax_apply",
              type: "POST",
              data: {
                emid: $("#employee").val(),
                year: $("#year").val()
              },
              beforeSend: function() {    
                Swal.fire({
                  title: 'DELETING',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
              },
              success: function(response) {
                Swal.close();
                sec_remove();
                $("#show_leave").html(response).show();
              },
            });
          },
        });
      }
    });
  });
});
</script>
