<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];
$year = date("Y");

$sql = "SELECT * FROM employee_dfamily WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

$job = ['C' => 'CHILD', 'D' => 'DECEASED', 'N' => 'NOT WORKING', 'S' => 'SCHOOLING', 'H' => 'HIGHER EDUCATION', 'W' => 'WORKING'];
$sex = ['M' => 'MALE', 'F' => 'FEMALE'];

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
    <?php while($row = $result->fetch_assoc()){ ?>
    <div class="card" style="margin-bottom: 15px;">
      <div class="card-body">
        <div class="row">
          <div class="col-12 text-success">
            <b><?php echo $row['CNAME']; ?></b>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-12">
            <ul class="list-group list-group-borderless small">
              <li class="list-group-item">
                <span class="title fw-medium w-40 d-inline-block">Relation</span>
                <span class="text">
                  <?php
                  foreach($result1 as $row1){ 
                    if($row1['CTYPE'] == 'RELAT' && $row1['CCODE'] == $row['CRELATION']){ 
                      echo ": ".$row1['CDESC'];
                    }
                  }
                  ?>
                </span>
                <br>
                <span class="title fw-medium w-40 d-inline-block">Gender</span>
                <span class="text">
                  <?php
                  if (isset($row['CSEX']) && array_key_exists($row['CSEX'], $sex)) {
                    echo ": ".$sex[$row['CSEX']];
                  }
                  ?>
                </span>
                <br>
                <span class="title fw-medium w-40 d-inline-block">Status</span>
                <span class="text">
                  <?php
                  if (isset($row['CSTATUS']) && array_key_exists($row['CSTATUS'], $job)) {
                    echo ": ".$job[$row['CSTATUS']];
                  }
                  ?>
                </span>
                <br>
                <span class="title fw-medium w-40 d-inline-block">Birth Date</span>
                <span class="text">
                  : <?php echo strtoupper(date("d M Y", strtotime($row['DBIRTH']))); ?>
                </span>
                <br>
                <span class="title fw-medium w-40 d-inline-block">Birth Certificate</span>
                <span class="text">
                  : <?php echo ($row['CNOBIRTHCE'] != '') ? $row['CNOBIRTHCE'] : '-' ?>
                </span>
                <br>
                <span class="title fw-medium w-40 d-inline-block">National ID</span>
                <span class="text">
                  : <?php echo ($row['CNOIC'] != '') ? $row['CNOIC'] : '-' ?>
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<?php include('footer.php'); ?>