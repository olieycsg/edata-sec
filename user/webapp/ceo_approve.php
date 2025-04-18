<?php 

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

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

  $sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND CSUPERVISO = 'CEO'";
  $result2 = $conn->query($sql2);

  foreach ($result2 as $key2 => $val2) { $cnoeh[] = $val2['CNOEE']; }

  $cnoehList = "'".implode("','", $cnoeh)."'";
  $sql3 = "SELECT * FROM eleave WHERE CNOEE IN ($cnoehList) AND MNOTES = 'pending' ORDER BY DLEAVE ASC";
  $result3 = $conn->query($sql3);
}

$sql4 = "SELECT * FROM eleave_leave_type";
$result4 = $conn->query($sql4);

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
                                <span class="badge bg-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-custom-class="custom-tooltip" title="<?php echo $v3['CREASON']; ?>"><i class="fas fa-circle-info"></i></span>
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
</script>
<?php include('footer.php'); ?>