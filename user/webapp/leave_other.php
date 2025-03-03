<?php 

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];
$year = date("Y");

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){
  if($row['CSEX'] == 'M'){
    if($row['CSHIFT'] == '0'){
      $sql1 = "SELECT * FROM eleave_leave_type WHERE ID IN ('3','10','12','14')";
    }else{
      $sql1 = "SELECT * FROM eleave_leave_type WHERE ID IN ('3','10','12')";
    }
    $result1 = $conn->query($sql1);
  }else if($row['CSEX'] == 'F'){
    if($row['CSHIFT'] == '0'){
      $sql1 = "SELECT * FROM eleave_leave_type WHERE ID IN ('2','3','12','14')";
    }else{
      $sql1 = "SELECT * FROM eleave_leave_type WHERE ID IN ('2','10','12')";
    }
    $result1 = $conn->query($sql1);
  }

  $staff = $row['CNAME'];
  $dhire = $row['DHIRE'];
  $division = $row['CDIVISION'];
  $superior = $row['CSUPERIOR'];
  $supervis = $row['CSUPERVISO'];
}

$sql2 = "SELECT * FROM eleave_publicholiday WHERE type = 'fixed' OR YEAR(dt_holiday) = '$year' ORDER BY MONTH(dt_holiday) ASC";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND YEAR(DLEAVE) = '$year'";
$result3 = $conn->query($sql3);

$sql5 = "SELECT employees.EmailAddress AS email, employees_demas.CNAME AS cname, employees_demas.CNOEE AS cnoee FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CJOB = '$superior' AND employees_demas.DRESIGN = '0000-00-00'";
$result5 = $conn->query($sql5);

$sql6 = "SELECT employees_demas.CNOEE AS cnoee FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CJOB = '$supervis' AND employees_demas.DRESIGN = '0000-00-00'";
$result6 = $conn->query($sql6);

$sql7 = "SELECT employees.EmailAddress AS email, sys_workflow_divisional.CNOEE AS cnoee FROM sys_workflow_divisional INNER JOIN employees ON sys_workflow_divisional.CNOEE = employees.EmployeeID WHERE sys_workflow_divisional.CDIVISION = '$division'";
$result7 = $conn->query($sql7);

if($row5 = $result5->fetch_assoc()){
  $email = $row5['email'];
  $cname = $row5['cname'];
  $mnoee = $row5['cnoee'];
}

if($row6 = $result6->fetch_assoc()){
  $fnoee = $row6['cnoee'];
}

if($row7 = $result7->fetch_assoc()){
  $smail = $row7['email'];
  $hnoee = $row7['cnoee'];
}

$hire = date("Y", strtotime($row['DHIRE']));

$url = [];
$med = [];

foreach ($result3 as $k3 => $v3) {
  if($v3['CCDLEAVE'] == '2'){
    $mat = $v3['CCDLEAVE'];
  }
  if($v3['CCDLEAVE'] == '3'){
    $med[] = $v3['NDAYS'];
  }
  if($v3['CCDLEAVE'] == '10'){
    $pat = $v3['CCDLEAVE'];
  }
  if($v3['CCDLEAVE'] == '12'){
    $mar = $v3['CCDLEAVE'];
  }
  if($v3['CCDLEAVE'] == '14'){
    $url[] = $v3['NDAYS'];
  }
}

$join = new DateTime($row['DHIRE']);
$curr = new DateTime(date("Y-m-d"));
$interval = $join->diff($curr);
$month = $interval->y * 12 + $interval->m;

if ($month < 24) {
  $ment = 14;
} else if ($month >= 24 && $month <= 60) {
  $ment = 18;
} else if ($month > 60) {
  $ment = 22;
}

$fmed = $ment - array_sum($med);

