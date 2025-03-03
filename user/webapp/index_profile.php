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
    if($row1['CTYPE'] == 'JOB' && $row1['CCODE'] == $row['CJOB']){
      $job = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'GRADE' && $row1['CCODE'] == $row['CGRADE']){
      $grade = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'SUPER' && $row1['CCODE'] == $row['CSUPERIOR']){
      $superior = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'BRANC' && $row1['CCODE'] == $row['CBRANCH']){
      $branch = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'RELAT' && $row1['CCODE'] == $row['CKINRELATN']){
      $relation = $row1['CDESC'];
    }
  }

  $marital = ['M' => 'MARRIED', 'S' => 'SINGLE', 'D' => 'DIVORCED', 'W' => 'WIDOW/WIDOWER'];
  $emptype = array('C' => 'CONTRACT', 'P' => 'PERMANENT', 'T' => 'TEMPORARY');

  foreach ($marital as $maritalkey => $maritalvalue){
    if($row['CSTMARTL'] == $maritalkey){
      $marriage = $maritalvalue;
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
    <div class="card">
      <div class="card-header bg-info text-white">
        <b>Personal Details</b>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-borderless small">
          <li class="list-group-item">
            <span class="title fw-medium w-40 d-inline-block">Employee Name</span>
            <br>
            <span class="text"><?php echo $row['CNAME']; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Employee ID</span>
            <span class="text">: <?php echo $row['CNOEE']; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Salutation</span>
            <span class="text">: <?php echo $row['CTITLE']; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Nationality</span>
            <span class="text">: <?php echo ($row['CCOUNTRY'] == 'MAL') ? 'MALAYSIAN' : '-' ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">National ID</span>
            <span class="text">: <?php echo ($row['CNOICNEW'] != '') ? $row['CNOICNEW'] : '-' ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">IC Color</span>
            <span class="text">: <?php echo ($row['CCOLORIC'] != '') ? $row['CCOLORIC'] : '-' ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Birth Date</span>
            <span class="text">: <?php echo strtoupper(date("d M Y", strtotime($row['DBIRTH']))); ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Gender</span>
            <span class="text">: <?php echo ($row['CSEX'] == "M") ? 'MALE' : 'FEMALE'; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Religion</span>
            <span class="text">: <?php echo strtoupper($religion); ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Race</span>
            <span class="text">: <?php echo strtoupper($race); ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Marital</span>
            <span class="text">: <?php echo strtoupper($marriage); ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Blood</span>
            <span class="text">: <?php echo strtoupper($row['CBLOOD']); ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Height</span>
            <span class="text">: <?php echo ($row['NHEIGHT'] != '') ? $row['NHEIGHT'] : '-'; ?> CM</span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Weight</span>
            <span class="text">: <?php echo ($row['NWEIGHT'] != '') ? $row['NWEIGHT'] : '-'; ?> KG</span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Post Code</span>
            <span class="text">: <?php echo ($row['CCDPOST'] != '') ? $row['CCDPOST'] : '-'; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Phone</span>
            <span class="text">: <?php echo ($row['CNOPHONEH'] != '') ? $row['CNOPHONEH'] : '-'; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Address</span>
            <br>
            <span class="text"><?php echo ($row['CADDRS1'] != '') ? strtoupper($row['CADDRS1']).' '.strtoupper($row['CADDRS2']).' '.strtoupper($row['CADDRS3']) : '-'; ?>
            </span>
          </li>
        </ul>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="card-header bg-info text-white">
        <b>In Service</b>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-borderless small">
          <li class="list-group-item">
            <span class="title fw-medium w-40 d-inline-block">Hire Date</span>
            <span class="text">: <?php echo strtoupper(date("d M Y", strtotime($row['DHIRE']))); ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Confirm Date</span>
            <span class="text">: <?php echo strtoupper(date("d M Y", strtotime($row['DCONFIRM']))); ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Employee Type</span>
            <span class="text">: <?php echo $emptype[$row['CTYPEMPL']] ?? '-'; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Grade</span>
            <span class="text">: <?php echo $grade; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Company</span>
            <span class="text">: <?php echo $row['CCOMPANY']; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Branch</span>
            <span class="text">: <?php echo $branch; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Employee Job</span>
            <br>
            <span class="text"><?php echo $job; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Position</span>
            <br>
            <span class="text"><?php echo $row['CPOSITION']; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Superior</span>
            <br>
            <span class="text"><?php echo $superior; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Division</span>
            <br>
            <span class="text"><?php echo $divi; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Department</span>
            <br>
            <span class="text"><?php echo $dept; ?></span>
          </li>
        </ul>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="card-header bg-info text-white">
        <b>Payroll</b>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-borderless small">
          <li class="list-group-item">
            <span class="title fw-medium w-40 d-inline-block">#EPF</span>
            <span class="text">: <?php echo ($row['CNOEPF'] != '') ? $row['CNOEPF'] : '-' ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">#Socso</span>
            <span class="text">: <?php echo ($row['CNOSOCSO'] != '') ? $row['CNOSOCSO'] : '-' ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">#Tax</span>
            <span class="text">: <?php echo ($row['CNOTAX'] != '') ? $row['CNOTAX'] : '-' ?></span>
          </li>
        </ul>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="card-header bg-info text-white">
        <b>Next of Kin</b>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-borderless small">
          <li class="list-group-item">
            <span class="title fw-medium w-40 d-inline-block">Name</span>
            <br>
            <span class="text"><?php echo $row['CKINNAME']; ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Relation</span>
            <span class="text">: <?php echo $relation; ?></span>
            <br>
            <span class="title fw-medium w-40 d-inline-block">Contact</span>
            <span class="text">: <?php echo ($row['CKINPHONEH'] != '') ? $row['CKINPHONEH'] : '-' ?></span>
            <hr>
            <span class="title fw-medium w-40 d-inline-block">Address</span>
            <br>
            <span class="text"><?php echo ($row['CKINADDRS1'] != '') ? strtoupper($row['CKINADDRS1']).' '.strtoupper($row['CKINADDRS2']).' '.strtoupper($row['CKINADDRS3']) : '-'; ?>
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); } ?>