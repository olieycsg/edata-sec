<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee_inservice WHERE CNOEE = '$code' ORDER BY CSEQUENCE ASC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_general_dcmisc";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM payroll_dccentre";
$result3 = $conn->query($sql3);

$sql4 = "SELECT * FROM payroll_dccharge";
$result4 = $conn->query($sql4);

$sql5 = "SELECT * FROM payroll_dcommetd";
$result5 = $conn->query($sql5);

if($row = $result->fetch_assoc()){

$rectype = array('A' => 'ACTING', 'C' => 'CONFIRMATION', 'D' => 'DEMOTION', 'E' => 'REINSTATEMENT', 'H' => 'HIRE', 'I' => 'INCREMENT', 'N' => 'PROMOTION CONFIRMATION', 'O' => 'PERMANENT APPOINTMENT', 'P' => 'PROMOTION', 'J' => 'SALARY ADJUSTMENTS', 'Q' => 'SUSPENSION', 'R' => 'REVISION', 'S' => 'REDESIGNATION', 'T' => 'TRANSFER', 'X' => 'CEASE');

$emptype = array('C' => 'CONTRACT', 'P' => 'PERMANENT', 'T' => 'TEMPORARY');

$payrate = array('D' => 'DAILY', 'M' => 'MONTHLY', 'H' => 'HOURLY');

foreach ($result1 as $key1 => $row) { 
  $gid[] = $row['CSEQUENCE'];
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
            <b><i class="fas fa-caret-right"></i> In Service</b>
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
              <p class="note note-success navbar">
                <strong>Section 1</strong>
              </p>
              <div class="row">
                <div class="col-md-2" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CSEQUENCE" class="form-control active" value="<?php echo end($gid) + 1; ?>" placeholder="..." disabled>
                    <label class="form-label text-primary">
                      <b>Sequence</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <select id="CTYPREC" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($rectype as $recvalue => $reclabel) { ?>
                    <option value="<?php echo $recvalue; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $reclabel; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Record Type</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="DREC" type="date" class="form-control active" value="<?php echo date("Y-m-d"); ?>">
                    <label class="form-label text-primary">
                      <b>Date</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="CSHFTGROUP" class="form-control active" value="G1" placeholder="e.g G1">
                    <label class="form-label text-primary">
                      <b>Shift Group</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CJOB" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'JOB'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CJOB'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Job</b>
                  </label>
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
                  <select id="CCATEGORY" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'CAT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCATEGORY'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                     </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Category</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CGRADE" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'GRADE'){?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CGRADE'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                     </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Grade</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CTYPEMPL" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <?php foreach ($emptype as $empvalue => $emplabel) { ?>
                    <option value="<?php echo $empvalue; ?>" data-mdb-icon="../img/icon.png" <?php if($row['CTYPEMPL'] == $empvalue){ echo "selected"; } ?>>
                      <?php echo $emplabel; ?>
                     </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Employee Type</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CCLASS" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'CLASS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCLASS'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Class</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CCOSTCENTR" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result3 as $row3){ ?>
                    <option value="<?php echo $row3['CCOSTCNTRE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCOSTCENTR'] == $row3['CCOSTCNTRE']){ echo "selected"; } ?>>
                      <?php echo $row3['CDESC']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cost Centre</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CSCALE" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php  foreach($result2 as $row2){ if($row2['CTYPE'] == 'SCALE'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSCALE'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Salary Scale (RM)</b>
                  </label>
                </div>
                <div class="col-md-9" style="padding: 10px;">
                  <select id="CCHARGE" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result4 as $row4){ ?>
                    <option value="<?php echo $row4['CCHARGE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCHARGE'] == $row4['CCHARGE']){ echo "selected"; } ?>>
                      <?php echo $row4['CDESC']." | ".$row4['CCHARGE']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Charge</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YPAYBASIC" class="form-control active" value="<?php echo $row['YPAYBASIC']; ?>" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Basic Pay (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="YINCREMENT" class="form-control active" value="<?php echo $row['YINCREMENT']; ?>" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Increment (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CPAYRATE" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <?php foreach ($payrate as $payvalue => $paylabel) { ?>
                    <option value="<?php echo $payvalue; ?>" data-mdb-icon="../img/icon.png" <?php if($row['CPAYRATE'] == $payvalue){ echo "selected"; } ?>>
                      <?php echo $paylabel; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Pay Rate</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="CMETDCOMP" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <?php foreach($result5 as $row5){ ?>
                    <option value="<?php echo $row5['CMETDCOMP'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CMETDCOMP'] == $row5['CMETDCOMP']){ echo "selected"; } ?>>
                      <?php echo $row5['CDESC']." | ".$row5['CMETDCOMP']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Computation Method</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CSUPERIOR" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SUPER'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSUPERIOR'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Superior</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CSUPERVISO" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SUPVS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSUPERVISO'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?></option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Supervisor</b>
                  </label>
                </div>
              </div>
              <br>
              <p class="note note-success navbar">
                <strong>Section 2</strong>
              </p>
              <div class="row">
                <div class="col-md-2" style="padding: 10px;">
                  <select id="CCOMPANY" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <option value="SEC" data-mdb-icon="../img/icon.png" <?php if($row['CCOMPANY'] == 'SEC'){ echo "selected"; } ?>>SEC</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Company</b>
                  </label>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <select id="CBRANCH" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'BRANC'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CBRANCH'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                     </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Branch</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CENTLLVE" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'LVENT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CENTLLVE'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Leave</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CENTLMEDIC" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'MCENT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CENTLMEDIC'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Medical</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CDEPARTMEN" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'DEPTM'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CDEPARTMEN'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Department</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="CDIVISION" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'DIVSN'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CDIVISION'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Division</b>
                  </label>
                </div>
              </div>
              <br>
              <p class="note note-success navbar">
                <strong>Section 3</strong>
              </p>
              <div class="row">
                <div class="col-md-4" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="IMTHSPROB" class="form-control active" value="<?php echo $row['IMTHSPROB']; ?>" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Probation</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="IMTHSNOTC" class="form-control active" value="<?php echo $row['IMTHSNOTC']; ?>" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Notice</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <select id="CRREASON" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'RREAS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CRREASON'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cease Reason</b>
                  </label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_service" class="btn btn-primary">
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
                  <th><b>SEQUENCE</b></th>
                  <th><b>DATE</b></th>
                  <th><b>RECORD</b></th>
                  <th><b>POSITION</b></th>
                  <th><b>DEPARTMENT</b></th>
                  <th><b>PAY RATE</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($result1 as $key1 => $row) { $id[] = $row['ID']; ?>
                <tr>
                  <td><?php echo count($id); ?></td>
                  <td><?php echo strtoupper(date("d M Y", strtotime($row['DREC']))); ?></td>
                  <td><?php if($row['CTYPREC'] != ''){ echo $row['CTYPREC']; }else{ echo "-"; } ?></td>
                  <td><?php if($row['CPOSITION'] != ''){ echo $row['CPOSITION']; }else{ echo "-"; } ?></td>
                  <td><?php echo $row['CDEPARTMEN']; ?></td>
                  <td><?php echo $row['CPAYRATE']; ?></td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $row['ID']; ?>" data-csequence="<?php echo $row['CSEQUENCE']; ?>" data-ctyprec="<?php echo $row['CTYPREC']; ?>" data-drec="<?php echo $row['DREC']; ?>" data-cshftgroup="<?php echo $row['CSHFTGROUP']; ?>" data-cjob="<?php echo $row['CJOB']; ?>" data-cposition="<?php echo $row['CPOSITION']; ?>" data-ccategory="<?php echo $row['CCATEGORY']; ?>" data-cgrade="<?php echo $row['CGRADE']; ?>" data-ctypempl="<?php echo $row['CTYPEMPL']; ?>" data-cclass="<?php echo $row['CCLASS']; ?>" data-ccostcentr="<?php echo $row['CCOSTCENTR']; ?>" data-cscale="<?php echo $row['CSCALE']; ?>" data-ccharge="<?php echo $row['CCHARGE']; ?>" data-ypaybasic="<?php echo $row['YPAYBASIC']; ?>" data-yincrement="<?php echo $row['YINCREMENT']; ?>" data-cpayrate="<?php echo $row['CPAYRATE']; ?>" data-cmetdcomp="<?php echo $row['CMETDCOMP']; ?>" data-csuperior="<?php echo $row['CSUPERIOR']; ?>" data-csuperviso="<?php echo $row['CSUPERVISO']; ?>" data-ccompany="<?php echo $row['CCOMPANY']; ?>" data-cbranch="<?php echo $row['CBRANCH']; ?>" data-centllve="<?php echo $row['CENTLLVE']; ?>" data-centlmedic="<?php echo $row['CENTLMEDIC']; ?>" data-cdepartmen="<?php echo $row['CDEPARTMEN']; ?>" data-cdivision="<?php echo $row['CDIVISION']; ?>" data-imthsprob="<?php echo $row['IMTHSPROB']; ?>" data-imthsnotc="<?php echo $row['IMTHSNOTC']; ?>" data-crreason="<?php echo $row['CRREASON']; ?>"></i>
                    <i class="fas fa-trash-can text-danger zoom pointer delete" data-id="<?php echo $row['ID']; ?>" data-date="<?php echo strtoupper(date("d F Y", strtotime($row['DREC']))); ?>"></i>
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
              <p class="note note-success navbar">
                <strong>Section 1</strong>
              </p>
              <div class="row">
                <div class="col-md-2" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECSEQUENCE" class="form-control active" placeholder="..." disabled>
                    <label class="form-label text-primary">
                      <b>Sequence</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <select id="ECTYPREC" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($rectype as $recvalue => $reclabel) { ?>
                    <option value="<?php echo $recvalue; ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $reclabel; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Record Type</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="EDREC" type="date" class="form-control active">
                    <label class="form-label text-primary">
                      <b>Date</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input id="ECSHFTGROUP" class="form-control active" placeholder="e.g G1">
                    <label class="form-label text-primary">
                      <b>Shift Group</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECJOB" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'JOB'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png">
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Job</b>
                  </label>
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
                  <select id="ECCATEGORY" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'CAT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCATEGORY'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                     </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Category</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECGRADE" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'GRADE'){?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CGRADE'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                     </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Grade</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECTYPEMPL" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="">- Select -</option>
                    <?php foreach ($emptype as $empvalue => $emplabel) { ?>
                    <option value="<?php echo $empvalue; ?>" data-mdb-icon="../img/icon.png" <?php if($row['CTYPEMPL'] == $empvalue){ echo "selected"; } ?>>
                      <?php echo $emplabel; ?>
                     </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Employee Type</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECCLASS" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'CLASS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCLASS'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Class</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECCOSTCENTR" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result3 as $row3){ ?>
                    <option value="<?php echo $row3['CCOSTCNTRE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCOSTCENTR'] == $row3['CCOSTCNTRE']){ echo "selected"; } ?>>
                      <?php echo $row3['CDESC']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cost Centre</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECSCALE" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php  foreach($result2 as $row2){ if($row2['CTYPE'] == 'SCALE'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSCALE'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Salary Scale (RM)</b>
                  </label>
                </div>
                <div class="col-md-9" style="padding: 10px;">
                  <select id="ECCHARGE" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result4 as $row4){ ?>
                    <option value="<?php echo $row4['CCHARGE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CCHARGE'] == $row4['CCHARGE']){ echo "selected"; } ?>>
                      <?php echo $row4['CDESC']." | ".$row4['CCHARGE']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Charge</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYPAYBASIC" class="form-control active" value="<?php echo $row['YPAYBASIC']; ?>" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Basic Pay (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EYINCREMENT" class="form-control active" value="<?php echo $row['YINCREMENT']; ?>" placeholder="0.00" onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                    <label class="form-label text-primary">
                      <b>Increment (RM)</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECPAYRATE" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="">- Select -</option>
                    <?php foreach ($payrate as $payvalue => $paylabel) { ?>
                    <option value="<?php echo $payvalue; ?>" data-mdb-icon="../img/icon.png" <?php if($row['CPAYRATE'] == $payvalue){ echo "selected"; } ?>>
                      <?php echo $paylabel; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Pay Rate</b>
                  </label>
                </div>
                <div class="col-md-3" style="padding: 10px;">
                  <select id="ECMETDCOMP" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="">- Select -</option>
                    <?php foreach($result5 as $row5){ ?>
                    <option value="<?php echo $row5['CMETDCOMP'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CMETDCOMP'] == $row5['CMETDCOMP']){ echo "selected"; } ?>>
                      <?php echo $row5['CDESC']." | ".$row5['CMETDCOMP']; ?>
                    </option>
                    <?php } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Computation Method</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECSUPERIOR" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SUPER'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSUPERIOR'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Superior</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECSUPERVISO" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'SUPVS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CSUPERVISO'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?></option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Supervisor</b>
                  </label>
                </div>
              </div>
              <br>
              <p class="note note-success navbar">
                <strong>Section 2</strong>
              </p>
              <div class="row">
                <div class="col-md-2" style="padding: 10px;">
                  <select id="ECCOMPANY" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="">- Select -</option>
                    <option value="SEC" data-mdb-icon="../img/icon.png" <?php if($row['CCOMPANY'] == 'SEC'){ echo "selected"; } ?>>SEC</option>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Company</b>
                  </label>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <select id="ECBRANCH" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'BRANC'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CBRANCH'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                     </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Branch</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECENTLLVE" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'LVENT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CENTLLVE'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Leave</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECENTLMEDIC" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'MCENT'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CENTLMEDIC'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Medical</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECDEPARTMEN" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'DEPTM'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CDEPARTMEN'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Department</b>
                  </label>
                </div>
                <div class="col-md-6" style="padding: 10px;">
                  <select id="ECDIVISION" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'DIVSN'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CDIVISION'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Division</b>
                  </label>
                </div>
              </div>
              <br>
              <p class="note note-success navbar">
                <strong>Section 3</strong>
              </p>
              <div class="row">
                <div class="col-md-4" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EIMTHSPROB" class="form-control active" value="<?php echo $row['IMTHSPROB']; ?>" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Probation</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="number" id="EIMTHSNOTC" class="form-control active" value="<?php echo $row['IMTHSNOTC']; ?>" placeholder="...">
                    <label class="form-label text-primary">
                      <b>Notice</b>
                    </label>
                  </div>
                </div>
                <div class="col-md-4" style="padding: 10px;">
                  <select id="ECRREASON" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach($result2 as $row2){ if($row2['CTYPE'] == 'RREAS'){ ?>
                    <option value="<?php echo $row2['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CRREASON'] == $row2['CCODE']){ echo "selected"; } ?>>
                      <?php echo $row2['CDESC']." | ".$row2['CCODE']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Cease Reason</b>
                  </label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_service" class="btn btn-primary">
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
  $("#add_service").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        add_in_service: '<?php echo $code; ?>',
        CSEQUENCE: $("#CSEQUENCE").val(),
        CTYPREC: $("#CTYPREC").val(),
        DREC: $("#DREC").val(),
        CSHFTGROUP: $("#CSHFTGROUP").val(),
        CJOB: $("#CJOB").val(),
        CPOSITION: $("#CPOSITION").val(),
        CCATEGORY: $("#CCATEGORY").val(),
        CGRADE: $("#CGRADE").val(),
        CTYPEMPL: $("#CTYPEMPL").val(),
        CCLASS: $("#CCLASS").val(),
        CCOSTCENTR: $("#CCOSTCENTR").val(),
        CSCALE: $("#CSCALE").val(),
        CCHARGE: $("#CCHARGE").val(),
        YPAYBASIC: $("#YPAYBASIC").val(),
        YINCREMENT: $("#YINCREMENT").val(),
        CPAYRATE: $("#CPAYRATE").val(),
        CMETDCOMP: $("#CMETDCOMP").val(),
        CSUPERIOR: $("#CSUPERIOR").val(),
        CSUPERVISO: $("#CSUPERVISO").val(),
        CCOMPANY: $("#CCOMPANY").val(),
        CBRANCH: $("#CBRANCH").val(),
        CENTLLVE: $("#CENTLLVE").val(),
        CENTLMEDIC: $("#CENTLMEDIC").val(),
        CDEPARTMEN: $("#CDEPARTMEN").val(),
        CDIVISION: $("#CDIVISION").val(),
        IMTHSPROB: $("#IMTHSPROB").val(),
        IMTHSNOTC: $("#IMTHSNOTC").val(),
        CRREASON: $("#CRREASON").val()
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
          url: 'modules/employees/in_service?code=<?php echo $code; ?>',
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
    var dat = $(this).attr('data-date');
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
          data: {delete_in_service:del},
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
              url: 'modules/employees/in_service?code=<?php echo $code; ?>',
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

$(document).ready(function() {
  $(".update_setup").click(function(){
    var target = $(this).data('mdb-target');
    $(target).modal('show');

    var CSEQUENCE = $(this).data('csequence');
    var CTYPREC = $(this).data('ctyprec');
    var DREC = $(this).data('drec');
    var CSHFTGROUP = $(this).data('cshftgroup');
    var CJOB = $(this).data('cjob');
    var CPOSITION = $(this).data('cposition');
    var CCATEGORY = $(this).data('ccategory');
    var CGRADE = $(this).data('cgrade');
    var CTYPEMPL = $(this).data('ctypempl');
    var CCLASS = $(this).data('cclass');
    var CCOSTCENTR = $(this).data('ccostcentr');
    var CSCALE = $(this).data('cscale');
    var CCHARGE = $(this).data('ccharge');
    var YPAYBASIC = $(this).data('ypaybasic');
    var YINCREMENT = $(this).data('yincrement');
    var CPAYRATE = $(this).data('cpayrate');
    var CMETDCOMP = $(this).data('cmetdcomp');
    var CSUPERIOR = $(this).data('csuperior');
    var CSUPERVISO = $(this).data('csuperviso');
    var COMPANY = $(this).data('ccompany');
    var BRANCH = $(this).data('cbranch');
    var ENTLLVE = $(this).data('centllve');
    var ENTLMEDIC = $(this).data('centlmedic');
    var DEPARTMEN = $(this).data('cdepartmen');
    var DIVISION = $(this).data('cdivision');
    var IMTHSPROB = $(this).data('imthsprob');
    var IMTHSNOTC = $(this).data('imthsnotc');
    var CRREASON = $(this).data('crreason');

    $("#ECSEQUENCE").val(CSEQUENCE);
    $("#ECTYPREC option[value='"+CTYPREC+"']").attr('selected', 'selected');
    $("#EDREC").val(DREC);
    $("#ECSHFTGROUP").val(CSHFTGROUP);
    $("#ECJOB option[value='"+CJOB+"']").attr('selected', 'selected');
    $("#ECPOSITION").val(CPOSITION);
    $("#ECCATEGORY option[value='"+CCATEGORY+"']").attr('selected', 'selected');
    $("#ECGRADE option[value='"+CGRADE+"']").attr('selected', 'selected');
    $("#ECTYPEMPL option[value='"+CTYPEMPL+"']").attr('selected', 'selected');
    $("#ECCLASS option[value='"+CCLASS+"']").attr('selected', 'selected');
    $("#ECCOSTCENTR option[value='"+CCOSTCENTR+"']").attr('selected', 'selected');
    $("#ECSCALE option[value='"+CSCALE+"']").attr('selected', 'selected');
    $("#ECCHARGE option[value='"+CCHARGE+"']").attr('selected', 'selected');
    $("#EYPAYBASIC").val(YPAYBASIC);
    $("#EYINCREMENT").val(YINCREMENT);
    $("#ECPAYRATE option[value='"+CPAYRATE+"']").attr('selected', 'selected');
    $("#ECMETDCOMP option[value='"+CMETDCOMP+"']").attr('selected', 'selected');
    $("#ECSUPERIOR option[value='"+CSUPERIOR+"']").attr('selected', 'selected');
    $("#ECSUPERVISO option[value='"+CSUPERVISO+"']").attr('selected', 'selected');
    $("#ECCOMPANY option[value='"+COMPANY+"']").attr('selected', 'selected');
    $("#ECBRANCH option[value='"+BRANCH+"']").attr('selected', 'selected');
    $("#ECENTLLVE option[value='"+ENTLLVE+"']").attr('selected', 'selected');
    $("#ECENTLMEDIC option[value='"+ENTLMEDIC+"']").attr('selected', 'selected');
    $("#ECDEPARTMEN option[value='"+DEPARTMEN+"']").attr('selected', 'selected');
    $("#ECDIVISION option[value='"+DIVISION+"']").attr('selected', 'selected');
    $("#EIMTHSPROB").val(IMTHSPROB);
    $("#EIMTHSNOTC").val(IMTHSNOTC);
    $("#ECRREASON option[value='"+CRREASON+"']").attr('selected', 'selected');
    $("#EID").val($(this).data('id'));

  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#ECTYPREC option").removeAttr('selected');
    $("#EDREC").val('');
    $("#ECSHFTGROUP").val('');
    $("#ECJOB option").removeAttr('selected');
    $("#ECPOSITION").val('');
    $("#ECCATEGORY option").removeAttr('selected');
    $("#ECGRADE option").removeAttr('selected');
    $("#ECTYPEMPL option").removeAttr('selected');
    $("#ECCLASS option").removeAttr('selected');
    $("#ECCOSTCENTR option").removeAttr('selected');
    $("#ECSCALE option").removeAttr('selected');
    $("#ECCHARGE option").removeAttr('selected');
    $("#EYPAYBASIC").val('');
    $("#EYINCREMENT").val('');
    $("#ECPAYRATE option").removeAttr('selected');
    $("#ECMETDCOMP option").removeAttr('selected');
    $("#ECSUPERIOR option").removeAttr('selected');
    $("#ECSUPERVISO option").removeAttr('selected');
    $("#ECCOMPANY option").removeAttr('selected');
    $("#ECBRANCH option").removeAttr('selected');
    $("#ECENTLLVE option").removeAttr('selected');
    $("#ECENTLMEDIC option").removeAttr('selected');
    $("#ECDEPARTMEN option").removeAttr('selected');
    $("#ECDIVISION option").removeAttr('selected');
    $("#EIMTHSPROB").val('');
    $("#EIMTHSNOTC").val('');
    $("#ECRREASON option").removeAttr('selected');
    $("#EID").val('');
  });
});

$(document).ready(function() {
  $("#edit_service").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_in_service: $("#EID").val(),
        CTYPREC: $("#ECTYPREC").val(),
        DREC: $("#EDREC").val(),
        CSHFTGROUP: $("#ECSHFTGROUP").val(),
        CJOB: $("#ECJOB").val(),
        CPOSITION: $("#ECPOSITION").val(),
        CCATEGORY: $("#ECCATEGORY").val(),
        CGRADE: $("#ECGRADE").val(),
        CTYPEMPL: $("#ECTYPEMPL").val(),
        CCLASS: $("#ECCLASS").val(),
        CCOSTCENTR: $("#ECCOSTCENTR").val(),
        CSCALE: $("#ECSCALE").val(),
        CCHARGE: $("#ECCHARGE").val(),
        YPAYBASIC: $("#EYPAYBASIC").val(),
        YINCREMENT: $("#EYINCREMENT").val(),
        CPAYRATE: $("#ECPAYRATE").val(),
        CMETDCOMP: $("#ECMETDCOMP").val(),
        CSUPERIOR: $("#ECSUPERIOR").val(),
        CSUPERVISO: $("#ECSUPERVISO").val(),
        CCOMPANY: $("#ECCOMPANY").val(),
        CBRANCH: $("#ECBRANCH").val(),
        CENTLLVE: $("#ECENTLLVE").val(),
        CENTLMEDIC: $("#ECENTLMEDIC").val(),
        CDEPARTMEN: $("#ECDEPARTMEN").val(),
        CDIVISION: $("#ECDIVISION").val(),
        IMTHSPROB: $("#EIMTHSPROB").val(),
        IMTHSNOTC: $("#EIMTHSNOTC").val(),
        CRREASON: $("#ECRREASON").val()
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
          url: 'modules/employees/in_service?code=<?php echo $code; ?>',
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