?>
<style>
  .calendar-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
  }

  .month-container {
    margin-bottom: 20px;
    width: 100%;
  }

  .calendar {
    background-color: #f4f4f4;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 10px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  .day-header {
    text-align: center;
    padding: 8px;
    border-bottom: 1px solid #ddd;
    width: 14.285%;
  }

  th {
    color: black;
  }

  td {
    text-align: center;
    padding: 8px;
    border: 1px solid #ddd;
  }

  h3 {
    text-align: center;
    color: #333;
  }

  .weekend{
    color: red;
  }

  @media screen and (max-width: 600px) {
    .day-header {
      font-size: 12px;
    }
  }

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
<div id="content">
  <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
    <div class="nk-app-root">
      <div class="nk-main">
        <?php include('sidebar.php'); ?>
        <div class="nk-wrap">
          <?php include('navbar.php'); ?>
          <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
              <div class="nk-ibx">
                <div class="nk-ibx-body">
                  <div class="calendar-container">
                    <div class="month-container">
                      <div class="calendar">
                        <div class="row g-2">
                          <div class="col-10">
                            <div class="form-group">
                              <div class="form-control-wrap">
                                <select id="select_type" class="js-select load">
                                  <option value="leave">Annual Leave</option>
                                  <option value="leave_other" selected>Other Leave</option>
                                  <option value="leave_analytics">Leave Analytics</option>
                                  <option value="on_leave">On Leave</option>
                                  <option value="leave_pending">Pending Leave</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-2">
                            <div class="form-group load">
                              <div class="form-control-wrap">
                                <a href="leave_other" class="btn btn-block btn-soft btn-primary">
                                  <em class="icon ni ni-reload-alt"></em>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <div class="form-control-wrap">
                                <select id="select_leave" class="js-select">
                                  <option value="">- Select Other Leave -</option>
                                  <?php if($emid != '2522-186'){ ?>
                                    <?php foreach ($result1 as $key1 => $value1) { ?>
                                    <option value="<?php echo $value1['ID']; ?>" <?php if($value1['ID'] != '14'){ echo "disabled"; } ?>><?php echo $value1['leave_type']; ?><?php if($value1['ID'] != '14'){ echo " | Temporarily Disable"; } ?></option>
                                    <?php } ?>
                                  <?php }else{ foreach ($result1 as $key1 => $value1) { ?>
                                  <option value="<?php echo $value1['ID']; ?>"><?php echo $value1['leave_type']; ?></option>
                                  <?php } } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container maternity">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12">
                            <b class="text-danger">Required *</b>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>Start Date <span class="text-danger">*</span></b>
                              </label>    
                              <div class="form-control-wrap">
                                <input placeholder="dd/mm/yyyy" class="form-control js-datepicker" data-clear-btn="true" autocomplete="off" id="start_02">
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>Entitlement</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                : <span id="entitle_02"></span>
                              </label>
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>End Date</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                : <span id="end_02"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="form-label">
                                <b>
                                  Baby Birth Certificate <span class="text-danger">*</span> 
                                  <i class="fas fa-caret-right"></i> 
                                  <span class="text-danger">JPG and PNG only</span>
                                </b>
                              </label>
                              <div class="form-control-wrap">
                                <input class="form-control" type="file" id="file_02">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container medical">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-4">     
                              <label class="form-label">
                                <b>Entitle - <?php echo date("Y"); ?></b>
                              </label>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>: <?php echo $ment; ?> Days</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b class="text-danger">Taken</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                <b class="text-danger">: <?php echo array_sum($med); ?> Days</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b class="text-success">Balance</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                <b class="text-success">: <?php echo $fmed; ?> Days</b>
                              </label>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>Start Date <span class="text-danger">*</span></b>
                              </label>    
                              <div class="form-control-wrap">
                                <input placeholder="dd/mm/yyyy" class="form-control js-datepicker" data-clear-btn="true" autocomplete="off" id="start_03">
                              </div>
                            </div>
                          </div>
                          <div class="col-6">
                            <label class="form-label">
                              <b>Days <span class="text-danger">*</span></b>
                            </label>
                            <div class="form-group">
                              <div class="form-control-wrap">
                                <select id="mdays_03" class="js-select">
                                  <?php for ($i = 1; $i <= $fmed; $i++) { ?>
                                  <option value="<?php echo $i; ?>"><?php echo $i; ?> Days</option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>End Date</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                : <span id="end_03"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="form-label">
                                <b>
                                  Medical Certificate <span class="text-danger">*</span> 
                                  <i class="fas fa-caret-right"></i> 
                                  <span class="text-danger">JPG and PNG only</span>
                                </b>
                              </label>
                              <div class="form-control-wrap">
                                <input class="form-control" type="file" id="file_03">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container paternity">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12">
                            <b class="text-danger">Required *</b>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>Start Date <span class="text-danger">*</span></b>
                              </label>    
                              <div class="form-control-wrap">
                                <input placeholder="dd/mm/yyyy" class="form-control js-datepicker" data-clear-btn="true" autocomplete="off" id="start_10">
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>Entitlement</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                : <span id="entitle_10"></span>
                              </label>
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>End Date</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                : <span id="end_10"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="form-label">
                                <b>
                                  Baby Birth Certificate <span class="text-danger">*</span> 
                                  <i class="fas fa-caret-right"></i> 
                                  <span class="text-danger">JPG and PNG only</span>
                                </b>
                              </label>
                              <div class="form-control-wrap">
                                <input class="form-control" type="file" id="file_10">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container marriage">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12">
                            <b class="text-danger">Required *</b>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>Start Date <span class="text-danger">*</span></b>
                              </label>    
                              <div class="form-control-wrap">
                                <input placeholder="dd/mm/yyyy" class="form-control js-datepicker" data-clear-btn="true" autocomplete="off" id="start_12">
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>Entitlement</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                : <span id="entitle_12"></span>
                              </label>
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>End Date</b>
                              </label>
                            </div>
                          </div>
                          <div class="col-8">
                            <div class="form-group">       
                              <label class="form-label">
                                : <span id="end_12"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="form-label">
                                <b>
                                  Marriage Certificate <span class="text-danger">*</span> 
                                  <i class="fas fa-caret-right"></i> 
                                  <span class="text-danger">JPG and PNG only</span>
                                </b>
                              </label>
                              <div class="form-control-wrap">
                                <input class="form-control" type="file" id="file_12">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container unrecorded">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">       
                              <label class="form-label">
                                <b>URL Entitlement</b>
                              </label>    
                              <select id="select_url" class="js-select">
                                <option value="">- Select -</option>
                                <?php 
                                $today = date("Y-m-d", strtotime("+1 day"));
                                foreach ($result2 as $k2 => $v2) {
                                  if($v2['type'] == 'fixed'){
                                    $fixed = $year."-".date('m-d', strtotime($v2['dt_holiday']));
                                    $saturday = date('N', strtotime($fixed));
                                  }else{
                                    $fixed = $v2['dt_holiday'];
                                    $saturday = date('N', strtotime($v2['dt_holiday']));
                                  }
                                  if($saturday == 6) {
                                    $sat[] = $saturday;
                                ?>
                                <option value="<?php echo $fixed; ?>" <?php if(count($sat) <= array_sum($url) || $fixed < $dhire){ echo "disabled"; }else if($fixed > $today){ echo "disabled"; } ?> data-date="<?php echo date("d M Y", strtotime($fixed)); ?>" data-desc="<?php echo $v2['description']; ?>"><?php echo date("d M Y", strtotime($fixed))." | ".$v2['description']; ?></option>
                                <?php } } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">    
                              <label class="form-label">
                                <b>URL Leave Date</b>
                              </label>  
                              <div class="form-control-wrap">
                                <div class="form-control-icon end"><em class="icon ni ni-calender-date-fill"></em></div>
                                <input type="date" class="form-control" id="start_14" disabled>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container remarks">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">    
                              <label for="reason" class="form-label">
                                <b>Remarks</b>
                              </label>    
                              <div class="form-control-wrap">        
                                <textarea placeholder="..." class="form-control" id="reason" rows="3"></textarea>    
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="month-container nodata">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-md-12 text-center">
                            <img src="../../img/error/a.svg" class="img-fluid">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <button class="floating-button bg-primary" id="apply">
    <em class="icon ni ni-save-fill"></em>
  </button>
</div>
<?php include('footer.php'); ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#select_type").change(function(){
    window.location.href = $(this).val();
  });
});

