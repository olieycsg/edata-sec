<?php 

ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];

$sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid' AND DRESIGN = '0000-00-00'";
$result1 = $conn->query($sql1);

if($row1 = $result1->fetch_assoc()){

  $head = $row1['CJOB'];
  $head1 = $row1['CJOB1'];
  $divi = $row1['CDIVISION'];

  $sqla = "SELECT * FROM sys_workflow_divisional WHERE CJOB = '$head'";
  $resulta = $conn->query($sqla);

  if($resulta->num_rows > 0) {
    $cstat = 1;
    if($rowa = $resulta->fetch_assoc()){

      $sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND CDIVISION = '$divi'";
      $result2 = $conn->query($sql2);

      foreach ($result2 as $key2 => $val2) { $cnoeh[] = $val2['CNOEE']; }

      $cnoehList = "'".implode("','", $cnoeh)."'";
      $sql3 = "SELECT * FROM eleave WHERE CNOEE IN ($cnoehList) AND MNOTES = 'recommended' ORDER BY DLEAVE ASC";
      $result3 = $conn->query($sql3);

      $sql5 = "SELECT * FROM eleave WHERE CNOEE IN ($cnoehList) AND MNOTES = 'pending' ORDER BY DLEAVE ASC";
      $result5 = $conn->query($sql5);

      /*$sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND CDIVISION = '$divi'";
      $result2 = $conn->query($sql2);

      foreach ($result2 as $key2 => $val2) { $cnoeh[] = $val2['CNOEE']; }

      $cnoehList = "'".implode("','", $cnoeh)."'";
      $sql3 = "SELECT * FROM eleave WHERE CNOEE IN ($cnoehList) AND MNOTES = 'recommended' ORDER BY DLEAVE ASC";
      $result3 = $conn->query($sql3);

      $sql2 = "SELECT * FROM employees_demas WHERE CSUPERIOR = CSUPERVISO AND DRESIGN = '0000-00-00' AND CDIVISION = '$divi'";
      $result2 = $conn->query($sql2);

      foreach ($result2 as $key2 => $val2) { $cnoeh[] = $val2['CNOEE']; }

      $cnoehList = "'".implode("','", $cnoeh)."'";
      $sql3 = "SELECT * FROM eleave WHERE CNOEE IN ($cnoehList) AND MNOTES = 'pending' ORDER BY DLEAVE ASC";
      $result3 = $conn->query($sql3);*/
    }
  }else{
    $cstat = 0;
    $sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND (CSUPERIOR = '$head' OR CSUPERIOR = '$head1')";
    $result2 = $conn->query($sql2);

    foreach ($result2 as $key2 => $val2) { $cnoeh[] = $val2['CNOEE']; }

    $cnoehList = "'".implode("','", $cnoeh)."'";
    $sql3 = "SELECT * FROM eleave WHERE CNOEE IN ($cnoehList) AND MNOTES = 'pending' ORDER BY DLEAVE ASC";
    $result3 = $conn->query($sql3);
  }
}

$sql4 = "SELECT * FROM eleave_leave_type";
$result4 = $conn->query($sql4);

$sql6 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND CDIVISION = '$divi'";
$result6 = $conn->query($sql6);

