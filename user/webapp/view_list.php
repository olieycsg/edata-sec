<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_SESSION['emid'];
$code = $_POST['code'];

$sql = "SELECT * FROM sys_workflow_divisional WHERE CDIVISION = '$code'";
$result = $conn->query($sql);

$asql = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$code'";
$aresult = $conn->query($asql);

if($arow = $aresult->fetch_assoc()){
  $csec = $arow['CNOEE'];
  $jsec = $arow['CJOB'];
}

if($row = $result->fetch_assoc()){

  $cnoee = $row['CNOEE'];
  $cjob = $row['CJOB'];

  $sqla = "SELECT * FROM employees_demas WHERE CNOEE = '$cnoee' AND DRESIGN = '0000-00-00'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$csec' AND DRESIGN = '0000-00-00'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CSUPERIOR = CSUPERVISO AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00'";
  $resultc = $conn->query($sqlc);

  $sqld = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $resultd = $conn->query($sqld);

  if($code != 'HRAD'){
    $sqle = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00'";
    $resulte = $conn->query($sqle);
  }else{
    $sqle = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND DRESIGN = '0000-00-00'";
    $resulte = $conn->query($sqle);
  }

  $sqlf = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00'";
  $resultf = $conn->query($sqlf);

  $sqlg = "SELECT * FROM sys_general_dcmisc WHERE CTYPE = 'DIVSN' AND CCODE = '$code'";
  $resultg = $conn->query($sqlg);

  if($rowg = $resultg->fetch_assoc()){
    $division = $rowg['CDESC'];
  }

}

?>
<style type="text/css">
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
</style>
<div class="list-group" style="margin-bottom: 30px; margin-top: 30px; margin-left: 10px; margin-right: 10px;">
  <span class="badge text-bg-success" style="margin-bottom: 10px; text-align: left;">
    <i class="fas fa-caret-right"></i> DIVISIONAL HEAD
  </span>
  <?php if($rowa = $resulta->fetch_assoc()) { ?>
  <a href="profile?emid=<?php echo $rowa['CNOEE']; ?>" class="text-dark load">
    <ol class="list-group" style="margin-bottom: 5px;">
      <li class="list-group-item">
        <div class="media-group">
          <?php if($rowa['CIMAGE'] == null){ ?>
          <div class="media media-md media-middle media-circle">
            <?php if($rowa['CSEX'] == 'M'){ ?>
            <img src="assets/images/male.png" alt="">
            <?php }else if($rowa['CSEX'] == 'F'){ ?>
            <img src="assets/images/female.png" alt="">
            <?php } ?>
          </div>
          <?php }else{ ?>
          <div class="media media-md media-middle media-circle">
            <img src="../../hrad/modules/employees/file/<?php echo $rowa['CIMAGE']; ?>">
          </div>
          <?php } ?>
          <div class="media-text">
            <b style="font-size: 13px;"><?php echo $rowa['CNAME']; ?></b>
            <span class="small text text-success" style="font-size: 10px; font-style: italic;"><?php echo $rowa['CPOSITION']; ?></span>
          </div>
        </div>
      </li>
    </ol>
  </a>
  <?php } ?>
</div>
<div class="list-group" style="margin-bottom: 30px; margin-top: 10px; margin-left: 10px; margin-right: 10px;">
  <span class="badge text-bg-primary" style="margin-bottom: 10px; text-align: left;">
    <i class="fas fa-caret-right"></i> CONFIDENTIAL SECRETARY
  </span>
  <?php if($rowb = $resultb->fetch_assoc()) { ?>
  <a href="profile?emid=<?php echo $rowb['CNOEE']; ?>" class="text-dark load">
    <ol class="list-group" style="margin-bottom: 5px;">
      <li class="list-group-item">
        <div class="media-group">
          <?php if($rowb['CIMAGE'] == null){ ?>
          <div class="media media-md media-middle media-circle">
            <?php if($rowb['CSEX'] == 'M'){ ?>
            <img src="assets/images/male.png" alt="">
            <?php }else if($rowb['CSEX'] == 'F'){ ?>
            <img src="assets/images/female.png" alt="">
            <?php } ?>
          </div>
          <?php }else{ ?>
          <div class="media media-md media-middle media-circle">
            <img src="../../hrad/modules/employees/file/<?php echo $rowb['CIMAGE']; ?>">
          </div>
          <?php } ?>
          <div class="media-text">
            <b style="font-size: 13px;"><?php echo $rowb['CNAME']; ?></b>
            <span class="small text text-primary" style="font-size: 10px; font-style: italic;"><?php echo $rowb['CPOSITION']; ?></span>
          </div>
        </div>
      </li>
    </ol>
  </a>
  <?php } ?>
</div>

<?php

