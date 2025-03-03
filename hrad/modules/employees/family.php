<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee_dfamily WHERE CNOEE = '$code' ORDER BY DBIRTH ASC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_general_dcmisc";
$result2 = $conn->query($sql2);

if($row = $result->fetch_assoc()){

$job = ['C' => 'CHILD', 'D' => 'DECEASED', 'N' => 'NOT WORKING', 'S' => 'SCHOOLING', 'H' => 'HIGHER EDUCATION', 'W' => 'WORKING'];

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
  .pointer{
    cursor: cursor;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-primary">
        <div class="row">
          <div class="col-11">
            <b><i class="fas fa-caret-right"></i> Family Info</b>
          </div>
          <div class="col-1 text-right">
            <i class="fas fa-circle-plus zoom pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#setup"></i>
          </div>
        </div>
      </div>
      <div class="modal fade" id="setup" data-mdb-backdrop="static">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-caret-right"></i> New Record</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CNAME" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Name</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CRELATION" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'RELAT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Relation</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CSEX" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <option value="M">MALE</option>
                    <option value="F">FEMALE</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Gender</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CSTATUS" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach ($job as $jobkey => $jobvalue){ ?>
                    <option value="<?php echo $jobkey; ?>">
                      <?php echo $jobvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Status</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DBIRTH" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Birth Date</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="CNOIC" class="form-control active" placeholder="e.g 123465121234" oninput="javascript: if (this.value.length > 12) this.value = this.value.slice(0, 12);">
                    <label class="form-label text-primary">
                      <b>New NRIC</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CNOBIRTHCE" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Birth Certificate</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="LTAXABLE">
                    <label class="form-check-label" for="LTAXABLE"><b>Tax Relief Child?</b></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_family" class="btn btn-primary">
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
                  <th><b>NAME</b></th>
                  <th><b>RELATION</b></th>
                  <th><b>GENDER</b></th>
                  <th><b>BIRTH DATE</b></th>
                  <th><b>STATUS</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php 

                foreach ($result1 as $key1 => $value1) {

                  foreach ($result2 as $key2 => $value2) {
                    if($value2['CTYPE'] == 'RELAT' && $value2['CCODE'] == $value1['CRELATION']){
                      $relation = $value2['CDESC'];
                    }
                  }

                ?>
                <tr>
                  <td><?php echo empty($value1['CNAME']) ? '-' : $value1['CNAME']; ?></td>
                  <td><?php echo $relation ?? '-'; ?></td>
                  <td><?php echo ($value1['CSEX'] == 'M') ? 'MALE' : (($value1['CSEX'] == 'F') ? 'FEMALE' : '-'); ?></td>
                  <td><?php echo (empty($value1['DBIRTH']) || $value1['DBIRTH'] === '0000-00-00') ? '-' : strtoupper(date("d M Y", strtotime($value1['DBIRTH']))); ?>
                  </td>
                  <td><?php echo isset($job[$value1['CSTATUS']]) ? $job[$value1['CSTATUS']] : '-'; ?></td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value1['ID']; ?>" data-cname="<?php echo $value1['CNAME']; ?>" data-crelation="<?php echo $value1['CRELATION']; ?>" data-csex="<?php echo $value1['CSEX']; ?>" data-cstatus="<?php echo $value1['CSTATUS']; ?>" data-dbirth="<?php echo $value1['DBIRTH']; ?>" data-cnoic="<?php echo $value1['CNOIC']; ?>" data-cnobirthce="<?php echo $value1['CNOBIRTHCE']; ?>" data-ltaxable="<?php echo $value1['LTAXABLE']; ?>"></i>
                    <i class="fas fa-trash-alt zoom pointer text-danger delete" data-id="<?php echo $value1['ID']; ?>" data-desc="<?php echo $value1['CNAME']; ?>"></i>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal fade edit_setup" data-mdb-backdrop="static">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-caret-right"></i> Edit Record</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input id="EID" hidden>
              <div class="row">
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECNAME" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Name</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECRELATION" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'RELAT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Relation</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECSEX" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <option value="M">MALE</option>
                    <option value="F">FEMALE</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Gender</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECSTATUS" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach ($job as $jobkey => $jobvalue){ ?>
                    <option value="<?php echo $jobkey; ?>">
                      <?php echo $jobvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Status</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDBIRTH" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Birth Date</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="ECNOIC" class="form-control active" placeholder="e.g 123465121234" oninput="javascript: if (this.value.length > 12) this.value = this.value.slice(0, 12);">
                    <label class="form-label text-primary">
                      <b>New NRIC</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECNOBIRTHCE" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Birth Certificate</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="ELTAXABLE">
                    <label class="form-check-label" for="ELTAXABLE"><b>Tax Relief Child?</b></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_family" class="btn btn-primary">
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
  $("#add_family").click(function() {
    var tax = $("#LTAXABLE").is(":checked") ? 1 : 0;
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        add_family: '<?php echo $code; ?>',
        CNAME: $("#CNAME").val(),
        CRELATION: $("#CRELATION").val(),
        CSEX: $("#CSEX").val(),
        CSTATUS: $("#CSTATUS").val(),
        DBIRTH: $("#DBIRTH").val(),
        CNOIC: $("#CNOIC").val(),
        CNOBIRTHCE: $("#CNOBIRTHCE").val(),
        LTAXABLE: tax
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
      success: function(response) {
        sec_remove();
        Swal.close();
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
      },
    });
  });
});

