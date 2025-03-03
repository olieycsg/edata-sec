<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

/*$emid = $_SESSION['emid'];
$mths = date('Y-m-d', strtotime($currentDate . ' -6 months'));

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){*/

?>
<link href="https://pbutcher.uk/flipdown/css/flipdown/flipdown.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"rel="stylesheet">
<style type="text/css">
.nk-ibx-body {
  display: flex;
  justify-content: center;
  align-items: center;
}

.flipdown {
  text-align: center;
}
</style>
<div id="content">
  <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
    <div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap">
          <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
              <div class="nk-ibx">
                <div class="nk-ibx-body text-center">
                  <img src="../../img/icon.png">
                  <div class="alert alert-primary" role="alert">
                    <b>New i-SEC Application will be live in <div class="spinner-border spinner-border-sm text-primary"></div></b>
                  </div>
                  <div class="flipdown" id="flipdown"></div>
                  <br>
                  <div class="alert alert-danger" style="text-align: left;" role="alert">
                    <b>
                      Any inquiry, please contact
                      HRA Division
                      <hr>
                      <div class="row">
                        <div class="col-8">
                          Puan Dayang Kamariah
                        </div>
                        <div class="col-4" style="text-align: right;">
                          <i class="fab fa-whatsapp text-success"></i>
                        </div>
                        <div class="col-8">
                          Miss Lai Sze Chee
                        </div>
                        <div class="col-4" style="text-align: right;">
                          <i class="fab fa-whatsapp text-success"></i>
                        </div>
                      </div>
                      <hr>
                      ICS Department
                      <br>
                      <div class="row">
                        <div class="col-8">
                          Mr Olivianus Pius
                        </div>
                        <div class="col-4" style="text-align: right;">
                          <a href="https://wa.me/60165874672?text=I%20want%20to%20ask%20about%20i-SEC%20Application.">
                            <i class="fab fa-whatsapp text-success"></i>
                          </a>
                        </div>
                      </div>
                    </b>
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
<script src="https://pbutcher.uk/flipdown/js/flipdown/flipdown.js"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', () => {
  var toDayFromNow = (new Date("Jul 22, 2024 23:59:59").getTime() / 1000) + (3600 / 60 / 60 / 24) - 1;
  var flipdown = new FlipDown(toDayFromNow)
  .start()
  .ifEnded(() => {
    document.querySelector(".flipdown").innerHTML = `<h2>Timer is ended</h2>`;
  });
});
</script>
<?php include('footer.php'); //} ?>