<?php 

include('../../../api.php');

$sql = "SELECT * FROM sys_workflow_divisional";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$result2 = $conn->query($sql2);

?>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <b><i class="fas fa-caret-right"></i> Leave Adjustments</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 col-6" style="padding: 5px;">
            <div class="md-form">
              <select id="type" class="sec-select" data-mdb-size="sm" data-mdb-select-init>
                <option value="1" data-icon="img/icon.png" class="rounded-circle">BULK PROCESS</option>
                <option value="2" data-icon="img/icon.png" class="rounded-circle">BY EMPLOYEE</option>
              </select>
            </div>
          </div>
          <div class="col-md-3 col-6" id="show_year" style="padding: 5px;">
            <div class="md-form">
              <select id="year" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
                <?php for ($y = date('Y'); $y >= date('Y') - 4; $y--) { ?>
                <option value="<?php echo $y; ?>" data-icon="img/icon.png" class="rounded-circle" <?php if($y == date("Y")){ echo "selected"; } ?>>
                  <?php echo $y; ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-12" id="show_division" style="padding: 5px;">
            <div class="md-form">
              <select id="division" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
                <option value="all">ALL DIVISION</option>
                <?php 
                foreach ($result as $key => $value) { 
                  foreach ($result1 as $key1 => $value1) {
                    if($value1['CCODE'] == $value['CDIVISION'] && $value1['CTYPE'] == 'DIVSN'){
                      $desc = $value1['CDESC'];
                    }
                  }
                ?>
                <option value="<?php echo $value['CDIVISION']; ?>" data-desc="<?php echo $desc; ?>" data-icon="img/icon.png" class="rounded-circle">
                  <?php echo $desc; ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-9" id="show_employee" style="padding: 5px;">
            <div class="md-form">
              <select id="employee" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
                <?php foreach ($result2 as $key2 => $value2) { ?>
                <option value="<?php echo $value2['CNOEE']; ?>" data-icon="img/icon.png" class="rounded-circle">
                  <?php echo $value2['CNAME']; ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row text-center sub_loader" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
      </div>
    </div>
  </div>
</div>
<span id="all_leave_adjustment"></span>
<script type="text/javascript">
$("#show_year").hide();
$("#show_division").hide();
$("#show_employee").hide();
$(".sub_loader").hide();
$(".nodata").show();
sec_function();
$(document).ready(function(){
  var type = $("#type").val();
  if(type == 1){
    $("#show_year").show();
    $("#show_division").show();
    $("#show_employee").hide();
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_adjustment").hide();
    var year = $("#year").val();
    var divi = $("#division").val();
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_adjustment_bulk",
      type:'POST',
      data:{year:year,divi:divi},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_adjustment").show().html(response);
      }
    });
  }
});

$(document).ready(function(){
  $("#type").change(function(){
    var type = $(this).val();
    if(type == 1){
      $("#show_year").show();
      $("#show_division").show();
      $("#show_employee").hide();
      $(".sub_loader").show();
      $(".nodata").hide();
      $("#all_leave_adjustment").hide();
      var year = $("#year").val();
      var divi = $("#division").val();
      $.ajax({
        url: "modules/leave/ajax/ajax_leave_adjustment_bulk",
        type:'POST',
        data:{year:year,divi:divi},
        success: function(response){
          $(".sub_loader").hide();
          $(".nodata").hide();
          $("#all_leave_adjustment").show().html(response);
        }
      });
    }else if(type == 2){
      $("#show_year").hide();
      $("#show_division").hide();
      $("#show_employee").show();
      $(".sub_loader").show();
      $(".nodata").hide();
      $("#all_leave_adjustment").hide();
      var employee = $("#employee").val();
      $.ajax({
        url: "modules/leave/ajax/ajax_leave_adjustment",
        type:'POST',
        data:{employee:employee},
        success: function(response){
          $(".sub_loader").hide();
          $(".nodata").hide();
          $("#all_leave_adjustment").show().html(response);
        }
      });
    }
  });
});

$(document).ready(function(){
  $("#year").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_adjustment").hide();
    var year = $("#year").val();
    var divi = $("#division").val();
    var desc = $("#division option:selected").attr("data-desc");
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_adjustment_bulk",
      type:'POST',
      data:{year:year,divi:divi,desc:desc},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_adjustment").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $("#division").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_adjustment").hide();
    var year = $("#year").val();
    var divi = $("#division").val();
    var desc = $("#division option:selected").attr("data-desc");
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_adjustment_bulk",
      type:'POST',
      data:{year:year,divi:divi,desc:desc},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_adjustment").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $("#employee").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_adjustment").hide();
    var employee = $("#employee").val();
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_adjustment",
      type:'POST',
      data:{employee:employee},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_adjustment").show().html(response);
      }
    });
  });
});
</script>