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
        <b><i class="fas fa-caret-right"></i> Who's On Leave</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-7 col-12" style="padding: 5px;">
            <select id="division" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
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
<span id="all_onleave"></span>
<script type="text/javascript">
$(".sub_loader").hide();
$(".nodata").show();
sec_function();
$(document).ready(function(){
  $("#division").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_onleave").hide();
    var year = $("#year").val();
    var month = $("#month").val();
    var division = $("#division").val();
    $.ajax({
      url: "modules/leave/ajax/ajax_onleave",
      type:'POST',
      data:{year:year,month:month,division:division},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_onleave").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $("#month").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_onleave").hide();
    var year = $("#year").val();
    var month = $("#month").val();
    var division = $("#division").val();
    $.ajax({
      url: "modules/leave/ajax/ajax_onleave",
      type:'POST',
      data:{year:year,month:month,division:division},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_onleave").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $("#year").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_onleave").hide();
    var year = $("#year").val();
    var month = $("#month").val();
    var division = $("#division").val();
    $.ajax({
      url: "modules/leave/ajax/ajax_onleave",
      type:'POST',
      data:{year:year,month:month,division:division},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_onleave").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $(".sub_loader").show();
  $(".nodata").hide();
  $("#all_onleave").hide();
  var year = $("#year").val();
  var month = $("#month").val();
  var division = $("#division").val();
  $.ajax({
    url: "modules/leave/ajax/ajax_onleave",
    type:'POST',
    data:{year:year,month:month,division:division},
    success: function(response){
      $(".sub_loader").hide();
      $(".nodata").hide();
      $("#all_onleave").show().html(response);
    }
  });
});
</script>