function formatDate(dateString) {
  var date = new Date(dateString);
  var day = date.getDate();
  var month = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(date);
  var year = date.getFullYear();
  return `${day} ${month} ${year}`;
}

$(".maternity").hide();
$(".medical").hide();
$(".paternity").hide();
$(".marriage").hide();
$(".unrecorded").hide();
$(".remarks").hide();

$(document).ready(function(){
  $("#select_leave").change(function(){
    var leave = $(this).val();
    if(leave == '2'){
      $("#start_02").blur(function(){
        var start = $(this).val();
        var dateParts = start.split("/");
        var oriDate = dateParts[2] + "-" + dateParts[0] + "-" + dateParts[1];
        var startDate = new Date(oriDate);
        startDate.setDate(startDate.getDate() + 98);
        startDate.setDate(startDate.getDate() - 1);
        var formattedDate = startDate.toISOString().split('T')[0];
        $("#entitle_02").html("98 Days");
        $("#end_02").html(formatDate(formattedDate));
      });
      $(".maternity").show();
      $(".medical").hide();
      $(".paternity").hide();
      $(".marriage").hide();
      $(".unrecorded").hide();
      $(".remarks").show();
      $(".nodata").hide();
    }else if(leave == '3'){
      $("#start_03").blur(function(){
        var start = $(this).val();
        var mdays = parseInt($("#mdays_03").val());
        var dateParts = start.split("/");
        var oriDate = dateParts[2] + "-" + dateParts[0] + "-" + dateParts[1];
        var startDate = new Date(oriDate);
        startDate.setDate(startDate.getDate() + mdays);
        startDate.setDate(startDate.getDate() - 1);
        var formattedDate = startDate.toISOString().split('T')[0];
        $("#end_03").html(formatDate(formattedDate));
      });
      $("#mdays_03").change(function(){
        var start = $("#start_03").val();
        var mdays = parseInt($(this).val());
        var dateParts = start.split("/");
        var oriDate = dateParts[2] + "-" + dateParts[0] + "-" + dateParts[1];
        var startDate = new Date(oriDate);
        startDate.setDate(startDate.getDate() + mdays);
        startDate.setDate(startDate.getDate() - 1);
        var formattedDate = startDate.toISOString().split('T')[0];
        $("#end_03").html(formatDate(formattedDate));
      });
      $(".maternity").hide();
      $(".medical").show();
      $(".paternity").hide();
      $(".marriage").hide();
      $(".unrecorded").hide();
      $(".remarks").show();
      $(".nodata").hide();
    }else if(leave == '10'){
      $("#start_10").blur(function(){
        var start = $(this).val();
        var dateParts = start.split("/");
        var oriDate = dateParts[2] + "-" + dateParts[0] + "-" + dateParts[1];
        var startDate = new Date(oriDate);
        startDate.setDate(startDate.getDate() + 7);
        startDate.setDate(startDate.getDate() - 1);
        var formattedDate = startDate.toISOString().split('T')[0];
        $("#entitle_10").html("7 Days");
        $("#end_10").html(formatDate(formattedDate));
      });
      $(".maternity").hide();
      $(".medical").hide();
      $(".paternity").show();
      $(".marriage").hide();
      $(".unrecorded").hide();
      $(".remarks").show();
      $(".nodata").hide();
    }else if(leave == '12'){
      $("#start_12").blur(function(){
        var start = $(this).val();
        var dateParts = start.split("/");
        var oriDate = dateParts[2] + "-" + dateParts[0] + "-" + dateParts[1];
        var startDate = new Date(oriDate);
        startDate.setDate(startDate.getDate() + 5);
        startDate.setDate(startDate.getDate() - 1);
        var formattedDate = startDate.toISOString().split('T')[0];
        $("#entitle_12").html("5 Days");
        $("#end_12").html(formatDate(formattedDate));
      });
      $(".maternity").hide();
      $(".medical").hide();
      $(".paternity").hide();
      $(".marriage").show();
      $(".unrecorded").hide();
      $(".remarks").show();
      $(".nodata").hide();
    }else if(leave == '14'){
      $("#reason").attr("disabled", true);
      $("#select_url").change(function() {
        var url = $(this).val();
        var date = new Date(url);
        date.setDate(date.getDate() + 1);
        var minDateValue = date.toISOString().split('T')[0];
        $("#start_14").attr("min", minDateValue);
        $("#start_14").removeAttr("disabled");
      });

      $(".maternity").hide();
      $(".medical").hide();
      $(".paternity").hide();
      $(".marriage").hide();
      $(".unrecorded").show();
      $(".remarks").show();
      $(".nodata").hide();
    }
  });
});