$(document).ready(function() {
  $(".update_setup").click(function(){
    var target = $(this).data('mdb-target');
    $(target).modal('show');

    var CNAME = $(this).data('cname');
    var CRELATION = $(this).data('crelation');
    var CSEX = $(this).data('csex');
    var CSTATUS = $(this).data('cstatus');
    var DBIRTH = $(this).data('dbirth');
    var CNOIC = $(this).data('cnoic');
    var CNOBIRTHCE = $(this).data('cnobirthce');
    var LTAXABLE = $(this).data('ltaxable');

    $("#ECNAME").val(CNAME);
    $("#ECRELATION option[value='"+CRELATION+"']").attr('selected', 'selected');
    $("#ECSEX option[value='"+CSEX+"']").attr('selected', 'selected');
    $("#ECSTATUS option[value='"+CSTATUS+"']").attr('selected', 'selected');
    $("#EDBIRTH").val(DBIRTH);
    $("#ECNOIC").val(CNOIC);
    $("#ECNOBIRTHCE").val(CNOBIRTHCE);
    $("#ELTAXABLE").prop('checked', LTAXABLE === 1 ? true : false);
    $("#EID").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#ECNAME").val('');
    $("#ECRELATION option").removeAttr('selected');
    $("#ECSEX option").removeAttr('selected');
    $("#ECSTATUS option").removeAttr('selected');
    $("#EDBIRTH").val('');
    $("#ECNOIC").val('');
    $("#ECNOBIRTHCE").val('');
    $("#ELTAXABLE").prop('checked', false);
    $("#EID").val('');
  });
});

$(document).ready(function() {
  $("#edit_family").click(function() {
    var tax = $("#ELTAXABLE").is(":checked") ? 1 : 0;
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_family: $("#EID").val(),
        CNAME: $("#ECNAME").val(),
        CRELATION: $("#ECRELATION").val(),
        CSEX: $("#ECSEX").val(),
        CSTATUS: $("#ECSTATUS").val(),
        DBIRTH: $("#EDBIRTH").val(),
        CNOIC: $("#ECNOIC").val(),
        CNOBIRTHCE: $("#ECNOBIRTHCE").val(),
        LTAXABLE: tax
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
      success: function(response) {
        sec_remove();
        Swal.close();
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
      },
    });
  });
});

$(document).ready(function() {
  $(".delete").click(function(){
    var del = $(this).attr('data-id');
    var dat = $(this).attr('data-desc');
    Swal.fire({
      title: 'ARE YOU SURE ?',
      html: "<strong>"+dat+" WILL BE DELETED</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "modules/employees/api_main",
          type: 'POST',
          data: {delete_family:del},
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
          success: function(response) {
            sec_remove();
            Swal.close();
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
          },
        });
      }
    });
  });
});
</script>
<?php } ?>