<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

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
                    <b>
                      Something good is coming
                      <br>
                      Stay tuned with us
                    </b>
                    <hr>
                    <b>
                      New i-SEC Application will be live in 
                      <div class="spinner-border spinner-border-sm text-primary"></div>
                    </b>
                  </div>
                  <div class="flipdown" id="flipdown"></div>
                  <br>
                  <div class="alert alert-success" style="text-align: left;" role="alert">
                    <b>
                      Any inquiry, please contact
                      HRA Division
                      <hr>
                      Puan Dayang Kamariah
                      <br>
                      Miss Lai Sze Chee
                      <hr>
                      Analyst Programmer, ICS Department
                      <br>
                      Mr Olivianus Pius
                      <hr>
                      <i class="fas fa-database"></i> Server Connection 
                      <i class="fas fa-caret-right"></i>
                      <span id="network"></span>
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
  var toDayFromNow = (new Date("Jul 23, 2024 14:59:59").getTime() / 1000) + (3600 / 60 / 60 / 24) - 1;
  var flipdown = new FlipDown(toDayFromNow)
  .start()
  .ifEnded(() => {
    document.querySelector(".flipdown").innerHTML = `<h2>Timer is ended</h2>`;
  });
});

var number = 0;
setInterval(function() {
  number = (500 + Math.random() * 1000).toFixed(2);
  var networkElement = document.getElementById("network");
  networkElement.innerHTML = number + " MB/s";
  networkElement.classList.add("text-success");
}, 1000);
</script>
<?php include('footer.php'); } ?>