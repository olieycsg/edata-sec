<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];

$s3 = "SELECT * FROM sys_workflow_divisional_access WHERE CNOEE = '$emid' OR SNOEE = '$emid'";
$r3 = $conn->query($s3);

?>
<style>
  .card-main {
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .card-content {
    padding: 20px;
  }

  .card-buttons {
    display: flex;
    background-color: #fff;
    margin-top: auto;
    position: sticky;
    bottom: 0;
    left: 0;

    button {
      flex: 1 1 auto;
      user-select: none;
      background: 0;
      font-size: 13px;
      border: 0;
      padding: 15px 5px;
      cursor: pointer;
      color: #5c5c6d;
      transition: 0.3s;
      font-family: "Jost", sans-serif;
      font-weight: 500;
      outline: 0;
      border-bottom: 3px solid transparent;

      &.is-active,
      &:hover {
        color: #2b2c48;
        border-bottom: 3px solid #8a84ff;
        background: linear-gradient(
          to bottom,
          rgba(127, 199, 231, 0) 0%,
          rgba(207, 204, 255, 0.2) 44%,
          rgba(211, 226, 255, 0.4) 100%
        );
      }
    }
  }
</style>
<div id="content">
  <div class="nk-header nk-header-fixed">
    <div class="container-fluid">
      <div class="nk-header-wrap">
        <div class="nk-header-logo ms-n1">
          <div class="nk-sidebar-toggle">
            <a href="more" style="display: inline-block; margin-right: 10px; text-align: center;" aria-label="Back">
              <h1><i class="fas fa-arrow-left-long load text-danger"></i></h1>
            </a>
            <i class="fas fa-circle-info" onclick="location.reload();"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container" style="margin-top: 80px; margin-bottom: 50px;">
    <div class="nk-content-body">
      <div class="row">
        <div class="col-6">
          <div class="form-group">
            <div class="form-control-wrap">
              <select id="year" class="js-select load">
                <?php for ($i = 2025; $i <= date('Y'); $i++) { ?>
                <option value="<?php echo $i; ?>" <?php if ($i == date('Y')) { echo "selected"; } ?>><?php echo $i; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <div class="form-control-wrap">
              <select id="month" class="js-select load">
                <?php for ($j = 1; $j <= 12; $j++) { ?>
                  <option value="<?php echo $j; ?>" <?php if ($j == date('m')) { echo "selected"; } ?>><?php echo date("F", strtotime('2025-'.$j.'-01')); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div id="show_data"></div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    var year = $("#year").val();
    var month = $("#month").val();
    $.ajax({
      url: "ajax_attendance",
      type:'POST',
      data:{year:year,month:month},
      success: function(response) {
        Swal.close();
        $("#show_data").html(response);
        $('[data-bs-toggle="tooltip"]').tooltip({
          customClass: function (tooltip) {
            return 'custom-tooltip';
          }
        });
      }
    });
  });

  $(document).ready(function(){
    $("#year").change(function () {
      var year = $("#year").val();
      var month = $("#month").val();
      $.ajax({
        url: "ajax_attendance",
        type:'POST',
        data:{year:year,month:month},
        success: function(response) {
          Swal.close();
          $("#show_data").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    });
  });

  $(document).ready(function(){
    $("#month").change(function () {
      var year = $("#year").val();
      var month = $("#month").val();
      $.ajax({
        url: "ajax_attendance",
        type:'POST',
        data:{year:year,month:month},
        success: function(response) {
          Swal.close();
          $("#show_data").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip({
            customClass: function (tooltip) {
              return 'custom-tooltip';
            }
          });
        }
      });
    });
  });
</script>
<?php include('footer.php'); ?>