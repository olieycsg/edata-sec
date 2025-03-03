<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];

$s1 = "SELECT * FROM sys_workflow_divisional";
$r1 = $conn->query($s1);

$s2 = "SELECT * FROM sys_general_dcmisc";
$r2 = $conn->query($s2);

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

                      foreach ($r2 as $k2 => $v2) {
                        if($v2['CTYPE'] == 'DIVSN' && $v2['CCODE'] == $v1['CDIVISION']){
                          $division = $v2['CDESC'];
                        }
                      }

                    ?>
                    <a href="main_chart?code=<?php echo $v1['CDIVISION']; ?>" class="text-dark load">
                      <ol class="list-group">
                        <li class="list-group-item">
                          <div class="media-group" style="display: flex; align-items: center; gap: 5px;">
                            <div class="media-text">
                              <b style="font-size: 13px;"><?php echo $division; ?></b>
                              <span class="smaller text-info" style="font-style: italic;">
                                <b>TAP FOR MORE INFORMATION</b>
                              </span>
                            </div>
                            <div style="margin-left: auto;">
                              <b><i class="fas fa-hand-pointer text-info"></i></b>
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
<?php include('footer.php'); ?>