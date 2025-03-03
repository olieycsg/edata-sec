<?php

$emid = $_SESSION['emid'];

$s3 = "SELECT * FROM sys_workflow_divisional_access WHERE CNOEE = '$emid' OR SNOEE = '$emid'";
$r3 = $conn->query($s3);

?>
<div class="nk-header nk-header-fixed">
  <div class="container-fluid">
    <div class="nk-header-wrap">
      <div class="nk-header-logo ms-n1">
        <div class="nk-sidebar-toggle">
          <a href="index" style="display: inline-block; margin-right: 10px; text-align: center;" aria-label="Home">
            <h1><i class="fas fa-user-tie load text-danger"></i></h1>
            <b class="text-dark" style="font-size: 13px;">User</b>
          </a>
          <a href="info" style="display: inline-block; margin-right: 10px; text-align: center;" aria-label="Info">
            <h1><i class="fas fa-circle-info load text-info"></i></h1>
            <b class="text-dark" style="font-size: 13px;">Info</b>
          </a>
          <a href="chart" style="display: inline-block; margin-right: 10px; text-align: center;" aria-label="Chart">
            <h1><i class="fas fa-sitemap load text-info"></i></h1>
            <b class="text-dark" style="font-size: 13px;">Chart</b>
          </a>
        </div>
      </div>
      <div class="nk-header-tools">
        <ul class="nk-quick-nav ms-2">
          <li class="dropdown">
            <a href="#" data-bs-toggle="dropdown">
              <div class="d-sm-none">
                <?php if ($_SESSION['emid'] == '2522-186') { ?>
                <a href="indexv1" style="display: inline-block; margin-right: 10px; text-align: center;">
                  <h1><i class="fas fa-vote-yea text-info load"></i></h1>
                  <b class="text-dark" style="font-size: 13px;">V1</b>
                </a>
                <a href="https://secims.com/voting/vote_apps?emid=<?php echo $_SESSION['emid']; ?>" style="display: inline-block; margin-right: 10px; text-align: center;" aria-label="Vote" class="turnon">
                  <h1><i class="fas fa-vote-yea text-info"></i></h1>
                  <b class="text-dark" style="font-size: 13px;">KSR</b>
                </a>
                <?php } ?>
                <?php if ($r3->num_rows > 0 || (isset($_SESSION['emid']) && $_SESSION['emid'] == '2522-186')) { ?>
                <a href="over_leave" style="display: inline-block; margin-right: 10px; text-align: center;" aria-label="More">
                  <h1><i class="far fa-calendar-check load text-info"></i></h1>
                  <b class="text-dark" style="font-size: 13px;">More</b>
                </a>
                <?php } ?>
                <a href="leave" style="display: inline-block; text-align: center;" aria-label="Leave">
                  <h1><i class="fas fa-calendar-check load text-success"></i></h1>
                  <b class="text-dark" style="font-size: 13px;">Leave</b>
                </a>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>