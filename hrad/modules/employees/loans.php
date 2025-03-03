<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee_fxloan WHERE CNOEE = '$code' ORDER BY DLOAN DESC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_general_dcmisc";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM payroll_dcbank ORDER BY CNAME ASC";
$result3 = $conn->query($sql3);

$sql4 = "SELECT * FROM payroll_dcalwded";
$result4 = $conn->query($sql4);

if($row = $result->fetch_assoc()){

$cycle = array('0E' => '0E', '01' => '01', '02' => '02');

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
            <b><i class="fas fa-caret-right"></i> Loans Info</b>
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
                    <input id="CREFLOAN" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Reference</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DLOAN" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Date</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CCDLOAN" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'LOAN'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Loan Code</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CBANK" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result3 as $row3){ ?>
                    <option value="<?php echo $row3['CCDBANK'] ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row3['CNAME']." | ".$row3['CCDBANK']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Bank</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YAMTLOAN" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Loan Amount (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="NINTEREST" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Interest (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CPERIOD" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Repayment Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CPERIOD2" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Repayment End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YAMTPAYMNT" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Repayment Amount (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YAMTLAST" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Last Payment (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YAMTPAID" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Amount Paid (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CCDDED" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result4 as $row4){ ?>
                    <option value="<?php echo $row4['CCDALWDED']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row4['CDESC']." | ".$row4['CCDALWDED']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Deduction</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CCYCLE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach ($cycle as $cyclekey => $cyclevalue) { ?>
                    <option value="<?php echo $cyclekey; ?>">
                      <?php echo $cyclevalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cycle</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="NBONDMTHS" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Bond Period</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DSBOND" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Bond Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DXBOND" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Bond Expiry</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YAMTBOND" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Bond Amount (RM)</b>
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
              <button id="add_loan" class="btn btn-primary">
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
                  <th><b>REFERENCE</b></th>
                  <th><b>DATE</b></th>
                  <th><b>LOAN CODE</b></th>
                  <th><b>AMOUNT</b></th>
                  <th><b></b></th>
                </tr>
              </thead>
              <tbody>
                <?php 

                foreach ($result1 as $key1 => $value1) {

                    foreach ($result2 as $key2 => $value2) {
                      if($value2['CTYPE'] == 'LOAN' && $value2['CCODE'] == $value1['CCDDED']){
                        $deduction = $value2['CDESC'];
                      }
                    }

                ?>
                <tr>
                  <td><?php echo $value1['CREFLOAN'] != '' ? $value1['CREFLOAN'] : '-'; ?></td>
                  <td><?php echo $value1['DLOAN'] != '0000-00-00 00:00:00' ? strtoupper(date("d M Y", strtotime($value1['DLOAN']))): '-'; ?></td>
                  <td><?php echo $value1['CCDLOAN'] != '' ? $value1['CCDLOAN'] : '-'; ?></td>
                  <td>RM <?php echo number_format((float)$value1['YAMTLOAN'], 2, '.', ','); ?></td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value1['ID']; ?>" data-crefloan="<?php echo $value1['CREFLOAN']; ?>" data-dloan="<?php echo date("Y-m-d", strtotime($value1['DLOAN'])); ?>" data-ccdloan="<?php echo $value1['CCDLOAN']; ?>" data-cbank="<?php echo $value1['CBANK']; ?>" data-yamtloan="<?php echo $value1['YAMTLOAN']; ?>" data-ninterest="<?php echo $value1['NINTEREST']; ?>" data-cperiod="<?php echo $value1['CPERIOD']; ?>" data-cperiod2="<?php echo $value1['CPERIOD2']; ?>" data-yamtpaymnt="<?php echo $value1['YAMTPAYMNT']; ?>" data-yamtlast="<?php echo $value1['YAMTLAST']; ?>" data-yamtpaid="<?php echo $value1['YAMTPAID']; ?>" data-ccdded="<?php echo $value1['CCDDED']; ?>" data-ccycle="<?php echo $value1['CCYCLE']; ?>" data-nbondmths="<?php echo $value1['NBONDMTHS']; ?>" data-dsbond="<?php echo date("Y-m-d", strtotime($value1['DSBOND'])); ?>" data-dxbond="<?php echo date("Y-m-d", strtotime($value1['DXBOND'])); ?>" data-yamtbond="<?php echo $value1['YAMTBOND']; ?>" data-mnotes="<?php echo $value1['MNOTES']; ?>"></i>
                    <i class="fas fa-trash-alt zoom pointer text-danger delete" data-id="<?php echo $value1['ID']; ?>" data-desc="<?php echo $value1['CREFLOAN']; ?>"></i>
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
                    <input id="ECREFLOAN" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                    <label class="form-label text-primary">
                      <b>Reference</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDLOAN" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Date</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECCDLOAN" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'LOAN'){ ?>
                    <option value="<?php echo $row2['CCODE']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Loan Code</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECBANK" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result3 as $row3){ ?>
                    <option value="<?php echo $row3['CCDBANK'] ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row3['CNAME']." | ".$row3['CCDBANK']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Bank</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYAMTLOAN" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Loan Amount (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="ENINTEREST" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Interest (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="ECPERIOD" class="form-control active" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Repayment Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="ECPERIOD2" class="form-control active" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Repayment End</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYAMTPAYMNT" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Repayment Amount (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYAMTLAST" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Last Payment (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYAMTPAID" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Amount Paid (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECCDDED" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result4 as $row4){ ?>
                    <option value="<?php echo $row4['CCDALWDED']; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row4['CDESC']." | ".$row4['CCDALWDED']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Deduction</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECCYCLE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
                    <option value="">- Select -</option>
                    <?php foreach ($cycle as $cyclekey => $cyclevalue) { ?>
                    <option value="<?php echo $cyclekey; ?>">
                      <?php echo $cyclevalue; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cycle</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="ENBONDMTHS" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Bond Period</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDSBOND" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Bond Start</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDXBOND" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Bond Expiry</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYAMTBOND" class="form-control active" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Bond Amount (RM)</b>
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
              <button id="edit_loan" class="btn btn-primary">
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
  $("#add_loan").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        add_loan: '<?php echo $code; ?>',
        CREFLOAN: $("#CREFLOAN").val(),
        DLOAN: $("#DLOAN").val(),
        CCDLOAN: $("#CCDLOAN").val(),
        CBANK: $("#CBANK").val(),
        YAMTLOAN: $("#YAMTLOAN").val(),
        NINTEREST: $("#NINTEREST").val(),
        CPERIOD: $("#CPERIOD").val(),
        CPERIOD2: $("#CPERIOD2").val(),
        YAMTPAYMNT: $("#YAMTPAYMNT").val(),
        YAMTLAST: $("#YAMTLAST").val(),
        YAMTPAID: $("#YAMTPAID").val(),
        CCDDED: $("#CCDDED").val(),
        CCYCLE: $("#CCYCLE").val(),
        NBONDMTHS: $("#NBONDMTHS").val(),
        DSBOND: $("#DSBOND").val(),
        DXBOND: $("#DXBOND").val(),
        YAMTBOND: $("#YAMTBOND").val(),
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
          url: 'modules/employees/loans?code=<?php echo $code; ?>',
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

    var CREFLOAN = $(this).data('crefloan');
    var DLOAN = $(this).data('dloan');
    var CCDLOAN = $(this).data('ccdloan');
    var CBANK = $(this).data('cbank');
    var YAMTLOAN = $(this).data('yamtloan');
    var NINTEREST = $(this).data('ninterest');
    var CPERIOD = $(this).data('cperiod');
    var CPERIOD2 = $(this).data('cperiod2');
    var YAMTPAYMNT = $(this).data('yamtpaymnt');
    var YAMTLAST = $(this).data('yamtlast');
    var YAMTPAID = $(this).data('yamtpaid');
    var CCDDED = $(this).data('ccdded');
    var CCYCLE = $(this).data('ccycle');
    var NBONDMTHS = $(this).data('nbondmths');
    var DSBOND = $(this).data('dsbond');
    var DXBOND = $(this).data('dxbond');
    var YAMTBOND = $(this).data('yamtbond');
    var MNOTES = $(this).data('mnotes');

    $("#ECREFLOAN").val(CREFLOAN);
    $("#EDLOAN").val(DLOAN);
    $("#ECCDLOAN option[value='"+CCDLOAN+"']").attr('selected', 'selected');
    $("#ECBANK option[value='"+CBANK+"']").attr('selected', 'selected');
    $("#EYAMTLOAN").val(YAMTLOAN);
    $("#ENINTEREST").val(NINTEREST);
    $("#ECPERIOD").val(CPERIOD);
    $("#ECPERIOD2").val(CPERIOD2);
    $("#EYAMTPAYMNT").val(YAMTPAYMNT);
    $("#EYAMTLAST").val(YAMTLAST);
    $("#EYAMTPAID").val(YAMTPAID);
    $("#ECCDDED option[value='"+CCDDED+"']").attr('selected', 'selected');
    $("#ECCYCLE option[value='"+CCYCLE+"']").attr('selected', 'selected');
    $("#ENBONDMTHS").val(NBONDMTHS);
    $("#EDSBOND").val(DSBOND);
    $("#EDXBOND").val(DXBOND);
    $("#EYAMTBOND").val(YAMTBOND);
    $("#EMNOTES").val(MNOTES);
    $("#EID").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#ECREFLOAN").val('');
    $("#EDLOAN").val('');
    $("#ECCDLOAN option").removeAttr('selected');
    $("#ECBANK option").removeAttr('selected');
    $("#EYAMTLOAN").val('');
    $("#ENINTEREST").val('');
    $("#ECPERIOD").val('');
    $("#ECPERIOD2").val('');
    $("#EYAMTPAYMNT").val('');
    $("#EYAMTLAST").val('');
    $("#EYAMTPAID").val('');
    $("#ECCDDED option").removeAttr('selected');
    $("#ECCYCLE option']").removeAttr('selected');
    $("#ENBONDMTHS").val('');
    $("#EDSBOND").val('');
    $("#EDXBOND").val('');
    $("#EYAMTBOND").val('');
    $("#EMNOTES").val('');
    $("#EID").val('');
  });
});

$(document).ready(function() {
  $("#edit_loan").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_loan: $("#EID").val(),
        CREFLOAN: $("#ECREFLOAN").val(),
        DLOAN: $("#EDLOAN").val(),
        CCDLOAN: $("#ECCDLOAN").val(),
        CBANK: $("#ECBANK").val(),
        YAMTLOAN: $("#EYAMTLOAN").val(),
        NINTEREST: $("#ENINTEREST").val(),
        CPERIOD: $("#ECPERIOD").val(),
        CPERIOD2: $("#ECPERIOD2").val(),
        YAMTPAYMNT: $("#EYAMTPAYMNT").val(),
        YAMTLAST: $("#EYAMTLAST").val(),
        YAMTPAID: $("#EYAMTPAID").val(),
        CCDDED: $("#ECCDDED").val(),
        CCYCLE: $("#ECCYCLE").val(),
        NBONDMTHS: $("#ENBONDMTHS").val(),
        DSBOND: $("#EDSBOND").val(),
        DXBOND: $("#EDXBOND").val(),
        YAMTBOND: $("#EYAMTBOND").val(),
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
          url: 'modules/employees/loans?code=<?php echo $code; ?>',
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
          data: {delete_loan:del},
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
              url: 'modules/employees/loans?code=<?php echo $code; ?>',
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