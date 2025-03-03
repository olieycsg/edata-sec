<?php 

session_start();
$code = $_GET['code'];
$admin = $_SESSION['sid'];

if($admin == '2522-186'){
  $data = ['1' => 'PROFILE', '2' => 'PERSONAL INFO', '3' => 'SERVICES', '4' => 'PAYROLL', '5' => 'SPOUSE & KIN', '6' => 'IN-SERVICE', '7' => 'QUALIFICATIONS', '8' => 'JOB HISTORY', '9' => 'FAMILY INFO', '10' => 'SKILLS', '11' => 'MEMBERSHIP', '12' => 'LOANS INFO'];
}else{
  $data = ['2' => 'PERSONAL INFO', '3' => 'SERVICES', '4' => 'PAYROLL', '5' => 'SPOUSE & KIN', '6' => 'IN-SERVICE', '7' => 'QUALIFICATIONS', '8' => 'JOB HISTORY', '9' => 'FAMILY INFO', '10' => 'SKILLS', '11' => 'MEMBERSHIP', '12' => 'LOANS INFO'];
}

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <div class="row">
          <div class="col-12">
            <b><i class="fas fa-caret-right"></i> Employee Function</b>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="md-form">
          <select id="search" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
            <option value="" disabled selected>- Select -</option>
            <?php foreach ($data as $value => $text){ ?>
            <option value="<?php echo $value; ?>" data-mdb-icon="../img/icon.png"><?php echo $text; ?></option>
            <?php } ?>
          </select>
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
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <img src="../img/nodata.png" class="img-fluid"> 
      </div>
    </div>
  </div>
</div>
<span id="show_get_data"></span>
<script type="text/javascript">
sec_function();
$('.sub_loader').hide();
$(document).ready(function() {
  var code = $(this).val();
  $.ajax({
    url: 'modules/employees/personal?code=<?php echo $code; ?>',
    beforeSend: function() {    
      $('.sub_loader').show();
      $('.no_data').hide();
      $('#show_get_data').hide();
    },
    success: function(data) {
      $.ajax({
        url: 'modules/employees/api_ajax',
        type: 'POST',
        dataType: 'html',
        data: { actv_employee: '<?php echo $code; ?>'},
        success: function(response) {
          $('#show_employee').html(response);
        }
      });
      $('.sub_loader').hide();
      $('.no_data').hide();
      $("#show_get_data").html(data).show();
      $("#search option[value='2']").attr('selected', 'selected');
    }
  });
});

$(document).ready(function() {
  $("#search").change(function(){
    var code = $(this).val();
    if(code == '1'){
      $.ajax({
        url: 'modules/employees/profile?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '2'){
      $.ajax({
        url: 'modules/employees/personal?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '3'){
      $.ajax({
        url: 'modules/employees/services?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '4'){
      $.ajax({
        url: 'modules/employees/payroll?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '5'){
      $.ajax({
        url: 'modules/employees/spouse?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '6'){
      $.ajax({
        url: 'modules/employees/in_service?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '7'){
      $.ajax({
        url: 'modules/employees/qualification?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '8'){
      $.ajax({
        url: 'modules/employees/job_history?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '9'){
      $.ajax({
        url: 'modules/employees/family?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '10'){
      $.ajax({
        url: 'modules/employees/skills?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '11'){
      $.ajax({
        url: 'modules/employees/membership?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }else if(code == '12'){
      $.ajax({
        url: 'modules/employees/loans?code=<?php echo $code; ?>',
        beforeSend: function() {    
          $('.sub_loader').show();
          $('.no_data').hide();
          $('#show_get_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $('.no_data').hide();
          $("#show_get_data").html(data).show();
        }
      });
    }
  });
});
</script>