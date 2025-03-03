<?php

include('../../../api.php');

$sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$result = $conn->query($sql);

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-9">
            <select id="employee" class="sec-select" data-mdb-select-init data-mdb-visible-options="10" data-mdb-filter="true">
              <option value="" disabled selected>- Employee -</option>
              <?php foreach ($result as $key => $value) { ?>
              <option value="<?php echo $value['CNOEE']; ?>" data-mdb-icon="../img/icon.png">
                <?php echo $value['CNAME']; ?>
              </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-3">
            <select id="mType" class="sec-select" data-mdb-select-init>
              <option value="1" data-mdb-icon="../img/icon.png">New Application</option>
              <option value="2" data-mdb-icon="../img/icon.png">Record Application</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_medicalApps"></span>
<div class="row text-center main_loader" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
      </div>
    </div>
  </div>
</div>
<div class="row no_data_ajax" style="margin-top: 20px; text-align: center;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <img src="../../img/nodata.png" class="img-fluid"> 
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  sec_function();
  $(".main_loader").hide();
  $(document).ready(function(){
    $("#employee").change(function(){
      $("#show_medicalApps").hide();
      var type = $("#mType").val();
      if(type == '1'){
        $.ajax({
          url: "modules/medical/ajax/ajax_medicalApps",
          type: "POST",
          data: {
            emid: $(this).val()
          },
          beforeSend: function() {    
            $(".main_loader").show();
            $(".no_data_ajax").hide();
          },
          success: function(response) {
            Swal.close();
            $(".main_loader").hide();
            $("#show_medicalApps").html(response).show();
          },
        });
      }else{
        $.ajax({
          url: "modules/medical/ajax/ajax_medicalApps_record",
          type: "POST",
          data: {
            emid: $(this).val()
          },
          beforeSend: function() {    
            $(".main_loader").show();
            $(".no_data_ajax").hide();
          },
          success: function(response) {
            Swal.close();
            $(".main_loader").hide();
            $("#show_medicalApps").html(response).show();
          },
        });
      }
    });
  });

  $(document).ready(function(){
    $("#mType").change(function(){
      $("#show_medicalApps").hide();
      var type = $(this).val();
      if(type == '1'){
        $.ajax({
          url: "modules/medical/ajax/ajax_medicalApps",
          type: "POST",
          data: {
            emid: $("#employee").val()
          },
          beforeSend: function() {    
            $(".main_loader").show();
            $(".no_data_ajax").hide();
          },
          success: function(response) {
            Swal.close();
            $(".main_loader").hide();
            $("#show_medicalApps").html(response).show();
          },
        });
      }else{
        $.ajax({
          url: "modules/medical/ajax/ajax_medicalApps_record",
          type: "POST",
          data: {
            emid: $("#employee").val()
          },
          beforeSend: function() {    
            $(".main_loader").show();
            $(".no_data_ajax").hide();
          },
          success: function(response) {
            Swal.close();
            $(".main_loader").hide();
            $("#show_medicalApps").html(response).show();
          },
        });
      }
    });
  });
</script>