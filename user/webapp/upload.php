<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'] ?? $_GET['emid'];
$year = date("Y");

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM eleave_leave_type WHERE ID IN ('2','3','10','12','14')";
$result1 = $conn->query($sql1);

if($row = $result->fetch_assoc()){

$hire = date("Y", strtotime($row['DHIRE']));

?>
<style>
  .calendar-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
  }

  .month-container {
    margin-bottom: 20px;
    width: 100%;
  }

  .calendar {
    background-color: #f4f4f4;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 10px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  .day-header {
    text-align: center;
    padding: 8px;
    border-bottom: 1px solid #ddd;
    width: 14.285%;
  }

  th {
    color: black;
  }

  td {
    text-align: center;
    padding: 8px;
    border: 1px solid #ddd;
  }

  h3 {
    text-align: center;
    color: #333;
  }

  .weekend{
    color: red;
  }

  @media screen and (max-width: 600px) {
    .day-header {
      font-size: 12px;
    }
  }

  .floating-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
  }

  .floating-button:hover {
    background-color: #2980b9;
  }
</style>
<div id="content">
  <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
    <div class="nk-app-root">
      <div class="nk-main">
        <?php include('sidebar.php'); ?>
        <div class="nk-wrap">
          <?php include('navbar.php'); ?>
          <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
              <div class="nk-ibx">
                <div class="nk-ibx-body">
                  <div class="calendar-container">
                    <div class="month-container">
                      <div class="calendar">
                        <h5 style="text-align: left;">
                          <a href="#" onclick="location.reload();"><?php echo $row['CNAME']; ?></a>
                        </h5>
                      </div>
                    </div>
                    <div class="month-container">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <input type="file">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">

</script>
<?php } ?>