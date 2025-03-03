<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];
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
<style>
  .card-main {
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .card-content {
    padding: 20px;
  }

  .card-buttons {
    display: flex;
    background-color: #fff;
    margin-top: auto;
    position: sticky;
    bottom: 0;
    left: 0;

    button {
      flex: 1 1 auto;
      user-select: none;
      background: 0;
      font-size: 13px;
      border: 0;
      padding: 15px 5px;
      cursor: pointer;
      color: #5c5c6d;
      transition: 0.3s;
      font-family: "Jost", sans-serif;
      font-weight: 500;
      outline: 0;
      border-bottom: 3px solid transparent;

      &.is-active,
      &:hover {
        color: #2b2c48;
        border-bottom: 3px solid #8a84ff;
        background: linear-gradient(
          to bottom,
          rgba(127, 199, 231, 0) 0%,
          rgba(207, 204, 255, 0.2) 44%,
          rgba(211, 226, 255, 0.4) 100%
        );
      }
    }
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
  <?php include('navbar.php'); ?>
  <div class="container" style="margin-top: 80px; margin-bottom: 50px;">
    <div class="nk-content-body">
      <div class="card">
        <div class="card-main">
          <div class="card-section is-active">
            <div class="card-content">
              <ul class="list-group list-group-borderless small">
                <li class="list-group-item">
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
              <hr>
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
<?php include('footer.php'); } ?>