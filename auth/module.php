<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

if($_SESSION['uid'] == ""){
  
  header('location: index');
  
}else{
  
include('api.php'); 
include('header.php');
include('sidebar.php');

$sql = "SELECT * FROM module";
$result = $conn->query($sql);

$hql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$hesult = $hris->query($hql);

$options = ["Employee" => 1, "Leave" => 2, "Payroll" => 3, "Medical" => 4, "Memo" => 5, "Training" => 6, "Performance" => 7, "System" => 8, "Reports" => 9];

?>
<main class="mb-5" style="margin-top: -55px;">
  <div class="container px-4">
    <div class="row" style="margin-top: 20px;">
      <div class="col-lg-12">
        <div class="card shadow-8">
          <div class="card-header">
            <div class="row">
              <div class="col-11">
                <b><i class="fas fa-caret-right"></i> System Module</b>
              </div>
              <div class="col-1 text-right">
                <i class="fas fa-circle-plus zoom pointer text-primary" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#setup"></i>
              </div>
            </div>
          </div>
          <div class="modal fade" id="setup" data-mdb-backdrop="static">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <b><i class="fas fa-user-tie"></i> New Module</b>
                  <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-12" style="margin-bottom: 20px;">
                      <select id="sid" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                        <option value="">- Select -</option>
                        <?php foreach ($hesult as $h1 => $hrw) { ?>
                        <option value="<?php echo $hrw['CNOEE']; ?>">
                          <?php echo $hrw['CNAME']; ?>
                        </option>
                        <?php } ?>
                      </select>
                      <label class="form-label select-label text-primary">
                        <b>Employee</b>
                      </label>
                    </div>
                    <div class="col-6" style="margin-bottom: 20px;">
                      <select id="module" class="sec-select" data-mdb-select-init multiple data-mdb-container="#setup" data-mdb-filter="true">
                        <?php foreach ($options as $label => $value) { ?>
                        <option value="<?php echo $value; ?>">
                          <?php echo htmlspecialchars($label); ?>
                        </option>
                        <?php } ?>
                      </select>
                      <label class="form-label select-label text-primary">
                        <b>Module</b>
                      </label>
                    </div>
                    <div class="col-6" style="margin-bottom: 20px;">
                      <select id="status" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                        <option value="1">Online</option>
                        <option value="0">Offline</option>
                      </select>
                      <label class="form-label select-label text-primary">
                        <b>Status</b>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button id="add" class="btn btn-primary">
                    <b><i class="fas fa-floppy-disk"></i> Save</b>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 overflow-x-scroll">
                <table id="list" class="table table-sm table-hover table-striped" style="white-space: nowrap;">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Module</th>
                      <th class="text-center">Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="result"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include('footer.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
  $.ajax({
    url: "table_module",
    type:'POST',
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
    success: function(response){
      Swal.close();
      $("#result").html(response);
    }
  });
});

$(document).ready(function(){
  $("#add").click(function(){
    var sid = $("#sid").val();
    var mod = $("#module").val();
    var sts = $("#status").val();
    $.ajax({
      url: "api_main",
      type:'POST',
      data:{add_module:sid,mod:mod,sts:sts},
      beforeSend: function() {    
        Swal.fire({
          title: 'SAVING',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function(response){
        $.ajax({
          url: "table_module",
          type:'POST',
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
          success: function(server){
            Swal.close();
            sec_remove();
            $("#result").html(server);
          }
        });
      }
    });
  });
});

$(document).ready(function(){
  $(".delete_all").click(function(){
    var id = 'all';
    Swal.fire({
      title: 'DELETE ALL DATA',
      html: "<strong>YOU WON'T BE ABLE TO REVERT THIS</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "api_main",
          type:'POST',
          data:{delete_module_all:id},
          beforeSend: function() {    
            Swal.fire({
              title: 'DELETING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response){
            $.ajax({
              url: "table_module",
              type:'POST',
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
              success: function(server){
                Swal.close();
                sec_remove();
                $("#result").html(server);
              }
            });
          }
        });
      }
    });
  });
});
</script>
<?php } ?>
