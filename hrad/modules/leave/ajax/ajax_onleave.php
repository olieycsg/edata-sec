<?php 

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../../api.php');

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

$get_month = $_POST['month'];
$get_year = $_POST['year'];
$get_division = $_POST['division'];

$get_date = $get_year."-".$get_month;
$minus_date_1 = date("Y-m", strtotime("-1 months", strtotime($get_date)));
$minus_date_2 = date("Y-m", strtotime("-2 months", strtotime($get_date)));
$minus_date_3 = date("Y-m", strtotime("-3 months", strtotime($get_date)));
$plus_date = date("Y-m", strtotime("+1 months", strtotime($get_date)));

if($get_division != 'all'){
  $sql = "SELECT * FROM employees_demas WHERE CDIVISION = '$get_division' AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}else{
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}

$sql3 = "SELECT * FROM eleave_publicholiday";
$result3 = $conn->query($sql3);

$sql4 = "SELECT * FROM eleave_leave_type";
$result4 = $conn->query($sql4);

?>
<style type="text/css">
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

  .calamity-color {
    background-color: #0b1952!important;
  }

  .other-color {
    background-color: #cc08c5!important;
  }.
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8 overflow-x-scroll">
      <div class="card-body">

      <style type="text/css">
      [data-staff='on']{
        text-align: center;
        vertical-align: middle;
      }
      </style>

      <?php 

      $year = $_POST['year']."-".$_POST['month'];
      $daysInMonth = date("t", strtotime($year));

      ?>
      <table id="tbl_onleave" class="table table-sm table-hover table-striped">
        <tr>
          <td></td>
          <?php 

          $dayAbbreviations = array(
            "monday" => "M",
            "tuesday" => "T",
            "wednesday" => "W",
            "thursday" => "T",
            "friday" => "F",
            "saturday" => "S",
            "sunday" => "<span style='color: red;'>S</span>"
          );

          for ($day = 1; $day <= 31; $day++) {
            $dayNumber = ($day <= $daysInMonth) ? $day : "";
            
            if ($dayNumber > 0 || $dayNumber != null) {
              $dayDate = $year . "-" . $dayNumber;
              $dt1 = strtotime($dayDate);
              $dt2 = strtolower(date("l", $dt1));
              
              if (isset($dayAbbreviations[$dt2])) {
                echo "<td class='text-center'><b>{$dayAbbreviations[$dt2]}</b></td>";
              }
            }
          }

        ?>
        </tr>
        <?php while($value = $result->fetch_assoc()){ ?>
        <tr class="text-center">
          <td style="font-size: 13px; text-align: left!important;">
            <strong><?php echo $value['CNAME']; ?></strong>
          </td>
          <?php

          $sql5 = "SELECT * FROM eleave WHERE CNOEE = '{$value['CNOEE']}' AND CCDLEAVE IN ('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16') AND DATE_FORMAT(DLEAVE, '%Y-%m') IN ('$minus_date_1', '$minus_date_2', '$minus_date_3', '$get_date', '$plus_date')";

          $result5 = $conn->query($sql5);

          for ($day = 1; $day <= 31; $day++) {
            $dayNumber = ($day <= $daysInMonth) ? $day : "";

            if ($dayNumber > 0 || $dayNumber != null) {
              $dayDate = $year."-".sprintf("%02d", $dayNumber);

              foreach ($result3 as $key3 => $value3) {
                if($value3['dt_holiday'] == $dayDate){
                  $holiday[$day] = $value3['dt_holiday'];
                  $holidayhl[$day] = date("m-d", strtotime($value3['dt_holiday']));
                  $holiday_desc[$day] = $value3['description'];
                  $holiday_type[$day] = $value3['type'];
                }else if(date("m-d", strtotime($value3['dt_holiday'])) == date("m-d", strtotime($dayDate)) && $value3['type'] == 'fixed'){
                  $holiday[$day] = $value3['dt_holiday'];
                  $holidayhl[$day] = date("m-d", strtotime($value3['dt_holiday']));
                  $holiday_desc[$day] = $value3['description'];
                  $holiday_type[$day] = $value3['type'];
                }
              }

              $dt1 = strtotime($dayDate);
              $dt2 = date("l", $dt1);
              $dt3 = strtolower($dt2);

              //WEEKEND
              if($dt3 == "saturday"){
                $datastyle = "class='pointer sec-tooltip' style='background: #ffe5e5' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='Weekend'";
              }else if($dt3 == "sunday"){
                $datastyle = "class='pointer sec-tooltip' style='background: #ffe5e5; color: red;' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='Weekend'";
              }else{
                $datastyle = "class='pointer sec-tooltip'";
              }

              //PUBLIC HOLIDAY
              if($holiday[$day] == $dayDate){
                $datastyle = "class='pointer sec-tooltip bg-success text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$holiday_desc[$day]}'";
              }else if(date("m-d", strtotime($holiday[$day])) == date("m-d", strtotime($dayDate)) && $holiday_type[$day] == 'fixed'){
                $datastyle = "class='pointer sec-tooltip bg-success text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$holiday_desc[$day]}'";
              }

              //TODAY
              if($dayDate == date("Y-m-d")){ 
                $datastyle = "class='zoom pointer sec-tooltip blue-gradient' style='color:white; font-weight: bold;' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='Today'";
              }
              
              //LEAVE SYSTEM
              foreach ($result5 as $key5 => $row5) {

                foreach ($result4 as $key4 => $value4) {
                  if($row5['CCDLEAVE'] == $value4['ID']){
                    $define[$key5] = $value4['leave_type'];
                  }
                }

                //LEAVE WITH 1.0
                if($row5['CNOEE'] == $value['CNOEE'] && $row5['DLEAVE'] == $dayDate && $row5['DLEAVE'] == $row5['DLEAVE2'] && $row5['NDAYS'] == '1.00' && $holidayhl[$day] != date("m-d", strtotime($row5['DLEAVE']))){
                  if($row5['MNOTES'] == 'approved'){
                    switch ($row5['CCDLEAVE']) {
                      case 1:
                        $datastyle = "class='pointer sec-tooltip amber-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 2:
                        $datastyle = "class='pointer sec-tooltip yellow-color text-dark' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 3:
                        $datastyle = "class='pointer sec-tooltip bg-info text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 4:
                        $datastyle = "class='pointer sec-tooltip pink-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 5:
                        $datastyle = "class='pointer sec-tooltip cyan-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 6:
                        $datastyle = "class='pointer sec-tooltip brown-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 7:
                        $datastyle = "class='pointer sec-tooltip grey-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 8:
                        $datastyle = "class='pointer sec-tooltip black-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 9:
                        $datastyle = "class='pointer sec-tooltip black text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 10:
                        $datastyle = "class='pointer sec-tooltip light-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 11:
                        $datastyle = "class='pointer sec-tooltip purple-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 12:
                        $datastyle = "class='pointer sec-tooltip green-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 13:
                        $datastyle = "class='pointer sec-tooltip peach-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 14:
                        $datastyle = "class='pointer sec-tooltip bg-danger text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 15:
                        $datastyle = "class='pointer sec-tooltip calamity-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 16:
                        $datastyle = "class='pointer sec-tooltip other-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      default:
                        break;
                    }
                  }else if($row5['MNOTES'] == 'pending' || $row5['MNOTES'] == 'recommended'){
                    switch ($row5['CCDLEAVE']) {
                      case 1:
                        $datastyle = "class='pointer sec-tooltip secondary-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5]'";
                        break;
                      case 2:
                        $datastyle = "class='pointer sec-tooltip yellow-color text-dark' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 3:
                        $datastyle = "class='pointer sec-tooltip bg-info text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 4:
                        $datastyle = "class='pointer sec-tooltip pink-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 5:
                        $datastyle = "class='pointer sec-tooltip cyan-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 6:
                        $datastyle = "class='pointer sec-tooltip brown-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 7:
                        $datastyle = "class='pointer sec-tooltip grey-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 8:
                        $datastyle = "class='pointer sec-tooltip black-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 9:
                        $datastyle = "class='pointer sec-tooltip black text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 10:
                        $datastyle = "class='pointer sec-tooltip light-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 11:
                        $datastyle = "class='pointer sec-tooltip purple-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 12:
                        $datastyle = "class='pointer sec-tooltip green-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 13:
                        $datastyle = "class='pointer sec-tooltip peach-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      case 14:
                        $datastyle = "class='pointer sec-tooltip bg-danger text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                        break;
                      default:
                        break;
                    }
                  }
                }

                //LEAVE WITH 0.5 (MORNING = 10)
                if($row5['CNOEE'] == $value['CNOEE'] && $row5['DLEAVE'] == $dayDate && $row5['DLEAVE'] == $row5['DLEAVE2'] && $row5['NDAYS'] == '0.50' &&  $row5['NHOURS'] == '10' && $holidayhl[$day] != date("m-d", strtotime($row5['DLEAVE']))){

                  $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";

                  if($row5['MNOTES'] == 'approved'){
                    switch ($row5['CCDLEAVE']) {
                      case 1:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                        break;
                      case 9:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(0,0,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                        break;
                      case 14:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                        break;
                      case 15:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                        break;
                      case 16:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                        break;
                      default:
                        break;
                    }
                  }else if($row5['MNOTES'] == 'pending' || $row5['MNOTES'] == 'recommended'){
                    switch ($row5['CCDLEAVE']) {
                      case 1:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(153,51,204) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5] (MORNING)'";
                        break;
                      default:
                        break;
                    }
                  }
                }

                //LEAVE WITH 0.5 (EVENING = 01)
                if($row5['CNOEE'] == $value['CNOEE'] && $row5['DLEAVE'] == $dayDate && $row5['DLEAVE'] == $row5['DLEAVE2'] && $row5['NDAYS'] == '0.50' &&  $row5['NHOURS'] == '01' && $holidayhl[$day] != date("m-d", strtotime($row5['DLEAVE']))){

                  $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";

                  if($row5['MNOTES'] == 'approved'){
                    switch ($row5['CCDLEAVE']) {
                      case 1:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(255,179,0) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                        break;
                      case 9:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(0,0,0) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                        break;
                      case 14:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                        break;
                      case 15:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                        break;
                      case 16:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                        break;
                      default:
                        break;
                    }
                  }else if($row5['MNOTES'] == 'pending' || $row5['MNOTES'] == 'recommended'){
                    switch ($row5['CCDLEAVE']) {
                      case 1:
                        $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(153,51,204) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5] (EVENING)'";
                        break;
                      default:
                        break;
                    }
                  }
                }

                //LEAVE WITH 1.0 ABOVE
                if($row5['CNOEE'] == $value['CNOEE'] && $row5['DLEAVE'] != $row5['DLEAVE2']){

                  if($row5['DLEAVE'] == $dayDate){
                    $newkey5[] = $key5;
                  }

                  $perioda1 = new DatePeriod(
                    new DateTime($row5['DLEAVE']),
                    new DateInterval('P1D'),
                    new DateTime($row5['DLEAVE2'].'+ 1 day')
                  );

                  foreach ($perioda1 as $keya1 => $valuea1) {

                    $dta1 = strtotime($valuea1->format('Y-m-d'));
                    $dta2 = date("l", $dta1);
                    $dta3 = strtolower($dta2);

                    if($valuea1->format('Y-m-d') == $dayDate && $dta3 != "saturday" && $dta3 != "sunday" && $holidayhl[$day] != $valuea1->format('m-d')){

                      $id5[count($newkey5)][] = $valuea1->format('m-d');

                      $nhoursa1[$key5] = str_split($row5['NHOURS'], 2);
                      foreach ($nhoursa1[$key5] as $keya1a => $valuea1a) {
                        if($keya1a == (count($id5[count($newkey5)]) - 1)){
                          $ida1a[$keya1][count($newkey5)] .= $keya1a;
                          $newvaluea1a[$keya1][count($newkey5)] .= $valuea1a;
                        }
                      }
                    }

                    if($valuea1->format('Y-m-d') == $dayDate && $dta3 != "saturday" && $dta3 != "sunday" && $holidayhl[$day] != $valuea1->format('m-d')){
                      if($row5['MNOTES'] == 'approved'){
                        if($row5['NHOURS'] != '1'){
                          if($newvaluea1a[$keya1][count($newkey5)] == '11'){
                            switch ($row5['CCDLEAVE']) {
                              case 1:
                                $datastyle = "class='pointer sec-tooltip amber-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 2:
                                $datastyle = "class='pointer sec-tooltip yellow-color text-dark' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 3:
                                $datastyle = "class='pointer sec-tooltip bg-info text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 4:
                                $datastyle = "class='pointer sec-tooltip pink-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 5:
                                $datastyle = "class='pointer sec-tooltip cyan-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 6:
                                $datastyle = "class='pointer sec-tooltip brown-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 7:
                                $datastyle = "class='pointer sec-tooltip grey-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 8:
                                $datastyle = "class='pointer sec-tooltip black-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 9:
                                $datastyle = "class='pointer sec-tooltip black text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 10:
                                $datastyle = "class='pointer sec-tooltip light-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 11:
                                $datastyle = "class='pointer sec-tooltip purple-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 12:
                                $datastyle = "class='pointer sec-tooltip green-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 13:
                                $datastyle = "class='pointer sec-tooltip peach-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 14:
                                $datastyle = "class='pointer sec-tooltip bg-danger text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 15:
                                $datastyle = "class='pointer sec-tooltip calamity-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 16:
                                $datastyle = "class='pointer sec-tooltip other-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              default:
                                break;
                            }
                          }else if($newvaluea1a[$keya1][count($newkey5)] == '10'){
                            switch ($row5['CCDLEAVE']) {
                              case 1:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(255,179,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                                break;
                              case 9:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(0,0,0) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                                break;
                              case 14:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(242,50,67) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                                break;
                              case 15:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(11,25,81) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                                break;
                              case 16:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(204,8,197) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (MORNING)'";
                                break;
                              default:
                                break;
                            }
                          }else if($newvaluea1a[$keya1][count($newkey5)] == '01'){
                            switch ($row5['CCDLEAVE']) {
                              case 1:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(255,179,0) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                                break;
                              case 9:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(0,0,0) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                                break;
                              case 14:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(242,50,67) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                                break;
                              case 15:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(11,25,81) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                                break;
                              case 16:
                                $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(204,8,197) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$define[$key5] (EVENING)'";
                                break;
                              default:
                                break;
                            }
                          }else{
                            switch ($row5['CCDLEAVE']) {
                              case 1:
                                $datastyle = "class='pointer sec-tooltip amber-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 2:
                                $datastyle = "class='pointer sec-tooltip yellow-color text-dark' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 3:
                                $datastyle = "class='pointer sec-tooltip bg-info text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 4:
                                $datastyle = "class='pointer sec-tooltip pink-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 5:
                                $datastyle = "class='pointer sec-tooltip cyan-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 6:
                                $datastyle = "class='pointer sec-tooltip brown-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 7:
                                $datastyle = "class='pointer sec-tooltip grey-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 8:
                                $datastyle = "class='pointer sec-tooltip black-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 9:
                                $datastyle = "class='pointer sec-tooltip black text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 10:
                                $datastyle = "class='pointer sec-tooltip light-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 11:
                                $datastyle = "class='pointer sec-tooltip purple-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 12:
                                $datastyle = "class='pointer sec-tooltip green-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 13:
                                $datastyle = "class='pointer sec-tooltip peach-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 14:
                                $datastyle = "class='pointer sec-tooltip bg-danger text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 15:
                                $datastyle = "class='pointer sec-tooltip calamity-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              case 16:
                                $datastyle = "class='pointer sec-tooltip other-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                                break;
                              default:
                                break;
                            }
                          }
                        }else{
                          switch ($row5['CCDLEAVE']) {
                            case 1:
                              $datastyle = "class='pointer sec-tooltip amber-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 2:
                              $datastyle = "class='pointer sec-tooltip yellow-color text-dark' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 3:
                              $datastyle = "class='pointer sec-tooltip bg-info text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 4:
                              $datastyle = "class='pointer sec-tooltip pink-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 5:
                              $datastyle = "class='pointer sec-tooltip cyan-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 6:
                              $datastyle = "class='pointer sec-tooltip brown-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 7:
                              $datastyle = "class='pointer sec-tooltip grey-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 8:
                              $datastyle = "class='pointer sec-tooltip black-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 9:
                              $datastyle = "class='pointer sec-tooltip black text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 10:
                              $datastyle = "class='pointer sec-tooltip light-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 11:
                              $datastyle = "class='pointer sec-tooltip purple-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 12:
                              $datastyle = "class='pointer sec-tooltip green-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 13:
                              $datastyle = "class='pointer sec-tooltip peach-gradient text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 14:
                              $datastyle = "class='pointer sec-tooltip bg-danger text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 15:
                              $datastyle = "class='pointer sec-tooltip calamity-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            case 16:
                              $datastyle = "class='pointer sec-tooltip other-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='{$define[$key5]}'";
                              break;
                            default:
                              break;
                          }
                        }
                      }else if($row5['MNOTES'] == 'pending' || $row5['MNOTES'] == 'recommended'){
                        if($row5['NHOURS'] != '1'){
                          if($newvaluea1a[$keya1][count($newkey5)] == '11'){
                            $datastyle = "class='pointer sec-tooltip secondary-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5]'";
                          }else if($newvaluea1a[$keya1][count($newkey5)] == '10'){
                            $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgb(153,51,204) 0%, rgba(254,254,254,1) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5] (MORNING)'";
                          }else if($newvaluea1a[$keya1][count($newkey5)] == '01'){
                            $datastyle = "class='pointer sec-tooltip' style='color: white; background: linear-gradient(180deg, rgba(254,254,254,1) 0%, rgb(153,51,204) 100%);' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5] (EVENING)'";
                          }else{
                            $datastyle = "class='pointer sec-tooltip secondary-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5]'";
                          }
                        }else{
                          $datastyle = "class='pointer sec-tooltip secondary-color text-white' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='PENDING APPLICATION $define[$key5]'";
                        }
                      }
                    }
                  }
                }
              }
              echo "<td data-staff='on' {$datastyle}>{$dayNumber}</td>";
            }
          }

          ?>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
</script>