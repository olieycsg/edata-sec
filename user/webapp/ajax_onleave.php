<?php 

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$divsn = $_POST['divsn'];
$year = $_POST['year'];
$month = $_POST['month'];

$sql = "SELECT * FROM employees_demas WHERE CDIVISION = '$divsn' AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM eleave";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave_leave_type";
$result2 = $conn->query($sql2);

$color = ['amber-color', 'yellow-color', 'info-color', 'pink-color', 'cyan-color', 'brown-color', 'grey-color', 'lime-color', 'black-color', 'light-color', 'purple-gradient', 'green-color', 'peach-gradient', 'danger-color', 'calamity-color', 'other-color'];

$numberOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$data = [];

foreach ($result as $value) {
  foreach ($result1 as $value1) {
    if ($value1['CNOEE'] == $value['CNOEE'] && date("Y-m", strtotime($value1['DLEAVE'])) <= sprintf("%04d-%02d", $year, $month) && date("Y-m", strtotime($value1['DLEAVE2'])) >= sprintf("%04d-%02d", $year, $month)) {
      $start = new DateTime($value1['DLEAVE']);
      $end = new DateTime($value1['DLEAVE2']);
      $end->modify('+1 day');
      $interval = new DateInterval('P1D');
      $dateRange = new DatePeriod($start, $interval, $end);
      foreach ($dateRange as $date) {
        $dayKey = $date->format('Y-m-d');
        foreach ($result2 as $value2) {
          if ($value1['CCDLEAVE'] == $value2['ID']) {
            $data[$dayKey][] = ['cname' => $value['CNAME'],'cleave' => $value2['leave_type'],'type' => $value1['CCDLEAVE'],'status' => $value1['MNOTES']];
          }
        } 
      }
    }
  } 
}

?>
<div class="calendar-container">
  <div class="month-container">
    <div class="calendar">
      <div class="accordion" id="headLeave">
        <?php
        for ($day = 1; $day <= $numberOfDays; $day++) {
          $date = new DateTime("$year-$month-$day");
          $dayKey = $date->format('Y-m-d');
        ?>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button <?php if ($date->format('N') >= 6) { echo "text-danger"; } else { echo "text-info"; } ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLeave_<?php echo $dayKey; ?>">
            <b><?php echo $date->format('D'); ?> <i class="fas fa-long-arrow-alt-right"></i> <?php echo $date->format('d F Y'); ?></b>
            </button>
          </h2>
          <div id="collapseLeave_<?php echo $dayKey; ?>" class="accordion-collapse show" data-bs-parent="#headLeave">
            <div class="accordion-body">
              <?php 
              if (isset($data[$dayKey])) {
                foreach ($data[$dayKey] as $leaveInfo) {
                  $getInfo = $leaveInfo['type'] - 1;
                  if ($leaveInfo['status'] == 'pending' || $leaveInfo['status'] == 'recommended') {
                    $colorClass = "secondary-color";
                    $leavePending = ' - Pending Application';
                  } else {
                    $colorClass = in_array($getInfo, array_keys($color)) ? $color[$getInfo] : '';
                    $leavePending = '';
                  }
                  $textDarkClass = $getInfo == 1 ? 'text-dark' : '';

                  if($leaveInfo['type'] == '1'){
                    if ($date->format('N') >= 6) {
                      echo "-";
                      continue;
                    }
                  }
              ?>
              <div class="row">
                <div class="col-10" style="font-size: 13px;"><b><?php echo $leaveInfo['cname']; ?></b></div>
                <div class="col-2" style="text-align: right;">
                  <span class="badge <?php echo $colorClass.' '.$textDarkClass; ?>" style="font-weight: bold"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip"
                    title="<?php echo $leaveInfo['cleave'].' '.$leaveInfo['nhours'].$leavePending; ?>">
                    <b><i class="fas fa-info"></i></b>
                  </span>
                </div>
              </div>
              <?php } }else{ ?>
              <b>-</b>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  var choicesItem = $('.choices__item');
  choicesItem.data('deletable', null);
  choicesItem.find('.choices__button').remove();
});

</script>

