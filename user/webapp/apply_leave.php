<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

$leaveArray = explode(',', $_POST['leave']);
$staff = $row['CNAME'];
$superior = $row['CSUPERIOR'];
$supervis = $row['CSUPERVISO'];

if($superior == $supervis){
  $direct = 1;
}

$sql1 = "SELECT employees.EmailAddress AS email, employees_demas.CNAME AS cname, employees_demas.CNOEE AS cnoee FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CJOB = '$superior' AND employees_demas.DRESIGN = '0000-00-00'";
$result1 = $conn->query($sql1);

if($row1 = $result1->fetch_assoc()){
  $email = $row1['email'];
  $cname = $row1['cname'];
  $mnoee = $row1['cnoee'];
}

$sql2 = "SELECT employees_demas.CNOEE AS cnoee FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CJOB = '$supervis' AND employees_demas.DRESIGN = '0000-00-00'";
$result2 = $conn->query($sql2);

if($row2 = $result2->fetch_assoc()){
  $fnoee = $row2['cnoee'];
}

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

  .floating-back {
    position: fixed;
    bottom: 20px;
    left: 20px;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
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
                  <div class="calendar-container" style="margin-top: 20px;">
                    <div class="month-container">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-12 text-center">
                            <h4>
                              <em class="icon ni ni-user-check-fill text-success"></em> 
                              <?php echo $staff; ?>
                            </h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="calendar-container">
                    <div class="month-container">
                      <div class="calendar">
                        <table class="table small">
                          <?php foreach ($leaveArray as $index => $leaveString) { ?>
                          <tr>
                            <th>
                              <h4>
                                <?php echo date("d M Y", strtotime($leaveString)); ?>
                                <i class="fas fa-long-arrow-alt-right"></i>
                                <?php echo date("l", strtotime($leaveString)); ?>
                              </h4>
                              <div class="d-grid gap-2">
                                <div class="btn-group" role="group" style="margin-bottom: 15px;">
                                  <input type="radio" class="btn-check" name="<?php echo $leaveString; ?>" id="leave1_<?php echo $leaveString; ?>" value="01" data-leave="<?php echo $leaveString; ?>">
                                  <label class="btn btn-outline-primary" for="leave1_<?php echo $leaveString; ?>">Morning</label>

                                  <input type="radio" class="btn-check" name="<?php echo $leaveString; ?>" id="leave2_<?php echo $leaveString; ?>" value="11" data-leave="<?php echo $leaveString; ?>" checked>
                                  <label class="btn btn-outline-primary" for="leave2_<?php echo $leaveString; ?>">Full Day</label>

                                  <input type="radio" class="btn-check" name="<?php echo $leaveString; ?>" id="leave3_<?php echo $leaveString; ?>" value="10" data-leave="<?php echo $leaveString; ?>">
                                  <label class="btn btn-outline-primary" for="leave3_<?php echo $leaveString; ?>">Afternoon</label>
                                </div>
                              </div>
                            </th>
                          </tr>
                          <?php } ?>
                          <tr>
                            <th colspan="2">
                              <input type="text" class="form-control" id="remarks" placeholder="e.g remarks">
                            </th>
                          </tr>
                        </table>
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
  <button class="floating-button bg-primary" id="apply">
    <em class="icon ni ni-save-fill"></em>
</button>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $(".select_leave").change(function(){
    var type = $(this).val();
    var load = $(this).attr('data-load');
    if(type == '11'){
      $(".type[data-read='"+load+"']").html('Full Day');
    }else if(type == '10'){
      $(".type[data-read='"+load+"']").html('Morning');
    }else if(type == '01'){
      $(".type[data-read='"+load+"']").html('Evening');
    }
  });
});

$(document).ready(function(){
  $("#apply").click(function(){
    function formatDates(dateString) {
      var date = new Date(dateString);
      var day = date.getDate();
      var month = new Intl.DateTimeFormat('en-US', { month: 'short' }).format(date);
      var year = date.getFullYear();
      return `${day} ${month} ${year}`;
    }

    var start = $('.btn-check').first().data('leave');
    var final = $('.btn-check').last().data('leave');

    var checkValues = [];
    $('.btn-check:checked').each(function() {
      checkValues.push($(this).val());
    });

    var cnoee = '<?php echo $emid; ?>';
    var nhours = checkValues.join('');
    var remarks = $("#remarks").val();
    var cname = '<?php echo $cname; ?>';
    var staff = '<?php echo $staff; ?>';
    var email = '<?php echo $email; ?>';
    var mnoee = '<?php echo $mnoee; ?>';
    var fnoee = '<?php echo $fnoee; ?>';
    var direct = '<?php echo $direct; ?>';
    Swal.fire({
      title: "Apply Annual Leave?<br>"+formatDates(start)+" - "+formatDates(final),
      showCancelButton: true,
      cancelButtonColor: "#d33",
      confirmButtonText: "Apply"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'api_main',
          type: 'POST',
          data: {add_leave_two:start,final:final,cnoee:cnoee,nhours:nhours,remarks:remarks,cname:cname,staff:staff,email:email,mnoee:mnoee,fnoee:fnoee,direct:direct},
          beforeSend: function(){    
            Swal.fire({
              title: 'PROCESSING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response) {
            window.location.href = 'leave_pending';
          }
        });
      }
    });
  });
});
</script>
<?php include('footer.php'); } ?>