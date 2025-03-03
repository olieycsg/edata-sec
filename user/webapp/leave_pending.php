<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];
$year = date("Y");

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND (MNOTES = 'pending' OR MNOTES = 'recommended' OR MNOTES = '') ORDER BY DLEAVE DESC";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave_leave_type";
$result2 = $conn->query($sql2);

if($row = $result->fetch_assoc()){

  $color = ['amber-color', 'yellow-color', 'info-color', 'pink-color', 'cyan-color', 'brown-color', 'grey-color', 'lime-color', 'black-color', 'light-color', 'purple-gradient', 'green-color', 'peach-gradient', 'danger-color'];

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
</style>
<div id="content">
  <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
    <div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap">
          <?php include('navbar.php'); ?>
          <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
              <div class="nk-ibx">
                <div class="nk-ibx-body">
                  <div class="calendar-container">
                    <div class="month-container">
                      <div class="calendar">
                        <div class="row g-2">
                          <div class="col-10">
                            <div class="form-group">
                              <div class="form-control-wrap">
                                <select id="select_type" class="js-select load">
                                  <option value="leave">Annual Leave</option>
                                  <option value="leave_other">Other Leave</option>
                                  <option value="leave_analytics">Leave Analytics</option>
                                  <option value="on_leave">On Leave</option>
                                  <option value="leave_pending" selected>Pending Leave</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-2">
                            <div class="form-group load">
                              <div class="form-control-wrap">
                                <a href="leave_pending" class="btn btn-block btn-soft btn-primary">
                                  <em class="icon ni ni-reload-alt"></em>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php foreach ($result1 as $k1 => $v1) { ?>
                    <div class="month-container">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-10">
                            <b>
                              <i class="far fa-calendar-alt"></i>
                              <?php echo strtoupper(date("d M Y", strtotime($v1['DLEAVE']))); ?>
                              <i class="fas fa-long-arrow-alt-right"></i>
                              <?php echo strtoupper(date("d M Y", strtotime($v1['DLEAVE2']))); ?>
                            </b>
                          </div>
                          <div class="col-2" style="text-align: right;">
                            <?php if($v1['MNOTES'] == 'pending'){ ?>
                            <i class="fas fa-trash-alt text-danger delete_leave" data-start="<?php echo $v1['DLEAVE']; ?>" data-final="<?php echo $v1['DLEAVE2']; ?>" data-files="<?php echo $v1['FILE']; ?>"></i>
                            <?php } ?>
                          </div>
                        </div>
                        <hr>
                        <div class="row">
                          <div class="col-12">
                            <span class="badge text-bg-primary-soft">
                              <b><?php echo $v1['NDAYS']; ?> Days</b>
                            </span>
                            <?php
                            foreach ($result2 as $k2 => $v2) { 
                              if($v2['ID'] == $v1['CCDLEAVE']){

                                $colorClass = in_array($k2, array_keys($color)) ? $color[$k2] : '';
                                $textDarkClass = $k2 == 1 ? 'text-dark' : '';

                                if($v1['NHOURS'] == '10'){
                                  $getType = ' (Morning)';
                                }else if($v1['NHOURS'] == '01'){
                                  $getType = ' (Afternoon)';
                                }else{
                                  $getType = ' (FullDay)';
                                }
                            ?>
                            <span class="badge text-bg-success-soft views" data-reason="<?php echo $v1['CREASON']; ?>">
                              <i class="fas fa-eye"></i>
                              Remarks
                            </span>
                            <span class="badge <?php echo $colorClass.' '.$textDarkClass; ?>">
                              <b><?php echo $v2['leave_type'].$getType; ?></b>
                            </span>
                            <hr>
                            <?php if($v1['FILE'] != ''){ ?>
                            <span class="badge text-bg-danger-soft" data-bs-toggle="modal" data-bs-target="#view_<?php echo $v1['id']; ?>">
                              <b><i class="fas fa-file-pdf"></i> Documents</b>
                            </span>
                            <div class="modal fade" id="view_<?php echo $v1['id']; ?>">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <b><i class="fas fa-file-pdf"></i> Supporting Documents</b>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <embed src="file/<?php echo $v1['FILE']; ?>" width="100%" height="100%">
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                                      <b><i class="far fa-times-circle"></i> Close</b>
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php } if($v1['MNOTES'] == 'recommended'){ ?>
                            <span class="badge text-bg-warning-soft">
                              <b>Status: Recommended</b>
                            </span>
                            <?php }else if($v1['MNOTES'] == 'pending'){ ?>
                            <span class="badge text-bg-danger-soft">
                              <b>Status: Pending</b>
                            </span>
                            <?php
                                }
                              }
                            } 
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $status[] = $v1['id']; } if($status[0] == ''){ ?>
                    <div class="month-container">
                      <div class="calendar">
                        <div class="row">
                          <div class="col-md-12 text-center">
                            <img src="../../img/error/a.svg" class="img-fluid">
                          </div>
                        </div>
                      </div>
                    </div>
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
<script type="text/javascript">
$(document).ready(function(){
  $("#select_type").change(function(){
    window.location.href = $(this).val();
  });
});

function formatDate(dateString) {
  var date = new Date(dateString);
  var day = date.getDate();
  var month = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(date);
  var year = date.getFullYear();
  return `${day} ${month} ${year}`;
}

$(document).ready(function(){
  $(".delete_leave").click(function(){
    var start = $(this).attr("data-start");
    var final = $(this).attr("data-final");
    var files = $(this).attr("data-files");
    if(start == final){
      var finalDate = formatDate(start);
    }else{
      var finalDate = formatDate(start)+" - "+formatDate(final);
    }

    Swal.fire({
      title: "Delete Leave?",
      html: "<strong class='text-danger'>"+finalDate+"<br>You won't be able to revert this</strong>",
      showCancelButton: true,
      confirmButtonText: 'Delete',
      cancelButtonText: 'Cancel',
      showLoaderOnConfirm: true,
    }).then((result) => {
      if (result.isConfirmed) {
        var cnoee = '<?php echo $emid; ?>';
        return $.ajax({
          url: "api_main",
          type: 'POST',
          data: {delete_leave_one:start,cnoee:cnoee,files:files},
          beforeSend: function(){    
            Swal.fire({
              title: 'DELETING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response) {
            location.reload();
          }
        });
      }
    });
  });
});

$(document).ready(function(){
  $(".views").click(function(){
    var view = $(this).attr('data-reason');
    if(view == ''){
      Swal.fire("No Data");
    }else{
      Swal.fire({
        html: '<h5><b>'+view+'</b></h5>',
        showConfirmButton: true,
        confirmButtonText: 'Close'
      });
    }
  });
});

$(document).ready(function() {
  var choicesItem = $('.choices__item');
  choicesItem.data('deletable', null);
  choicesItem.find('.choices__button').remove();
});
</script>
<?php } ?>