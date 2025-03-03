<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee_dmember WHERE CNOEE = '$code'";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_general_dcmisc";
$result2 = $conn->query($sql2);

if($row = $result->fetch_assoc()){

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
            <b><i class="fas fa-caret-right"></i> Membership</b>
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
                  <select id="CCDMEMBER" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'MEMBR'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Membership</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CDESC" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Description</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CNOMEMBER" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Member ID</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DSINCE" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Since</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3 col-6" style="padding: 10px;">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="LCORPORATE">
                    <label class="form-check-label" for="LCORPORATE"><b>Corporate?</b></label>
                  </div>
                </div>
                <div class="col-md-3 col-6" style="padding: 10px;">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="LTRANSFER">
                    <label class="form-check-label" for="LTRANSFER"><b>Transfer?</b></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_membership" class="btn btn-primary">
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
                  <th><b>CODE</b></th>
                  <th><b>DESCRIPTION</b></th>
                  <th><b>SINCE</b></th>
                  <th><b>MEMBERSHIP</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($result1 as $key1 => $value1) { ?>
                <tr>
                  <td><?php echo $value1['CCDMEMBER'] != '' ? $value1['CCDMEMBER'] : '-'; ?></td>
                  <td><?php echo $value1['CDESC'] != '' ? $value1['CDESC'] : '-'; ?></td>
                  <td><?php echo $value1['DSINCE'] != '' ? strtoupper(date("d M Y", strtotime($value1['DSINCE']))) : '-'; ?></td>
                  <td><?php echo $value1['CNOMEMBER'] != '' ? $value1['CNOMEMBER'] : '-'; ?></td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value1['ID']; ?>" data-ccdmember="<?php echo $value1['CCDMEMBER']; ?>" data-cdesc="<?php echo $value1['CDESC']; ?>" data-cnomember="<?php echo $value1['CNOMEMBER']; ?>" data-dsince="<?php echo $value1['DSINCE']; ?>" data-lcorporate="<?php echo $value1['LCORPORATE']; ?>" data-ltransfer="<?php echo $value1['LTRANSFER']; ?>"></i>
                    <i class="fas fa-trash-alt zoom pointer text-danger delete" data-id="<?php echo $value1['ID']; ?>" data-desc="<?php echo $value1['CCDMEMBER']; ?>"></i>
                  </td>
                </tr>
                <?php } ?> 
              </tbody>
            </thead>
          </table>
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
                  <select id="ECCDMEMBER" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'MEMBR'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Membership</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECDESC" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Description</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECNOMEMBER" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Member ID</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDSINCE" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Since</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3 col-6" style="padding: 10px;">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="ELCORPORATE">
                    <label class="form-check-label" for="LCORPORATE"><b>Corporate?</b></label>
                  </div>
                </div>
                <div class="col-md-3 col-6" style="padding: 10px;">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="ELTRANSFER">
                    <label class="form-check-label" for="LTRANSFER"><b>Transfer?</b></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_membership" class="btn btn-primary">
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
  $("#add_membership").click(function() {
    var data1 = $("#LCORPORATE").is(":checked") ? 1 : 0;
    var data2 = $("#LTRANSFER").is(":checked") ? 1 : 0;
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        add_membership: '<?php echo $code; ?>',
        CCDMEMBER: $("#CCDMEMBER").val(),
        CDESC: $("#CDESC").val(),
        CNOMEMBER: $("#CNOMEMBER").val(),
        DSINCE: $("#DSINCE").val(),
        LCORPORATE: data1,
        LTRANSFER: data2
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
          url: 'modules/employees/membership?code=<?php echo $code; ?>',
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

    var CCDMEMBER = $(this).data('ccdmember');
    var CDESC = $(this).data('cdesc');
    var CNOMEMBER = $(this).data('cnomember');
    var DSINCE = $(this).data('dsince');
    var LCORPORATE = $(this).data('lcorporate');
    var LTRANSFER = $(this).data('ltransfer');

    $("#ECCDMEMBER option[value='"+CCDMEMBER+"']").attr('selected', 'selected');
    $("#ECDESC").val(CDESC);
    $("#ECNOMEMBER").val(CNOMEMBER);
    $("#EDSINCE").val(new Date(new Date(DSINCE).getTime() + 86400000).toISOString().slice(0, 10));
    $("#ELCORPORATE").prop('checked', LCORPORATE === 1 ? true : false);
    $("#ELTRANSFER").prop('checked', LTRANSFER === 1 ? true : false);
    $("#EID").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#ECCDMEMBER option").removeAttr('selected');
    $("#ECDESC").val('');
    $("#EDSINCE").val('');
    $("#ELCORPORATE").prop('checked', false);
    $("#ELTRANSFER").prop('checked', false);
    $("#EID").val('');
  });
});

$(document).ready(function() {
  $("#edit_membership").click(function() {
    var data1 = $("#ELCORPORATE").is(":checked") ? 1 : 0;
    var data2 = $("#ELTRANSFER").is(":checked") ? 1 : 0;
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_membership: $("#EID").val(),
        CCDMEMBER: $("#ECCDMEMBER").val(),
        CDESC: $("#ECDESC").val(),
        CNOMEMBER: $("#ECNOMEMBER").val(),
        DSINCE: $("#EDSINCE").val(),
        LCORPORATE: data1,
        LTRANSFER: data2
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
          url: 'modules/employees/membership?code=<?php echo $code; ?>',
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
          data: {delete_membership:del},
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
              url: 'modules/employees/membership?code=<?php echo $code; ?>',
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