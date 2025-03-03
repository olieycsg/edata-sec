<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <select id="employee" class="sec-select" data-mdb-select-init>
              <option value="" disabled selected>- Function -</option>
              <option value="1" data-mdb-icon="../img/icon.png">Panel Doctor</option>
              <option value="2" data-mdb-icon="../img/icon.png">Medical Entitlement</option>
              <option value="3" data-mdb-icon="../img/icon.png">Medical Benefit</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_medicalSetup"></span>
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
      $("#show_medicalSetup").hide();
      var setup = $(this).val();
      if(setup == '1'){
        $.ajax({
          url: "modules/medical/ajax/ajax_medicalSetup_doctor",
          type: "POST",
          data: {
            type: setup
          },
          beforeSend: function() {    
            $(".main_loader").show();
            $(".no_data_ajax").hide();
          },
          success: function(response) {
            Swal.close();
            $(".main_loader").hide();
            $("#show_medicalSetup").html(response).show();
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
            $("#show_medicalSetup").html(response).show();
          },
        });
      }
    });
  });
</script>