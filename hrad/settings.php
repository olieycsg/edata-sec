<?php $options = [1 => 'CODES LIST', 2 => 'DIVISIONAL HEAD', 3 => 'DIVISIONAL SECRETARY', 4 => 'WORKFLOW VIEWER']; ?>
<style>
.md-pills li {
  padding: 0.2rem!important;
}
.md-pills .nav-link.active {
  color: #fff!important;
}

.search_settings{
  width: 100%!important;
}
</style>
<div class="tab-content card pt-4">
  <div class="row">
    <div class="col-md-12">
      <div class="md-form">
        <select id="search_settings" class="search_settings">
          <option value="" disabled selected>- SEARCH SETTINGS -</option>
          <?php foreach ($options as $value => $label){ ?>
          <option value="<?php echo $value; ?>">
            <?php echo $label; ?>
          </option>
          <?php } ?>
        </select>
      </div>
    </div>
  </div>
</div>
<br class="loader">
<div class="loader tab-content card pt-3">
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="preloader-wrapper big active text-center">
        <div class="spinner-layer spinner-green-only">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="gap-patch">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_data"></span>
<br class="no_data">
<div class="no_data tab-content card pt-5">
  <div class="row">
    <div class="col-md-12 text-center">
      <img src="img/nodatas.png" class="img-fluid">
    </div>
  </div>
</div>
<script type="text/javascript">
$('.loader').hide();
$(document).ready(function() {
  $("#search_settings").change(function(){
    var code = $(this).val();
    if(code == '1'){
      $.ajax({
        url: 'modules/settings/codes_list',
        beforeSend: function() {    
          $('.loader').show();
          $('.no_data').hide();
          $('#show_data').hide();
        },
        success: function(data) {
          $('.loader').hide();
          $('.no_data').hide();
          $("#show_data").html(data).show();
          footer();
        }
      });
    }else if(code == '2'){
      $.ajax({
        url: 'modules/settings/div_head',
        beforeSend: function() {    
          $('.loader').show();
          $('.no_data').hide();
          $('#show_data').hide();
        },
        success: function(data) {
          $('.loader').hide();
          $('.no_data').hide();
          $("#show_data").html(data).show();
          footer();
        }
      });
    }else if(code == '3'){
      $.ajax({
        url: 'modules/settings/div_secretary',
        beforeSend: function() {    
          $('.loader').show();
          $('.no_data').hide();
          $('#show_data').hide();
        },
        success: function(data) {
          $('.loader').hide();
          $('.no_data').hide();
          $("#show_data").html(data).show();
          footer();
        }
      });
    }else if(code == '4'){
      $.ajax({
        url: 'modules/settings/viewer',
        beforeSend: function() {    
          $('.loader').show();
          $('.no_data').hide();
          $('#show_data').hide();
        },
        success: function(data) {
          $('.loader').hide();
          $('.no_data').hide();
          $("#show_data").html(data).show();
          footer();
        }
      });
    }
  });
});
</script>