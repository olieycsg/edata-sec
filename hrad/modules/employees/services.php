<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM payroll_dccentre";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM payroll_dccharge";
$result3 = $conn->query($sql3);

$sql4 = "SELECT * FROM payroll_dcommetd";
$result4 = $conn->query($sql4);

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
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DHIRE" type="date" class="form-control active" value="<?php if($row['DHIRE'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DHIRE'])); } ?>">
              <label class="form-label text-primary">
                <b>Hire Date</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DCONFIRM" type="date" class="form-control active" value="<?php if($row['DCONFIRM'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DCONFIRM'])); } ?>">
              <label class="form-label text-primary">
                <b>Confirm Date</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DRESIGN" type="date" class="form-control active" value="<?php if($row['DRESIGN'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DRESIGN'])); } ?>">
              <label class="form-label text-primary">
                <b>Cease Date</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="IMTHSPROB" type="number" class="form-control active" value="<?php echo $row['IMTHSPROB']; ?>" placeholder="e.g. 6">
              <label class="form-label text-primary">
                <b>Probation</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="IDYSPROB" type="number" class="form-control active" value="<?php echo $row['IDYSPROB']; ?>" placeholder="e.g. 6">
              <label class="form-label text-primary">
                <b>Probation Ext</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="IMTHSPROB2" type="number" class="form-control active" value="<?php echo $row['IMTHSPROB2']; ?>" placeholder="e.g. 6">
              <label class="form-label text-primary">
                <b>Extended</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="IDYSPROB2" type="number" class="form-control active" value="<?php echo $row['IDYSPROB2']; ?>" placeholder="e.g. 6">
              <label class="form-label text-primary">
                <b>Extended Ext</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="IMTHSNOTC" type="number" class="form-control active" value="<?php echo $row['IMTHSNOTC']; ?>" placeholder="e.g. 6">
              <label class="form-label text-primary">
                <b>Notice Period</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="IDYSNOTC" type="number" class="form-control active" value="<?php echo $row['IDYSNOTC']; ?>" placeholder="e.g. 6">
              <label class="form-label text-primary">
                <b>Notice Period Ext</b>
              </label>
            </div>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CCATEGORY" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'CAT'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCATEGORY'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
               </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Category</b>
            </label>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CGRADE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'GRADE'){?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CGRADE'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
               </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Grade</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CCLASS" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'CLASS'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCLASS'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Class</b>
            </label>
          </div>
          <div class="col-md-8" style="padding: 10px;">
            <select id="CJOB" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'JOB'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CJOB'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Job</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CPOSITION" class="form-control" value="<?php echo $row['CPOSITION']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Position</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CSCALE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php  foreach($result1 as $row1){ if($row1['CTYPE'] == 'SCALE'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSCALE'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Salary Scale (RM)</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CTYPEMPL" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init >
              <option value="">- Select -</option>
              <option value="C" data-mdb-icon="../img/icon.png" <?php if($row['CTYPEMPL'] == 'C'){ echo "selected"; } ?>>CONTRACT</option>
              <option value="P" data-mdb-icon="../img/icon.png" <?php if($row['CTYPEMPL'] == 'P'){ echo "selected"; } ?>>PERMANENT</option>
              <option value="T" data-mdb-icon="../img/icon.png" <?php if($row['CTYPEMPL'] == 'T'){ echo "selected"; } ?>>TEMPORARY</option>
            </select>
            <label class="form-label select-label text-primary">
              <b>Employee Type</b>
            </label>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CCOSTCENTR" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result2 as $row2){ ?>
              <option value="<?php echo $row2['CCOSTCNTRE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCOSTCENTR'] == $row2['CCOSTCNTRE']){ echo "selected"; } ?>>
                <?php echo $row2['CDESC']; ?>
              </option>
              <?php } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Cost Centre</b>
            </label>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CCHARGE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result3 as $row3){ ?>
              <option value="<?php echo $row3['CCHARGE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCHARGE'] == $row3['CCHARGE']){ echo "selected"; } ?>>
                <?php echo $row3['CDESC']." | ".$row3['CCHARGE']; ?>
              </option>
              <?php } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Charge</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CPAYRATE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
              <option value="">- Select -</option>
              <option value="D" data-mdb-icon="../img/icon.png" <?php if($row['CPAYRATE'] == 'D'){ echo "selected"; } ?>>DAILY</option>
              <option value="M" data-mdb-icon="../img/icon.png" <?php if($row['CPAYRATE'] == 'M'){ echo "selected"; } ?>>MONTHLY</option>
              <option value="H" data-mdb-icon="../img/icon.png" <?php if($row['CPAYRATE'] == 'H'){ echo "selected"; } ?>>HOURLY</option>
            </select>
            <label class="form-label select-label text-primary">
              <b>Pay Rate</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CMETDCOMP" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result4 as $row4){ ?>
              <option value="<?php echo $row4['CMETDCOMP'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CMETDCOMP'] == $row4['CMETDCOMP']){ echo "selected"; } ?>>
                <?php echo $row4['CDESC']." | ".$row4['CMETDCOMP']; ?>
              </option>
              <?php } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Computation Method</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="number" id="YPAYBASIC" class="form-control" value="<?php echo $row['YPAYBASIC']; ?>" placeholder="e.g. 100.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
              <label class="form-label text-primary">
                <b>Basic Pay (RM)</b>
              </label>
            </div>
          </div>
          <div class="col-md-8" style="padding: 10px;">
            <select id="CSUPERIOR" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'SUPER'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSUPERIOR'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Superior</b>
            </label>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSHFTGROUP" class="form-control" value="<?php echo $row['CSHFTGROUP']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Shift Group</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <select id="CCOMPANY" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
              <option value="">- Select -</option>
              <option value="SEC" data-mdb-icon="../img/icon.png" <?php if($row['CCOMPANY'] == 'SEC'){ echo "selected"; } ?>>SEC</option>
            </select>
            <label class="form-label select-label text-primary">
              <b>Company</b>
            </label>
          </div>
          <div class="col-md-8" style="padding: 10px;">
            <select id="CSUPERVISO" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'SUPVS'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSUPERVISO'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?></option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Supervisor</b>
            </label>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CBRANCH" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init>
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'BRANC'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CBRANCH'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
               </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Branch</b>
            </label>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CDIVISION" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'DIVSN'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CDIVISION'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Division</b>
            </label>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CDEPARTMEN" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'DEPTM'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CDEPARTMEN'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Department</b>
            </label>
          </div>
        </div>
        <br>
        <p class="note note-success navbar">
          <strong>Leave and Medical Entitlement</strong>
        </p>
        <div class="row">
          <div class="col-md-6" style="padding: 10px;">
            <select id="CENTLLVE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'LVENT'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CENTLLVE'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Leave</b>
            </label>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <select id="CENTLMEDIC" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="">- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'MCENT'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CENTLMEDIC'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']." | ".$row1['CCODE']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Medical</b>
            </label>
          </div>
          <div class="col-md-12" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DREFENTL" type="date" class="form-control active" value="<?php if($row['DREFENTL'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DREFENTL'])); } ?>">
              <label class="form-label text-primary">
                <b>Ref Date</b>
              </label>
            </div>
          </div>
        </div>
        <br>
        <p class="note note-success navbar">
          <strong>Shift Worker Setting</strong>
        </p>
        <div class="row">
          <div class="col-md-12" style="padding: 10px;">
            <select id="CSHIFT" class="sec-select" data-mdb-select-init>
              <option value="">- Select -</option>
              <option value="0" data-mdb-icon="../img/icon.png" <?php if($row['CSHIFT'] == '0'){ echo "selected"; } ?>>
                NORMAL HOURS
              </option>
              <option value="1" data-mdb-icon="../img/icon.png" <?php if($row['CSHIFT'] == '1'){ echo "selected"; } ?>>
                SHIFT HOURS
              </option>
            </select>
            <label class="form-label select-label text-primary">
              <b>Setting</b>
            </label>
          </div>
        </div>
        <br>
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
  $("#save").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_services: '<?php echo $code; ?>',
        DHIRE: $("#DHIRE").val(),
        DCONFIRM: $("#DCONFIRM").val(),
        DRESIGN: $("#DRESIGN").val(),
        IMTHSPROB: $("#IMTHSPROB").val(),
        IDYSPROB: $("#IDYSPROB").val(),
        IMTHSPROB2: $("#IMTHSPROB2").val(),
        IDYSPROB2: $("#IDYSPROB2").val(),
        IMTHSNOTC: $("#IMTHSNOTC").val(),
        IDYSNOTC: $("#IDYSNOTC").val(),
        CCATEGORY: $("#CCATEGORY").val(),
        CGRADE: $("#CGRADE").val(),
        CCLASS: $("#CCLASS").val(),
        CJOB: $("#CJOB").val(),
        CPOSITION: $("#CPOSITION").val(),
        CSCALE: $("#CSCALE").val(),
        CTYPEMPL: $("#CTYPEMPL").val(),
        CCOSTCENTR: $("#CCOSTCENTR").val(),
        CCHARGE: $("#CCHARGE").val(),
        CPAYRATE: $("#CPAYRATE").val(),
        CMETDCOMP: $("#CMETDCOMP").val(),
        YPAYBASIC: $("#YPAYBASIC").val(),
        CSUPERIOR: $("#CSUPERIOR").val(),
        CSHFTGROUP: $("#CSHFTGROUP").val(),
        CCOMPANY: $("#CCOMPANY").val(),
        CSUPERVISO: $("#CSUPERVISO").val(),
        CBRANCH: $("#CBRANCH").val(),
        CDIVISION: $("#CDIVISION").val(),
        CDEPARTMEN: $("#CDEPARTMEN").val(),
        CENTLLVE: $("#CENTLLVE").val(),
        CENTLMEDIC: $("#CENTLMEDIC").val(),
        DREFENTL: $("#DREFENTL").val(),
        CSHIFT: $("#CSHIFT").val()
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
          url: 'modules/employees/services?code=<?php echo $code; ?>',
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