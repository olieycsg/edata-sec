<?php 

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../../api.php');

$get_month = $_POST['month'];
$get_year = $_POST['year'];
$get_division = $_POST['division'];
$get_view = $_POST['view'];

if($get_division != 'all'){
  $sql = "SELECT * FROM employees_demas WHERE CDIVISION = '$get_division' AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}else{
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}

$sql1 = "SELECT * FROM eleave ORDER BY DLEAVE ASC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave_leave_type";
$result2 = $conn->query($sql2);

$statusColors = ['pending' => 'text-danger', 'recommended' => 'text-primary', 'approved' => 'text-success'];

$leaveColors = ['1' => 'amber-color text-dark', '2' => 'yellow-color text-dark', '3' => 'info-color text-white', '4' => 'pink-color text-dark', '5' => 'cyan-color text-dark', '6' => 'brown-color text-white', '7' => 'grey-color text-white', '8' => 'lime-color text-dark', '9' => 'black-color text-white', '10' => 'light-color text-white', '11' => 'purple-gradient text-dark', '12' => 'green-color text-dark', '13' => 'peach-gradient text-dark', '14' => 'danger-color text-white', '15' => 'calamity-color text-white', '16' => 'other-color text-white'];

?>
<style type="text/css">
  .amber-color {
    background-color: #ffb300!important;
  }

  .yellow-color {
    background-color: #ff0!important;
  }

  .info-color {
    background-color: #33b5e5!important;
  }

  .pink-color {
    background-color: #e91e63!important;
  }

  .cyan-color {
    background-color: #006064!important;
  }

  .brown-color{
    background-color: #3e2723!important;
  }

  .grey-color{
    background-color: #424242!important;
  }

  .lime-color{
    background-color: #aeea00!important;
  }

  .black-color{
    background-color: #000000!important;
  }

  .light-color{
    background-color: #01579b!important;
  }

  .purple-gradient {
    background: linear-gradient(40deg,#ff6ec4,#7873f5) !important;
  }

  .green-color{
    background-color: #00e676!important;
  }

  .peach-gradient {
    background: linear-gradient(40deg,#ffd86f,#fc6262) !important;
  }

  .danger-color {
    background-color: #ff3547!important;
  }

  .success-color {
    background-color: #00c851!important;
  }

  .secondary-color {
    background-color: #93c!important;
  }

  .blue-gradient {
    background: linear-gradient(40deg,#45cafc,#303f9f)!important;
  }

  .text-left{
    text-align: left;
  }

  .text-right{
    text-align: right;
  }

  .calamity-color{
    background-color: #0b1952!important;
  }

  .other-color{
    background-color: #cc08c5!important;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8 overflow-x-scroll">
      <div class="card-body">
        <table id="tbl_onleave" class="table table-sm table-hover table-striped no-wrap">
          <thead class="success-color white-text">
            <tr class="text-left">
              <th style="text-align: left;"><b>NAME</b></th>
              <th><b>LEAVE</b></th>
              <th><b>START</b></th>
              <th><b>END</b></th>
              <th><b>DAYS</b></th>
              <th><b>STATUS</b></th>
              <th><i class="fas fa-cog"></i></th>
            </tr>
          </thead>
          <tbody>
            <?php 

            if($get_month != 'all'){
              while($value1 = $result1->fetch_assoc()){

                $get_date = $get_year."-".$get_month;
                if(date("Y-m", strtotime($value1['DLEAVE'])) == $get_date){
                  foreach ($result as $key => $value) {

                    if($get_view != 'all'){
                      $runsql = $value['CNOEE'] == $value1['CNOEE'] && $get_view == $value1['MNOTES'];
                    }else{
                      $runsql = $value['CNOEE'] == $value1['CNOEE'];
                    }

                    if($runsql){
                      foreach ($result2 as $key2 => $value2) {
                        if($value2['ID'] == $value1['CCDLEAVE']){
                          $leave_type = $value2['leave_type'];
                        }
                      }
            ?>
            <tr class="text-left">
              <td style="font-size: 13px; text-align: left;">
                <strong><?php echo $value['CNAME']; ?></strong>
              </td>
              <td>
                <?php

                $leaveTypeClass = $leaveColors[$value1['CCDLEAVE']] ?? '';

                if ($leaveTypeClass) {
                  echo "<span class='badge $leaveTypeClass'>$leave_type</span>";
                }

                ?>
              </td>
              <td><?php echo strtoupper(date("d M Y", strtotime($value1['DLEAVE']))); ?></td>
              <td><?php echo strtoupper(date("d M Y", strtotime($value1['DLEAVE2']))); ?></td>
              <td><?php echo $value1['NDAYS']; ?></td>
              <td>
                <?php 

                $statusClass = $statusColors[$value1['MNOTES']] ?? '';

                if ($statusClass) {
                  echo "<strong class='$statusClass'>" . strtoupper($value1['MNOTES']) . "</strong>";
                }

                ?>
              </td>
              <td>
                <?php if(in_array($value1['CCDLEAVE'], ['2','3','4','5','10','12'])){ ?>
                <i class="fas fa-file-pdf text-danger zoom pointer view_file" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".leave_setup" data-file="<?php echo $value1['FILE']; ?>"></i>
                <?php } ?>
                <i class="fas fa-circle-info text-primary zoom pointer reason" data-reason="<?php echo $value1['CREASON']; ?>"></i>
              </td>
            </tr>
            <?php 
            } } } } }else{ 

              while($value1 = $result1->fetch_assoc()){
                for ($i = 1; $i <= 12 ; $i++) { 
                  $get_date[$i] = $get_year."-".sprintf("%02d", $i);
                  if(date("Y-m", strtotime($value1['DLEAVE'])) == $get_date[$i]){
                  foreach ($result as $key => $value) {

                    if($get_view != 'all'){
                      $runsql = $value['CNOEE'] == $value1['CNOEE'] && $get_view == $value1['MNOTES'];
                    }else{
                      $runsql = $value['CNOEE'] == $value1['CNOEE'];
                    }

                    if($runsql){
                      foreach ($result2 as $key2 => $value2) {
                        if($value2['ID'] == $value1['CCDLEAVE']){
                          $leave_type = $value2['leave_type'];
                        }
                      }
            ?>
            <tr class="text-center">
              <td style="font-size: 13px; text-align: left;">
                <strong><?php echo $value['CNAME']; ?></strong>
              </td>
              <td>
                <?php

                $leaveTypeClass = $leaveColors[$value1['CCDLEAVE']] ?? '';

                if ($leaveTypeClass) {
                  echo "<span class='badge $leaveTypeClass'>$leave_type</span>";
                }

                ?>
              </td>
              <td><?php echo strtoupper(date("d M Y", strtotime($value1['DLEAVE']))); ?></td>
              <td><?php echo strtoupper(date("d M Y", strtotime($value1['DLEAVE2']))); ?></td>
              <td><?php echo $value1['NDAYS']; ?></td>
              <td>
                <?php 

                $statusClass = $statusColors[$value1['MNOTES']] ?? '';

                if ($statusClass) {
                  echo "<strong class='$statusClass'>" . strtoupper($value1['MNOTES']) . "</strong>";
                }

                ?>
              </td>
              <td>
                <?php if(in_array($value1['CCDLEAVE'], ['2','3','4','5','10','12'])){ ?>
                <i class="fas fa-file-pdf text-danger zoom pointer view_file" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".leave_setup" data-file="<?php echo $value1['FILE']; ?>"></i>
                <?php } ?>
                <i class="fas fa-circle-info text-primary zoom pointer reason" data-reason="<?php echo $value1['CREASON']; ?>"></i>
              </td>
            </tr>
            <?php } } } } } } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade leave_setup" data-mdb-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <b><i class="fas fa-file-pdf text-danger"></i> SUPPORTING DOCUMENT</b>
        <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <span id="show_file"></span>
      </div>        
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();

$(document).ready(function() {
  $(".reason").click(function(){
    var info = $(this).attr('data-reason');
    Swal.fire({
      html: "<b>"+info+"</b>"
    });
  });
});

$(document).ready(function() {
  $(".view_file").click(function(){
    var target = $(this).data('mdb-target');
    $(target).modal('show');
    var dataFile = $(this).attr('data-file');
    if (dataFile) {
      $('#show_file').html('<img src="../../../user/webapp/file/' + dataFile + '" class="img-thumbnail">');
    } else {
      $('#show_file').html('<b>No Data</b>');
    }
  });
});

</script>