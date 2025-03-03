<?php $data = ['1' => "Div. Head Setup", '2' => "Div. Secretary Setup", '3' => "Workflow Setup", '4' => "Workflow Viewer", '5' => "Basic Codes"]; ?>
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="md-form">
          <select id="search" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
            <option value="" disabled selected>- Select -</option>
            <?php foreach ($data as $value => $text){ ?>
            <option value="<?php echo $value; ?>" data-mdb-icon="../img/icon.png"><?php echo $text; ?></option>
            <?php } ?>
          </select>
          <label class="form-label select-label text-primary">
            <b>System</b>
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
    if(code == '1'){
      $.ajax({
        url: 'modules/system/div_head',
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
    }else if(code == '2'){
      $.ajax({
        url: 'modules/system/div_secretary',
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
    }else if(code == '3'){
      $.ajax({
        url: 'modules/system/viewer_setup',
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
    }else if(code == '4'){
      $.ajax({
        url: 'modules/system/viewer',
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
    }else if(code == '5'){
      $.ajax({
        url: 'modules/system/codes_list',
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
    }
  });
});
</script>