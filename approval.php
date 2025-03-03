<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('api.php');

$cn = $_GET['cn'];
$st = $_GET['st'];

$sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
$resulta = $conn->query($sqla);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta http-equiv="Cache-control" content="public">
  <title>i-SEC | ADMIN</title>
  <link rel="icon" type="image/x-icon" href="../img/icon.png">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
  <link rel="stylesheet" href="src/css/mdb.min.css">
</head>
<body>
  <style>
    @media (min-width: 992px) {
      #intro {
        margin-top: -58.59px;
      }
    }
    .navbar .nav-link {
      color: #fff !important;
    }
    .card {
      background-color: rgba(255, 255, 255, 0.7);
    }

    .form-outline .form-control~.form-label {
      color: black!important;
    }

    .form-outline .trailing {
      color: blue;
    }

    .zoom:hover{
      transform: scale(1.05)!important;
    }

    .pointer{
      cursor: pointer;
    }

    .form-outline 
    .form-control~.form-notch div {
      border-color: #3b71ca;
    }
  </style>
  <nav class="navbar navbar-expand-lg navbar-dark d-none d-lg-block" style="z-index: 2000;">
    <div class="container-fluid">
      <span class="navbar-brand nav-link">
        <img src="img/icon.png" style="width: 40px;">
      </span>
    </div>
  </nav>
  <div id="intro" class="bg-image vh-100 shadow-1-strong">
    <div class="mask" style="
      background: linear-gradient(
        45deg,
        rgba(29, 236, 197, 0.7),
        rgba(91, 14, 214, 0.7) 100%
      );
      ">
      <div class="container d-flex align-items-center justify-content-center text-center h-100">
        <div class="card">
          <div class="card-body text-dark">
            <div class="row">
              <div class="col-md-12 text-center">
                <img src="img/logo.png" class="img-fluid" style="width: 290px; margin-bottom: 10px;" alt="logo">
              </div>
            </div>
            <?php if ($resulta->num_rows > 0) { ?>
            <hr class="hr hr-blurry">
            <h5>LEAVE STATUS</h5>
            <hr class="hr hr-blurry">
            <h5 class="text-success">APPROVED</h5>
            <?php }else{ ?>
            <hr class="hr hr-blurry">
            <h5 class="text-danger">LEAVE NOT FOUND</h5>
            <hr class="hr hr-blurry">
            <h5 class="text-danger">DELETED / CANCELLED</h5>
            <?php } ?>
            <hr class="hr hr-blurry">
            <div class="row">
              <div class="col-md-12 text-center">
                <h5><strong>"SABAH MAJU JAYA"</strong></h5>
                <strong>
                  COPYRIGHT © 2021 - <?php echo date('Y'); ?> BY<br>
                  <a href="https://www.sabahenergycorp.com/" target="_blank">SABAH ENERGY CORPORATION SDN BHD</a><br> 
                  ALL RIGHT RESERVED
                </strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<script type="text/javascript">
document.addEventListener('contextmenu', function (e) {
  e.preventDefault();
});

document.addEventListener('keydown', function (e) {
  if (e.key === 'F12' || e.keyCode === 123) {
    e.preventDefault();
  }
});
</script>