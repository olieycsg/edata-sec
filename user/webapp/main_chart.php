<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];
$code = $_GET['code'];

$s1 = "SELECT * FROM sys_workflow_divisional";
$r1 = $conn->query($s1);

$s2 = "SELECT * FROM sys_general_dcmisc";
$r2 = $conn->query($s2);

?>
<div id="content">
  <body class="nk-body">
    <div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap">
          <?php include('navbar_chart.php'); ?>
          <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
              <div class="nk-ibx">
                <div class="nk-ibx-body">
                  <div class="list-group">
                    <div class="card" style="margin-top: 10px; margin-left: 10px; margin-right: 10px;">
                      <div class="card-body" style="text-align: center;">
                        <div class="btn-group" role="group">
                          <input type="radio" class="btn-check" id="chart" name="mode" checked>
                          <label class="btn btn-outline-info" for="chart"><b><i class="fas fa-sitemap"></i> CHART</b></label>
                          <input type="radio" class="btn-check" id="list" name="mode">
                          <label class="btn btn-outline-info" for="list"><b><i class="fas fa-list"></i> LIST</b></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <span id="show_data"></span>
          </div>
        </div>
      </div>
    </div>
  </body>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
  var mode = $("input[name='mode']:checked");
  if (mode.length > 0) {
    $.ajax({
      url: "view_chart",
      type:'POST',
      data:{code:'<?php echo $code; ?>'},
      beforeSend: function() {
        Swal.fire({
          title: 'LOADING',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function(response) {
        Swal.close();
        $("#show_data").html(response);
      }
    });
  }
});

$(document).ready(function() {
  $("input[name='mode']").change(function() {
    var mode = $("input[name='mode']:checked").attr('id');
    if (mode === "chart") {
      $.ajax({
        url: "view_chart",
        type: 'POST',
        data: { code: '<?php echo $code; ?>' },
        beforeSend: function() {
          Swal.fire({
            title: 'LOADING',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function(response) {
          Swal.close();
          $("#show_data").html(response);
        }
      });
    }
  });
});

$(document).ready(function() {
  $("input[name='mode']").change(function() {
    var mode = $("input[name='mode']:checked").attr('id');
    if (mode === "list") {
      $.ajax({
        url: "view_list",
        type: 'POST',
        data: { code: '<?php echo $code; ?>' },
        beforeSend: function() {
          Swal.fire({
            title: 'LOADING',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function(response) {
          Swal.close();
          $("#show_data").html(response);
        }
      });
    }
  });
});

</script>