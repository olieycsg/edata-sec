<?php

include('../../../api.php');

$sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM eleave_leave_type";
$result1 = $conn->query($sql1);

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <b><i class="fas fa-caret-right"></i> Apply Leave</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-10" style="padding: 10px;">
            <select id="employee" class="sec-select" data-mdb-select-init data-mdb-visible-options="10"data-mdb-filter="true">
              <option value="" disabled selected>- Employee -</option>
              <?php foreach ($result as $key => $value) { ?>
              <option value="<?php echo $value['CNOEE']; ?>" data-sex="<?php echo $value['CSEX']; ?>" data-mdb-icon="../img/icon.png">
                <?php echo $value['CNAME']; ?>
              </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <select id="year" class="sec-select" data-mdb-select-init>
              <?php for ($i = date("Y"); $i >= date("Y") - 8; $i--) { ?>
              <option value="<?php echo $i; ?>" data-mdb-icon="../img/icon.png" <?php if($i == date("Y")){ echo "selected"; } ?> >
                <?php echo $i; ?>
              </option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_leave"></span>
<div class="row text-center main_loader" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
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
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <img src="../../img/nodata.png" class="img-fluid"> 
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(".sub_loader").hide();
$(".nodata").show();
$(".main_loader").hide();
sec_function();

$("#type option").attr('disabled', 'disabled');
$(document).ready(function(){
  $("#employee").change(function(){
    $("#show_leave").hide();
    $.ajax({
      url: "modules/leave/ajax/ajax_apply",
      type: "POST",
      data: {
        emid: $(this).val(),
        year: $("#year").val()
      },
      beforeSend: function() {    
        $(".main_loader").show();
        $(".no_data").hide();
      },
      success: function(response) {
        Swal.close();
        $(".main_loader").hide();
        $("#show_leave").html(response).show();
      },
    });
  });
});

$(document).ready(function(){
  $("#year").change(function(){
    $("#show_leave").hide();
    $.ajax({
      url: "modules/leave/ajax/ajax_apply",
      type: "POST",
      data: {
        emid: $("#employee").val(),
        year: $(this).val()
      },
      beforeSend: function() {    
        $(".main_loader").show();
        $(".no_data").hide();
      },
      success: function(response) {
        Swal.close();
        $(".no_data").hide();
        $(".main_loader").hide();
        $("#show_leave").html(response).show();
      },
    });
  });
});

</script>