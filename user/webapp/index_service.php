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

  $sql2 = "SELECT * FROM employee_inservice WHERE CNOEE = '$emid' ORDER BY CSEQUENCE DESC";
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

  .card-timeline {
    margin-top: 30px;
    position: relative;
    &:after {
      background: linear-gradient(
        to top,
        rgba(134, 214, 243, 0) 0%,
        rgba(81, 106, 204, 1) 100%
      );
      content: "";
      left: 42px;
      width: 2px;
      top: 0;
      height: 100%;
      position: absolute;
      content: "";
    }
  }

  .card-item {
    position: relative;
    padding-left: 60px;
    padding-right: 20px;
    padding-bottom: 30px;
    z-index: 1;
    &:last-child {
      padding-bottom: 5px;
    }

    &:after {
      content: attr(data-year);
      width: 10px;
      position: absolute;
      top: 0;
      left: 37px;
      width: 8px;
      height: 8px;
      line-height: 0.6;
      border: 2px solid #fff;
      font-size: 11px;
      text-indent: -35px;
      border-radius: 50%;
      color: rgba(#868686, 0.7);
      background: linear-gradient(
        to bottom,
        lighten(#516acc, 20%) 0%,
        #516acc 100%
      );
    }
  }

  .card-item-title {
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 5px;
  }

  .card-item-desc {
    font-size: 13px;
    color: #6f6f7b;
    line-height: 1.5;
    font-family: "DM Sans", sans-serif;
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
              <div class="card-subtitle"><b>IN SERVICE RECORD</b></div>
                <div class="card-timeline">
                  <?php while($row2 = $result2->fetch_assoc()){ ?>
                  <div class="card-item" data-year="<?php echo strtoupper(date("Y", strtotime($row2['DREC']))); ?>">
                    <div class="card-item-title">
                      <?php 
                      foreach ($rectype as $recvalue => $reclabel) {
                        if($recvalue == $row2['CTYPREC']){
                          echo $reclabel;
                        }
                      }
                      foreach ($result1 as $key1 => $row1) {
                        if($row1['CTYPE'] == 'DIVSN' && $row1['CCODE'] == $row2['CDIVISION']){ 
                          $hdivi = $row1['CDESC'];
                        }
                      }
                      ?>
                    </div>
                    <div class="card-item-desc">
                      <b style="font-style: italic;" class="text-info">
                        <?php echo strtoupper(date("d M Y", strtotime($row2['DREC']))); ?>
                      </b>
                      <br>
                      <span style="font-style: italic; font-size: 10px;">
                        <?php echo ($row2['CPOSITION'] != '') ? $row2['CPOSITION'] : '-'; ?>
                        <br>
                        <?php echo ($hdivi != '') ? $hdivi : '-'; ?>
                      </span>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); } ?>