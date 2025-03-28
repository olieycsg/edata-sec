<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];

$s3 = "SELECT * FROM sys_workflow_divisional_access WHERE CNOEE = '$emid' OR SNOEE = '$emid'";
$r3 = $conn->query($s3);

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
</style>
<div id="content">
  <?php include('navbar.php'); ?>
  <div class="container" style="margin-top: 80px; margin-bottom: 50px;">
    <div class="nk-content-body">
      <!-- <div class="row">
        <div class="col-12">
          <b onclick="location.reload()">Refresh Data</b>
        </div>
      </div>
      <hr> -->
      <div class="row" style="text-align: center;" style="margin-top: 50px;">
        <?php if ($r3->num_rows > 0 || (isset($_SESSION['emid']) && $_SESSION['emid'] == '2522-186')) { ?>
        <div class="col-3">
          <div class="d-flex flex-column align-items-center position-relative load">
            <a href="over_leave" class="btn btn-light position-relative new-bg" 
               style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-calendar-check" style="font-size: 23px;"></i>
            </a>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 8px; margin-left: -5px;">New</span>
            <small style="margin-top: 5px;"><b>Leave<br>(Secretary)</b></small>
          </div>
        </div>
        <?php } ?>
        <?php if ($_SESSION['emid'] == '2522-186') { ?>
        <div class="col-3">
          <div class="d-flex flex-column align-items-center position-relative load">
            <a href="attendance" class="btn btn-light position-relative new-bg" 
               style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-calendar-check" style="font-size: 23px;"></i>
            </a>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 8px; margin-left: -5px;">New</span>
            <small style="margin-top: 5px;"><b>Attendance<br>(Report)</b></small>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php if ($_SESSION['emid'] == '2522-186') { ?>
      <hr>
      <div class="row" style="text-align: center;">
        <div class="col-3">
          <div class="d-flex flex-column align-items-center position-relative load">
            <a href="https://secims.com/voting/vote_access?emid=<?php echo $_SESSION['emid']; ?>" class="btn btn-light position-relative new-bg" 
               style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-key" style="font-size: 23px;"></i>
            </a>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 8px; margin-left: -5px;">New</span>
            <small style="margin-top: 5px;"><b>KSR<br>(Settings)</b></small>
          </div>
        </div>
        <div class="col-3">
          <div class="d-flex flex-column align-items-center position-relative load">
            <a href="https://secims.com/voting/vote_position?emid=<?php echo $_SESSION['emid']; ?>" class="btn btn-light position-relative new-bg" 
               style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-user-lock" style="font-size: 23px;"></i>
            </a>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 8px; margin-left: -5px;">New</span>
            <small style="margin-top: 5px;"><b>KSR<br>(Position)</b></small>
          </div>
        </div>
      </div>
      <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php include('footer.php'); ?>