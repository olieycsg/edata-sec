<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM payroll_dcbank";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM payroll_dpaytbl";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM sys_general_dcompref";
$result3 = $conn->query($sql3);

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
      <div class="card-body">
        <div class="row">
          <div class="col-md-3" style="padding: 10px;">
            <select id="CPAYMODE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <option value="B" data-mdb-icon="../img/icon.png" <?php if($row['CPAYMODE'] == 'B'){ echo "selected"; } ?>>BANK</option>
              <option value="C" data-mdb-icon="../img/icon.png" <?php if($row['CPAYMODE'] == 'C'){ echo "selected"; } ?>>CASH</option>
              <option value="Q" data-mdb-icon="../img/icon.png" <?php if($row['CPAYMODE'] == 'Q'){ echo "selected"; } ?>>CHEQUE</option>
            </select>
            <label class="form-label select-label text-primary">
              <b>Pay Mode</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CPERIODLAS" class="form-control active" value="<?php echo $row['CPERIODLAS']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Last Pay Period</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CCYCLELAST" class="form-control active" value="<?php echo $row['CCYCLELAST']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Last Pay Cycle</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="LDEDHRD" value="<?php echo $row['LDEDHRD']; ?>" <?php if($row['LDEDHRD'] == '1'){ echo "checked"; } ?>>
              <label class="form-check-label" for="LDEDHRD"><b>Contribute HRD?</b></label>
            </div>
          </div>
          <div class="col-md-9" style="padding: 10px;">
            <select id="CBANK" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result1 as $row1){ ?>
              <option value="<?php echo $row1['CCDBANK'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CBANK'] == $row1['CCDBANK']){ echo "selected"; } ?>>
                <?php echo $row1['CNAME']." | ".$row1['CCDBANK']; ?>
              </option>
              <?php } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Bank Name</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOACC" class="form-control active" value="<?php echo $row['CNOACC']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Bank Account</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOASB" class="form-control active" value="<?php echo $row['CNOASB']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>ASB</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOTBHAJI" class="form-control active" value="<?php echo $row['CNOTBHAJI']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Tabung Haji</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOUNION" class="form-control active" value="<?php echo $row['CNOUNION']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Union</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOPERMIT" class="form-control active" value="<?php echo $row['CNOPERMIT']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Work Permit</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DCONTRCT" type="date" class="form-control active" value="<?php if($row['DCONTRCT'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DCONTRCT'])); } ?>">
              <label class="form-label text-primary">
                <b>Contract (From)</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DXCONTRCT" type="date" class="form-control active" value="<?php if($row['DXCONTRCT'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DXCONTRCT'])); } ?>">
              <label class="form-label text-primary">
                <b>Contract (To)</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DPERMIT" type="date" class="form-control active" value="<?php if($row['DPERMIT'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DPERMIT'])); } ?>">
              <label class="form-label text-primary">
                <b>Permit (From)</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DXPERMIT" type="date" class="form-control active" value="<?php if($row['DXPERMIT'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DXPERMIT'])); } ?>">
              <label class="form-label text-primary">
                <b>Permit (To)</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CTBLORP" class="form-control active" value="<?php echo $row['CTBLORP']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>ORP Table</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CINCRTBL" class="form-control active" value="<?php echo $row['CINCRTBL']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Increment Table</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CBONUSTBL" class="form-control active" value="<?php echo $row['CBONUSTBL']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Bonus Table</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CTBLZAKAT" class="form-control active" value="<?php echo $row['CTBLZAKAT']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Zakat Table</b>
              </label>
            </div>
          </div>
        </div>
        <br>
        <p class="note note-success navbar">
          <strong>Employment Insurance System (EIS)</strong>
        </p>
        <div class="row">
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOEIS" class="form-control active" value="<?php echo $row['CNOEIS']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EIS ID</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CTBLEIS" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result2 as $row2){ if($row2['CTYPTBL'] == 'EIS'){ ?>
              <option value="<?php echo $row2['CNOTBL'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CTBLEIS'] == $row2['CNOTBL']){ echo "selected"; } ?>>
                <?php echo $row2['CNOTBL']." - ".$row2['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>EIS Table</b>
            </label>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CCATEIS" class="form-control active" value="<?php echo $row['CCATEIS']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EIS Category</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CICEIS" class="form-control active" value="<?php echo $row['CICEIS']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EIS NOIC</b>
              </label>
            </div>
          </div>
        </div>
        <br>
        <p class="note note-success navbar">
          <strong>Employees Provident Fund (EPF)</strong>
        </p>
        <div class="row">
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOEPF" class="form-control active" value="<?php echo $row['CNOEPF']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EPF ID</b>
              </label>
            </div>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CTBLEPF" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result2 as $row2){ if($row2['CTYPTBL'] == 'EPF'){ ?>
              <option value="<?php echo $row2['CNOTBL'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CTBLEPF'] == $row2['CNOTBL']){ echo "selected"; } ?>>
                <?php echo $row2['CNOTBL']." | ".$row2['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>EPF Table</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CINITEPF" class="form-control active" value="<?php echo $row['CINITEPF']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EPF Initial</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CCATEPF" class="form-control active" value="<?php echo $row['CCATEPF']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EPF Category</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNKEPF" class="form-control active" value="<?php echo $row['CNKEPF']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EPF NK</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CICEPF" class="form-control active" value="<?php echo $row['CICEPF']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EPF NOIC</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CREFEPF" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result3 as $row3){ if($row3['CTYPREF'] == 'EPF'){ ?>
              <option value="<?php echo $row3['CTYPREF'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CREFEPF'] == $row3['CTYPREF']){ echo "selected"; } ?>>
                <?php echo $row3['CTYPREF']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>EPF Reference</b>
            </label>
          </div>
        </div>
        <br>
        <p class="note note-success navbar">
          <strong>Social Security Organisation (SOCSO)</strong>
        </p>
        <div class="row">
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOSOCSO" class="form-control active" value="<?php echo $row['CNOSOCSO']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>SOCSO ID</b>
              </label>
            </div>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CTBLSOCSO" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result2 as $row2){ if($row2['CTYPTBL'] == 'SOC'){ ?>
              <option value="<?php echo $row2['CNOTBL'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CTBLSOCSO'] == $row2['CNOTBL']){ echo "selected"; } ?>>
                <?php echo $row2['CNOTBL']." | ".$row2['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>SOCSO Table</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CCATSOCSO" class="form-control active" value="<?php echo $row['CCATSOCSO']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>SOCSO Category</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CICSOCSO" class="form-control active" value="<?php echo $row['CICSOCSO']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>SOCSO NOIC</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CREFSOCSO" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result3 as $row3){ if($row3['CTYPREF'] == 'SOC'){ ?>
              <option value="<?php echo $row3['CTYPREF'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CREFSOCSO'] == $row3['CTYPREF']){ echo "selected"; } ?>><?php echo $row3['CTYPREF']; ?></option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>SOCSO Reference</b>
            </label>
          </div>
        </div>
        <br>
        <p class="note note-success navbar">
          <strong>Tax</strong>
        </p>
        <div class="row">
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOTAX" class="form-control active" value="<?php echo $row['CNOTAX']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Tax ID</b>
              </label>
            </div>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CTBLTAX" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result2 as $row2){ if($row2['CTYPTBL'] == 'TAX'){ ?>
              <option value="<?php echo $row2['CNOTBL'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CTBLTAX'] == $row2['CNOTBL']){ echo "selected"; } ?>>
                <?php echo $row2['CNOTBL']." | ".$row2['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Tax Table</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CBRANCHTAX" class="form-control active" value="<?php echo $row['CBRANCHTAX']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Tax Branch</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CREFTAX" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result3 as $row3){ if($row3['CTYPREF'] == 'TAX'){ ?>
              <option value="<?php echo $row3['CTYPREF'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CREFTAX'] == $row3['CTYPREF']){ echo "selected"; } ?>>
                <?php echo $row3['CTYPREF']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Tax Reference</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="LERPAYTAX" value="<?php echo $row['LERPAYTAX']; ?>" <?php if($row['LERPAYTAX'] == '1'){ echo "checked"; } ?>>
              <label class="form-check-label text-dark" for="LERPAYTAX"><b>Tax by Employeer?</b></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-right">
            <button id="save" class="btn btn-primary zoom pointer">
              <b><i class="fas fa-save"></i> SAVE</b>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $('#LDEDHRD').change(function() {
    if ($(this).is(':checked')) {
      $(this).val(1);
    } else {
      $(this).val(0);
      $(this).removeAttr('checked');
    }
  });
});

$(document).ready(function() {
  $('#LERPAYTAX').change(function() {
    if ($(this).is(':checked')) {
      $(this).val(1);
    } else {
      $(this).val(0);
      $(this).removeAttr('checked');
    }
  });
});

$(document).ready(function() {
  $("#save").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_payroll: '<?php echo $code; ?>',
        CPAYMODE: $("#CPAYMODE").val(),
        CPERIODLAS: $("#CPERIODLAS").val(),
        CCYCLELAST: $("#CCYCLELAST").val(),
        LDEDHRD: $("#LDEDHRD").val(),
        CBANK: $("#CBANK").val(),
        CNOACC: $("#CNOACC").val(),
        CNOASB: $("#CNOASB").val(),
        CNOTBHAJI: $("#CNOTBHAJI").val(),
        CNOUNION: $("#CNOUNION").val(),
        CNOPERMIT: $("#CNOPERMIT").val(),
        DCONTRCT: $("#DCONTRCT").val(),
        DXCONTRCT: $("#DXCONTRCT").val(),
        DPERMIT: $("#DPERMIT").val(),
        DXPERMIT: $("#DXPERMIT").val(),
        CTBLORP: $("#CTBLORP").val(),
        CINCRTBL: $("#CINCRTBL").val(),
        CBONUSTBL: $("#CBONUSTBL").val(),
        CTBLZAKAT: $("#CTBLZAKAT").val(),
        CNOEIS: $("#CNOEIS").val(),
        CTBLEIS: $("#CTBLEIS").val(),
        CCATEIS: $("#CCATEIS").val(),
        CICEIS: $("#CICEIS").val(),
        CNOEPF: $("#CNOEPF").val(),
        CTBLEPF: $("#CTBLEPF").val(),
        CINITEPF: $("#CINITEPF").val(),
        CCATEPF: $("#CCATEPF").val(),
        CNKEPF: $("#CNKEPF").val(),
        CICEPF: $("#CICEPF").val(),
        CREFEPF: $("#CREFEPF").val(),
        CNOSOCSO: $("#CNOSOCSO").val(),
        CTBLSOCSO: $("#CTBLSOCSO").val(),
        CCATSOCSO: $("#CCATSOCSO").val(),
        CICSOCSO: $("#CICSOCSO").val(),
        CREFSOCSO: $("#CREFSOCSO").val(),
        CNOTAX: $("#CNOTAX").val(),
        CTBLTAX: $("#CTBLTAX").val(),
        CBRANCHTAX: $("#CBRANCHTAX").val(),
        CREFTAX: $("#CREFTAX").val(),
        LERPAYTAX: $("#LERPAYTAX").val()
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
        Swal.close();
        $.ajax({
          url: 'modules/employees/payroll?code=<?php echo $code; ?>',
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
</script>
<?php } ?>