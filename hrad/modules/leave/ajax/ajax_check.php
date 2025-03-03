<?php 

include('../../../../api.php');

$type = $_POST['type'];
$start = $_POST['start'];
$end = $_POST['end'];
$url = $_POST['url'];
$cnt = $_POST['cnt'];
$emid = $_POST['emid'];
$year = $_POST['year'];
$shift = $_POST['shift'];

$hldy = [];
$count = [];
$lvcount = [];
$different = [];

?>

<!-- Start Annual Leave -->
<?php if($type == '1'){ ?>
  <?php
  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  if($shift == '0'){
    $sqla = "SELECT * FROM eleave_publicholiday WHERE DATE_FORMAT(dt_holiday, '%Y') = '$year' OR type = 'fixed' ORDER BY dt_holiday ASC";
    $resulta = $conn->query($sqla);

    foreach ($resulta as $keya => $valuea) {
      if($valuea['type'] == 'fixed'){
        $hldy[] = $year."-".date("m-d", strtotime($valuea['dt_holiday']));
      }else{
        $hldy[] = $valuea['dt_holiday'];
      }
    }

    foreach ($date_range as $show_date) {
      if(!in_array($show_date->format('Y-m-d'), $hldy) && $show_date->format('N') < 6) {
        $different[] = $show_date->format('Y-m-d');
      }
    }
  }

  if($shift == '1'){
    foreach ($date_range as $show_date) {
      $different[] = $show_date->format('Y-m-d');
    }
  }

  $sql = "SELECT * FROM eleave_fxleavea WHERE CNOEE = '$emid' AND DATE_FORMAT(DADJUST, '%Y') = '$year' AND CCDLEAVE = '$type' AND CTYPADJUST = '1'";
  $result = $conn->query($sql);

  $sql1 = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result1 = $conn->query($sql1);

  $sql2 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
  $result2 = $conn->query($sql2);

  if($row = $result->fetch_assoc()){

    foreach ($result1 as $k1 => $v1) {
      $lvcount[] = $v1['NDAYS'];
    }

    if($row2 = $result2->fetch_assoc()){

      $cgrade = $row2['CGRADE'];
      $date1 = new DateTime($row2['DHIRE']);
      $date2 = new DateTime();
      $date3 = new DateTime(($year + 1).'-01-01');

      $interval1 = $date1->diff($date2);
      $interval2 = $date1->diff($date3);

      if($year == date('Y', strtotime($row2['DHIRE']))){
        if(date('d', strtotime($row2['DHIRE'])) == '01'){
          $month = 13 - date('m', strtotime($row2['DHIRE'])); 
        }else{
          $month = 12 - date('m', strtotime($row2['DHIRE'])); 
        }
      }else if($year == date('Y')){
        $month = ($interval1->y * 12 + $interval1->m);
      }else{
        $month = $interval2->y * 12 + $interval2->m;
      }

      $grade = ['NE1', 'NE2', 'NE3', 'NE4', 'NE5', 'NE6'];
    }
  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $bf = $row['NDAYS']; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo strtoupper(date("m/Y")); ?></b>
          </div>
          <div class="col-6 text-right">
            <b>
              <?php
              if(!in_array($row2['CGRADE'], $grade)){
                if($month > 60){
                  if($year == date('Y')){
                    $currMonth = date("m") - 1;
                    echo $entitle = number_format((float)$currMonth * (30/12), 2, '.', ',');
                  }else{
                    echo $entitle = "30.00";
                  }
                }else{
                  if($year == date('Y', strtotime($row2['DHIRE']))){
                    echo $entitle = number_format((float)$month * (24/12), 2, '.', ',');
                  }else if($year == date('Y')){
                    $currMonth = date("m") - 1;
                    echo $entitle = number_format((float)$currMonth * (24/12), 2, '.', ',');
                  }else{
                    echo $entitle = "24.00";
                  }
                }
              }else if(in_array($row2['CGRADE'], $grade)){
                if($month > 60){
                  if($year == date('Y')){
                    $currMonth = date("m") - 1;
                    echo $entitle = number_format((float)$currMonth * (21/12), 2, '.', ',');
                  }else{
                    echo $entitle = "21.00";
                  }
                }else{
                  if($year == date('Y', strtotime($row2['DHIRE']))){
                    echo $entitle = number_format((float)$month * (14/12), 2, '.', ',');
                  }else if($year == date('Y')){
                    $currMonth = date("m") - 1;
                    echo $entitle = number_format((float)$currMonth * (14/12), 2, '.', ',');
                  }else{
                    echo $entitle = "14.00";
                  }
                }
              }
              ?>
              DAYS
            </b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($lvcount), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)($bf + $entitle) - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
        <option value="10">Morning</option>
        <option value="01">Evening</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <span id="show_weekend"></span>
    <input id="get_hour" hidden>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var load_hour = [];
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          load_hour.push($(this).val());
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_hour').val(load_hour.join(''));
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_hour = [];
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            chge_hour.push($(this).val());
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_hour').val(chge_hour.join(''));
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>

    <?php if($shift == '0') { ?>
    $(document).ready(function(){
      var dateToCheck = new Date('<?php echo $start; ?>');
      if (dateToCheck.getDay() === 0 || dateToCheck.getDay() === 6) {
        $("#show_weekend").html('<div class="col-md-12"><div class="note note-danger"><b>SATURDAY/SUNDAY <i class="fas fa-arrow-right-long"></i> DISABLED</b></div></div>');
      }else{
        $("#show_weekend").hide();
      }
    });
    <?php } ?>
  </script>
<?php } } ?>
<!-- End Annual Leave -->

