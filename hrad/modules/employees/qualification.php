<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee_dquali WHERE CNOEE = '$code' ORDER BY IYEARTO DESC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_general_dcmisc";
$result2 = $conn->query($sql2);

if($row = $result->fetch_assoc()){

$highest = array('T' => 'YES', 'F' => 'NO');
$status = array('C' => 'COMPLETED', 'P' => 'PERSUING');

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
            <b><i class="fas fa-caret-right"></i> Qualifications</b>
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
                  <select id="CCDQUALI" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'QUALI'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-icon="../img/icon.png" class="rounded-circle">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Qualification</b>
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
                  <select id="LHIGHEST" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach ($highest as $highkey => $highvalue){ ?>
                    <option value="<?php echo $highkey; ?>" data-icon="../img/icon.png" class="rounded-circle">
                      <?php echo $highvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Highest Education</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="IYEARFROM" class="form-control active" placeholder="e.g. 2023" oninput="javascript: if (this.value.length > 4) this.value = this.value.slice(0, 4)">
                    <label class="form-label text-primary">
                      <b>Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="IYEARTO" class="form-control active" placeholder="e.g. 2023" oninput="javascript: if (this.value.length > 4) this.value = this.value.slice(0, 4)">
                    <label class="form-label text-primary">
                      <b>End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CGRADE" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Grade</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CSCHOOL" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SCHOO'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-icon="../img/icon.png" class="rounded-circle" >
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Institute</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CMAJOR" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'MAJOR'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-icon="../img/icon.png" class="rounded-circle">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Major</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CSTATUS" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach ($status as $statkey => $statvalue) { ?>
                    <option value="<?php echo $statkey; ?>" data-icon="../img/icon.png" class="rounded-circle">
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
                    <input id="CNOCERT" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Certification</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_qualification" class="btn btn-primary">
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
                  <th><b>RECORD</b></th>
                  <th><b>START</b></th>
                  <th><b>END</b></th>
                  <th><b>MAJOR</b></th>
                  <th><b>INSTITUTE</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($result1 as $key1 => $value1) { ?>
                <tr>
                  <td><?php echo $value1['CCDQUALI']; ?></td>
                  <td><?php echo $value1['CDESC']; ?></td>
                  <td><?php echo $value1['IYEARFROM'] != '0' ? $value1['IYEARFROM'] : '-'; ?></td>
                  <td><?php echo $value1['IYEARTO'] != '0' ? $value1['IYEARTO'] : '-'; ?></td>
                  <td><?php echo $value1['CMAJOR'] != '' ? $value1['CMAJOR'] : '-'; ?></td>
                  <td><?php echo $value1['CSCHOOL'] != '' ? $value1['CSCHOOL'] : '-'; ?></td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value1['ID']; ?>" data-ccdquali="<?php echo $value1['CCDQUALI']; ?>" data-cdesc="<?php echo $value1['CDESC']; ?>" data-lhighest="<?php echo $value1['LHIGHEST']; ?>" data-iyearfrom="<?php echo $value1['IYEARFROM']; ?>" data-iyearto="<?php echo $value1['IYEARTO']; ?>" data-cgrade="<?php echo $value1['CGRADE']; ?>" data-cschool="<?php echo $value1['CSCHOOL']; ?>" data-cmajor="<?php echo $value1['CMAJOR']; ?>" data-cstatus="<?php echo $value1['CSTATUS']; ?>" data-cnocert="<?php echo $value1['CNOCERT']; ?>"></i>
                    <i class="fas fa-trash-can text-danger zoom pointer delete" data-id="<?php echo $value1['ID']; ?>" data-desc="<?php echo $value1['CDESC']; ?>"></i>
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
                  <select id="ECCDQUALI" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'QUALI'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-icon="../img/icon.png" class="rounded-circle">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Qualification</b>
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
                  <select id="ELHIGHEST" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach ($highest as $highkey => $highvalue){ ?>
                    <option value="<?php echo $highkey; ?>" data-icon="../img/icon.png" class="rounded-circle">
                      <?php echo $highvalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Highest Education</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EIYEARFROM" class="form-control active" placeholder="e.g. 2023" oninput="javascript: if (this.value.length > 4) this.value = this.value.slice(0, 4)">
                    <label class="form-label text-primary">
                      <b>Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EIYEARTO" class="form-control active" placeholder="e.g. 2023" oninput="javascript: if (this.value.length > 4) this.value = this.value.slice(0, 4)">
                    <label class="form-label text-primary">
                      <b>End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECGRADE" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Grade</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECSCHOOL" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SCHOO'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-icon="../img/icon.png" class="rounded-circle" >
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Institute</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECMAJOR" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'MAJOR'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-icon="../img/icon.png" class="rounded-circle">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Major</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECSTATUS" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="" disabled selected>- Select -</option>
                    <?php foreach ($status as $statkey => $statvalue) { ?>
                    <option value="<?php echo $statkey; ?>" data-icon="../img/icon.png" class="rounded-circle">
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
                    <input id="ECNOCERT" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Certification</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_qualification" class="btn btn-primary">
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
  $("#add_qualification").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        add_qualification: '<?php echo $code; ?>',
        CCDQUALI: $("#CCDQUALI").val(),
        CDESC: $("#CDESC").val(),
        LHIGHEST: $("#LHIGHEST").val(),
        IYEARFROM: $("#IYEARFROM").val(),
        IYEARTO: $("#IYEARTO").val(),
        CGRADE: $("#CGRADE").val(),
        CSCHOOL: $("#CSCHOOL").val(),
        CMAJOR: $("#CMAJOR").val(),
        CSTATUS: $("#CSTATUS").val(),
        CNOCERT: $("#CNOCERT").val()
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
          url: 'modules/employees/qualification?code=<?php echo $code; ?>',
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

    var CCDQUALI = $(this).data('ccdquali');
    var CDESC = $(this).data('cdesc');
    var LHIGHEST = $(this).data('lhighest');
    var IYEARFROM = $(this).data('iyearfrom');
    var IYEARTO = $(this).data('iyearto');
    var CGRADE = $(this).data('cgrade');
    var CSCHOOL = $(this).data('cschool');
    var CMAJOR = $(this).data('cmajor');
    var CSTATUS = $(this).data('cstatus');
    var CNOCERT = $(this).data('cnocert');

    $("#ECCDQUALI option[value='"+CCDQUALI+"']").attr('selected', 'selected');
    $("#ECDESC").val(CDESC);
    $("#ELHIGHEST option[value='"+LHIGHEST+"']").attr('selected', 'selected');
    $("#EIYEARFROM").val(IYEARFROM);
    $("#EIYEARTO").val(IYEARTO);
    $("#ECGRADE").val(CGRADE);
    $("#ECSCHOOL option[value='"+CSCHOOL+"']").attr('selected', 'selected');
    $("#ECMAJOR option[value='"+CMAJOR+"']").attr('selected', 'selected');
    $("#ECSTATUS option[value='"+CSTATUS+"']").attr('selected', 'selected');
    $("#ECNOCERT").val(CNOCERT);
    $("#EID").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#ECCDQUALI option").removeAttr('selected');
    $("#ECDESC").val('');
    $("#ELHIGHEST option").removeAttr('selected');
    $("#EIYEARFROM").val('');
    $("#EIYEARTO").val('');
    $("#ECGRADE").val('');
    $("#ECSCHOOL option").removeAttr('selected');
    $("#ECMAJOR option").removeAttr('selected');
    $("#ECSTATUS option").removeAttr('selected');
    $("#ECNOCERT").val('');
    $("#EID").val('');
  });
});

$(document).ready(function() {
  $("#edit_qualification").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_qualification: $("#EID").val(),
        CCDQUALI: $("#ECCDQUALI").val(),
        CDESC: $("#ECDESC").val(),
        LHIGHEST: $("#ELHIGHEST").val(),
        IYEARFROM: $("#EIYEARFROM").val(),
        IYEARTO: $("#EIYEARTO").val(),
        CGRADE: $("#ECGRADE").val(),
        CSCHOOL: $("#ECSCHOOL").val(),
        CMAJOR: $("#ECMAJOR").val(),
        CSTATUS: $("#ECSTATUS").val(),
        CNOCERT: $("#ECNOCERT").val()
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
          url: 'modules/employees/qualification?code=<?php echo $code; ?>',
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
          data: {delete_qualification:del},
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
              url: 'modules/employees/qualification?code=<?php echo $code; ?>',
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