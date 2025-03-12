<?php 

include('../api.php');

$s1 = "SELECT * FROM sys_workflow_divisional";
$r1 = $conn->query($s1);

$s2 = "SELECT * FROM sys_general_dcmisc";
$r2 = $conn->query($s2);


?>
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="md-form">
          <select id="search" class="sec-select" data-mdb-visible-options="15" data-mdb-select-init>
            <option value="" disabled selected>- Select -</option>
            <?php
            foreach ($r1 as $k1 => $v1) { 
              foreach ($r2 as $k2 => $v2) {
                if($v2['CTYPE'] == 'DIVSN' && $v2['CCODE'] == $v1['CDIVISION']){
                  $division = $v2['CDESC'];
                }
              }

            ?>
            <option value="<?php echo $v1['CDIVISION']; ?>" data-mdb-icon="../img/icon.png"><?php echo $division; ?></option>
            <?php } ?>
          </select>
          <label class="form-label select-label text-primary">
            <b>Division</b>
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row text-center loader" style="margin-top: 20px;">
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
        <img src="../img/nodata.png" class="img-fluid"> 
      </div>
    </div>
  </div>
</div>
<span id="show_data"></span>
<script type="text/javascript">
$('.loader').hide();
$(document).ready(function() {
  $("#search").change(function(){
    var code = $(this).val();
    $.ajax({
      url: 'modules/attendance/main',
      beforeSend: function() {    
        $('.loader').show();
        $('.no_data').hide();
        $('#show_data').hide();
      },
      success: function(data) {
        $('.loader').hide();
        $('.no_data').hide();
        $("#show_data").html(data).show();
      }
    });
  });
});
</script>