<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_SESSION['emid'];
$year = date("Y");

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid' AND DRESIGN = '0000-00-00'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

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
        height: auto;
        color: var(--white);
        background:
          linear-gradient(45deg, rgba(24, 148, 187, 1) 0%, rgba(24, 148, 187, 0) 70%),
          linear-gradient(135deg, rgba(155, 216, 143, 1) 10%, rgba(155, 216, 143, 0) 80%),
          linear-gradient(225deg, rgba(148, 219, 182, 1) 10%, rgba(148, 219, 182, 0) 80%),
          linear-gradient(315deg, rgba(0, 150, 213, 1) 100%, rgba(0, 150, 213, 0) 70%);
        position: relative;
        z-index: 1;
        overflow: hidden;
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
        background-color: #f8f9fa;
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
    </style>
  </head>
  <div id="loader-overlay">
    <div class="loader"></div>
  </div>
  <body>
    <div id="content">
      <header class="header">
        <div class="header__wrapper">
          <div class="header-icons">
            <div class="header-item__mail">
              <i class="fa fa-envelope"></i>
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
      <main class="main" style="margin-bottom: 150px; padding: 30px;">
        <div class="row">
          <div class="col-4">
            <a href="index_profile" class="card load text-dark">
              <div class="card-body text-center">
                <h3 class="card-title"><i class="fas fa-user-tie"></i></h3>
                <b style="font-size: 15px;">Profile</b>
              </div>
            </a>
          </div>
          <div class="col-4">
            <a href="index_service" class="card load text-dark">
              <div class="card-body text-center">
                <h3 class="card-title"><i class="fab fa-buffer"></i></h3>
                <b style="font-size: 15px;">In-Service</b>
              </div>
            </a>
          </div>
          <div class="col-4">
            <a href="index_related" class="card load text-dark">
              <div class="card-body text-center">
                <h3 class="card-title"><i class="fas fa-people-arrows"></i></h3>
                <b style="font-size: 15px;">Related</b>
              </div>
            </a>
          </div>
          <div class="col-12" style="margin-top: 15px;">
            <div class="card">
              <div class="card-body text-center text-primary">
                <h3 class="card-title"><i class="fas fa-bell"></i></h3>
                <b style="font-size: 15px;">Coming Soon</b>
              </div>
            </div>
          </div>
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
        <?php if($_SESSION['emid'] == '2522-186') { ?>
        <a href="https://secims.com/voting/vote_apps?emid=<?php echo $_SESSION['emid']; ?>" class="footer__item text-dark">
          <i class="fa fa-user-tie load"></i>
          <b>KSR</b>
        </a>
        <?php }else{ ?>
        <span class="footer__item text-dark">
          <i class="fa fa-ban text-danger"></i>
          <b class="text-danger">KSR</b>
        </span>
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