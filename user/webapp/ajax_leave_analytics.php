<?php

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_POST['emid'];
$year = $_POST['year'];

if(date("Y") == $year){
  $years = $_POST['year'] + 1;
}

$annual = [];
$annually = [];

$sql1 = "SELECT * FROM eleave_publicholiday";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave_leave_type";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year'";
$result3 = $conn->query($sql3);

$sql4 = "SELECT * FROM eleave_fxleavea WHERE CNOEE = '$emid'";
$result4 = $conn->query($sql4);

$sql5 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result5 = $conn->query($sql5);

foreach ($result3 as $key3 => $value3) {
  if($value3['CCDLEAVE'] == '1' && $year == date("Y", strtotime($value3['DLEAVE']))){
    $annual[] = $value3['NDAYS'];
  }

  if(date("Y") == $year){
    if($value3['CCDLEAVE'] == '1' && ($year + 1) == date("Y", strtotime($value3['DLEAVE']))){
      $annually[] = $value3['NDAYS'];
    }
  }
}

foreach ($result4 as $key4 => $value4) {
  if(date("Y", strtotime($value4['DADJUST'])) == $year){
    $bf = $value4['NDAYS'];
  }
}

$anval = isset($annual) ? array_sum($annual) : 0;
$bfval = isset($bf) ? $bf : 0;
$ngrade = ['NE1','NE2','NE3','NE4','NE5','NE6'];