while($rowc = $resultc->fetch_assoc()) {

  $sql1 = "SELECT * FROM sys_general_dcmisc WHERE CTYPE = 'DEPTM' AND CCODE = '".$rowc['CDEPARTMEN']."'";
  $result1 = $conn->query($sql1);

  if($row1 = $result1->fetch_assoc()){
    $dept = $row1['CDESC'];
  }

?>
<div class="list-group" style="margin-top: 30px; margin-left: 10px; margin-right: 10px;">
  <span class="badge text-bg-info" style="margin-bottom: 10px; text-align: left;">
    <i class="fas fa-caret-right"></i> <?php echo $dept; ?>
  </span>
  <a href="profile?emid=<?php echo $rowc['CNOEE']; ?>" class="text-dark load">
    <ol class="list-group" style="margin-bottom: 5px;">
      <li class="list-group-item">
        <div class="media-group">
          <?php if($rowc['CIMAGE'] == null){ ?>
          <div class="media media-md media-middle media-circle">
            <?php if($rowc['CSEX'] == 'M'){ ?>
            <img src="assets/images/male.png" alt="">
            <?php }else if($rowc['CSEX'] == 'F'){ ?>
            <img src="assets/images/female.png" alt="">
            <?php } ?>
          </div>
          <?php }else{ ?>
          <div class="media media-md media-middle media-circle">
            <img src="../../hrad/modules/employees/file/<?php echo $rowc['CIMAGE']; ?>">
          </div>
          <?php } ?>
          <div class="media-text">
            <b style="font-size: 13px;"><?php echo $rowc['CNAME']; ?></b>
            <span class="small text text-info" style="font-size: 10px; font-style: italic;"><?php echo $rowc['CPOSITION']; ?></span>
          </div>
        </div>
      </li>
    </ol>
  </a>
</div>

<?php 

foreach ($resultd as $keyd => $rowd) {
  if($rowd['CSUPERIOR'] == $rowc['CJOB']) {

?>
<div class="list-group" style="margin-top: 10px; margin-left: 10px; margin-right: 10px;">
  <a href="profile?emid=<?php echo $rowd['CNOEE']; ?>" class="text-dark load">
    <ol class="list-group" style="margin-bottom: 5px;">
      <li class="list-group-item">
        <div class="media-group">
          <?php if($rowd['CIMAGE'] == null){ ?>
          <div class="media media-md media-middle media-circle">
            <?php if($rowd['CSEX'] == 'M'){ ?>
            <img src="assets/images/male.png" alt="">
            <?php }else if($rowd['CSEX'] == 'F'){ ?>
            <img src="assets/images/female.png" alt="">
            <?php } ?>
          </div>
          <?php }else{ ?>
          <div class="media media-md media-middle media-circle">
            <img src="../../hrad/modules/employees/file/<?php echo $rowd['CIMAGE']; ?>">
          </div>
          <?php } ?>
          <div class="media-text">
            <b style="font-size: 13px;"><?php echo $rowd['CNAME']; ?></b>
            <span class="small text" style="font-size: 10px; font-style: italic;"><?php echo $rowd['CPOSITION']; ?></span>
          </div>
        </div>
      </li>
    </ol>
  </a>
</div>
<?php

foreach($resulte as $keye => $rowe) {
  if($rowe['CSUPERIOR'] == $rowd['CJOB']) {

?>
<div class="list-group" style="margin-top: 10px; margin-left: 10px; margin-right: 10px;">
  <a href="profile?emid=<?php echo $rowe['CNOEE']; ?>" class="text-dark load">
    <ol class="list-group" style="margin-bottom: 5px;">
      <li class="list-group-item">
        <div class="media-group">
          <?php if($rowe['CIMAGE'] == null){ ?>
          <div class="media media-md media-middle media-circle">
            <?php if($rowe['CSEX'] == 'M'){ ?>
            <img src="assets/images/male.png" alt="">
            <?php }else if($rowe['CSEX'] == 'F'){ ?>
            <img src="assets/images/female.png" alt="">
            <?php } ?>
          </div>
          <?php }else{ ?>
          <div class="media media-md media-middle media-circle">
            <img src="../../hrad/modules/employees/file/<?php echo $rowe['CIMAGE']; ?>">
          </div>
          <?php } ?>
          <div class="media-text">
            <b style="font-size: 13px;"><?php echo $rowe['CNAME']; ?></b>
            <span class="small text" style="font-size: 10px; font-style: italic;"><?php echo $rowe['CPOSITION']; ?></span>
          </div>
        </div>
      </li>
    </ol>
  </a>
</div>
<?php

foreach($resultf as $keyf => $rowf) {
  if($rowf['CSUPERIOR'] == $rowe['CJOB']) {

?>
<div class="list-group" style="margin-top: 10px; margin-left: 10px; margin-right: 10px;">
  <a href="profile?emid=<?php echo $rowf['CNOEE']; ?>" class="text-dark load">
    <ol class="list-group" style="margin-bottom: 5px;">
      <li class="list-group-item">
        <div class="media-group">
          <?php if($rowf['CIMAGE'] == null){ ?>
          <div class="media media-md media-middle media-circle">
            <?php if($rowf['CSEX'] == 'M'){ ?>
            <img src="assets/images/male.png" alt="">
            <?php }else if($rowf['CSEX'] == 'F'){ ?>
            <img src="assets/images/female.png" alt="">
            <?php } ?>
          </div>
          <?php }else{ ?>
          <div class="media media-md media-middle media-circle">
            <img src="../../hrad/modules/employees/file/<?php echo $rowf['CIMAGE']; ?>">
          </div>
          <?php } ?>
          <div class="media-text">
            <b style="font-size: 13px;"><?php echo $rowf['CNAME']; ?></b>
            <span class="small text" style="font-size: 10px; font-style: italic;"><?php echo $rowf['CPOSITION']; ?></span>
          </div>
        </div>
      </li>
    </ol>
  </a>
</div>
<?php } } ?>
<?php } } ?>
<?php } } ?>
<?php } ?>
<script type="text/javascript">

</script>