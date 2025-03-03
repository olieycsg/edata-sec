<?php 

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../../api.php');

$get_year = $_POST['year'];
$get_view = $_POST['view'];

if($get_view == 'lists'){

$sql = "SELECT * FROM eleave_publicholiday WHERE DATE_FORMAT(dt_holiday, '%Y') = '$get_year' OR type = 'fixed' ORDER BY dt_holiday ASC";
$result = $conn->query($sql);

?>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 col-6" style="padding: 5px;">
            <div class="md-form">
              <input type="date" id="get_date" class="form-control datepicker">
            </div>
          </div>
          <div class="col-md-8 col-6" style="padding: 5px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="text" id="get_description" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Holiday</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-12" style="padding: 5px;">
            <div class="md-form">
              <button class="btn btn-primary btn-block zoom pointer z-depth-3" id="save_holiday">
                <i class="fas fa-plus-circle"></i> ADD HOLIDAY
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8 overflow-x-scroll">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12" style="overflow-x:auto;">
            <style type="text/css">
            #tbl_public_holiday th, td {
              white-space: nowrap;
              border: 1px solid white;
              padding: 5px!important;
            }
            </style>
            <table id="tbl_public_holiday" class="table table-striped" cellspacing="0" width="100%">
              <thead class="success-color white-text">
                <tr class="text-left">
                  <th><b>PUBLIC HOLIDAY</b></th>
                  <th><b>HOLIDAY DATE</b></th>
                  <th><b>HOLIDAY TYPE</b></th>
                  <th><i class="fas fa-gear"></i></th>
                </tr>
              </thead>
              <tbody>
                <?php 

                foreach ($result as $key => $value) { 
                  if($value['type'] == 'fixed'){
                    $date = $get_year."-".date("m-d", strtotime($value['dt_holiday']));
                  }else{
                    $date = $value['dt_holiday'];
                  }

                ?>
                <tr class="text-left">
                  <td>
                    <input class="description" style="width :100%;" value="<?php echo $value['description']; ?>" data-id="<?php echo $value['id']; ?>">
                  </td>
                  <td>
                    <input type="date" class="dt_holiday" style="width :100%" value="<?php echo $date; ?>" data-id="<?php echo $value['id']; ?>">
                  </td>
                  <td>
                    <select class="dt_type" style="width :100%;" data-id="<?php echo $value['id']; ?>">
                      <option value="fixed" <?php if($value['type'] == 'fixed'){ echo "selected"; } ?>>FIXED</option>
                      <option value="uf" <?php if($value['type'] == 'uf'){ echo "selected"; } ?>>NON FIXED</option>
                    </select>              
                  </td>
                  <td>
                    <i class="fas fa-trash-alt text-danger zoom pointer delete" data-delete="<?php echo $value['id']; ?>" data-desc="<?php echo $value['description']; ?>"></i>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $("#save_holiday").click(function(){
    var date = $("#get_date").val();
    var desc = $("#get_description").val();
    if(date == ''){
      Swal.fire('Date Required');
      $("#get_date").focus();
    }else if(desc == ''){
      Swal.fire('Description Required');
      $("#get_description").focus();
    }else{
      $.ajax({
        url: "modules/leave/api_main",
        type:'POST',
        data:{add_holiday:date,desc:desc},
        beforeSend: function() {    
          Swal.fire({
            title: 'SAVING',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function(response){
          Swal.close();
          var year = $("#year").val();
          var view = $("#view").val();
          $.ajax({
            url: "modules/leave/ajax/ajax_public_holiday",
            type:'POST',
            data:{year:year,view:view},
            success: function(response){
              $(".sub_loader").hide();
              $(".nodata").hide();
              $("#all_public_holiday").show().html(response);
            }
          });
        },
      });
    }
  });
});

$(document).ready(function() {
  $(".description").blur(function(){
    var update_descph = $(this).val().toUpperCase();
    var phid = $(this).attr('data-id');
    $.ajax({
      url: "modules/leave/api_main",
      type:'POST',
      data:{update_descph:update_descph,phid:phid},
      beforeSend: function() {    
        Swal.fire({
          title: 'SAVING',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function(response){
        Swal.close();
        var year = $("#year").val();
        var view = $("#view").val();
        $.ajax({
          url: "modules/leave/ajax/ajax_public_holiday",
          type:'POST',
          data:{year:year,view:view},
          success: function(response){
            $(".loader").hide();
            $(".nodata").hide();
            $("#all_public_holiday").show().html(response);
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".dt_holiday").blur(function(){
    var update_dateph = $(this).val();
    var phid = $(this).attr('data-id');
    $.ajax({
      url: "modules/leave/api_main",
      type:'POST',
      data:{update_dateph:update_dateph,phid:phid},
      beforeSend: function() {    
        Swal.fire({
          title: 'SAVING',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function(response){
        Swal.close();
        var year = $("#year").val();
        var view = $("#view").val();
        $.ajax({
          url: "modules/leave/ajax/ajax_public_holiday",
          type:'POST',
          data:{year:year,view:view},
          success: function(response){
            $(".loader").hide();
            $(".nodata").hide();
            $("#all_public_holiday").show().html(response);
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".dt_type").change(function(){
    var update_typeph = $(this).val();
    var phid = $(this).attr('data-id');
    $.ajax({
      url: "modules/leave/api_main",
      type:'POST',
      data:{update_typeph:update_typeph,phid:phid},
      beforeSend: function() {    
        Swal.fire({
          title: 'UPDATING DATA',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function(response){
        Swal.close();
        var year = $("#year").val();
        var view = $("#view").val();
        $.ajax({
          url: "modules/leave/ajax/ajax_public_holiday",
          type:'POST',
          data:{year:year,view:view},
          success: function(response){
            $(".loader").hide();
            $(".nodata").hide();
            $("#all_public_holiday").show().html(response);
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".delete").click(function(){
    var delete_public_holiday = $(this).attr('data-delete');
    var desc = $(this).attr('data-desc');
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<b>DELETE "+desc+"<br>YOU WON'T BE ABLE TO REVERT THIS</b>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "modules/leave/api_main",
          type:'POST',
          data:{delete_public_holiday:delete_public_holiday},
          beforeSend: function() {    
            Swal.fire({
              title: 'DELETING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response){
            Swal.close();
            var year = $("#year").val();
            var view = $("#view").val();
            $.ajax({
              url: "modules/leave/ajax/ajax_public_holiday",
              type:'POST',
              data:{year:year,view:view},
              success: function(response){
                $(".loader").hide();
                $(".nodata").hide();
                $("#all_public_holiday").show().html(response);
              }
            });
          },
        });
      }
    });
  });
});
</script>

<?php 

}else if($get_view  == 'table'){ 

$sql = "SELECT * FROM eleave_publicholiday WHERE DATE_FORMAT(dt_holiday, '%Y') = '$get_year' OR type = 'fixed' ORDER BY dt_holiday ASC";
$result = $conn->query($sql);

$get_holiday = [];
foreach ($result as $key => $value) {
  if($value['type'] == 'fixed'){
    $get_holiday[] = $get_year."-".date("m-d", strtotime($value['dt_holiday']));
  }else{
    $get_holiday[] = $value['dt_holiday'];
  }
}

$final_date = implode(",",$get_holiday);

$year = $_POST['year'];
$headings = ["S","M","T","W","T","F","S"];

?>
<style type="text/css">
  .success-color {
    background-color: #00c851!important;
  }
  .blue-gradient {
    background: linear-gradient(40deg,#45cafc,#303f9f)!important;
  }
</style>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 col-6" style="padding: 5px;">
            <div class="md-form">
              <input type="date" id="get_date" class="form-control datepicker">
            </div>
          </div>
          <div class="col-md-8 col-6" style="padding: 5px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="text" id="get_description" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Holiday</b>
              </label>
            </div>
          </div>
          <div class="col-md-2 col-12" style="padding: 5px;">
            <div class="md-form">
              <button class="btn btn-primary btn-block zoom pointer z-depth-3" id="save_holiday">
                <i class="fas fa-plus-circle"></i> ADD HOLIDAY
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <style type="text/css">
            #tbl_public_holiday th, td {
              white-space: nowrap;
              padding: 5px!important;
            }

            .day:nth-child(7n), .day:nth-child(7n+1){
              background: #ffe5e5;
            }

            .day:nth-child(7n+1){
              color: red;
            }

            .daytitle:nth-child(7n+1){
              color: red!important;
            }

            .new_holiday:hover{
              background: blue;
              color: white;
            }
            </style>
            <table id="tbl_public_holiday" class="table table-sm table-hover table-striped no-wrap">
              <thead class="bg-primary">
                <tr class="text-center">
                  <th style="text-align: left;"><b>MONTHS</b></th>
                  <?php
                  for($x = 1; $x <= 37; $x++) { 
                    $title = ($headings[($x % 7) ]);
                    echo "<td class='daytitle text-center'><b>{$title}</b></td>";
                  }
                  ?>
                </tr>
              </thead>
              <?php 
              for($month = 1; $month <= 12; $month++) {
                $thisMonth   = new DateTime("{$year}-{$month}");
                $daysInMonth = $thisMonth->format("t");
                $monthName   = $thisMonth->format("F");
              ?>
                <tr>
                  <?php

                  echo "<td style='text-transform:uppercase'><b>{$monthName}</b></td>";
                  $dayOffsetArray = [
                    "Monday"    => 0,
                    "Tuesday"   => 1,
                    "Wednesday" => 2,
                    "Thursday"  => 3,
                    "Friday"    => 4,
                    "Saturday"  => 5,
                    "Sunday"    => 6,
                  ];

                  $offset = $dayOffsetArray[$thisMonth->format("l")];
                  for ($i = 0; $i < $offset; $i++){
                    echo "<td class='day'></td>";
                  }

                  $final_date2[$month] = [];

                  $final_date1 = explode(",", $final_date);
                  foreach ($final_date1 as $key1 => $value1) {
                    if(date("m", strtotime($value1)) == $month){
                      $final_date2[$month][] = date("m-d", strtotime($value1));
                    }
                  }

                  //echo $final_date3 = implode(",", $final_date2[$month])."<br>";
                  $final_date3 = implode(",", $final_date2[$month]);

                  for ($day = 1; $day <= 37 - $offset; $day++) {
                    
                    $dayNumber = ($day <= $daysInMonth) ? $day : "";

                    $new_date = sprintf("%02d", $month)."-".sprintf("%02d", $dayNumber);

                    $final_date4 = explode(",", $final_date3);
                    foreach ($final_date4 as $key4 => $value4) {
                      if($value4 == $new_date){
                        $final_date5 = $value4;
                      }
                    }

                    foreach ($result as $key => $value) {
                      if($value['type'] == 'fixed' && date("m-d", strtotime($value['dt_holiday'])) == $new_date){
                        $dataid = $value['id'];
                        $datadesc = $value['description'];
                      }else if(date("m-d", strtotime($value['dt_holiday'])) == $new_date){
                        $dataid = $value['id'];
                        $datadesc = $value['description'];
                      }
                    }

                    $new_holiday = strtoupper(date("d F Y", strtotime($get_year."-".$new_date)));
                    
                    if($final_date5 == $new_date){
                      $datastyle = "class='day text-center success-color text-white zoom pointer delete_holiday sec-tooltip' data-delete='{$dataid}' data-date='{$new_holiday}' data-desc='{$datadesc}' style='font: 700 15px system-ui;' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='$datadesc'";
                    }else{
                      $datastyle = "class='day text-center zoom pointer new_holiday' data-holiday='{$new_holiday}'";
                    }

                    if(date("Y-m-d") == $get_year."-".$new_date){
                      $datastyle = "class='day text-center blue-gradient text-white zoom pointer new_holiday sec-tooltip' style='font: 700 15px system-ui;'data-holiday='{$new_holiday}' data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement='top' title='TODAY'";
                    }

                    echo "<td {$datastyle}>{$dayNumber}</td>";
                  } 
                  ?>
                </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $("#save_holiday").click(function(){
    var date = $("#get_date").val();
    var desc = $("#get_description").val();
    if(date == ''){
      Swal.fire('Date Required');
      $("#get_date").focus();
    }else if(desc == ''){
      Swal.fire('Description Required');
      $("#get_description").focus();
    }else{
      $.ajax({
        url: "modules/leave/api_main",
        type:'POST',
        data:{add_holiday:date,desc:desc},
        beforeSend: function() {    
          Swal.fire({
            title: 'SAVING',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function(response){
          Swal.close();
          var year = $("#year").val();
          var view = $("#view").val();
          $.ajax({
            url: "modules/leave/ajax/ajax_public_holiday",
            type:'POST',
            data:{year:year,view:view},
            success: function(response){
              $(".sub_loader").hide();
              $(".nodata").hide();
              $("#all_public_holiday").show().html(response);
            }
          });
        },
      });
    }
  });
});

$(document).ready(function() {
  $(".new_holiday").click(function(){
    var dateObject = new Date($(this).attr("data-holiday"));
    dateObject.setDate(dateObject.getDate() + 1);
    var formattedDate = dateObject.toISOString().split('T')[0];
    $("#get_date").val(formattedDate);
    $("#get_description").focus();
  });
});

$(document).ready(function() {
  $(".delete_holiday").click(function(){
    var delete_public_holiday = $(this).attr('data-delete');
    var date = $(this).attr('data-date');
    var desc = $(this).attr('data-desc');
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<b>DELETE "+date+"<br>HOLIDAY : "+desc+" <br>YOU WON'T BE ABLE TO REVERT THIS</b>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "modules/leave/api_main",
          type:'POST',
          data:{delete_public_holiday:delete_public_holiday},
          beforeSend: function() {    
            Swal.fire({
              title: 'DELETING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response){
            Swal.close();
            var year = $("#year").val();
            var view = $("#view").val();
            $.ajax({
              url: "modules/leave/ajax/ajax_public_holiday",
              type:'POST',
              data:{year:year,view:view},
              success: function(response){
                $(".loader").hide();
                $(".nodata").hide();
                $("#all_public_holiday").show().html(response);
              }
            });
          },
        });
      }
    });
  });
});
</script>
<?php } ?>