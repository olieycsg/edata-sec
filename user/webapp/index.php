<?php 

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_SESSION['emid'];
$year = date("Y");

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid' AND DRESIGN = '0000-00-00'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

  $ksr = $row['KSR'];
  $head = $row['CJOB'];
  $divi = $row['CDIVISION'];

  $sqla = "SELECT * FROM sys_workflow_divisional WHERE CJOB = '$head'";
  $resulta = $conn->query($sqla);

  if($resulta->num_rows > 0) {
    $chead = 1;
    if($rowa = $resulta->fetch_assoc()){
      $sql2 = "SELECT COUNT(eleave.ID) AS cnt FROM employees_demas LEFT JOIN eleave ON employees_demas.CNOEE = eleave.CNOEE WHERE employees_demas.DRESIGN = '0000-00-00' AND employees_demas.CDIVISION = '$divi' AND (eleave.MNOTES = 'recommended' OR (eleave.MNOTES = 'pending' AND employees_demas.CSUPERIOR = employees_demas.CSUPERVISO))";
      $result2 = $conn->query($sql2);
      if ($row2 = $result2->fetch_assoc()) {
        $cnt = $row2['cnt'];
        $status = 1;
        $shows = 1;
      }
    }
  }else{
    $chead = 0;
    $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$emid' AND DRESIGN = '0000-00-00' AND CJOB IN (SELECT CSUPERIOR FROM employees_demas)";
    $result1 = $conn->query($sql1);

    if($row1 = $result1->fetch_assoc()){
      $cjob = $row1['CJOB'];

      $sql2 = "SELECT COUNT(eleave.ID) AS id FROM employees_demas LEFT JOIN eleave ON employees_demas.CNOEE = eleave.CNOEE AND eleave.MNOTES = 'pending' WHERE employees_demas.DRESIGN = '0000-00-00' AND employees_demas.CSUPERIOR = '$cjob'";
      $result2 = $conn->query($sql2);
      if($row2 = $result2->fetch_assoc()){
        $cnt = $row2['cid'];
        $status = 1;
        $shows = 1;
      }
    }
  }

  $sql3 = "SELECT COUNT(eleave.ID) AS cid FROM employees_demas LEFT JOIN eleave ON employees_demas.CNOEE = eleave.CNOEE AND eleave.MNOTES = 'pending' WHERE employees_demas.DRESIGN = '0000-00-00' AND employees_demas.CSUPERVISO = 'CEO'";
  $result3 = $conn->query($sql3);
  if($row3 = $result3->fetch_assoc()){
    $ccnt = $row3['cid'];
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>i-SEC Apps</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="css/mdb.min.css">
    <style type="text/css">
      #loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
      }

      .loader {
        border: 4px solid #00007F;
        border-top: 4px solid #f3f3f3;
        border-radius: 50%;
        width: 40px;
        height:40px;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      #content {
        display: none;
      }

      :root {
        --black: #000000;
        --white: #ffffff;
        --light-gray: #e4e4e4;
        --dark-gray: #414545;
        --pink: #e8308c;
        --blue: #16a9c7;
        --light-blue: #48DFE4;
        --dark-blue: #3080e8;
        --orange: #e86830;
        --green: #30e849;
        --body-bg-color: #e7eefa;
      }

      html {
        font-family: 'Montserrat', sans-serif;
        touch-action: manipulation;
      }

      .header {
        padding: 1em;
        width: 100%;
        color: var(--white);
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
      }

      .header__wrapper {
        width: 90%;
        margin: auto;
      }

      .header-icons {
        display: flex;
        justify-content: space-between;
        font-size: 1.6em;
      }

      .header-icons .fa:hover {
        color: rgba(255, 255, 255, 0.5);
        cursor: pointer;
      }

      .header-item__img {
        text-align: center;
        margin-top: 1.4em;
      }

      .header-item__img img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: var(--white);
      }

      .header-item__img h2,
      .header-item__img h3 {
        text-align: center;
      }

      .header-item__img h3 {
        font-weight: 200;
      }

      body {
        background-color: var(--body-bg-color);
      }

      .main__wrapper {
        max-width: 1200px;
        width: 90%;
        margin: auto;
        display: flex;
        flex-direction: column;
      }

      .icons {
        background: var(--white);
        margin: 1em 0;
        border-radius: 0.4em;
        display: flex;
        justify-content: space-around;
        padding: 1.6em;
        box-shadow: -1px 5px 18px -7px rgba(0, 0, 0, 0.75);
      }

      .icons .fa {
        font-size: 2em;
      }

      .icons .fa:hover {
        color: var(--dark-gray);
        opacity: 0.4;
        cursor: pointer;
      }

      .main__panel {
        display: flex;
        flex-flow: row wrap;
        align-items: center;
        justify-content: space-around;
        background: var(--white);
        margin: 1em 0;
        border-radius: 0.4em;
        box-shadow: -1px 5px 18px -7px rgba(0, 0, 0, 0.75);
      }

      .main__panel--img {
        flex-basis: 33%;
        padding: 1.6em;
      }

      .main__panel--img img {
        width: 100%;
        height: auto;
        opacity: 0.7;
        transition: all 0.3s ease-in;
      }

      .main__panel--img img:hover {
        transform: scale(1.1);
        opacity: 1;
        cursor: pointer;
      }

      .main__panel--footer {
        flex-basis: 100%;
        background-color: var(--light-blue);
        border-radius: 0 0 0.4em 0.4em;
        padding: 1.2em;
        text-align: center;
        font-weight: bold;
        font-size: 18px;
        color: var(--blue);
      }

      .footer__menu {
        display: flex;
        justify-content: space-around;
        align-items: center;
        position: fixed;
        bottom: 0;
        width: 100%;
        background: var(--white);
        padding: 0.7em 0;
        box-shadow: -1px -2px 18px -7px rgba(0, 0, 0, 0.75);
      }

      .footer__menu .fa {
        font-size: 2em;
        color: rgba(0, 0, 0, 0.7);
        transition: color 0.3s ease-in-out;
      }

      .footer__menu .fa:hover {
        color: var(--light-blue);
      }

      @media screen and (max-width: 540px) {
        .header-icons {
          font-size: 1.2em;
        }

        .main__wrapper {
          padding: 0 1em;
        }

        .icons .fa {
          font-size: 1.5em;
        }

        .main__panel--img {
          flex-basis: 100%;
        }

        .footer__menu .fa {
          font-size: 1.5em;
        }
      }

      .footer__menu {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px;
        background: rgba(255, 255, 255, 0.8);
      }

      .new-bg{
        background: rgba(255, 255, 255, 0.8);
      }

      .footer__item {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
      }

      .footer__item b {
        margin-top: 5px;
        font-size: 12px;
      }

      .card-body{
        padding: 10px 1px 10px 1px!important;
      }

      .bg {
        background: url(img/main.jpg) no-repeat;
        background-size: cover;
        height: 100%;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: -3;
      }
      
      .bg:before {
        content: "";
        width: 100%;
        height: 100%;
        background: #000;
        position: fixed;
        z-index: -1;
        top: 0;
        left: 0;
        opacity: 0.3;
      }

      @keyframes sf-fly-by-1 {
        from {
          transform: translateZ(-600px);
          opacity: 0.5;
        }
        to {
          transform: translateZ(0);
          opacity: 0.5;
        }
      }

      @keyframes sf-fly-by-2 {
        from {
          transform: translateZ(-1200px);
          opacity: 0.5;
        }
        to {
          transform: translateZ(-600px);
          opacity: 0.5;
        }
      }

      @keyframes sf-fly-by-3 {
        from {
          transform: translateZ(-1800px);
          opacity: 0.5;
        }
        to {
          transform: translateZ(-1200px);
          opacity: 0.5;
        }
      }

      .star-field {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        perspective: 600px;
        -webkit-perspective: 600px;
        z-index: -1;
      }

      .star-field .layer {
        box-shadow: -411px -476px #cccccc, 777px -407px #d4d4d4, -387px -477px #fcfcfc, -91px -235px #d4d4d4, 491px -460px #f7f7f7, 892px -128px #f7f7f7, 758px -277px #ededed, 596px 378px #cccccc, 647px 423px whitesmoke, 183px 389px #c7c7c7,
            524px -237px #f0f0f0, 679px -535px #e3e3e3, 158px 399px #ededed, 157px 249px #ededed, 81px -450px #ebebeb, 719px -360px #c2c2c2, -499px 473px #e8e8e8, -158px -349px #d4d4d4, 870px -134px #cfcfcf, 446px 404px #c2c2c2,
            440px 490px #d4d4d4, 414px 507px #e6e6e6, -12px 246px #fcfcfc, -384px 369px #e3e3e3, 641px -413px #fcfcfc, 822px 516px #dbdbdb, 449px 132px #c2c2c2, 727px 146px #f7f7f7, -315px -488px #e6e6e6, 952px -70px #e3e3e3,
            -869px -29px #dbdbdb, 502px 80px #dedede, 764px 342px #e0e0e0, -150px -380px #dbdbdb, 654px -426px #e3e3e3, -325px -263px #c2c2c2, 755px -447px #c7c7c7, 729px -177px #c2c2c2, -682px -391px #e6e6e6, 554px -176px #ededed,
            -85px -428px #d9d9d9, 714px 55px #e8e8e8, 359px -285px #cfcfcf, -362px -508px #dedede, 468px -265px #fcfcfc, 74px -500px #c7c7c7, -514px 383px #dbdbdb, 730px -92px #cfcfcf, -112px 287px #c9c9c9, -853px 79px #d6d6d6,
            828px 475px #d6d6d6, -681px 13px #fafafa, -176px 209px #f0f0f0, 758px 457px #fafafa, -383px -454px #ededed, 813px 179px #d1d1d1, 608px 98px whitesmoke, -860px -65px #c4c4c4, -572px 272px #f7f7f7, 459px 533px #fcfcfc,
            624px -481px #e6e6e6, 790px 477px #dedede, 731px -403px #ededed, 70px -534px #cccccc, -23px 510px #cfcfcf, -652px -237px whitesmoke, -690px 367px #d1d1d1, 810px 536px #d1d1d1, 774px 293px #c9c9c9, -362px 97px #c2c2c2,
            563px 47px #dedede, 313px 475px #e0e0e0, 839px -491px #e3e3e3, -217px 377px #d4d4d4, -581px 239px #c2c2c2, -857px 72px #cccccc, -23px 340px #dedede, -837px 246px white, 170px -502px #cfcfcf, 822px -443px #e0e0e0, 795px 497px #e0e0e0,
            -814px -337px #cfcfcf, 206px -339px #f2f2f2, -779px 108px #e6e6e6, 808px 2px #d4d4d4, 665px 41px #d4d4d4, -564px 64px #cccccc, -380px 74px #cfcfcf, -369px -60px #f7f7f7, 47px -495px #e3e3e3, -383px 368px #f7f7f7, 419px 288px #d1d1d1,
            -598px -50px #c2c2c2, -833px 187px #c4c4c4, 378px 325px whitesmoke, -703px 375px #d6d6d6, 392px 520px #d9d9d9, -492px -60px #c4c4c4, 759px 288px #ebebeb, 98px -412px #c4c4c4, -911px -277px #c9c9c9;
        transform-style: preserve-3d;
        position: absolute;
        top: 50%;
        left: 50%;
        height: 4px;
        width: 4px;
        border-radius: 2px;
      }

      .star-field .layer:nth-child(1) {
        animation: sf-fly-by-1 5s linear infinite;
      }

      .star-field .layer:nth-child(2) {
        animation: sf-fly-by-2 5s linear infinite;
      }

      .star-field .layer:nth-child(3) {
        animation: sf-fly-by-3 5s linear infinite;
      }
    </style>
  </head>
  <div id="loader-overlay">
    <div class="loader"></div>
  </div>
  <body>
    <div id="content">
      <div class="bg"></div>
      <div class="star-field">
      <div class="layer"></div>
      <div class="layer"></div>
      <div class="layer"></div>
      <header class="header">
        <div class="header__wrapper">
          <div class="header-icons">
            <div class="header-item__mail">
              <b id="network" style="font-size: 15px;"></b>
            </div>
          </div>
          <div class="header-item__img">
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
              <img src="../../hrad/modules/employees/file/<?php echo $row['CIMAGE']; ?>" alt="Profile" class="img-thumbnail" data-enlargable>
            <?php } ?>
            <hr class="hr hr-blurry">
            <b><?php echo $row['CNAME']; ?></b>
            <p style="font-size: 11px; font-style: italic;"><?php echo $row['CPOSITION']; ?></p>
          </div>
        </div>
      </header>
      <main class="main" style="margin-bottom: 150px; padding-right: 20px; padding-left: 20px; padding-bottom: 20px;">
        <div class="row text-white" style="text-align: center;">
          <div class="col-3">
            <div class="d-flex flex-column align-items-center position-relative load">
              <a href="index_profile" class="btn btn-light position-relative new-bg" 
                 style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-tie" style="font-size: 23px;"></i>
              </a>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 8px; margin-left: -5px;">New</span>
              <small style="margin-top: 5px;"><b>Profile</b></small>
            </div>
          </div>
          <div class="col-3">
            <div class="d-flex flex-column align-items-center position-relative load">
              <a href="index_service" class="btn btn-light position-relative new-bg" 
                 style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
                <i class="fab fa-buffer" style="font-size: 23px;"></i>
              </a>
              <small style="margin-top: 5px;"><b>Milestone</b></small>
            </div>
          </div>
          <div class="col-3">
            <div class="d-flex flex-column align-items-center position-relative load">
              <a href="index_related" class="btn btn-light position-relative new-bg" 
                 style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-people-arrows" style="font-size: 23px;"></i>
              </a>
              <small style="margin-top: 5px;"><b>Related</b></small>
            </div>
          </div>
          <div class="col-3">
            <div class="d-flex flex-column align-items-center position-relative load">
              <a href="index_family" class="btn btn-light position-relative new-bg" 
                 style="width: 60px; height: 60px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-users" style="font-size: 23px;"></i>
              </a>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="font-size: 8px; margin-left: -5px;">New</span>
              <small style="margin-top: 5px;"><b>Family</b></small>
            </div>
          </div>
          <div class="col-6" style="margin-top: 15px;">
            <div class="card new-bg">
              <div class="card-body text-center text-primary">
                <i class="fas fa-bell"></i>
                <br>
                <b style="font-size: 15px;">Coming Soon</b>
              </div>
            </div>
          </div>
          <?php if($shows == '1'){ if($emid != '2101-184'){ ?>
          <div class="col-6" style="margin-top: 15px;">
            <a href="recommend" class="card load text-dark new-bg">
              <div class="card-body text-center <?php echo ($cnt > 0) ? 'text-danger' : 'text-success'; ?>">
                <b style="font-size: 15px;">
                  <?php echo ($cnt > 0) ? $cnt.' Leave(s) Left' : 'Completed'; ?>
                </b>
                <br>
                <b style="font-size: 15px;">
                  <?php echo ($cnt > 0) ? '<i class="fas fa-calendar-xmark"></i>' : '<i class="fas fa-calendar-check"></i>'; ?> 
                  <?php echo ($chead == 0) ? 'Recommend' : 'Approval'; ?> 
                </b>
              </div>
            </a>
          </div>
          <?php }else{ ?>
          <div class="col-6" style="margin-top: 15px;">
            <a href="ceo_approve" class="card load text-dark new-bg">
              <div class="card-body text-center <?php echo ($ccnt > 0) ? 'text-danger' : 'text-success'; ?>">
                <b style="font-size: 15px;">
                  <?php echo ($ccnt > 0) ? $ccnt.' Leave(s) Left' : 'Completed'; ?>
                </b>
                <br>
                <b style="font-size: 15px;">
                  <?php echo ($ccnt > 0) ? '<i class="fas fa-calendar-xmark"></i>' : '<i class="fas fa-calendar-check"></i>'; ?> 
                  <?php echo 'Approval'; ?> 
                </b>
              </div>
            </a>
          </div>
          <?php } } if($emid == '2522-186'){ ?>
          <div class="col-6" style="margin-top: 15px;">
            <a href="index_new" class="card load text-dark new-bg">
              <div class="card-body text-center text-danger">
                <i class="fas fa-laptop-code"></i>
                <br>
                <b style="font-size: 15px;">Development Site</b>
              </div>
            </a>
          </div>
          <?php } ?>
        </div>
      </main>
      <footer class="footer__menu">
        <div class="footer__item" onclick="location.reload()">
          <i class="fa fa-user load text-primary"></i>
          <b>Me</b>
        </div>
        <a href="leave" class="footer__item text-dark">
          <i class="fa fa-calendar-check load"></i>
          <b>Leave</b>
        </a>
        <a href="info" class="footer__item text-dark">
          <i class="fa fa-newspaper load"></i>
          <b>News</b>
        </a> 
        <a href="chart" class="footer__item text-dark">
          <i class="fa fa-sitemap load"></i>
          <b>Chart</b>
        </a>
        <?php if($ksr == '1'){ ?>
        <a href="https://secims.com/voting/vote_apps?emid=<?php echo $_SESSION['emid']; ?>" class="footer__item text-dark">
          <i class="fa fa-user-tie load"></i>
          <b>KSR</b>
        </a>
        <?php }else{ ?>
        <div class="footer__item text-dark">
          <i class="fas fa-ban text-danger"></i>
          <b>KSR</b>
        </div>
        <?php } ?>
        <a href="more" class="footer__item text-dark">
          <i class="fa fa-square-arrow-up-right load"></i>
          <b>More</b>
        </a>
      </footer>
     </div>
  </body>
</html>
<script type="text/javascript">
  var number = 0;
  setInterval(function() {
    number = (500 + Math.random() * 1000).toFixed(2);
    var networkElement = document.getElementById("network");
    networkElement.innerHTML = '<span class="badge badge-success"><i class="fas fa-signal"></i> ' + number + " MB/s</span>";
    networkElement.classList.add("text-white");
  }, 1000);
  
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