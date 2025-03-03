<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_GET['emid'];
$year = date("Y");

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid' AND DRESIGN = '0000-00-00'";
$result = $conn->query($sql);

$sqla = "SELECT * FROM employees_demas WHERE CNOEE != '$emid' AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$resulta = $conn->query($sqla);

if($row = $result->fetch_assoc()){

  $sql1 = "SELECT * FROM sys_general_dcmisc";
  $result1 = $conn->query($sql1);

  while($row1 = $result1->fetch_assoc()){
    if($row1['CTYPE'] == 'DIVSN' && $row1['CCODE'] == $row['CDIVISION']){
      $divi = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'DEPTM' && $row1['CCODE'] == $row['CDEPARTMEN']){
      $dept = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'RELIG' && $row1['CCODE'] == $row['CRELIGION']){
      $religion = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'RACE' && $row1['CCODE'] == $row['CRACE']){
      $race = $row1['CDESC'];
    }
  }

  $marital = ['M' => 'MARRIED', 'S' => 'SINGLE', 'D' => 'DIVORCED', 'W' => 'WIDOW/WIDOWER'];

  foreach ($marital as $maritalkey => $maritalvalue){
    if($row['CSTMARTL'] == $maritalkey){
      $marriage = $maritalvalue;
    }
  }

  $sql2 = "SELECT * FROM employee_inservice WHERE CNOEE = '$emid' ORDER BY CSEQUENCE ASC";
  $result2 = $conn->query($sql2);

  $rectype = array('A' => 'ACTING', 'C' => 'CONFIRMATION', 'D' => 'DEMOTION', 'E' => 'REINSTATEMENT', 'H' => 'HIRE', 'I' => 'INCREMENT', 'N' => 'PROMOTION CONFIRMATION', 'O' => 'PERMANENT APPOINTMENT', 'P' => 'PROMOTION', 'J' => 'SALARY ADJUSTMENTS', 'Q' => 'SUSPENSION', 'R' => 'REVISION', 'S' => 'REDESIGNATION', 'T' => 'TRANSFER', 'X' => 'CEASE');

  foreach ($resulta as $keya => $vala) {
    if($row['CSUPERIOR'] == $vala['CJOB']){
      $superior = $vala['CNAME'];
    }
    if($row['CSUPERVISO'] == $vala['CJOB']){
      $superviso = $vala['CNAME'];
    }
  }

?>
<style type="text/css">
  .box {
    width: auto;
    height: fit-content;
    border-radius: 20px;
    padding: 20px;
    text-align: center;
    background: #ededed;
    margin-left: 15px;
    margin-right: 15px;
    box-shadow: 0 0 0 8px rgba(255, 255, 255, 0.2);

  }

  .image img {
    height: auto;
    width: 120px;
    border-radius: 50%;

    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 5px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.8);
  }

  .card{
    width: auto;
    height: fit-content;
    border-radius: 20px;
    margin-left: 15px;
    margin-right: 15px;
  }

  .card{
    box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
  }

  hr{
    border: 0;
    height: 1px;
    background-image: linear-gradient(to right, #00b9ff, #59d941, #f0f0f0);
  }
</style>
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
                  <div class="box card" style="margin-top: 10px;">
                    <div class="content">
                      <div class="image">
                        <?php 
                        if($row['CIMAGE'] == ''){ 
                          if($row['CSEX'] == 'M'){ 
                        ?>
                          <img src="assets/images/male.png" class="card-avatar" data-enlargable>
                          <?php }else if($row['CSEX'] == 'F'){ ?>
                          <img src="assets/images/female.png" class="card-avatar" data-enlargable>
                        <?php 
                          } 
                        }else{ 
                        ?>
                          <img src="../../hrad/modules/employees/file/<?php echo $row['CIMAGE']; ?>" class="card-avatar" data-enlargable>
                        <?php } ?>
                      </div>
                      <br>
                      <h3><?php echo $row['CNAME']; ?></h3>
                      <span class="small text text-info" style="font-size: 12px; font-style: italic;"><?php echo $row['CPOSITION']; ?></span>
                    </div>
                  </div>
                  <div class="card" style="margin-top: 10px; margin-bottom: 10px;">
                    <div class="card-body">
                      <ul class="list-group list-group-borderless small">
                        <li class="list-group-item">
                          <div class="media-text">
                            <a class="title">
                              <?php echo strtoupper(date("d M Y", strtotime($row['DHIRE']))); ?>, 
                              <?php echo strtoupper(date("l", strtotime($row['DHIRE']))); ?>
                            </a>
                            <b class="text smaller text-info" style="font-style: italic;">Joined</b>
                          </div>
                          <div class="media-text">
                            <a class="title">
                              <?php

                              $hireDate = new DateTime($row['DHIRE']);
                              $currentDate = new DateTime();

                              $interval = $hireDate->diff($currentDate);

                              $years = $interval->y;
                              $months = $interval->m;
                              $days = $interval->d;

                              echo "$years YEARS, $months MONTHS, $days DAYS";

                              ?>
                            </a>
                            <b class="text smaller text-info" style="font-style: italic;">In Service</b>
                          </div>
                          <div class="media-text">
                            <a class="title">
                              <?php echo strtoupper(date("d M Y", strtotime($row['DBIRTH']))); ?>, 
                              <?php echo strtoupper(date("l", strtotime($row['DBIRTH']))); ?>
                            </a>
                            <b class="text smaller text-danger" style="font-style: italic;">Date of Birth</b>
                          </div>
                          <hr>
                          <div class="media-text">
                            <a class="title"><?php echo $divi; ?></a>
                            <b class="text smaller text-info" style="font-style: italic;">Division</b>
                          </div>
                          <div class="media-text">
                            <a class="title"><?php echo $dept; ?></a>
                            <b class="text smaller text-info" style="font-style: italic;">Department</b>
                          </div>
                          <?php if($row['CJOB'] != 'CEO'){ ?>
                            <hr>
                            <?php if($superior != $superviso){ ?>
                            <div class="media-text">
                              <a class="title"><?php echo $superior; ?></a>
                              <b class="text smaller text-warning" style="font-style: italic;">Leave Recommender</b>
                            </div>
                            <?php } ?>
                            <div class="media-text">
                              <a class="title"><?php echo $superviso; ?></a>
                              <b class="text smaller text-success" style="font-style: italic;">Leave Approver</b>
                            </div>
                          <?php } ?>
                        </li>
                      </ul>
                      <br>
                      <?php 
                      foreach ($resulta as $keya => $vala) { 
                        if($row['CDIVISION'] == $vala['CDIVISION']){ 
                      ?>
                      <a href="profile?emid=<?php echo $vala['CNOEE']; ?>" class="text-dark load">
                        <ol class="list-group" style="margin-bottom: 5px;">
                          <li class="list-group-item">
                            <div class="media-group">
                              <?php if($vala['CIMAGE'] == null){ ?>
                              <div class="media media-md media-middle media-circle">
                                <?php if($vala['CSEX'] == 'M'){ ?>
                                <img src="assets/images/male.png" alt="">
                                <?php }else if($vala['CSEX'] == 'F'){ ?>
                                <img src="assets/images/female.png" alt="">
                                <?php } ?>
                              </div>
                              <?php }else{ ?>
                              <div class="media media-md media-middle media-circle">
                                <img src="../../hrad/modules/employees/file/<?php echo $vala['CIMAGE']; ?>">
                              </div>
                              <?php } ?>
                              <div class="media-text">
                                <b style="font-size: 13px;"><?php echo $vala['CNAME']; ?></b>
                                <span class="small text text-info" style="font-size: 10px; font-style: italic;"><?php echo $vala['CPOSITION']; ?></span>
                              </div>
                            </div>
                          </li>
                        </ol>
                      </a>
                      <?php } } ?>
                    </div>
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
$('img[data-enlargable]').addClass('img-enlargable').click(function(){
  var src = $(this).attr('src');
  $('<div>').css({
    background: 'RGBA(255, 255, 255, 1) url('+src+') no-repeat center',
    backgroundSize: 'contain',
    width:'100%', height:'100%',
    position:'fixed',
    zIndex:'10000',
    top:'0', left:'0',
    cursor: 'zoom-out'
  }).click(function(){
    $(this).remove();
  }).appendTo('body');
});
</script>

<?php include('footer.php'); } ?>