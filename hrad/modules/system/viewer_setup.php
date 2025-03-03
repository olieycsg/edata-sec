<?php 

include('../../../api.php');

$sql = "SELECT * FROM sys_workflow ORDER BY ID DESC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$result2 = $conn->query($sql2);

foreach ($result1 as $k1 => $v1) { 
  if($v1['CTYPE'] == 'DIVSN'){
    $a1[] = $v1['CCODE'].",".$v1['CDESC'];
  }
  if($v1['CTYPE'] == 'DEPTM'){
    $a2[] = $v1['CCODE'].",".$v1['CDESC'];
  }
  if($v1['CTYPE'] == 'JOB'){
    $a3[] = $v1['CCODE'].",".$v1['CDESC'];
  }
}

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
      <div class="card-header text-white bg-success">
        <div class="row">
          <div class="col-11">
            <b><i class="fas fa-caret-right"></i> Workflow Setup</b>
          </div>
          <div class="col-1 text-right">
            <i class="fas fa-circle-plus zoom pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#setup"></i>
          </div>
        </div>
      </div>
      <div class="modal fade" id="setup" data-mdb-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-sitemap"></i> New Workflow</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <select id="module" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <option value="eLeave">LEAVE</option>
                    <option value="eMedical">MEDICAL</option>
                    <option value="training">TRAINING</option>
                    <option value="performance">PERFORMANCE</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Module</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="division" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($a1 as $b1){ $c1 = explode(",", $b1); ?>
                    <option value="<?php echo $c1[0]; ?>">(<?php echo $c1[0]; ?>) <?php echo $c1[1]; ?></option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Division</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="department" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($a2 as $b2){ $c2 = explode(",", $b2); ?>
                    <option value="<?php echo $c2[0]; ?>">(<?php echo $c2[0]; ?>) <?php echo $c2[1]; ?></option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Department</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="job" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($a3 as $b3){ $c3 = explode(",", $b3); ?>
                    <option value="<?php echo $c3[0]; ?>">(<?php echo $c3[0]; ?>) <?php echo $c3[1]; ?></option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Job</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="status" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <option value="approved">APPROVED</option>
                    <option value="recommended">RECOMMENDED</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Status</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="cross" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="0">DISABLED</option>
                    <option value="1">ENABLED</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cross Divisional Actions</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <textarea class="form-control active" id="description" rows="4" placeholder="..." oninput="this.value = this.value.toUpperCase()"></textarea>
                    <label class="form-label text-primary active" for="description"><b>Description</b></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_workflow" class="btn btn-primary">
                <b><i class="fas fa-floppy-disk"></i> Save</b>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 overflow-x-scroll">
            <table class="table table-sm table-hover table-striped" style="white-space: nowrap;">
              <thead class="text-left">
                <tr>
                  <th><b>JOB</b></th>
                  <th><b>MODULE</b></th>
                  <th><b>DIVISION</b></th>
                  <th><b>DEPARTMENT</b></th>
                  <th><b>STATUS</b></th>
                  <th><b>CROSS DIVISIONAL</b></th>
                  <th><b>REMARKS</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="text-left">
                <?php foreach ($result as $key => $value) { ?>
                <tr>
                  <td>
                    <?php 
                    foreach ($a3 as $b3){ 
                      $c3 = explode(",", $b3);
                      if($c3[0] == $value['CJOB']){
                        $job = $c3[1];
                      }
                    }
                    ?>
                    <b class="text-primary zoom pointer sec-tooltip" style="font-style: italic; text-decoration: underline;" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $job; ?>"><?php echo $value['CJOB']; ?></b>
                  </td>
                  <td>
                    <span class="badge badge-<?php echo ($value['MODULE'] == 'eLeave') ? 'primary' : (($value['MODULE'] == 'eMedical') ? 'success' :  (($value['MODULE'] == 'training') ? 'danger' :  (($value['MODULE'] == 'performance') ? 'info' : ''))) ?>">
                      <?php echo ($value['MODULE'] == 'eLeave') ? 'LEAVE' : (($value['MODULE'] == 'eMedical') ? 'MEDICAL' :  (($value['MODULE'] == 'training') ? 'TRAINING' :  (($value['MODULE'] == 'performance') ? 'PERFORMANCE' : 'Unknown'))) ?>
                    </span>
                  </td>
                  <td>
                    <?php 
                    foreach ($a1 as $b1){ 
                      $c1 = explode(",", $b1);
                      if($c1[0] == $value['CDIVISION']){
                        $div = $c1[1];
                      }
                    }
                    ?>
                    <b class="text-primary zoom pointer sec-tooltip" style="font-style: italic; text-decoration: underline;" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $div; ?>"><?php echo $value['CDIVISION']; ?></b>
                  </td>
                  <td>
                    <?php 
                    foreach ($a2 as $b2){ 
                      $c2 = explode(",", $b2);
                      if($c2[0] == $value['CDEPARTMEN']){
                        $dep = $c2[1];
                      }
                    }
                    ?>
                    <b class="text-primary zoom pointer sec-tooltip" style="font-style: italic; text-decoration: underline;" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $dep; ?>"><?php echo $value['CDEPARTMEN']; ?></b>
                  </td>
                  <td>
                    <span class="badge badge-<?php echo ($value['ACTION'] == 'approved') ? 'success' : (($value['ACTION'] == 'recommended') ? 'danger' : 'info') ?>">
                      <?php echo ($value['ACTION'] == 'approved') ? 'APPROVED' : (($value['ACTION'] == 'recommended') ? 'RECOMMENDED' : 'UNKNOWN') ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-<?php echo ($value['CROSDIVACC'] == '1') ? 'success' : (($value['CROSDIVACC'] == '0') ? 'danger' : 'warning') ?>">
                      <?php echo ($value['CROSDIVACC'] == '1') ? 'ENABLED' : (($value['CROSDIVACC'] == '0') ? 'DISABLED' : 'NO DATA') ?>
                    </span>
                  </td>
                  <td>
                    <b class="text-primary zoom pointer sec-tooltip" style="font-style: italic; text-decoration: underline;" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $value['REMARKS'] != '' ? $value['REMARKS'] : 'NO DATA'; ?>">View</b>
                  </td>
                  <td>
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value['ID']; ?>" data-module="<?php echo $value['MODULE']; ?>" data-division="<?php echo $value['CDIVISION']; ?>" data-department="<?php echo $value['CDEPARTMEN']; ?>" data-job="<?php echo $value['CJOB']; ?>" data-status="<?php echo $value['ACTION']; ?>" data-cross="<?php echo $value['CROSDIVACC']; ?>" data-remarks="<?php echo $value['REMARKS']; ?>"></i>
                    <i class="far fa-trash-can text-danger zoom pointer delete" data-id="<?php echo $value['ID']; ?>"></i>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal fade edit_setup" data-mdb-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-sitemap"></i> Update Workflow</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input id="eid" hidden>
              <div class="row">
                <div class="col-12">
                  <select id="emodule" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="eLeave">LEAVE</option>
                    <option value="eMedical">MEDICAL</option>
                    <option value="training">TRAINING</option>
                    <option value="performance">PERFORMANCE</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Module</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="edivision" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <?php foreach ($a1 as $b1){ $c1 = explode(",", $b1); ?>
                    <option value="<?php echo $c1[0]; ?>">(<?php echo $c1[0]; ?>) <?php echo $c1[1]; ?></option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Division</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="edepartment" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($a2 as $b2){ $c2 = explode(",", $b2); ?>
                    <option value="<?php echo $c2[0]; ?>">(<?php echo $c2[0]; ?>) <?php echo $c2[1]; ?></option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Department</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="ejob" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($a3 as $b3){ $c3 = explode(",", $b3); ?>
                    <option value="<?php echo $c3[0]; ?>">(<?php echo $c3[0]; ?>) <?php echo $c3[1]; ?></option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Job</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="estatus" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="">- Select -</option>
                    <option value="approved">APPROVED</option>
                    <option value="recommended">RECOMMENDED</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Status</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <select id="ecross" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="0">DISABLED</option>
                    <option value="1">ENABLED</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cross Divisional Actions</b>
                  </label>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <textarea class="form-control active" id="edescription" rows="4" placeholder="..." oninput="this.value = this.value.toUpperCase()"></textarea>
                    <label class="form-label text-primary active" for="edescription"><b>Description</b></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_workflow" class="btn btn-primary">
                <b><i class="fas fa-floppy-disk"></i> Save</b>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $("#add_workflow").click(function(){
    $.ajax({
      url:'modules/system/api_main',
      type:'POST',
      data:{
        add_workflow: $("#module").val(),
        division: $("#division").val(),
        department: $("#department").val(),
        job: $("#job").val(),
        status: $("#status").val(),
        cross: $("#cross").val(),
        description: $("#description").val()
      },
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
        Swal.close();
        sec_remove();
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
            sec_function();
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".update_setup").click(function(){
    var target = $(this).data('mdb-target');
    $(target).modal('show');

    var mode = $(this).data('module');
    var divi = $(this).data('division');
    var dept = $(this).data('department');
    var jobs = $(this).data('job');
    var stat = $(this).data('status');
    var cros = $(this).data('cross');
    var rmrk = $(this).data('remarks');

    $("#emodule option[value='"+mode+"']").attr('selected', 'selected');
    $("#edivision option[value='"+divi+"']").attr('selected', 'selected');
    $("#edepartment option[value='"+dept+"']").attr('selected', 'selected');
    $("#ejob option[value='"+jobs+"']").attr('selected', 'selected');
    $("#estatus option[value='"+stat+"']").attr('selected', 'selected');
    $("#ecross option[value='"+cros+"']").attr('selected', 'selected');
    $("#edescription").val(rmrk);
    $("#eid").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#emodule option").removeAttr('selected');
    $("#edivision option").removeAttr('selected');
    $("#edepartment option").removeAttr('selected');
    $("#ejob option").removeAttr('selected');
    $("#estatus option").removeAttr('selected');
    $("#ecross option").removeAttr('selected');
    $("#edescription").val('');
    $("#eid").val('');
  });
});

$(document).ready(function() {
  $("#edit_workflow").click(function(){
    $.ajax({
      url:'modules/system/api_main',
      type:'POST',
      data:{
        edit_workflow: $("#eid").val(),
        emode: $("#emodule").val(),
        edivi: $("#edivision").val(),
        edept: $("#edepartment").val(),
        ejob: $("#ejob").val(),
        estat: $("#estatus").val(),
        cross: $("#ecross").val(),
        edesc: $("#edescription").val()
      },
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
        Swal.close();
        sec_remove();
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
            sec_function();
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".delete").click(function(){
    var del = $(this).attr('data-id');
    Swal.fire({
      title: 'ARE YOU SURE ?',
      html: "<strong>DATA WILL BE DELETED</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'modules/system/api_main',
          type: 'POST',
          data: {delete_workflow:del},
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
            Swal.close();
            $(".sidenav-backdrop").remove();
            $(".modal-backdrop").remove();
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
          },
        });
      }
    });
  });
});
</script>