<!-- Start Maternity Leave -->
<?php if($type == '2'){ ?>
  <?php 

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
  $result1 = $conn->query($sql1);

  if ($result->num_rows > 0) {
    $stat = 1;
  }

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "0.00" : "98.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "98.00" : "0.00"; ?></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = ($stat == '1') ? "0.00" : "98.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if($stat == '1'){ ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance == '0.00') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);
    <?php } ?>
  </script>
<?php } ?>
<!-- End Maternity Leave -->

<!-- Start Medical Leave -->
<?php if($type == '3'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
  $result1 = $conn->query($sql1);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  if($row1 = $result1->fetch_assoc()){
    $dhire = $row1['DHIRE'];
  }

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b>
              <?php

              $dhireDate = new DateTime($dhire);
              $currtDate = new DateTime();

              $interval = $currtDate->diff($dhireDate);
              $years = $interval->y;

              if($years < 2){
                $entitle = 14;
                echo "14.00";
              }else if($years >= 2 && $years <= 5){
                $entitle = 18;
                echo "18.00";
              }else if($years > 5){
                $entitle = 22;
                echo "22.00";
              }

              ?>
              DAYS
            </b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Medical Leave -->

<!-- Start Hospitalisation Leave -->
<?php if($type == '4'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  $entitle = 60;

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo number_format((float)$entitle, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Hospitalisation Leave -->

<!-- Start Extended Medical Leave -->
<?php if($type == '5'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  $entitle = 180;

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo number_format((float)$entitle, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Extended Medical Leave -->

<!-- Start Special Paid Leave -->
<?php if($type == '6'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  $entitle = 5;

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo number_format((float)$entitle, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Special Paid Leave -->

<!-- Start Compassionate Leave -->
<?php if($type == '7'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  $entitle = 7;

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo number_format((float)$entitle, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Compassionate Leave -->

<!-- Start Relocation Leave -->
<?php if($type == '8'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  $entitle = 4;

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo number_format((float)$entitle, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Relocation Leave -->

<!-- Start Leave Without Pay Leave -->
<?php if($type == '9'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  $entitle = 30;

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo number_format((float)$entitle, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Leave Without Pay Leave -->

<!-- Start Paternity Leave -->
<?php if($type == '10'){ ?>
  <?php 

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
  $result1 = $conn->query($sql1);

  if ($result->num_rows > 0) {
    $stat = 1;
  }

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "0.00" : "7.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "7.00" : "0.00"; ?></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = ($stat == '1') ? "0.00" : "7.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if($stat == '1'){ ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance == '0.00') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);
    <?php } ?>
  </script>
<?php } ?>
<!-- End Paternity Leave -->

<!-- Start Pilgrimage Leave -->
<?php if($type == '11'){ ?>
  <?php 

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
  $result1 = $conn->query($sql1);

  if ($result->num_rows > 0) {
    $stat = 1;
  }

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "0.00" : "40.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "40.00" : "0.00"; ?></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = ($stat == '1') ? "0.00" : "40.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if($stat == '1'){ ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance == '0.00') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);
    <?php } ?>
  </script>
<?php } ?>
<!-- End Pilgrimage Leave -->

<!-- Start Marriage Leave -->
<?php if($type == '12'){ ?>
  <?php 

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
  $result1 = $conn->query($sql1);

  if ($result->num_rows > 0) {
    $stat = 1;
  }

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "0.00" : "5.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo ($stat == '1') ? "5.00" : "0.00"; ?></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = ($stat == '1') ? "0.00" : "5.00"; ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if($stat == '1'){ ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance == '0.00') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);
    <?php } ?>
  </script>
<?php } ?>
<!-- End Marriage Leave -->

<!-- Start Study Leave -->
<?php if($type == '13'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_days" hidden>
  </div>
  <script type="text/javascript">
    sec_function();
    $("#apply_leave").attr('disabled', false);

    $(document).ready(function(){
      var chge_days = 0;
      $('.leave_status option:selected').each(function() {
        if ($(this).val() === "11") {
          chge_days += 1.00;
        } else {
          chge_days += 0.5;
        }
      });
      $('#get_days').val(chge_days);
    });

    $(document).ready(function(){
      $('.leave_status').change(function(){
        var chge_days = 0;
        var balance = '<?php echo $balance; ?>'; 
        $('.leave_status').find('option:selected').each(function() {
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_days').val(chge_days);
        $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
        $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
      });
    });
  </script>
<?php } ?>
<!-- End Study Leave -->

<!-- Start Unrecorded Leave -->
<?php if($type == '14'){ ?>
  <?php
  if($url != ''){
    $start_date = new DateTime($start);
    $end_date = new DateTime($end);

    $diffinter = new DateInterval('P1D');
    $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

    foreach ($date_range as $show_date) {
      $different[] = $show_date->format('Y-m-d');
    }
  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-10" style="padding: 8px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Unrecorded Leave only available for <?php echo date("d F Y", strtotime($url." +1 day"));; ?> and above.</b>
      </div>
    </div>
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
        <option value="10">Morning</option>
        <option value="01">Evening</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_hour" hidden>
    <input id="get_days" hidden>
  </div>
  <script type="text/javascript">
    sec_function();
    $("#apply_leave").attr('disabled', false);

    $(document).ready(function(){
      var load_hour = [];
      var chge_days = 0;
      $('.leave_status option:selected').each(function() {
        load_hour.push($(this).val());
        if ($(this).val() === "11") {
          chge_days += 1.00;
        } else {
          chge_days += 0.5;
        }
      });
      $('#get_hour').val(load_hour.join(''));
      $('#get_days').val(chge_days);
    });

    $(document).ready(function(){
      $('.leave_status').change(function(){
        var chge_hour = [];
        var chge_days = 0;
        var balance = '<?php echo $balance; ?>'; 
        $('.leave_status').find('option:selected').each(function() {
          chge_hour.push($(this).val());
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_hour').val(chge_hour.join(''));
        $('#get_days').val(chge_days);
        $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
        $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
      });
    });
  </script>
<?php } } ?>
<!-- End Unrecorded Leave -->

<!-- Start Calamity Leave -->
<?php if($type == '15'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' AND CCDLEAVE = '$type'";
  $result = $conn->query($sql);

  foreach ($result as $key => $value) {
    $count[] = $value['NDAYS'];
  }

  $entitle = 5;

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-secondary">
        <div class="row">
          <div class="col-6">
            <b>B/FORWARD <?php echo $year - 1; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><i class="fas fa-square-xmark text-danger"></i></b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-success">
        <div class="row">
          <div class="col-6">
            <b>ENTITLE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo number_format((float)$entitle, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-primary">
        <div class="row">
          <div class="col-6">
            <b>TAKEN <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $taken = number_format((float)array_sum($count), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-danger">
        <div class="row">
          <div class="col-6">
            <b>BALANCE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b><?php echo $balance = number_format((float)$entitle - $taken, 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-warning">
        <div class="row">
          <div class="col-6">
            <b>NEW LEAVE <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <b id="show_new_leave"><?php echo $new_leave = number_format((float)count($different), 2, '.', ''); ?> DAYS</b>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" style="padding: 8px;">
      <div class="note note-info">
        <div class="row">
          <div class="col-6">
            <b>FORECAST <?php echo $year; ?></b>
          </div>
          <div class="col-6 text-right">
            <?php if($balance - count($different) < 0){ ?>
            <i class="fas fa-square-xmark text-danger"></i>
            <?php }else{ ?>
            <b id="show_forecast"><?php echo number_format((float)$balance - count($different), 2, '.', ''); ?> DAYS</b>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <?php if($balance - count($different) < 0){ ?>
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="note note-danger">
        <b><i class="fas fa-exclamation"></i> Insufficient Leave Balance</b>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
        <option value="10">Morning</option>
        <option value="01">Evening</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_hour" hidden>
    <input id="get_days" hidden>
  </div>
  <?php } ?>
  <script type="text/javascript">
    sec_function();
    <?php if($balance - count($different) < 0 || count($different) == '') { ?>
      $("#apply_leave").attr('disabled', true);
    <?php }else{ ?>
      $("#apply_leave").attr('disabled', false);

      $(document).ready(function(){
        var load_hour = [];
        var chge_days = 0;
        $('.leave_status option:selected').each(function() {
          load_hour.push($(this).val());
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_hour').val(load_hour.join(''));
        $('#get_days').val(chge_days);
      });

      $(document).ready(function(){
        $('.leave_status').change(function(){
          var chge_hour = [];
          var chge_days = 0;
          var balance = '<?php echo $balance; ?>'; 
          $('.leave_status').find('option:selected').each(function() {
            chge_hour.push($(this).val());
            if ($(this).val() === "11") {
              chge_days += 1.00;
            } else {
              chge_days += 0.5;
            }
          });
          $('#get_hour').val(chge_hour.join(''));
          $('#get_days').val(chge_days);
          $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
          $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
        });
      });
    <?php } ?>
  </script>
<?php } ?>
<!-- End Calamity Leave -->

<!-- Start Other Leave -->
<?php if($type == '16'){ ?>
  <?php

  $start_date = new DateTime($start);
  $end_date = new DateTime($end);

  $diffinter = new DateInterval('P1D');
  $date_range = new DatePeriod($start_date, $diffinter, $end_date->modify('+1 day'));

  foreach ($date_range as $show_date) {
    $different[] = $show_date->format('Y-m-d');
  }

  ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <?php foreach ($different as $diffval){ ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select leave_status" data-mdb-select-init>
        <option value="11">FullDay</option>
        <option value="10">Morning</option>
        <option value="01">Evening</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo date("d M Y (D)", strtotime($diffval)); ?></b>
      </label>
    </div>
    <?php } ?>
    <input id="get_hour" hidden>
    <input id="get_days" hidden>
  </div>
  <script type="text/javascript">
    sec_function();
    $("#apply_leave").attr('disabled', false);

    $(document).ready(function(){
      var load_hour = [];
      var chge_days = 0;
      $('.leave_status option:selected').each(function() {
        load_hour.push($(this).val());
        if ($(this).val() === "11") {
          chge_days += 1.00;
        } else {
          chge_days += 0.5;
        }
      });
      $('#get_hour').val(load_hour.join(''));
      $('#get_days').val(chge_days);
    });

    $(document).ready(function(){
      $('.leave_status').change(function(){
        var chge_hour = [];
        var chge_days = 0;
        var balance = '<?php echo $balance; ?>'; 
        $('.leave_status').find('option:selected').each(function() {
          chge_hour.push($(this).val());
          if ($(this).val() === "11") {
            chge_days += 1.00;
          } else {
            chge_days += 0.5;
          }
        });
        $('#get_hour').val(chge_hour.join(''));
        $('#get_days').val(chge_days);
        $('#show_new_leave').html(chge_days.toFixed(2)+" DAYS");
        $('#show_forecast').html((balance - chge_days).toFixed(2)+" DAYS");
      });
    });
  </script>
<?php } ?>
<!-- End Calamity Leave -->