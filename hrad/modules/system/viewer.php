<?php 

include('../../../api.php');

$sql = "SELECT * FROM sys_general_dcmisc AS A JOIN employees_demas AS B ON A.CCODE = B.CDIVISION WHERE B.DRESIGN = '0000-00-00' AND A.CTYPE = 'DIVSN' GROUP BY B.CDIVISION";
$result = $conn->query($sql);

?>
<style type="text/css">
  .text-left{
    text-align: left;
  }
  .text-right{
    text-align: right;
  }
  .text-center{
    text-align: center;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-primary">
        <b><i class="fas fa-caret-right"></i> Workflow Viewer</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <select id="search_table" class="sec-select" data-mdb-size="sm" data-mdb-select-init>
              <option value="1">WORKFLOW CHART</option>
              <option value="2">WORKFLOW TABLE</option>
            </select>
          </div>
          <div class="col-8">
            <select id="search_viewer" class="sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
              <?php foreach($result as $row){ ?>
              <option value="<?php echo $row['CCODE'] ?>">
                <?php echo $row['CDESC']; ?>
              </option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_ajax_data"></span>
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
sec_function();
$('.sub_loader').hide();
$(document).ready(function() {
  var code = $("#search_viewer").val();
  var table = $("#search_table").val();
  if(table == '1'){
    $.ajax({
      url: 'modules/system/ajax/ajax_viewer?code='+code,
      beforeSend: function() {
        $('.sub_loader').show();
        $('#show_ajax_data').hide();
      },
      success: function(data) {
        $('.sub_loader').hide();
        $("#show_ajax_data").html(data).show();
      }
    });
  }else if(table == '2'){
    $.ajax({
      url: 'modules/system/ajax/ajax_table?code='+code,
      beforeSend: function() {
        $('.sub_loader').show();
        $('#show_ajax_data').hide();
      },
      success: function(data) {
        $('.sub_loader').hide();
        $("#show_ajax_data").html(data).show();
      }
    });
  }
});

$(document).ready(function() {
  $("#search_table").change(function(){
    var code = $("#search_viewer").val();
    var table = $(this).val();
    if(table == '1'){
      $.ajax({
        url: 'modules/system/ajax/ajax_viewer?code='+code,
        beforeSend: function() {
          $('.sub_loader').show();
          $('#show_ajax_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $("#show_ajax_data").html(data).show();
        }
      });
    }else if(table == '2'){
      $.ajax({
        url: 'modules/system/ajax/ajax_table?code='+code,
        beforeSend: function() {
          $('.sub_loader').show();
          $('#show_ajax_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $("#show_ajax_data").html(data).show();
        }
      });
    }
  });
});

$(document).ready(function() {
  $("#search_viewer").change(function(){
    var code = $(this).val();
    var table = $("#search_table").val();
    if(table == '1'){
      $.ajax({
        url: 'modules/system/ajax/ajax_viewer?code='+code,
        beforeSend: function() {
          $('.sub_loader').show();
          $('#show_ajax_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $("#show_ajax_data").html(data).show();
        }
      });
    }else if(table == '2'){
      $.ajax({
        url: 'modules/system/ajax/ajax_table?code='+code,
        beforeSend: function() {
          $('.sub_loader').show();
          $('#show_ajax_data').hide();
        },
        success: function(data) {
          $('.sub_loader').hide();
          $("#show_ajax_data").html(data).show();
        }
      });
    }
  });
});
</script>