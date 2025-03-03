<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee_dskill WHERE CNOEE = '$code' ORDER BY CCDSKILL ASC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_general_dcmisc";
$result2 = $conn->query($sql2);

if($row = $result->fetch_assoc()){

$status = ['A' => 'ACTIVELY USED', 'N' => 'INACTIVE'];
$thru = ['C' => 'CURRENT COMPANY SPONSORED', 'P' => 'PREVIOUS COMPANY SPONSORED', 'S' => 'SELF ACQUIRED'];

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
            <b><i class="fas fa-caret-right"></i> Skills</b>
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
                  <select id="CCDSKILL" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SKILL'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Code</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="CLEVEL" class="form-control active" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Level</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CSTATUS" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach($status as $statkey => $statvalue){ ?>
                    <option value="<?php echo $statkey; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $statvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Status</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CDESC" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Record</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="IYEARFROM" class="form-control active" placeholder="e.g 2023">
                    <label class="form-label text-primary">
                      <b>Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="IYEARTO" class="form-control active" placeholder="e.g 2023">
                    <label class="form-label text-primary">
                      <b>End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CTHRU" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach($thru as $thrukey => $thruvalue){ ?>
                    <option value="<?php echo $thrukey; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $thruvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Acquired Thru</b>
                  </label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_skill" class="btn btn-primary">
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
                  <th><b>LEVEL</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($result1 as $key1 => $value1) { ?>
                <tr>
                  <td><?php echo $value1['CCDSKILL'] != '' ? $value1['CCDSKILL'] : '-'; ?></td>
                  <td><?php echo $value1['CDESC'] != '' ? $value1['CDESC'] : '-'; ?></td>
                  <td><?php echo $value1['CLEVEL'] != '' ? $value1['CLEVEL'] : '-'; ?></td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value1['ID']; ?>" data-ccdskill="<?php echo $value1['CCDSKILL']; ?>"; data-clevel="<?php echo $value1['CLEVEL']; ?>"; data-cstatus="<?php echo $value1['CSTATUS']; ?>"; data-cdesc="<?php echo $value1['CDESC']; ?>"; data-iyearfrom="<?php echo $value1['IYEARFROM']; ?>"; data-iyearto="<?php echo $value1['IYEARTO']; ?>"; data-cthru="<?php echo $value1['CTHRU']; ?>"></i>
                    <i class="fas fa-trash-alt zoom pointer text-danger delete" data-id="<?php echo $value1['ID']; ?>" data-desc="<?php echo $value1['CCDSKILL']; ?>"></i>
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
                  <select id="ECCDSKILL" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SKILL'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Code</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="ECLEVEL" class="form-control active" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Level</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECSTATUS" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach($status as $statkey => $statvalue){ ?>
                    <option value="<?php echo $statkey; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $statvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Status</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECDESC" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Record</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EIYEARFROM" class="form-control active" placeholder="e.g 2023">
                    <label class="form-label text-primary">
                      <b>Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EIYEARTO" class="form-control active" placeholder="e.g 2023">
                    <label class="form-label text-primary">
                      <b>End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECTHRU" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach($thru as $thrukey => $thruvalue){ ?>
                    <option value="<?php echo $thrukey; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $thruvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Acquired Thru</b>
                  </label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_skill" class="btn btn-primary">
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
  $("#add_skill").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        add_skill: '<?php echo $code; ?>',
        CNAME: $("#CNAME").val(),
        CCDSKILL: $("#CCDSKILL").val(),
        CLEVEL: $("#CLEVEL").val(),
        CSTATUS: $("#CSTATUS").val(),
        CDESC: $("#CDESC").val(),
        IYEARFROM: $("#IYEARFROM").val(),
        IYEARTO: $("#IYEARTO").val(),
        CTHRU: $("#CTHRU").val()
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
          url: 'modules/employees/skills?code=<?php echo $code; ?>',
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

    var CCDSKILL = $(this).data('ccdskill');
    var CLEVEL = $(this).data('clevel');
    var CSTATUS = $(this).data('cstatus');
    var CDESC = $(this).data('cdesc');
    var IYEARFROM = $(this).data('iyearfrom');
    var IYEARTO = $(this).data('iyearto');
    var CTHRU = $(this).data('cthru');

    $("#ECCDSKILL option[value='"+CCDSKILL+"']").attr('selected', 'selected');
    $("#ECLEVEL").val(CLEVEL);
    $("#ECSTATUS option[value='"+CSTATUS+"']").attr('selected', 'selected');
    $("#ECDESC").val(CDESC);
    $("#EIYEARFROM").val(IYEARFROM);
    $("#EIYEARTO").val(IYEARTO);
    $("#ECTHRU option[value='"+CTHRU+"']").attr('selected', 'selected');
    $("#EID").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#ECCDSKILL option").removeAttr('selected');
    $("#ECLEVEL").val('');
    $("#ECSTATUS option").removeAttr('selected');
    $("#ECDESC").val('');
    $("#EIYEARFROM").val('');
    $("#EIYEARTO").val('');
    $("#ECTHRU option").removeAttr('selected');
    $("#EID").val('');
  });
});

$(document).ready(function() {
  $("#edit_skill").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_skill: $("#EID").val(),
        CNAME: $("#ECNAME").val(),
        CCDSKILL: $("#ECCDSKILL").val(),
        CLEVEL: $("#ECLEVEL").val(),
        CSTATUS: $("#ECSTATUS").val(),
        CDESC: $("#ECDESC").val(),
        IYEARFROM: $("#EIYEARFROM").val(),
        IYEARTO: $("#EIYEARTO").val(),
        CTHRU: $("#ECTHRU").val()
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
          url: 'modules/employees/skills?code=<?php echo $code; ?>',
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
          data: {delete_skill:del},
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
              url: 'modules/employees/skills?code=<?php echo $code; ?>',
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