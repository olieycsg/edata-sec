<?php

include('../../../api.php');

$cdivi = $_GET['cdivi'];

$sql = "SELECT * FROM attendance WHERE cdivi = '$cdivi' GROUP BY cnoee ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $exist = 1;
  $cnoee = [];
  while ($row = $result->fetch_assoc()) {
    $cnoee[] = $row['cnoee'];
  }
} else {
  $exist = 0;
}

if (!empty($cnoee)) {
  $cnoee_list = "'".implode("','", $cnoee)."'";
  $sql1 = "SELECT * FROM employees_demas WHERE CNOEE IN ($cnoee_list) AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result1 = $conn->query($sql1);
}

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="<?php if ($exist == 1) { echo "col-md-9 col-12"; }else{ echo "col-md-10 col-12"; } ?>">
            <input type="file" class="form-control" id="csvFile" <?php if ($exist == 1) { echo "disabled"; } ?>>
          </div>
          <?php if ($exist == 1) { ?>
          <div class="col-md-1 col-12" style="text-align: center;">
            <div class="md-form">
              <button id="delete" class="btn btn-danger btn-block" >
                <b><i class="far fa-trash-can"></i></b>
              </button>
            </div>
          </div>
          <div class="col-md-1 col-12" style="text-align: center;">
            <div class="md-form">
              <button id="sync_all" class="btn btn-success btn-block">
                <b><i class="fas fa-calculator"></i></b>
              </button>
            </div>
          </div>
          <div class="col-md-1 col-12" style="text-align: center;">
            <div class="md-form">
              <button id="print_all" class="btn btn-primary btn-block">
                <b><i class="fas fa-print"></i></b>
              </button>
            </div>
          </div>
          <?php }else{ ?>
          <div class="col-md-2 col-12" style="text-align: center;">
            <div class="md-form">
              <button id="upload" class="btn btn-primary btn-block">
                <b><i class="fas fa-cloud-arrow-up"></i> Upload</b>
              </button>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_Attendance"></span>
<div class="row text-center main_loader" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
      </div>
    </div>
  </div>
</div>
<?php if ($exist == 0) { ?>
<div class="row no_data_ajax" style="margin-top: 20px; text-align: center;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <img src="../../img/nodata.png" class="img-fluid"> 
      </div>
    </div>
  </div>
</div>
<?php }else{ ?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-11 col-12">
            <div class="md-form">
              <select id="employee" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                <option value="">- Select -</option>
                <?php foreach ($result1 as $key => $val) { ?>
                <option value="<?php echo $val['CNOEE']; ?>" data-mdb-icon="../img/icon.png"><?php echo $val['CNAME']; ?></option>
                <?php } ?>
              </select>
              <label class="form-label select-label text-primary">
                <b>Employee</b>
              </label>
            </div>
          </div>
          <div class="col-md-1 col-12" style="text-align: center;">
            <div class="md-form">
              <button id="print" class="btn btn-primary btn-block" >
                <b><i class="fas fa-print"></i></b>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_MainTable"></span>
<?php } ?>
<script type="text/javascript">
  sec_function();
  $(".main_loader").hide();
  document.getElementById("csvFile").addEventListener("blur", function () {
    const fileInput = this;
    const file = fileInput.files[0];

    if (file && file.type !== "text/csv" && !file.name.endsWith(".csv")) {
      Swal.fire("CSV File Only");
      fileInput.value = "";
    }
  });

  $(document).ready(function () {
    $("#print_all").click(function () {
      function generateRandomString(length = 255) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
      }
      var divi = $("#search").val();
      window.open("modules/attendance/print_all?code="+generateRandomString()+"&divi="+divi, "_blank");
    });
  }); 

  $(document).ready(function () {
    $("#print").click(function () {
      function generateRandomString(length = 255) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
      }
      var cnoee = $("#employee").val();
      window.open("modules/attendance/print?code="+generateRandomString()+"&cnoee="+cnoee, "_blank");
    });
  });

  $(document).ready(function () {
    $("#upload").click(function () {
      var fileInput = $("#csvFile")[0];
      var file = fileInput.files[0];

      if (!file) {
        Swal.fire("CSV Required");
        return;
      }

      var formData = new FormData();
      formData.append("csvFile", file);

      $("#show_Attendance").hide();
      $.ajax({
        url: "modules/attendance/api_main",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {    
          Swal.fire({
            title: 'Uploading',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function (response) {
          Swal.close();
          var code = $("#search").val();
          $.ajax({
            url: "modules/attendance/main?cdivi="+code,
            beforeSend: function () {
              $(".loader").show();
              $(".no_data").hide();
              $("#show_data").hide();
            },
            success: function (data) {
              $(".loader").hide();
              $(".no_data").hide();
              $("#show_data").html(data).show();
            }
          });
        }
      });
    });
  });

  $(document).ready(function () {
    $("#delete").click(function () {
      var del = 'delete';
      $.ajax({
        url: "modules/attendance/api_main",
        type: "POST",
        data: {delete:del},
        beforeSend: function() {    
          Swal.fire({
            title: 'Deleting',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function (response) {
          Swal.close();
          var code = $("#search").val();
          $.ajax({
            url: "modules/attendance/main",
            beforeSend: function () {
              $(".loader").show();
              $(".no_data").hide();
              $("#show_data").hide();
            },
            success: function (data) {
              $(".loader").hide();
              $(".no_data").hide();
              $("#show_data").html(data).show();
            }
          });
        }
      });
    });
  });

  $(document).ready(function() {
    $("#employee").change(function(){
      var name = $(this).val();
      $.ajax({
        url: 'modules/attendance/main_table',
        type: 'POST',
        data: {name:name},
        beforeSend: function() {    
          Swal.fire({
            title: 'Loading',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function(data) {
          Swal.close();
          $("#show_MainTable").html(data).show();
        }
      });
    });
  });

  $(document).ready(function () {
    $("#sync_all").click(function () {
      var sync_all = "sync_all";

      Swal.fire({
        title: "Authenticating",
        allowEscapeKey: false,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      var interval = setInterval(function () {
        $.ajax({
          url: "modules/attendance/progress",
          type: "GET",
          dataType: "json",
          success: function (response) {
            var progress = response.progress;

            Swal.update({
              title: "Processing Database",
              html: `<strong><h2>${progress}%</h2></strong>`,
              allowEscapeKey: false,
              allowOutsideClick: false,
              showConfirmButton: false
            });

            if (progress >= 100) {
              clearInterval(interval);
              Swal.fire({
                title: "Finalizing Report",
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
            }
          },
        });
      }, 1000);

      $.ajax({
        url: "modules/attendance/api_bulk",
        type: "POST",
        data: { sync_all: sync_all },
        success: function () {
          clearInterval(interval);
          Swal.close();
          var code = $("#search").val();
          $.ajax({
            url: "modules/attendance/main?cdivi="+code,
            success: function (data) {
              $(".loader").hide();
              $(".no_data").hide();
              $("#show_data").html(data).show();
            },
          });
        },
      });
    });
  });

  /*$(document).ready(function() {
    $("#sync").click(function(){
      var cnoee = $("#employee").val();
      if (cnoee === '') {
        Swal.fire("Employee Required");
        return;
      }else{ 
        $.ajax({
          url: 'modules/attendance/api_main',
          type: 'POST',
          data: {sync:cnoee},
          beforeSend: function() {    
            Swal.fire({
              title: 'Processing',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(data) {
            var name = $("#employee").val();
            $.ajax({
              url: 'modules/attendance/main_table',
              type: 'POST',
              data: {name:name},
              beforeSend: function() {    
                Swal.fire({
                  title: 'Loading',
                  allowEscapeKey: false,
                  allowOutsideClick: false,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                });
              },
              success: function(data) {
                Swal.close();
                $("#show_MainTable").html(data).show();
              }
            });
          }
        });
      }
    });
  });*/
</script>