<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

if($_SESSION['sid'] == ""){
  
  header('location: index');
  
}else{

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/
  
include('../api.php'); 
include('header.php');
include('sidebar.php');

$year = date("Y");
$month = date("m");

if(isset( $_GET['module'])){
  $module = $_GET['module'];
}else{
  $module = '';
}

$total_one = [];
$total_twos = [];
$total_twof = [];
$total_threes = [];
$total_threef = [];
$total_fours = [];
$total_fourf = [];

if($module == ''){
  $s1 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
  $r1 = $conn->query($s1);

  foreach ($r1 as $k1 => $v1) {
    $total_one[] = $v1['CNOEE'];
    if(date("Y", strtotime($v1['DHIRE'])) == ($year - 1)){
      $total_twos[] = $v1['DHIRE'];
    }else if(date("Y", strtotime($v1['DHIRE'])) == $year){
      $total_twof[] = $v1['DHIRE'];
    }
  }

  $s2 = "SELECT * FROM eleave";
  $r2 = $conn->query($s2);

  foreach ($r2 as $k2 => $v2) {
    if($v2['MNOTES'] == 'approved' && ($v2['ICNTEE'] == 'approved' || $v2['ICNTEE'] == 'processed')){
      if(date("Y-m", strtotime($v2['DLEAVE'])) == ($year.'-'.sprintf("%02d", ($month - 1)))){
        $total_threes[] = $v2['DLEAVE'];
      }else if(date("Y-m", strtotime($v2['DLEAVE'])) == ($year.'-'.sprintf("%02d", $month))){
        $total_threef[] = $v2['DLEAVE'];
      }
    }else if($v2['MNOTES'] == 'pending' && $v2['ICNTEE'] == 'unprocessed'){
      if(date("Y-m", strtotime($v2['DLEAVE'])) == ($year.'-'.sprintf("%02d", ($month - 1)))){
        $total_fours[] = $v2['DLEAVE'];
      }else if(date("Y-m", strtotime($v2['DLEAVE'])) == ($year.'-'.sprintf("%02d", $month))){
        $total_fourf[] = $v2['DLEAVE'];
      }
    }
  }
}

?>
<main class="mb-5" style="margin-top: -55px;">
  <div class="container px-4">
    <?php if($module == ''){ ?>
    <div class="row">
      <div class="col-lg-3" style="margin-top: 20px;">
        <div class="card shadow-8">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="px-2 badge-primary rounded-3 me-2">
                <i class="fas fa-user-tie fa-sm fa-fw"></i>
              </div>
              <p class="text-muted mb-0"><b>Active Staff - <?php echo date("Y") ?></b></p>
            </div>
            <h4 class="mb-0">
              <?php echo count($total_one); ?>
              <span class="text-success" style="font-size: 0.875rem">
                <span><i class="fas fa-caret-right"></i> 100.00%</span>
              </span>
            </h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3" style="margin-top: 20px;">
        <div class="card shadow-8">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="px-2 badge-primary rounded-3 me-2">
                <i class="fas fa-user-plus fa-sm fa-fw"></i>
              </div>
              <p class="text-muted mb-0"><b>New Staff - <?php echo date("Y") ?></b></p>
            </div>
            <h4 class="mb-0">
              <?php echo count($total_twof); ?>
              <span class="<?php if(count($total_twof) > count($total_twos)) { echo "text-success"; }else{ echo "text-danger"; } ?>" style="font-size: 0.875rem">
                <?php if(count($total_twof) > count($total_twos)) { echo "<i class='fas fa-arrow-up fa-sm'></i>"; }else{ echo "<i class='fas fa-arrow-down fa-sm'></i>"; } ?>
                <span>
                  <?php echo number_format((((count($total_twof) - count($total_twos)) / abs(count($total_twos))) * 100), 2); ?>%
                </span>
              </span>
            </h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3" style="margin-top: 20px;">
        <div class="card shadow-8">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="px-2 badge-success rounded-3 me-2">
                <i class="far fa-calendar-check fa-sm fa-fw"></i>
              </div>
              <p class="text-muted mb-0"><b>On Leave - <?php echo date("M Y") ?></b></p>
            </div>
            <h4 class="mb-0">
              <?php echo count($total_threef); ?>
              <span class="<?php if(count($total_threef) > count($total_threes)) { echo "text-success"; }else{ echo "text-danger"; } ?>" style="font-size: 0.875rem">
                <?php if(count($total_threef) > count($total_threes)) { echo "<i class='fas fa-arrow-up fa-sm'></i>"; }else{ echo "<i class='fas fa-arrow-down fa-sm'></i>"; } ?>
                <span>
                 <?php echo count($total_threes) === 0 ? "0" : number_format((((count($total_threef) - count($total_threes)) / abs(count($total_threes))) * 100), 2); ?>%
                </span>
              </span>
            </h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3" style="margin-top: 20px;">
        <div class="card shadow-8">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="px-2 badge-danger rounded-3 me-2">
                <i class="fas fa-sync-alt fa-sm fa-fw"></i>
              </div>
              <p class="text-muted mb-0"><b>Pending - <?php echo date("M Y") ?></b></p>
            </div>
            <h4 class="mb-0">
              <?php echo count($total_fourf); ?>
              <span class="<?php if(count($total_fourf) > count($total_fours)) { echo "text-success"; }else{ echo "text-danger"; } ?>" style="font-size: 0.875rem">
                <?php if(count($total_fourf) > count($total_fours)) { echo "<i class='fas fa-arrow-up fa-sm'></i>"; }else{ echo "<i class='fas fa-arrow-down fa-sm'></i>"; } ?>
                <span>
                  <?php echo count($total_fours) === 0 ? "0" : number_format((((count($total_fourf) - count($total_fours)) / abs(count($total_fours))) * 100), 2); ?>%
                </span>
              </span>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12" style="margin-top: 20px;">
        <div class="card shadow-8">
          <div class="card-body">
            <img src="../img/maintenance.jpg" class="img-fluid">
          </div>
        </div>
      </div>
    </div>
    <?php } 

    foreach ($result as $key => $value) {

      if ($value['module'] == 1 && $value['status'] == '1') {
        if($module == 'employee'){ include("employee.php"); }
        $access = 1;
      }

      if ($value['module'] == 2 && $value['status'] == '1') {
        if($module == 'leave'){ include("leave.php"); }
        $access = 1;
      }

      if ($value['module'] == 3 && $value['status'] == '1') {
        if($module == 'payroll'){ include("payroll.php"); }
        $access = 1;
      }

      if ($value['module'] == 4 && $value['status'] == '1') {
        if($module == 'medical'){ include("medical.php"); }
        $access = 1;
      }

      if ($value['module'] == 5 && $value['status'] == '1') {
        if($module == 'memo'){ include("memo.php"); }
        $access = 1;
      }

      if ($value['module'] == 6 && $value['status'] == '1') {
        if($module == 'training'){ include("training.php"); }
        $access = 1;
      }

      if ($value['module'] == 7 && $value['status'] == '1') {
        if($module == 'performance'){ include("performance.php"); }
        $access = 1;
      }

      if ($value['module'] == 8 && $value['status'] == '1') {
        if($module == 'system'){ include("system.php"); }
        $access = 1;
      }

      if ($value['module'] == 9 && $value['status'] == '1') {
        if($module == 'reports'){ include("reports.php"); }
        $access = 1;
      }

      if ($value['module'] == 10 && $value['status'] == '1') {
        if($module == 'attendance'){ include("attendance.php"); }
        $access = 1;
      }
      
    }

    if($access != 1){

    ?>
    <div class="row">
      <div class="col-lg-12" style="margin-top: 20px;">
        <div class="card shadow-8">
          <div class="card-body text-danger text-center">
            <b><i class="fas fa-bell"></i> YOU DO NOT HAVE PERMISSION TO ACCESS THIS MODULE</b>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</main>
<?php include('footer.php'); } ?>
