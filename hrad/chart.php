<?php 

include('../api.php');

$sql = "SELECT * FROM sys_general_dcmisc AS A JOIN employees_demas AS B ON A.CCODE = B.CDIVISION WHERE B.DRESIGN = '0000-00-00' AND A.CTYPE = 'DIVSN' GROUP BY B.CDIVISION";
$result = $conn->query($sql);

?>
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <select id="search_table" class="select_data" style="width: 100%;">
              <option value="1">WORKFLOW CHART</option>
              <option value="2">WORKFLOW TABLE</option>
            </select>
          </div>
          <div class="col-8">
            <select id="search_viewer" class="select_data" style="width: 100%;">
              <?php foreach($result as $row){ ?>
              <option value="<?php echo $row['CCODE'] ?>">
                <?php echo $row['CDESC']." | ".$row['CCODE']; ?>
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
<br class="sub_loader">
<div class="sub_loader tab-content card pt-3">
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
<script type="text/javascript">
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