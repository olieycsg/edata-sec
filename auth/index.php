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
  <header>
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
          <img src="../img/icon.png" style="width: 40px;">
        </span>
      </div>
    </nav>
    <style>
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
                  <h4>HRIS SUPER ADMIN</h4>
                </div>
              </div>
              <hr class="hr hr-blurry">
              <div class="row">
                <div class="col-md-12" style="margin-bottom: 30px;">
                  <div class="form-outline" data-mdb-input-init>
                    <i class="far fa-envelope trailing"></i>
                    <input type="text" id="user" class="form-control form-control-lg form-icon-trailing active" placeholder="e.g ********">
                    <label class="form-label" for="user">Username</label>
                  </div>
                </div>
                <div class="col-md-12" style="margin-bottom: 30px;">
                  <div class="form-outline" data-mdb-input-init>
                    <i class="fas fa-laptop-code trailing"></i>
                    <input type="password" id="auth" class="form-control form-control-lg form-icon-trailing active" placeholder="e.g ********">
                    <label class="form-label" for="auth">Authentication Code</label>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-bottom: 30px;">
                <div class="col-6"></div>
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
                    ALL RIGHT RESERVED
                  </strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script type="text/javascript" src="../src/js/mdb.umd.min.js"></script>
</body>
</html>
<script type="text/javascript">
$(document).ready(function () {
  $("#access").on("click", function () {
    var user = $("#user").val();
    var auth = $("#auth").val();
    if(user == ''){
      Swal.fire("Username Required");
    }else if(auth == ''){
      Swal.fire("Code Required");    
    }else{
      $.ajax({
        url:'api_login',
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