foreach ($result5 as $key5 => $value5) {

  $hire = $value5['DHIRE'];
  $hireDate = new DateTime($hire);

  if($year != date("Y")){
    $currentDate = new DateTime(($year + 1)."-01-01");
  }else{
    $currentDate = new DateTime(date("Y-m-d"));
  }

  $interval = $hireDate->diff($currentDate);

  $years = $interval->y;
  $months = $interval->m;
  $days = $interval->d;

  $date1 = new DateTime($value5['DHIRE']);
  $date2 = new DateTime();
  $date3 = new DateTime(($year + 1).'-01-01');

  $interval1 = $date1->diff($date2);
  $interval2 = $date1->diff($date3);

  if($year == date('Y', strtotime($value5['DHIRE']))){
    if(date('d', strtotime($value5['DHIRE'])) == '01'){
      if(date('m', strtotime($value5['DHIRE'])) == date("m")){
        $month = 0;
      }else{
        $month = 13 - date('m', strtotime($value5['DHIRE'])); 
      }
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
      $entcf = 30;
      if($year == date('Y')){
        $entitle = number_format((float)(date("m") - 1) * (30/12), 2, '.', ',');
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
      $entcf = 24;
      if($year == date('Y', strtotime($value5['DHIRE'])) && $year != date('Y')){
        $getMonth = date('m', strtotime($value5['DHIRE']));
        if(date('d', strtotime($value5['DHIRE'])) == '01'){
          $entitle = number_format((float)(13 - $getMonth) * (24/12), 2, '.', ',');
        }else{
          $entitle = number_format((float)(13 - ($getMonth + 1)) * (24/12), 2, '.', ',');
        }
      }else if($year != date('Y', strtotime($value5['DHIRE'])) && $year == date('Y')){
         $entitle = number_format((float)(date("m") - 1) * (24/12), 2, '.', ',');
      }else if($year == date('Y', strtotime($value5['DHIRE'])) && $year == date('Y')){
        if($month < 1){
          $entitle = 0;
        }else{
          if(date('d', strtotime($value5['DHIRE'])) == '01'){
            $entitle = number_format((float)date("m") * (24/12), 2, '.', ',');
          }else{
            $entitle = number_format((float)(date("m") - 1) * (24/12), 2, '.', ',');
          }
        }
      }else if($year < date('Y', strtotime($value5['DHIRE']))){
        $entitle = 0;
      }else{
        $entitle = 24;
      }
    }
  }else if(in_array($value5['CGRADE'], $ngrade)){
    if($month >= 60){
      $entcf = 21;
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
      $entcf = 14;
      if($year == date('Y', strtotime($value5['DHIRE'])) && $year != date('Y')){
        $getMonth = date('m', strtotime($value5['DHIRE']));
        if(date('d', strtotime($value5['DHIRE'])) == '01'){
          $entitle = number_format((float)(13 - $getMonth) * (14/12), 2, '.', ',');
        }else{
          $entitle = number_format((float)(13 - ($getMonth + 1)) * (14/12), 2, '.', ',');
        }
      }else if($year != date('Y', strtotime($value5['DHIRE'])) && $year == date('Y')){
         $entitle = number_format((float)(date("m") - 1) * (14/12), 2, '.', ',');
      }else if($year == date('Y', strtotime($value5['DHIRE'])) && $year == date('Y')){
        if($month < 1){
          $entitle = 0;
        }else{
          if(date('d', strtotime($value5['DHIRE'])) == '01'){
            $entitle = number_format((float)date("m") * (14/12), 2, '.', ',');
          }else{
            $entitle = number_format((float)(date("m") - 1) * (14/12), 2, '.', ',');
          }
        }
      }else if($year < date('Y', strtotime($value5['DHIRE']))){
        $entitle = 0;
      }else{
        $entitle = 14;
      }
    }
  }
}

$last_month = date("m") - 1;

if(date('m') == 1){
  $entitleInfo = "As at January ".date("Y");
}else{
  $entitleInfo = "As at ".cal_days_in_month(CAL_GREGORIAN, $last_month = date('m') - 1 ?: 12, $year = $last_month == 12 ? date('Y') - 1 : date('Y'))." ".date("F Y", mktime(0, 0, 0, $last_month, 1, $year));
}

if(($bfval - $entcf) > 0){
  $entcfinal = $bfval - $entcf;
}else{
  $entcfinal = 0;
}

if($year >= date("Y", strtotime($hire))){
?>
<div class="calendar-container">
  <div class="month-container">
    <div class="calendar">
      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <b style="font-style: italic;  font-size: 13px;">Joined SEC</b>
          <span class="badge text-bg-info" style="font-style: italic;  font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" title="<?php echo date("d F Y", strtotime($hire)); ?>">
            <i class="fas fa-eye"></i> View
          </span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <b style="font-style: italic;  font-size: 13px;">In Service</b>
          <?php if($_POST['year'] < date('Y', strtotime($hire))){ ?>
          <span class="badge text-bg-success" style="font-style: italic;  font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" title="No Data">
            <i class="fas fa-eye"></i> View 
          </span>
          <?php }else{ ?>
          <span class="badge text-bg-success" style="font-style: italic;  font-size: 11px;" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" title="<?php echo $years > 0 ? "$years years, $months months, $days days" : "$months months, $days days"; ?>">
            <i class="fas fa-eye"></i> View
          </span>
          <?php } ?>
        </li>
      </ul>
    </div>
  </div>
</div>
<div class="calendar-container">
  <div class="month-container">
    <div class="calendar">
      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <b style="font-style: italic;  font-size: 13px;">Leave B/F <i class="fas fa-circle-info text-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" title="Year <?php echo $year - 1; ?>"></i>
          </b>
          <b style="font-style: italic;  font-size: 13px;"><?php echo ($bfval !== null) ? number_format($bfval, 2).' days' : '-'; ?></b>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?php if($year != date("Y")){ ?>
          <b style="font-style: italic;  font-size: 13px;">Entitlement <i class="fas fa-circle-info text-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
            title="Year <?php echo $year; ?>"></i>
          </b>
          <?php }else{ ?>
          <b style="font-style: italic;  font-size: 13px;">Entitlement <i class="fas fa-circle-info text-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
            title="<?php echo $entitleInfo; ?>"></i>
          </b>
          <?php } ?>
          <b style="font-style: italic;  font-size: 13px;">
            <?php echo ($entitle !== null) ? number_format($entitle, 2).' days' : '-'; ?>
          </b>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <b style="font-style: italic;  font-size: 13px;">Taken</b>
          <b class="text-danger" style="font-style: italic;  font-size: 13px;">
            <?php echo number_format($anval + array_sum($annually), 2); ?> days
          </b>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <b style="font-style: italic;  font-size: 13px;">Balance</b>
          <b class="text-success" style="font-style: italic;  font-size: 13px;">
            <?php echo number_format(($bfval + $entitle) - ($anval + array_sum($annually)), 2); ?> days
          </b>
        </li>
        <!-- <li class="list-group-item d-flex justify-content-between align-items-center">
          <b style="font-style: italic;  font-size: 13px;">Leave C/F <i class="fas fa-circle-info text-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" title="The <?php //echo $year - 2; ?> leave balance must be utilized by January <?php //echo $year; ?>. This leave will automatically expire in February <?php //echo $year; ?>."></i>
          </b>
          <b class="text-info" style="font-style: italic;  font-size: 13px;">
            <?php //echo number_format($entcfinal, 2); ?> days
          </b>
        </li> -->
      </ul>
    </div>
  </div>
</div>
<div class="calendar-container">
  <div class="month-container">
    <div class="calendar">
      <ul class="list-group">
        <?php 

        $color = ['amber-color', 'yellow-color', 'info-color', 'pink-color', 'cyan-color', 'brown-color', 'grey-color', 'lime-color', 'black-color', 'light-color', 'purple-gradient', 'green-color', 'peach-gradient', 'danger-color', 'calamity-color', 'other-color'];

        foreach ($result2 as $key2 => $value2) {
          $colorClass = in_array($key2, array_keys($color)) ? $color[$key2] : '';
          $textDarkClass = $key2 == 1 ? 'text-dark' : '';

          foreach ($result3 as $key3 => $value3) {
            if($value3['CCDLEAVE'] == $value2['ID'] && date("Y", strtotime($value3['DLEAVE'])) == $_POST['year']){
              $count[$key2][] = $value3['NDAYS'];
            }
          }

        ?>
        <li class="list-group-item">
          <div class="row">
            <div class="col-8">
              <span class="badge text-bg-success <?php echo $colorClass.' '.$textDarkClass; ?>"><i class="fas fa-info"></i></span>
              <b style="font-style: italic;  font-size: 13px;"><?php echo $value2['leave_type']; ?></b> 
            </div>
            <div class="col-4" style="text-align: right;">
              <b style="font-style: italic;  font-size: 13px;"><?php echo is_array($count[$key2]) ? array_sum($count[$key2]).' days' : '-'; ?></b>
            </div>
          </div>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function() {
  var choicesItem = $('.choices__item');
  choicesItem.data('deletable', null);
  choicesItem.find('.choices__button').remove();
});
</script>

