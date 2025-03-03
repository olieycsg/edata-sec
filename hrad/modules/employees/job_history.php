<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee_djobhis WHERE CNOEE = '$code' ORDER BY DTO DESC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_general_dcmisc";
$result2 = $conn->query($sql2);

if($row = $result->fetch_assoc()){

$payrate = array('D' => 'DAILY', 'M' => 'MONTHLY', 'H' => 'HOURLY');

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
            <b><i class="fas fa-caret-right"></i> Job History</b>
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
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DFROM" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DTO" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CCOMPANY" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Company</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CPOSITION" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Position</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CCDEXPRNCE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'EXPRN'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Experience</b>
                  </label>
                </div>
                <div class="col-md-2" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YPAYLAST" class="form-control active" placeholder="e.g. 1000.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Last Salary</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-2" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="IMONTHS" class="form-control active" placeholder="e.g. 1">
                    <label class="form-label text-primary">
                      <b>Months</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-5" style="padding: 10px;">
                  <select id="CREASON" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'RREAS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Reason</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CRATEPAY" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($payrate as $payvalue => $paylabel) { ?>
                    <option value="<?php echo $payvalue; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $paylabel; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Pay Rate</b>
                  </label>
                </div>
                <div class="col-md-12" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <textarea id="CADDRS1" class="form-control active" rows="2" placeholder="..." oninput="this.value = this.value.toUpperCase()"></textarea>
                    <label class="form-label text-primary">
                      <b>Address</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-12" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <textarea id="MNOTES" class="form-control active" rows="2" placeholder="..." oninput="this.value = this.value.toUpperCase()"></textarea>
                    <label class="form-label text-primary">
                      <b>Notes</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_job_history" class="btn btn-primary">
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
                  <th><b>START</b></th>
                  <th><b>END</b></th>
                  <th><b>COMPANY</b></th>
                  <th><b>LAST POSITION</b></th>
                  <th><b>LAST PAY</b></th>
                  <th><b>RATE</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($result1 as $key1 => $value1) { ?>
                <tr>
                  <td><?php echo (empty($value1['DFROM']) || $value1['DFROM'] === '0000-00-00') ? '-' : strtoupper(date("d M Y", strtotime($value1['DFROM']))); ?></td>
                  <td><?php echo (empty($value1['DTO']) || $value1['DTO'] === '0000-00-00') ? '-' : strtoupper(date("d M Y", strtotime($value1['DTO']))); ?></td>
                  <td><?php echo $value1['CCOMPANY'] != '' ? $value1['CCOMPANY'] : "-"; ?></td>
                  <td><?php echo $value1['CPOSITION'] != '' ? $value1['CPOSITION'] : "-"; ?></td>
                  <td><?php echo $value1['YPAYLAST'] != '' ? "RM " . number_format((float)$value1['YPAYLAST'], 2, '.', ',') : "-"; ?></td>
                  <td><?php echo $value1['CRATEPAY'] != '' ? $value1['CRATEPAY'] : "-"; ?></td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value1['ID']; ?>" data-dfrom="<?php echo $value1['DFROM']; ?>" data-dto="<?php echo $value1['DTO']; ?>" data-ccompany="<?php echo $value1['CCOMPANY']; ?>" data-cposition="<?php echo $value1['CPOSITION']; ?>" data-ccdexprnce="<?php echo $value1['CCDEXPRNCE']; ?>" data-ypaylast="<?php echo $value1['YPAYLAST']; ?>" data-imonths="<?php echo $value1['IMONTHS']; ?>" data-creason="<?php echo $value1['CREASON']; ?>" data-cratepay="<?php echo $value1['CRATEPAY']; ?>" data-caddrs1="<?php echo $value1['CADDRS1']; ?>" data-mnotes="<?php echo $value1['MNOTES']; ?>" data-ccdquali="<?php echo $value1['CCDQUALI']; ?>"></i>
                    <i class="fas fa-trash-can text-danger zoom pointer delete" data-id="<?php echo $value1['ID']; ?>" data-desc="<?php echo strtoupper(date("d F Y", strtotime($value1['DFROM']))); ?>"></i>
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
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDFROM" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDTO" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECCOMPANY" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Company</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECPOSITION" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Position</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECCDEXPRNCE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'EXPRN'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Experience</b>
                  </label>
                </div>
                <div class="col-md-2" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYPAYLAST" class="form-control active" placeholder="e.g. 1000.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Last Salary</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-2" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EIMONTHS" class="form-control active" placeholder="e.g. 1">
                    <label class="form-label text-primary">
                      <b>Months</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-5" style="padding: 10px;">
                  <select id="ECREASON" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'RREAS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Reason</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECRATEPAY" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($payrate as $payvalue => $paylabel) { ?>
                    <option value="<?php echo $payvalue; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $paylabel; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Pay Rate</b>
                  </label>
                </div>
                <div class="col-md-12" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <textarea id="ECADDRS1" class="form-control active" rows="2" placeholder="..." oninput="this.value = this.value.toUpperCase()"></textarea>
                    <label class="form-label text-primary">
                      <b>Address</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-12" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <textarea id="EMNOTES" class="form-control active" rows="2" placeholder="..." oninput="this.value = this.value.toUpperCase()"></textarea>
                    <label class="form-label text-primary">
                      <b>Notes</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_job_history" class="btn btn-primary">
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
  $("#add_job_history").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        add_job_history: '<?php echo $code; ?>',
        DFROM: $("#DFROM").val(),
        DTO: $("#DTO").val(),
        CCOMPANY: $("#CCOMPANY").val(),
        CPOSITION: $("#CPOSITION").val(),
        CCDEXPRNCE: $("#CCDEXPRNCE").val(),
        YPAYLAST: $("#YPAYLAST").val(),
        IMONTHS: $("#IMONTHS").val(),
        CREASON: $("#CREASON").val(),
        CRATEPAY: $("#CRATEPAY").val(),
        CADDRS1: $("#CADDRS1").val(),
        MNOTES: $("#MNOTES").val()
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
          url: 'modules/employees/job_history?code=<?php echo $code; ?>',
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

    var DFROM = $(this).data('dfrom');
    var DTO = $(this).data('dto');
    var CCOMPANY = $(this).data('ccompany');
    var CPOSITION = $(this).data('cposition');
    var CCDEXPRNCE = $(this).data('ccdexprnce');
    var YPAYLAST = $(this).data('ypaylast');
    var IMONTHS = $(this).data('imonths');
    var CREASON = $(this).data('creason');
    var CRATEPAY = $(this).data('cratepay');
    var CADDRS1 = $(this).data('caddrs1');
    var MNOTES = $(this).data('mnotes');

    $("#EDFROM").val(DFROM);
    $("#EDTO").val(DTO);
    $("#ECCOMPANY").val(CCOMPANY);
    $("#ECPOSITION").val(CPOSITION);
    $("#ECCDEXPRNCE option[value='"+CCDEXPRNCE+"']").attr('selected', 'selected');
    $("#EYPAYLAST").val(YPAYLAST);
    $("#EIMONTHS").val(IMONTHS);
    $("#ECREASON option[value='"+CREASON+"']").attr('selected', 'selected');
    $("#ECRATEPAY option[value='"+CRATEPAY+"']").attr('selected', 'selected');
    $("#ECADDRS1").val(CADDRS1);
    $("#EMNOTES").val(MNOTES);
    $("#EID").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#EDFROM").val(EDFROM);
    $("#EDTO").val(EDTO);
    $("#ECCOMPANY").val(ECCOMPANY);
    $("#ECPOSITION").val(ECPOSITION);
    $("#ECCDEXPRNCE option[value='"+CCDEXPRNCE+"']").attr('selected', 'selected');
    $("#EYPAYLAST").val(EYPAYLAST);
    $("#EIMONTHS").val(EIMONTHS);
    $("#ECREASON option[value='"+CREASON+"']").attr('selected', 'selected');
    $("#ECRATEPAY option[value='"+CRATEPAY+"']").attr('selected', 'selected');
    $("#ECADDRS1").val(ECADDRS1);
    $("#EMNOTES").val(EMNOTES);
    $("#ECCDQUALI option").removeAttr('selected');
    $("#EID").val('');
  });
});

$(document).ready(function() {
  $("#edit_job_history").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_job_history: $("#EID").val(),
        DFROM: $("#EDFROM").val(),
        DTO: $("#EDTO").val(),
        CCOMPANY: $("#ECCOMPANY").val(),
        CPOSITION: $("#ECPOSITION").val(),
        CCDEXPRNCE: $("#ECCDEXPRNCE").val(),
        YPAYLAST: $("#EYPAYLAST").val(),
        IMONTHS: $("#EIMONTHS").val(),
        CREASON: $("#ECREASON").val(),
        CRATEPAY: $("#ECRATEPAY").val(),
        CADDRS1: $("#ECADDRS1").val(),
        MNOTES: $("#EMNOTES").val()
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
          url: 'modules/employees/job_history?code=<?php echo $code; ?>',
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
          data: {delete_job_history:del},
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
              url: 'modules/employees/job_history?code=<?php echo $code; ?>',
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