<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../../api.php');

$year = $_POST['year'];
$divi = $_POST['divi'];
$desc = $_POST['desc'];
$clse = $_POST['year'] + 1;

if($divi == 'all'){
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND DATE_FORMAT(DHIRE, '%Y') <= '$year' AND CTYPEMPL = 'P' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}else{
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND DATE_FORMAT(DHIRE, '%Y') <= '$year' AND CTYPEMPL = 'P' AND CDIVISION = '$divi' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}

$sql1 = "SELECT * FROM eleave_fxleavea WHERE DATE_FORMAT(DADJUST, '%Y') = '$year'";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave WHERE CCDLEAVE = '1' AND DATE_FORMAT(DLEAVE, '%Y') = '$year'";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM eleave_fxleavea WHERE DATE_FORMAT(DADJUST, '%Y') = '$clse'";
$result3 = $conn->query($sql3);

if($_SESSION['sid'] == '2522-186' && $divi == 'all'){
?>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <b>CLOSING LEAVE BALANCE FOR <?php echo $year; ?> - B/F <?php echo $year + 1; ?></b>
          </div>
          <div class="col-6" style="text-align: right;">
            <?php if ($result3->num_rows > 0) { ?>
            <button class="btn btn-danger" id="restore">
              <i class="far fa-circle-dot"></i> DELETE BALANCE
            </button>
            <?php }else{ ?>
            <button class="btn btn-primary" id="closing">
              <i class="far fa-circle-dot"></i> CLOSING LEAVE
            </button>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-12 overflow-x-scroll">
            <table class="table table-sm table-hover table-striped" style="white-space: nowrap;">
              <thead>
                <tr class="text-center">
                  <th style="text-align: left;"><b>EMPLOYEE NAME</b></th>
                  <th style="text-align: left;"><b>HIRE DATE</b></th>
                  <th style="text-align: left;"><b>GRADE</b></th>
                  <th style="text-align: right;"><b>B/F <?php echo $year - 1; ?></b></th>
                  <th style="text-align: right;">
                    <b>
                    <?php 

                    if($year == date('Y')){
                      echo date("m/Y"); 
                    }else{
                      echo '12/'.$year;
                    }

                    ?>
                    </b>
                  </th>
                  <th style="text-align: right; color: red;"><b>TAKEN</b></th>
                  <th style="text-align: right; color: green;"><b>BALANCE</b></th>
                  <th style="text-align: right;"><b>EXP <?php echo $year + 1; ?></b></th>
                </tr>
              </thead>
              <tbody>
                <?php $closing = []; foreach ($result as $key => $value) { ?>
                <tr class="text-center">
                  <!-- name -->
                  <td style="text-align: left;">
                    <b><?php echo $value['CNAME']; ?></b>
                  </td>
                  <!-- hire -->
                  <td style="text-align: left;">
                    <?php echo strtoupper(date("d M Y", strtotime($value['DHIRE']))); ?>
                  </td>
                  <!-- grade -->
                  <td style="text-align: left;">
                    <?php echo $value['CGRADE'] ?: '-'; ?>
                  </td>
                  <!-- b/f -->
                  <td style="text-align: right; color: blue;">
                    <b>
                    <?php 

                    $found = false;
                    foreach ($result1 as $key1 => $value1) {
                      if ($value1['CNOEE'] == $value['CNOEE']) {
                        echo $value1['NDAYS'];
                        $found = true;
                        $bf = $value1['NDAYS'];
                        break;
                      }
                    }

                    if (!$found) {
                      $bf = 0;
                      echo "0.00";
                    }

                    ?>
                    </b>
                  </td>
                  <!-- entitle -->
                  <td style="text-align: right;">
                    <b>
                    <?php

                    $date1 = new DateTime($value['DHIRE']);
                    $date2 = new DateTime();
                    $date3 = new DateTime(($year + 1).'-01-01');

                    $interval1 = $date1->diff($date2);
                    $interval2 = $date1->diff($date3);

                    if($year == date('Y', strtotime($value['DHIRE']))){
                      if(date('d', strtotime($value['DHIRE'])) == '01'){
                        $month = 13 - date('m', strtotime($value['DHIRE'])); 
                      }else{
                        $month = 12 - date('m', strtotime($value['DHIRE'])); 
                      }
                    }else if($year == date('Y')){
                      $month = ($interval1->y * 12 + $interval1->m);
                    }else{
                      $month = $interval2->y * 12 + $interval2->m;
                    }

                    $grade = ['NE1', 'NE2', 'NE3', 'NE4', 'NE5', 'NE6'];

                    if(!in_array($value['CGRADE'], $grade)){
                      if($month >= 60){
                        if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (30/12), 2, '.', ',');
                        }else{
                          if ($month > 59 && $month < 71) {
                            $new = ($month - 60) * (30/12);
                            $old = (12 - ($month - 60)) * (24/12);
                            echo "<b class='sec-tooltip pointer' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='5 Years (24 days - 30 days)' style='color: green;'>".$entitle = number_format((float)$new + $old, 2, '.', ',')."</b>";
                          }else{
                            echo $entitle = "30.00";
                          }
                        }
                      }else{
                        if($year == date('Y', strtotime($value['DHIRE']))){
                          echo $entitle = number_format((float)$month * (24/12), 2, '.', ',');
                        }else if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (24/12), 2, '.', ',');
                        }else{
                          echo $entitle = "24.00";
                        }
                      }
                    }else if(in_array($value['CGRADE'], $grade)){
                      if($month >= 60){
                        if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (21/12), 2, '.', ',');
                        }else{
                          if ($month > 59 && $month < 71) {
                            $new = ($month - 60) * (21/12);
                            $old = (12 - ($month - 60)) * (14/12);
                            echo "<b class='sec-tooltip pointer' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='5 Years (14 days - 21 days)' style='color: green;'>".$entitle = number_format((float)$new + $old, 2, '.', ',')."</b>";
                          }else{
                            echo $entitle = "21.00";
                          }
                        }
                      }else{
                        if($year == date('Y', strtotime($value['DHIRE']))){
                          echo $entitle = number_format((float)$month * (14/12), 2, '.', ',');
                        }else if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (14/12), 2, '.', ',');
                        }else{
                          echo $entitle = "14.00";
                        }
                      }
                    }

                    foreach ($result2 as $key2 => $value2) {
                      if($value2['CNOEE'] == $value['CNOEE']){
                        $taken[$key] += $value2['NDAYS'];
                      }
                    }

                    ?>
                    </b>
                  </td>
                  <!-- taken -->
                  <td style="text-align: right; color: <?php if($taken[$key] > 0){ echo "red"; } ?>;">
                    <b><?php echo number_format((float)$taken[$key], 2, '.', ','); ?></b>
                  </td>
                  <!-- balance -->
                  <?php 

                  $balance = number_format((float)($bf + $entitle) - $taken[$key], 2, '.', ',');
                  $expire = $balance - $entitle;
                  $closing[] = $value['CNOEE'].",".$balance;

                  ?>
                  <td style="text-align: right; color: <?php if($balance <= 0){ echo "red"; }else{ echo "green"; } ?>;">
                    <b><?php echo ($balance != 0) ? $balance : '0.00'; ?></b>
                  </td>
                  <!-- expire -->
                  <td style="text-align: right;">
                    <?php 
                    if($expire > 0 && $year != date("Y")){
                      echo "<b style='color: red;'>".number_format((float)$expire, 2, '.', ',')."</b>";
                    }else{
                      echo '0.00';
                    }

                    ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $("#restore").click(function(){
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<strong>LEAVE BALANCE WILL BE DELETED<br>YEAR <?php echo $year + 1; ?></strong>",
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
            leave_restore: '<?php echo $year; ?>'
          },
          beforeSend: function() {    
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
            Swal.fire({
              title: "COMPLETED",
              icon: "success"
            }).then(() => {
              location.reload();
            });
          }
        });
      }
    });
  });
});

$(document).ready(function() {
  $("#closing").click(function(){
    var closing = <?php echo json_encode($closing); ?>; 
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<strong>LEAVE BALANCE WILL BE CLOSED<br>YEAR <?php echo $year; ?></strong>",
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
            leave_closing: '<?php echo $year; ?>',
            closing: closing
          },
          beforeSend: function() {    
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
            Swal.fire({
              title: "COMPLETED",
              icon: "success"
            }).then(() => {
              location.reload();
            });
          }
        });
      }
    });
  });
});
</script>