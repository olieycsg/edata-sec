<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <b><i class="fas fa-caret-right"></i> Public Holiday Setup</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 col-6">
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
          <div class="col-md-6 col-6">
            <div class="md-form">
              <select id="view" class="sec-select" data-mdb-size="sm" data-mdb-select-init>
                <option value="lists" data-icon="img/icon.png" class="rounded-circle">LIST</option>
                <option value="table" data-icon="img/icon.png" class="rounded-circle">TABLE</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="all_public_holiday"></span>
<div class="row text-center sub_loader" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(".sub_loader").hide();
$(".nodata").show();
sec_function();
$(document).ready(function(){
  $("#year").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_public_holiday").hide();
    var year = $("#year").val();
    var view = $("#view").val();
    $.ajax({
      url: "modules/leave/ajax/ajax_public_holiday",
      type:'POST',
      data:{year:year,view:view},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_public_holiday").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $("#view").change(function(){
    $(".sub_loader").show();
    $(".nodata").hide();
    $("#all_public_holiday").hide();
    var year = $("#year").val();
    var view = $("#view").val();
    $.ajax({
      url: "modules/leave/ajax/ajax_public_holiday",
      type:'POST',
      data:{year:year,view:view},
      success: function(response){
        $(".sub_loader").hide();
        $(".nodata").hide();
        $("#all_public_holiday").show().html(response);
      }
    });
  });
});

$(document).ready(function(){
  $(".sub_loader").show();
  $(".nodata").hide();
  $("#all_public_holiday").hide();
  var year = $("#year").val();
  var view = $("#view").val();
  $.ajax({
    url: "modules/leave/ajax/ajax_public_holiday",
    type:'POST',
    data:{year:year,view:view},
    success: function(response){
      $(".sub_loader").hide();
      $(".nodata").hide();
      $("#all_public_holiday").show().html(response);
    }
  });
});
</script>