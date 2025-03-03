<?php 

include('../../../../api.php'); 
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CDIVISION = '$code' AND DRESIGN = '0000-00-00'";
$result = $conn->query($sql);

$sqlf = "SELECT * FROM employees_demas WHERE CDIVISION = '$code' AND DRESIGN = '0000-00-00' GROUP BY CSUPERIOR ASC";
$resultf = $conn->query($sqlf);

$sql1 = "SELECT * FROM sys_workflow_divisional WHERE CDIVISION = '$code'";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$code'";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM sys_workflow WHERE CDIVISION = '$code'";
$result3 = $conn->query($sql3);

if($row1 = $result1->fetch_assoc()){
  $head = $row1['CJOB'];
}

if($row2 = $result2->fetch_assoc()){
  $secs = $row2['CJOB'];
}

foreach ($resultf as $kf => $vf) {
  $superior[] = $vf['CSUPERIOR'];
}

?>
<style type="text/css">
.text-left{
  text-align: left;
}
.text-right{
  text-align: right;
}
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <b><i class="fas fa-caret-right"></i> Workflow Viewer Table</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 text-center overflow-x-scroll">
            <table id="tbl_codes" class="table table-sm table-hover table-striped no-wrap">
              <thead class="text-left">
                <tr>
                  <th><b>NAME</b></th>
                  <th><b>POST</b></th>
                  <th><b>DESCRIPTION</b></th>
                  <th><b>LEAVE</b></th>
                  <th><b>MEDICAL</b></th>
                </tr>
              </thead>
              <tbody class="text-left">
                <?php

                foreach ($result as $key => $value) { if ($value['CJOB'] == $head){ 
                  foreach ($result3 as $k3 => $v3) {
                    if($value['CJOB'] == $v3['CJOB'] && $v3['MODULE'] == 'eLeave'){
                      $id1 = $v3['ID'];
                      $act_leave = $v3['ACTION'];
                    }else if($value['CJOB'] == $v3['CJOB'] && $v3['MODULE'] == 'eMedical'){
                      $id2 = $v3['ID'];
                      $act_medic = $v3['ACTION'];
                    }
                  }

                ?>
                <tr>
                  <td><b><?php echo $value['CNAME']; ?></b></td>
                  <td><b><?php echo $value['CJOB']; ?></b></td>
                  <td><b><?php echo $value['CPOSITION']; ?></b></td>
                  <td>
                    <select class="form-control form-control-sm leave" style="width: 100%;" data-id="<?php echo $id1; ?>">
                      <option value="">- Select -</option>
                      <option value="approved" <?php echo $act_leave === 'approved' ? 'selected' : ''; ?>>Approved</option>
                      <option value="recommended" <?php echo $act_leave === 'recommended' ? 'selected' : ''; ?>>Recommended</option>
                    </select>
                  </td>
                  <td>
                    <select class="form-control form-control-sm medic" style="width: 100%;" data-id="<?php echo $id2; ?>">
                      <option value="">- Select -</option>
                      <option value="approved" <?php echo $act_medic === 'approved' ? 'selected' : ''; ?>>Approved</option>
                      <option value="recommended" <?php echo $act_medic === 'recommended' ? 'selected' : ''; ?>>Recommended</option>
                    </select>
                  </td>
                </tr>
                <?php } } ?>

                <?php 

                foreach ($result as $key => $value) { if (in_array($value['CJOB'], $superior) && $value['CJOB'] != $head && $value['CJOB'] != '') {
                  foreach ($result3 as $k3 => $v3) {
                    if($value['CJOB'] == $v3['CJOB'] && $v3['MODULE'] == 'eLeave'){
                      $id3 = $v3['ID'];
                      $act_leaves = $v3['ACTION'];
                    }else if($value['CJOB'] == $v3['CJOB'] && $v3['MODULE'] == 'eMedical'){
                      $id4 = $v3['ID'];
                      $act_medics = $v3['ACTION'];
                    }
                  }
                ?>
                <tr>
                  <td><b><?php echo $value['CNAME']; ?></b></td>
                  <td><b><?php echo $value['CJOB']; ?></b></td>
                  <td><b><?php echo $value['CPOSITION']; ?></b></td>
                  <td>
                    <select class="form-control form-control-sm leave" style="width: 100%;" data-id="<?php echo $id3; ?>">
                      <option value="">- Select -</option>
                      <option value="approved" <?php echo $act_leaves === 'approved' ? 'selected' : ''; ?>>Approved</option>
                      <option value="recommended" <?php echo $act_leaves === 'recommended' ? 'selected' : ''; ?>>Recommended</option>
                    </select>
                  </td>
                  <td>
                    <select class="form-control form-control-sm medic" style="width: 100%;" data-id="<?php echo $id4; ?>">
                      <option value="">- Select -</option>
                      <option value="approved" <?php echo $act_medics === 'approved' ? 'selected' : ''; ?>>Approved</option>
                      <option value="recommended" <?php echo $act_medics === 'recommended' ? 'selected' : ''; ?>>Recommended</option>
                    </select>
                  </td>
                </tr>
                <?php } } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  $(".leave").change(function() {
    var act = $(this).val();
    var id = $(this).attr("data-id");
    $.ajax({
      url: "modules/system/api_main",
      type: "POST",
      data: {edit_module:id,act:act},
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
          url: 'modules/system/ajax/ajax_table?code=<?php echo $code; ?>',
          beforeSend: function() {
            $('.sub_loader').show();
            $('.no_data').hide();
            $('#show_ajax_data').hide();
          },
          success: function(data) {
            $('.sub_loader').hide();
            $('.no_data').hide();
            $("#show_ajax_data").html(data).show();
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".medic").change(function() {
    var act = $(this).val();
    var id = $(this).attr("data-id");
    $.ajax({
      url: "modules/system/api_main",
      type: "POST",
      data: {edit_module:id,act:act},
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
          url: 'modules/system/ajax/ajax_table?code=<?php echo $code; ?>',
          beforeSend: function() {
            $('.sub_loader').show();
            $('.no_data').hide();
            $('#show_ajax_data').hide();
          },
          success: function(data) {
            $('.sub_loader').hide();
            $('.no_data').hide();
            $("#show_ajax_data").html(data).show();
          }
        });
      },
    });
  });
});
</script>