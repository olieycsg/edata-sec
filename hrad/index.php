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
  <link rel="stylesheet" href="../src/css/mdb.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    .video-container {
      position: relative;
      padding-bottom: 56.25%;
      height: 0;
      overflow: hidden;
      max-width: 100%;
      background: #000;
    }
    .video-container iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }
  </style>
  <nav class="navbar navbar-expand-lg navbar-dark d-none d-lg-block" style="z-index: 2000;">
    <div class="container-fluid">
      <span class="navbar-brand nav-link">
        <img src="../img/icon.png" style="width: 40px;">
      </span>
      <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#navbarLogin"
        aria-controls="navbarLogin" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarLogin">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 pointer zoom" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#view">
          <li class="nav-item active">
            <a class="nav-link">Disclaimer</a>
          </li>
        </ul>
        <div class="modal fade" id="view">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">
                  <b>SEC DISCLAIMER</b>
                </h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="alert alert-success text-justify" role="alert">
                  <i class="fas fa-caret-right"></i>
                  Welcome to the lastest sabah energy corporation management and information system (secmis). All-access to, and any use of, the secmis and any web page or internet site established by any of the department's, programs or divisions, is governed by the following disclaimers and limits on use.
                  <hr>
                  <i class="fas fa-caret-right"></i>
                  This web-based system solution page provides you with various modules designed for the use of most organizations from small to large corporations with a number of employees from authorized branches and locations.
                </div>
                <div class="alert alert-primary text-justify" role="alert">
                  <h5><strong>Terms of use</strong></h5>
                  <hr>
                  <i class="fas fa-caret-right"></i>
                  By visiting this system, users agree that they will not use it for any unlawful activity or use it in any way that would harm any person or entity.
                </div>
                <div class="alert alert-danger text-justify" role="alert">
                  <h5><strong>Unauthorized use / access</strong></h5>
                  <hr>
                  <i class="fas fa-caret-right"></i>
                  This computing system is operated by the sabah energy corporation sdn. Bhd. And is for official use only. Unauthorized access, unauthorized attempted access, or unauthorized use of any state computing system is a violation and may be subject to prosecution.
                  <br><br>
                  <i class="fas fa-caret-right"></i>
                  Individuals using this computing system without authority, or in excess of their authority, are subject to having their activities on this system monitored and recorded by system personnel. All http and ftp accesses are logged. In the course of such monitoring, or in the course of system maintenance or troubleshooting, the activities of authorized users also may be monitored.
                  <br><br>
                  <i class="fas fa-caret-right"></i>
                  Anyone using this system expressly consents to such monitoring and is advised that if such monitoring reveals possible evidence of criminal activity, further legal action may be taken.
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <div id="intro" class="bg-image vh-100 shadow-1-strong">
    <video style="min-width: 100%; min-height: 100%;" playsinline autoplay muted loop>
      <source class="h-100" src="../img/play.mp4" type="video/mp4">
    </video>
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
                <img src="../img/logo.png" class="img-fluid" style="width: 290px; margin-bottom: 10px;" alt="logo">
                <br>
                <h4>i-SEC ADMINISTRATOR</h4>
              </div>
            </div>
            <hr class="hr hr-blurry">
            <div class="row">
              <div class="col-md-12" style="margin-bottom: 30px;">
                <div class="form-outline" data-mdb-input-init>
                  <i class="far fa-envelope trailing"></i>
                  <input type="text" id="user" class="form-control form-control-lg form-icon-trailing active" placeholder="e.g olivianus@sabahenergycorp.com" onblur="checkEmail()">
                  <label class="form-label" for="user">Email</label>
                </div>
              </div>
              <div class="col-md-12" style="margin-bottom: 30px;">
                <div class="form-outline" data-mdb-input-init>
                  <i class="fas fa-laptop-code trailing"></i>
                  <input type="text" id="auth" class="form-control form-control-lg form-icon-trailing active" placeholder="e.g ********">
                  <label class="form-label" for="auth">Authentication Code</label>
                </div>
              </div>
            </div>
            <div class="row" style="margin-bottom: 30px;">
              <div class="col-6">
                <button id="generate" class="btn btn-success btn-block zoom">
                  <b><i class="fas fa-sync-alt"></i> Generate</b>
                </button>
              </div>
              <div class="col-6">
                <button id="access" class="btn btn-primary btn-block zoom">
                  <b><i class="fas fa-sign-in-alt"></i> Get Access</b>
                </button>
              </div>
            </div>
            <hr class="hr hr-blurry">
            <div class="row">
              <div class="col-md-12 text-center">
                <strong>
                  COPYRIGHT © 2021 - <?php echo date('Y'); ?> BY<br>
                  <a href="https://www.sabahenergycorp.com/" target="_blank">SABAH ENERGY CORPORATION SDN BHD</a><br> 
                  ALL RIGHTS RESERVED
                </strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script type="text/javascript" src="../src/js/mdb.umd.min.js"></script>
</body>
</html>
<script type="text/javascript">
function checkEmail() {
  var email = document.getElementById('user');
  var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

  if (!filter.test(email.value)) {
    Swal.fire({
      html: '<h5><strong style="font-weight: bold;">Invalid email detected<br>e.g olivianus@sabahenergycorp.com</strong></h5>',
    });
    $('#user').val('');
    return false;
  }
}

$(document).ready(function () {
  $("#generate").on("click", function () {
    var user = $("#user").val();
    if(user == ''){
      Swal.fire("Email Required");
    }else{
      $.ajax({
        url:'api_mail',
        type:'POST',
        data:{request_code:user},
        beforeSend: function() {    
          Swal.fire({
            title: 'Generating',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success:function(response){
          if($.trim(response) == '0'){
            Swal.fire({
              title: 'Unauthorized Email',
            });
          }else{
            Swal.fire({
              confirmButtonText: 'CLOSE',
              html: '<strong>Authentication Code has been emailed<br>'+user+'<strong>',
            });
          }
        },
      });
    }
  });
});

$(document).ready(function () {
  $("#access").on("click", function () {
    var user = $("#user").val();
    var auth = $("#auth").val();
    if(user == ''){
      Swal.fire("Email Required");
    }else if(auth == ''){
      Swal.fire("Code Required");    
    }else{
      $.ajax({
        url:'api_mail',
        type:'POST',
        data:{login_code:user,auth:auth},
        beforeSend: function() {    
          Swal.fire({
            title: 'Validating',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success:function(response){
          if($.trim(response) == '0'){
            Swal.fire({
              title: 'Invalid Access',
            });
          }else{
            window.location.href = 'dashboard';
          }
        },
      });
    }
  });
});

document.addEventListener('contextmenu', function (e) {
  e.preventDefault();
});

document.addEventListener('keydown', function (e) {
  if (e.key === 'F12' || e.keyCode === 123) {
    e.preventDefault();
  }
});
</script>