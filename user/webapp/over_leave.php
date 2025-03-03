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

$sql = "SELECT * FROM sys_workflow_divisional_access WHERE CNOEE = '$emid' OR SNOEE = '$emid'";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
  $division[] = $row['CDIVISION'];
}

$division_list = implode("','", array_map([$conn, 'real_escape_string'], $division));

if($emid == '2522-186'){ 
  $sql1 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result1 = $conn->query($sql1);
}else if($emid != '2522-186'){ 
  $sql1 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND CDIVISION IN ('$division_list') ORDER BY CNAME ASC";
  $result1 = $conn->query($sql1);
}

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
                          <div class="col-6">
                            <div class="form-group">
                              <div class="form-control-wrap">
                                <select id="select_type" class="js-select load">
                                  <option value="over_leave" selected>Annual Leave</option>
                                  <option value="over_leave_analytics">Leave Analytics</option>
                                  <option value="over_leave_pending">Pending Leave</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-group">
                              <div class="form-control-wrap">
                                <select id="select_year" class="js-select" data-search="true" data-sort="false">
                                  <?php for ($y = date("Y") + 1; $y >= date("Y") - 3; $y--) { ?>
                                    <option value="<?php echo $y; ?>" <?php if ($y == date('Y')) { echo "selected"; } ?>><?php echo $y; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                              <div class="form-control-wrap">
                                <select id="select_user" class="js-select" data-search="true" data-sort="false">
                                  <option value="">- SELECT EMPLOYEE -</option>
                                  <?php foreach ($result1 as $key1 => $value1) { ?>
                                  <option value="<?php echo $value1['CNOEE']; ?>"><?php echo $value1['CNAME']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <?php if($division == 'ENG'){ ?>
                          <div class="col-12" style="margin-top: 10px;">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="shift_worker">  
                              <label class="form-check-label" for="shift_worker">
                                <b>Shift Worker</b>
                              </label>
                            </div>
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="status" class="calendar-container">
                    <div class="month-container">
                      <div class="calendar text-center">
                        <div class="spinner-border text-success"></div>
                      </div>
                    </div>
                  </div>
                  <div id="show_leave"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
  $("#select_type").change(function(){
    window.location.href = $(this).val();
  });
});

$(document).ready(function(){
  if ($("#shift_worker").is(':checked')) {
    $("#status").show();
    $("#show_leave").hide();
    var emid = $("#select_user").val();
    var year = $("#select_year").val();
    var shift = '1';
    $.ajax({
      url: "ajax_over_leave",
      type:'POST',
      data:{year:year,emid:emid,shift:shift},
      success: function(response) {
        $("#status").hide();
        $("#show_leave").show();
        $("#show_leave").html(response);
        $('[data-bs-toggle="tooltip"]').tooltip({
          customClass: function (tooltip) {
            return 'custom-tooltip';
          }
        });
      }
    });
  } else {
    $(".weekend").removeClass("get_date");
    $("#status").show();
    $("#show_leave").hide();
    var emid = $("#select_user").val();
    var year = $("#select_year").val();
    var shift = '0';
    $.ajax({
      url: "ajax_over_leave",
      type:'POST',
      data:{year:year,emid:emid,shift:shift},
      success: function(response) {
        $("#status").hide();
        $("#show_leave").show();
        $("#show_leave").html(response);
        $('[data-bs-toggle="tooltip"]').tooltip({
          customClass: function (tooltip) {
            return 'custom-tooltip';
          }
        });
      }
    });
  }
});

$(document).ready(function(){
  $("#select_user").change(function(){
    if ($("#shift_worker").is(':checked')) {
      $("#status").show();
      $("#show_leave").hide();
      var emid = $("#select_user").val();
      var year = $("#select_year").val();
      var shift = '1';
      $.ajax({
        url: "ajax_over_leave",
        type:'POST',
        data:{year:year,emid:emid,shift:shift},
        success: function(response) {
          $("#status").hide();
          $("#show_leave").show();
          $("#show_leave").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    } else {
      $(".weekend").removeClass("get_date");
      $("#status").show();
      $("#show_leave").hide();
      var emid = $("#select_user").val();
      var year = $("#select_year").val();
      var shift = '0';
      $.ajax({
        url: "ajax_over_leave",
        type:'POST',
        data:{year:year,emid:emid,shift:shift},
        success: function(response) {
          $("#status").hide();
          $("#show_leave").show();
          $("#show_leave").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    }
  });
});

$(document).ready(function(){
  $("#select_year").change(function(){
    if ($("#shift_worker").is(':checked')) {
      $("#status").show();
      $("#show_leave").hide();
      var emid = $("#select_user").val();
      var year = $("#select_year").val();
      var shift = '1';
      $.ajax({
        url: "ajax_over_leave",
        type:'POST',
        data:{year:year,emid:emid,shift:shift},
        success: function(response) {
          $("#status").hide();
          $("#show_leave").show();
          $("#show_leave").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    } else {
      $(".weekend").removeClass("get_date");
      $("#status").show();
      $("#show_leave").hide();
      var emid = $("#select_user").val();
      var year = $("#select_year").val();
      var shift = '0';
      $.ajax({
        url: "ajax_over_leave",
        type:'POST',
        data:{year:year,emid:emid,shift:shift},
        success: function(response) {
          $("#status").hide();
          $("#show_leave").show();
          $("#show_leave").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    }
  });
});

$(document).ready(function(){
  $("#shift_worker").change(function(){
    if ($(this).is(':checked')) {
      $("#status").show();
      $("#show_leave").hide();
      var emid = $("#select_user").val();
      var year = $("#select_year").val();
      var shift = '1';
      $.ajax({
        url: "ajax_over_leave",
        type:'POST',
        data:{year:year,emid:emid,shift:shift},
        success: function(response) {
          $("#status").hide();
          $("#show_leave").show();
          $("#show_leave").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    } else {
      $(".weekend").removeClass("get_date");
      $("#status").show();
      $("#show_leave").hide();
      var emid = $("#select_user").val();
      var year = $("#select_year").val();
      var shift = '0';
      $.ajax({
        url: "ajax_over_leave",
        type:'POST',
        data:{year:year,emid:emid,shift:shift},
        success: function(response) {
          $("#status").hide();
          $("#show_leave").show();
          $("#show_leave").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    }
  });
});
</script>