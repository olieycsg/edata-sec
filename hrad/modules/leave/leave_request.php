<?php

include('../../../api.php');

$sql = "SELECT * FROM sys_workflow_divisional";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <b><i class="fas fa-caret-right"></i> Leave Status</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-7 col-12" style="padding: 5px;">
            <select id="division" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
              <option value="all">ALL DIVISION</option>
              <?php foreach ($result as $key => $value) { ?>
              <option value="<?php echo $value['CDIVISION']; ?>">
              <?php 
              foreach ($result1 as $key1 => $value1) {
                if($value1['CCODE'] == $value['CDIVISION'] && $value1['CTYPE'] == 'DIVSN'){
                  echo $value1['CDESC'];
                }
              }
              ?>
              </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-3 col-6" style="padding: 5px;">
            <div class="md-form">
              <select id="month" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
                <option value="all">ENTIRE YEAR</option>
                <?php for($m = 1; $m <= 12 ; $m++) { ?>
                <option value="<?php echo sprintf("%02d", $m); ?>" data-icon="img/icon.png" class="rounded-circle" <?php if($m == date("m")){ echo "selected"; } ?>>
                  <?php echo strtoupper(date('F', strtotime(date('Y')."-".$m))); ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-2 col-6" style="padding: 5px;">
            <div class="md-form">
              <select id="year" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
                <?php for ($y = date('Y') + 1; $y >= 2000; $y--) { ?>
                <option value="<?php echo $y; ?>" data-icon="img/icon.png" class="rounded-circle" <?php if($y == date("Y")){ echo "selected"; } ?>>
                  <?php echo $y; ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <div class="form-check form-check-inline">
              <input class="form-check-input viewleave" type="radio" id="inlineRadio1" name="viewleave" value="all" checked>
              <label class="form-check-label" for="inlineRadio1"><b>All</b></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input viewleave" type="radio" id="inlineRadio2" name="viewleave" value="approved">
              <label class="form-check-label" for="inlineRadio2"><b>Approved</b></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input viewleave" type="radio" id="inlineRadio3" name="viewleave" value="pending">
              <label class="form-check-label" for="inlineRadio3"><b>Pending</b></label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input viewleave" type="radio" id="inlineRadio4" name="viewleave" value="recommended">
              <label class="form-check-label" for="inlineRadio4"><b>Recommended</b></label>
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
<span id="all_leave_request"></span>
<script type="text/javascript">
  
sec_function();
$(".sub_loader").hide();
$(".nodata").show();

$(document).ready(function(){
  $("#division").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_request").hide();
    var year = $("#year").val();
    var month = $("#month").val();
    var division = $("#division").val();
    var view = $('input[name="viewleave"]:checked').val();
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_request",
      type:'POST',
      data:{year:year,month:month,division:division,view:view},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_request").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $("#month").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_request").hide();
    var year = $("#year").val();
    var month = $("#month").val();
    var division = $("#division").val();
    var view = $('input[name="viewleave"]:checked').val();
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_request",
      type:'POST',
      data:{year:year,month:month,division:division,view:view},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_request").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $("#year").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_request").hide();
    var year = $("#year").val();
    var month = $("#month").val();
    var division = $("#division").val();
    var view = $('input[name="viewleave"]:checked').val();
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_request",
      type:'POST',
      data:{year:year,month:month,division:division,view:view},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_request").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $(".viewleave").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_leave_request").hide();
    var year = $("#year").val();
    var month = $("#month").val();
    var division = $("#division").val();
    var view = $('input[name="viewleave"]:checked').val();
    $.ajax({
      url: "modules/leave/ajax/ajax_leave_request",
      type:'POST',
      data:{year:year,month:month,division:division,view:view},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_leave_request").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $(".sub_loader").show();
  $(".nodata").hide();
  $("#all_leave_request").hide();
  var year = $("#year").val();
  var month = $("#month").val();
  var division = $("#division").val();
  var view = $('input[name="viewleave"]:checked').val();
  $.ajax({
    url: "modules/leave/ajax/ajax_leave_request",
    type:'POST',
    data:{year:year,month:month,division:division,view:view},
    success: function(response){
      $(".sub_loader").hide();
      $(".nodata").hide();
      $("#all_leave_request").show().html(response);
    }
  });
});
</script>