<div class="row">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="md-form">
          <span id="show_employee"></span>
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
$(document).ready(function(){
  $.ajax({
    url: 'modules/employees/api_ajax',
    type: 'POST',
    dataType: 'html',
    data: { init_employee: true },
    success: function(response) {
      $('#show_employee').html(response);
    }
  });
});
</script>