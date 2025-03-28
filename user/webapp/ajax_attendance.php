<?php 

ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_SESSION['emid'];
$attd = $_POST['year']."-".sprintf("%02d", $_POST['month']);

$sql = "SELECT * FROM attendance WHERE cnoee = '$emid' AND DATE_FORMAT(edate, '%Y-%m') = '$attd'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

?>
<div class="row">
  <?php 
  foreach ($result as $key => $val) {

  $dayOfWeek = date('N', strtotime($val['edate']));
  $alertClass = ($dayOfWeek == 6 || $dayOfWeek == 7) ? "bg-danger" : "bg-info"; 

  ?>
  <div class="col-12" style="margin-top: 20px;">
    <div class="card">
      <div class="card-header <?php echo $alertClass; ?> text-white">
        <b><i class="fas fa-caret-right"></i> 
          <?php echo date("d F Y", strtotime($val['edate'])); ?> / <?php echo date("D", strtotime($val['edate'])); ?>
        </b>
      </div>
      <div class="card-body">
        <li class="nk-data-list-item">
          <div class="media media-middle media-circle text-bg-primary-soft">
            <em class="icon ni ni-clock-fill"></em>
          </div>
          <div class="amount-wrap">
            <div class="amount h3 mb-0">$68,740</div>
            <span class="smaller">Total Income</span>
          </div>
        </li>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
<?php }else{ ?>
<div class="row" style="margin-top: 20px;">
  <div class="col-12">
    <div class="alert alert-danger" role="alert">
      <b><i class="fas fa-bell"></i> No Record</b>
    </div>
  </div>
</div>
<?php } ?>
