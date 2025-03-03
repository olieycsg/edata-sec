<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];
$mths = date('Y-m-d', strtotime($currentDate . ' -6 months'));

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

$sql1 = "SELECT * FROM employees_demas ORDER BY DAY(DBIRTH) ASC";
$r1 = $conn->query($sql1);

$sql2 = "SELECT * FROM employees_demas WHERE DHIRE > '$mths' ORDER BY DHIRE DESC";
$r2 = $conn->query($sql2);

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
                    foreach ($r1 as $k1 => $v1) { 
                      if($v1['DRESIGN'] == '0000-00-00' && date("m", strtotime($v1['DBIRTH'])) == date("m")){
                        $day = date("Y-m")."-".date("d", strtotime($v1['DBIRTH']));
                    ?>
                    <a href="profile?emid=<?php echo $v1['CNOEE']; ?>" class="text-dark load">
                      <ol class="list-group">
                        <li class="list-group-item">
                          <div class="media-group" style="display: flex; align-items: center; gap: 5px;">
                            <?php if($v1['CIMAGE'] == null){ ?>
                            <div class="media media-md media-middle media-circle">
                              <?php if($v1['CSEX'] == 'M'){ ?>
                              <img src="assets/images/male.png" alt="">
                              <?php }else if($v1['CSEX'] == 'F'){ ?>
                              <img src="assets/images/female.png" alt="">
                              <?php } ?>
                            </div>
                            <?php }else{ ?>
                            <div class="media media-md media-middle media-circle">
                              <img src="../../hrad/modules/employees/file/<?php echo $v1['CIMAGE']; ?>">
                            </div>
                            <?php } ?>
                            <div class="media-text">
                              <b style="font-size: 13px;"><?php echo $v1['CNAME']; ?></b>
                              <span class="smaller text-danger" style="font-style: italic;">
                                <b>
                                  BIRTHDAY <em class="icon ni ni-caret-right-fill"></em> 
                                  <?php echo strtoupper(date("d F", strtotime($v1['DBIRTH']))); ?>, 
                                  <?php echo strtoupper(date("D", strtotime($day))); ?>
                                </b>
                              </span>
                            </div>
                            <div style="margin-left: auto;">
                              <b><i class="fas fa-gift text-danger"></i></b>
                            </div>
                          </div>
                        </li>
                      </ol>
                    </a>
                    <?php } } ?>

                    <?php foreach ($r2 as $k2 => $v2) { ?>
                    <a href="profile?emid=<?php echo $v2['CNOEE']; ?>" class="text-dark load">
                      <ol class="list-group">
                        <li class="list-group-item">
                          <div class="media-group" style="display: flex; align-items: center; gap: 5px;">
                            <?php if($v2['CIMAGE'] == null){ ?>
                            <div class="media media-md media-middle media-circle">
                              <?php if($v2['CSEX'] == 'M'){ ?>
                              <img src="assets/images/male.png" alt="">
                              <?php }else if($v2['CSEX'] == 'F'){ ?>
                              <img src="assets/images/female.png" alt="">
                              <?php } ?>
                            </div>
                            <?php }else{ ?>
                            <div class="media media-md media-middle media-circle">
                              <img src="../../hrad/modules/employees/file/<?php echo $v2['CIMAGE']; ?>">
                            </div>
                            <?php } ?>
                            <div class="media-text">
                              <b style="font-size: 13px;"><?php echo $v2['CNAME']; ?></b>
                              <span class="smaller text-info" style="font-style: italic;">
                                <b>
                                  JOINED <em class="icon ni ni-caret-right-fill"></em> 
                                  <?php echo strtoupper(date("d M Y", strtotime($v2['DHIRE']))); ?>,
                                  <?php echo strtoupper(date("D", strtotime($v2['DHIRE']))); ?>
                                </b>
                              </span>
                            </div>
                            <div style="margin-left: auto;">
                              <b><i class="fas fa-user-plus text-info"></i></b>
                            </div>
                          </div>
                        </li>
                      </ol>
                    </a>
                    <?php } ?>
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
<?php include('footer.php'); } ?>