$(document).ready(function(){
  $("#select_url").change(function(){
    var selectedOption = $(this).find("option:selected").text();
    var dateDescArray = selectedOption.split("|");
    var date = dateDescArray[0].trim();
    var desc = dateDescArray[1].trim();
    $("#reason").val("LEAVE IN LIEU - "+desc+" ("+date+")");
  });
});

$(document).ready(function(){
  $("#apply").click(function(){
    var leave = $("#select_leave").val();
    var cname = '<?php echo $cname; ?>';
    var staff = '<?php echo $staff; ?>';
    var email = '<?php echo $email; ?>';
    var mnoee = '<?php echo $mnoee; ?>';
    var fnoee = '<?php echo $fnoee; ?>';
    var hnoee = '<?php echo $hnoee; ?>';

    if(leave == '2'){
      var start = formatDate($("#start_02").val());
      var end = $("#end_02").html();
      var ent = $("#entitle_02").html();
      var reason = $("#reason").val();
      Swal.fire({
        title: "Maternity Leave",
        html: '<div class="row">' +
            '<div class="col-12"><b>' + start + ' - ' + end + '</b></div>' +
            '<div class="col-12">Entitlement : ' + ent + '</div>' +
            '</div>',
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          var cnoee = '<?php echo $emid; ?>';
          var formData = new FormData();
          var file = $('#file_02')[0].files[0];

          if (!file) {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Please upload Baby Birth Certificate</b>'
            });
            return false;
          }

          if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Only JPG and PNG allowed</b>'
            });
            return false;
          }

          if (file.size > 1048576) {
            var img = new Image();
            img.onload = function () {
              var canvas = document.createElement('canvas');
              var ctx = canvas.getContext('2d');
              var MAX_WIDTH = 600;
              var MAX_HEIGHT = 1068;
              var width = img.width;
              var height = img.height;

              if (width > height) {
                if (width > MAX_WIDTH) {
                  height *= MAX_WIDTH / width;
                  width = MAX_WIDTH;
                }
              }else{
                if (height > MAX_HEIGHT) {
                  width *= MAX_HEIGHT / height;
                  height = MAX_HEIGHT;
                }
              }

              canvas.width = width;
              canvas.height = height;

              ctx.drawImage(img, 0, 0, width, height);
              canvas.toBlob(function (blob) {
                var extension = file.type === 'image/jpeg' ? '.jpg' : '.png';
                var fileWithExtension = new File([blob], 'filename' + extension, { type: file.type });
                formData.append('add_maternity', start);
                formData.append('end', end);
                formData.append('ent', ent);
                formData.append('reason', reason);
                formData.append('cnoee', cnoee);

                formData.append('cname', cname);
                formData.append('staff', staff);
                formData.append('email', email);
                formData.append('mnoee', mnoee);
                formData.append('fnoee', fnoee);

                formData.append('file', fileWithExtension);
                $.ajax({
                  url: "api_main",
                  type: 'POST',
                  data: formData,
                  contentType: false,
                  processData: false,
                  beforeSend: function () {
                    Swal.fire({
                      title: 'PROCESSING',
                      allowEscapeKey: false,
                      allowOutsideClick: false,
                      didOpen: () => {
                        Swal.showLoading();
                      },
                    });
                  },
                  success: function (response) {
                    window.location.href = "leave_pending";
                  }
                });
              }, file.type);
            };
            img.src = URL.createObjectURL(file);
          }else{
            formData.append('add_maternity', start);
            formData.append('end', end);
            formData.append('ent', ent);
            formData.append('reason', reason);
            formData.append('cnoee', cnoee);

            formData.append('cname', cname);
            formData.append('staff', staff);
            formData.append('email', email);
            formData.append('mnoee', mnoee);
            formData.append('fnoee', fnoee);

            formData.append('file', file);
            $.ajax({
              url: "api_main",
              type: 'POST',
              data: formData,
              contentType: false,
              processData: false,
              beforeSend: function () {
                Swal.fire({
                  title: 'PROCESSING',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
              },
              success: function (response) {
                window.location.href = "leave_pending";
              }
            });
          }
        }
      });
    }else if(leave == '3'){
      var start = formatDate($("#start_03").val());
      var end = $("#end_03").html();
      var ent = parseInt($("#mdays_03").val());
      var reason = $("#reason").val();
      Swal.fire({
        title: "Medical Leave",
        html: '<div class="row">' +
            '<div class="col-12"><b>' + start + ' - ' + end + '</b></div>' +
            '<div class="col-12">Entitlement : ' + ent + ' Days</div>' +
            '</div>',
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          var cnoee = '<?php echo $emid; ?>';
          var formData = new FormData();
          var file = $('#file_03')[0].files[0];

          if (!file) {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Please upload Medical Certificate</b>'
            });
            return false;
          }

          if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Only JPG and PNG allowed</b>'
            });
            return false;
          }

          if (file.size > 1048576) {
            var img = new Image();
            img.onload = function () {
              var canvas = document.createElement('canvas');
              var ctx = canvas.getContext('2d');
              var MAX_WIDTH = 600;
              var MAX_HEIGHT = 1068;
              var width = img.width;
              var height = img.height;

              if (width > height) {
                if (width > MAX_WIDTH) {
                  height *= MAX_WIDTH / width;
                  width = MAX_WIDTH;
                }
              }else{
                if (height > MAX_HEIGHT) {
                  width *= MAX_HEIGHT / height;
                  height = MAX_HEIGHT;
                }
              }

              canvas.width = width;
              canvas.height = height;

              ctx.drawImage(img, 0, 0, width, height);
              canvas.toBlob(function (blob) {
                var extension = file.type === 'image/jpeg' ? '.jpg' : '.png';
                var fileWithExtension = new File([blob], 'filename' + extension, { type: file.type });
                formData.append('add_medical', start);
                formData.append('end', end);
                formData.append('ent', ent);
                formData.append('reason', reason);
                formData.append('cnoee', cnoee);

                formData.append('cname', cname);
                formData.append('staff', staff);
                formData.append('email', email);
                formData.append('mnoee', mnoee);
                formData.append('fnoee', fnoee);

                formData.append('file', fileWithExtension);
                $.ajax({
                  url: "api_main",
                  type: 'POST',
                  data: formData,
                  contentType: false,
                  processData: false,
                  beforeSend: function () {
                    Swal.fire({
                      title: 'PROCESSING',
                      allowEscapeKey: false,
                      allowOutsideClick: false,
                      didOpen: () => {
                        Swal.showLoading();
                      },
                    });
                  },
                  success: function (response) {
                    window.location.href = "leave_pending";
                  }
                });
              }, file.type);
            };
            img.src = URL.createObjectURL(file);
          }else{
            formData.append('add_medical', start);
            formData.append('end', end);
            formData.append('ent', ent);
            formData.append('reason', reason);
            formData.append('cnoee', cnoee);
            formData.append('staff', staff);
            formData.append('email', email);
            formData.append('hnoee', hnoee);

            formData.append('file', file);
            $.ajax({
              url: "api_main",
              type: 'POST',
              data: formData,
              contentType: false,
              processData: false,
              beforeSend: function () {
                Swal.fire({
                  title: 'PROCESSING',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
              },
              success: function (response) {
                window.location.href = "leave_pending";
              }
            });
          }
        }
      });
    }else if(leave == '10') {
      var start = formatDate($("#start_10").val());
      var end = $("#end_10").html();
      var ent = $("#entitle_10").html();
      var reason = $("#reason").val();
      Swal.fire({
        title: "Paternity Leave",
        html: '<div class="row">' +
            '<div class="col-12"><b>' + start + ' - ' + end + '</b></div>' +
            '<div class="col-12">Entitlement : ' + ent + '</div>' +
            '</div>',
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          var cnoee = '<?php echo $emid; ?>';
          var formData = new FormData();
          var file = $('#file_10')[0].files[0];

          if (!file) {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Please upload Baby Birth Certificate</b>'
            });
            return false;
          }

          if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Only JPG and PNG allowed</b>'
            });
            return false;
          }

          if (file.size > 1048576) {
            var img = new Image();
            img.onload = function () {
              var canvas = document.createElement('canvas');
              var ctx = canvas.getContext('2d');
              var MAX_WIDTH = 600;
              var MAX_HEIGHT = 1068;
              var width = img.width;
              var height = img.height;

              if (width > height) {
                if (width > MAX_WIDTH) {
                  height *= MAX_WIDTH / width;
                  width = MAX_WIDTH;
                }
              }else{
                if (height > MAX_HEIGHT) {
                  width *= MAX_HEIGHT / height;
                  height = MAX_HEIGHT;
                }
              }

              canvas.width = width;
              canvas.height = height;

              ctx.drawImage(img, 0, 0, width, height);
              canvas.toBlob(function (blob) {
                var extension = file.type === 'image/jpeg' ? '.jpg' : '.png';
                var fileWithExtension = new File([blob], 'filename' + extension, { type: file.type });
                formData.append('add_paternity', start);
                formData.append('end', end);
                formData.append('ent', ent);
                formData.append('reason', reason);
                formData.append('cnoee', cnoee);

                formData.append('cname', cname);
                formData.append('staff', staff);
                formData.append('email', email);
                formData.append('mnoee', mnoee);
                formData.append('fnoee', fnoee);

                formData.append('file', fileWithExtension);
                $.ajax({
                  url: "api_main",
                  type: 'POST',
                  data: formData,
                  contentType: false,
                  processData: false,
                  beforeSend: function () {
                    Swal.fire({
                      title: 'PROCESSING',
                      allowEscapeKey: false,
                      allowOutsideClick: false,
                      didOpen: () => {
                        Swal.showLoading();
                      },
                    });
                  },
                  success: function (response) {
                    window.location.href = "leave_pending";
                  }
                });
              }, file.type);
            };
            img.src = URL.createObjectURL(file);
          }else{
            formData.append('add_paternity', start);
            formData.append('end', end);
            formData.append('ent', ent);
            formData.append('reason', reason);
            formData.append('cnoee', cnoee);

            formData.append('cname', cname);
            formData.append('staff', staff);
            formData.append('email', email);
            formData.append('mnoee', mnoee);
            formData.append('fnoee', fnoee);

            formData.append('file', file);
            $.ajax({
              url: "api_main",
              type: 'POST',
              data: formData,
              contentType: false,
              processData: false,
              beforeSend: function () {
                Swal.fire({
                  title: 'PROCESSING',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
              },
              success: function (response) {
                window.location.href = "leave_pending";
              }
            });
          }
        }
      });
    }else if(leave == '12'){
      var start = formatDate($("#start_12").val());
      var end = $("#end_12").html();
      var ent = $("#entitle_12").html();
      var reason = $("#reason").val();
      Swal.fire({
        title: "Marriage Leave",
        html: '<div class="row">' +
            '<div class="col-12"><b>' + start + ' - ' + end + '</b></div>' +
            '<div class="col-12">Entitlement : ' + ent + '</div>' +
            '</div>',
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function () {
          var cnoee = '<?php echo $emid; ?>';
          var formData = new FormData();
          var file = $('#file_12')[0].files[0];

          if (!file) {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Please upload Marriage Certificate</b>'
            });
            return false;
          }

          if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
            Swal.fire({
              title: '<b class="text-danger">Error</b>',
              html: '<b>Only JPG and PNG allowed</b>'
            });
            return false;
          }

          if (file.size > 1048576) {
            var img = new Image();
            img.onload = function () {
              var canvas = document.createElement('canvas');
              var ctx = canvas.getContext('2d');
              var MAX_WIDTH = 600;
              var MAX_HEIGHT = 1068;
              var width = img.width;
              var height = img.height;

              if (width > height) {
                if (width > MAX_WIDTH) {
                  height *= MAX_WIDTH / width;
                  width = MAX_WIDTH;
                }
              }else{
                if (height > MAX_HEIGHT) {
                  width *= MAX_HEIGHT / height;
                  height = MAX_HEIGHT;
                }
              }

              canvas.width = width;
              canvas.height = height;

              ctx.drawImage(img, 0, 0, width, height);
              canvas.toBlob(function (blob) {
                var extension = file.type === 'image/jpeg' ? '.jpg' : '.png';
                var fileWithExtension = new File([blob], 'filename' + extension, { type: file.type });
                formData.append('add_marriage', start);
                formData.append('end', end);
                formData.append('ent', ent);
                formData.append('reason', reason);
                formData.append('cnoee', cnoee);

                formData.append('cname', cname);
                formData.append('staff', staff);
                formData.append('email', email);
                formData.append('mnoee', mnoee);
                formData.append('fnoee', fnoee);

                formData.append('file', fileWithExtension);
                $.ajax({
                  url: "api_main",
                  type: 'POST',
                  data: formData,
                  contentType: false,
                  processData: false,
                  beforeSend: function () {
                    Swal.fire({
                      title: 'PROCESSING',
                      allowEscapeKey: false,
                      allowOutsideClick: false,
                      didOpen: () => {
                        Swal.showLoading();
                      },
                    });
                  },
                  success: function (response) {
                    window.location.href = "leave_pending";
                  }
                });
              }, file.type);
            };
            img.src = URL.createObjectURL(file);
          }else{
            formData.append('add_marriage', start);
            formData.append('end', end);
            formData.append('ent', ent);
            formData.append('reason', reason);
            formData.append('cnoee', cnoee);

            formData.append('cname', cname);
            formData.append('staff', staff);
            formData.append('email', email);
            formData.append('mnoee', mnoee);
            formData.append('fnoee', fnoee);

            formData.append('file', file);
            $.ajax({
              url: "api_main",
              type: 'POST',
              data: formData,
              contentType: false,
              processData: false,
              beforeSend: function () {
                Swal.fire({
                  title: 'PROCESSING',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
              },
              success: function (response) {
                window.location.href = "leave_pending";
              }
            });
          }
        }
      });
    }else if(leave == '14'){
      var url = $("#select_url").val();
      var start = formatDate($("#start_14").val());
      var reason = $("#reason").val();
      if(url == ''){
        Swal.fire("URL Entitlement required");
      }else{
        Swal.fire({
          title: "Unrecorded Leave<br>"+formatDate(start),
          html: '<div class="btn-group" role="group" style="margin-bottom: 15px;">' +
                  '<input type="radio" class="btn-check" name="leave" id="leave1" value="10">' +
                  '<label class="btn btn-outline-primary" for="leave1">Morning</label>' +
                  '<input type="radio" class="btn-check" name="leave" id="leave2" value="11" checked>' +
                  '<label class="btn btn-outline-primary" for="leave2">Full Day</label>' +
                  '<input type="radio" class="btn-check" name="leave" id="leave3" value="01">' +
                  '<label class="btn btn-outline-primary" for="leave3">Evening</label>' +
                '</div>',
          showCancelButton: true,
          confirmButtonText: 'Proceed',
          cancelButtonText: 'Cancel',
          showLoaderOnConfirm: true,
          preConfirm: function() {
            var add_leave = $("input[name='leave']:checked").val();
            var cnoee = '<?php echo $emid; ?>';
            return $.ajax({
              url: "api_main",
              type: 'POST',
              data: {add_url:add_leave,start:start,reason:reason,cnoee:cnoee,cname:cname,staff:staff,email:email,mnoee:mnoee,fnoee:fnoee},
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
                window.location.href = "leave_pending";
              }
            });
          }
        });
      }
    }
  });
});

$(document).ready(function() {
  var choicesItem = $('.choices__item');
  choicesItem.data('deletable', null);
  choicesItem.find('.choices__button').remove();
});
</script>