?>
<div id="content">
  <body class="nk-body">
    <div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap">
          <?php include('navbar.php'); ?>
          <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
              <div class="nk-ibx">
                <div class="nk-ibx-body">
                  <div class="list-group">
                    <?php 
                    foreach ($result3 as $k3 => $v3) {
                      foreach ($result2 as $key2 => $val2) { 
                        if($v3['CNOEE'] == $val2['CNOEE']){
                          $super = $val2['CSUPERIOR'];
                          foreach ($result6 as $key6 => $val6) { 
                            if($val6['CJOB'] == $super){
                              $crname1 = $val6['CNAME'];
                            }
                            if($val6['CJOB1'] == $super){
                              $crname2 = $val6['CNAME'];
                            }
                          }
                        }
                      }
                      foreach ($result as $key => $val) {
                        if($v3['CNOEE'] == $val['CNOEE']){
                          foreach ($result4 as $key4 => $val4) {
                            if($v3['CCDLEAVE'] == $val4['ID']){
                              $type = $val4['leave_type'];
                              $colr = $val4['leave_code'];
                            }
                          }
                    ?>
                    <ol class="list-group">
                      <li class="list-group-item">
                        <div class="media-group" style="display: flex; align-items: center; gap: 5px;">
                          <?php if($val['CIMAGE'] == null){ ?>
                          <div class="media media-md media-middle media-circle">
                            <?php if($val['CSEX'] == 'M'){ ?>
                            <img src="assets/images/male.png">
                            <?php }else if($val['CSEX'] == 'F'){ ?>
                            <img src="assets/images/female.png">
                            <?php } ?>
                          </div>
                          <?php }else{ ?>
                          <div class="media media-md media-middle media-circle">
                            <img src="../../hrad/modules/employees/file/<?php echo $val['CIMAGE']; ?>">
                          </div>
                          <?php } ?>
                          <div class="media-text">
                            <b style="font-size: 13px;"><?php echo $val['CNAME']; ?></b>
                            <span class="smaller" style="font-style: italic;">
                              <b style="text-transform: uppercase;">
                                <span class="badge bg-info" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" title="<?php echo $v3['CREASON']; ?>"><i class="fas fa-circle-info"></i></span>
                                <span class="badge <?php echo $colr; ?>"><?php echo $type; ?></span>
                                <br>
                                <?php echo strtoupper(date("d M Y", strtotime($v3['DLEAVE']))); ?>
                                <i class="fas fa-right-long"></i> 
                                <?php echo strtoupper(date("d M Y", strtotime($v3['DLEAVE2']))); ?>
                                <br>
                                <?php 
                                if($v3['NDAYS'] < 1){
                                  if($v3['NHOURS'] == '10'){
                                    echo $v3['NDAYS']." DAYS (MORNING)";
                                  }
                                  if($v3['NHOURS'] == '01'){
                                    echo $v3['NDAYS']." DAYS (AFTERNOON)";
                                  }
                                }else{
                                  echo $v3['NDAYS']." DAYS (FULLDAY)";
                                }
                                ?>
                                <br>
                                <b style="font-style: italic;" class="<?php echo ($v3['MNOTES'] === 'pending') ? 'text-danger' : 'text-primary'; ?>">STATUS: <?php echo $v3['MNOTES']; ?></b>
                                <br>
                                <b style="font-style: italic;" class="text-info">RECOMMENDER: <?php echo $crname1; ?></b>
                                <br>
                                <b style="font-style: italic;" class="text-info">OVERRIDE: <?php echo $crname2 ?? '-'; ?></b>
                              </b>
                            </span>
                          </div>
                          <div style="margin-left: auto;">
                            <span class="badge bg-success">
                              <i class="fas fa-check apply" data-id="<?php echo $v3['id']; ?>"></i>
                            </span>
                          </div>
                        </div>
                      </li>
                    </ol>
                    <?php } } } ?>

                    <?php 
                    foreach ($result5 as $k5 => $v5) {
                      foreach ($result2 as $key2 => $val2) { 
                        if($v5['CNOEE'] == $val2['CNOEE']){
                          $super = $val2['CSUPERIOR'];
                          foreach ($result6 as $key6 => $val6) { 
                            if($val6['CJOB'] == $super){
                              $crname1 = $val6['CNAME'];
                            }
                            if($val6['CJOB1'] == $super){
                              $crname2 = $val6['CNAME'];
                            }
                          }
                        }
                      }
                      foreach ($result as $key => $val) {
                        if($v5['CNOEE'] == $val['CNOEE']){
                          foreach ($result4 as $key4 => $val4) {
                            if($v5['CCDLEAVE'] == $val4['ID']){
                              $type = $val4['leave_type'];
                              $colr = $val4['leave_code'];
                            }
                          }
                    ?>
                    <ol class="list-group">
                      <li class="list-group-item">
                        <div class="media-group" style="display: flex; align-items: center; gap: 5px;">
                          <?php if($val['CIMAGE'] == null){ ?>
                          <div class="media media-md media-middle media-circle">
                            <?php if($val['CSEX'] == 'M'){ ?>
                            <img src="assets/images/male.png">
                            <?php }else if($val['CSEX'] == 'F'){ ?>
                            <img src="assets/images/female.png">
                            <?php } ?>
                          </div>
                          <?php }else{ ?>
                          <div class="media media-md media-middle media-circle">
                            <img src="../../hrad/modules/employees/file/<?php echo $val['CIMAGE']; ?>">
                          </div>
                          <?php } ?>
                          <div class="media-text">
                            <b style="font-size: 13px;"><?php echo $val['CNAME']; ?></b>
                            <span class="smaller" style="font-style: italic;">
                              <b style="text-transform: uppercase;">
                                <span class="badge bg-info" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" title="<?php echo $v5['CREASON']; ?>"><i class="fas fa-circle-info"></i></span>
                                <span class="badge <?php echo $colr; ?>"><?php echo $type; ?></span>
                                <br>
                                <?php echo strtoupper(date("d M Y", strtotime($v5['DLEAVE']))); ?>
                                <i class="fas fa-right-long"></i> 
                                <?php echo strtoupper(date("d M Y", strtotime($v5['DLEAVE2']))); ?>
                                <br>
                                <?php 
                                if($v5['NDAYS'] < 1){
                                  if($v5['NHOURS'] == '10'){
                                    echo $v5['NDAYS']." DAYS (MORNING)";
                                  }
                                  if($v5['NHOURS'] == '01'){
                                    echo $v5['NDAYS']." DAYS (AFTERNOON)";
                                  }
                                }else{
                                  echo $v5['NDAYS']." DAYS (FULLDAY)";
                                }
                                ?>
                                <br>
                                <b style="font-style: italic;" class="<?php echo ($v3['MNOTES'] === 'pending') ? 'text-danger' : 'text-primary'; ?>">STATUS: <?php echo $v5['MNOTES']; ?></b>
                                <br>
                                <b style="font-style: italic;" class="text-info">RECOMMENDER: <?php echo $crname1; ?></b>
                                <br>
                                <b style="font-style: italic;" class="text-info">OVERRIDE: <?php echo $crname2 ?? '-'; ?></b>
                              </b>
                            </span>
                          </div>
                        </div>
                      </li>
                    </ol>
                    <?php } } } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</div>
<script type="text/javascript">
<?php if($cstat == 0){ ?>
$(document).ready(function() {
  $(".apply").click(function(){
    var id = $(this).attr('data-id');
    Swal.fire({
      title: 'ARE YOU SURE ?',
      html: "<strong>RECOMMEND FOR DIVISION HEAD'S APPROVAL</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "api_apps",
          type: 'POST',
          data: {recommend_leave:id},
          beforeSend: function() {    
            Swal.fire({
              title: 'PROCESSING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response) {
            location.reload();
          },
        });
      }
    });
  });
});
<?php }else{ ?>
$(document).ready(function() {
  $(".apply").click(function(){
    var id = $(this).attr('data-id');
    Swal.fire({
      title: 'ARE YOU SURE ?',
      html: "<strong>APPROVE LEAVE</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "api_apps",
          type: 'POST',
          data: {approve_leave:id},
          beforeSend: function() {    
            Swal.fire({
              title: 'PROCESSING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response) {
            location.reload();
          },
        });
      }
    });
  });
});
<?php } ?>
</script>
<?php include('footer.php'); ?>