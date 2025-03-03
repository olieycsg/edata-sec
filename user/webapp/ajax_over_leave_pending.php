<?php 

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');

$emid = $_POST['emid'];

$sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND (CCDLEAVE = '1' OR CCDLEAVE = '14') AND (MNOTES = 'pending' OR MNOTES = 'recommended' OR MNOTES = '') ORDER BY DLEAVE DESC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM eleave_leave_type";
$result1 = $conn->query($sql1);

$color = ['amber-color', 'yellow-color', 'info-color', 'pink-color', 'cyan-color', 'brown-color', 'grey-color', 'lime-color', 'black-color', 'light-color', 'purple-gradient', 'green-color', 'peach-gradient', 'danger-color'];

foreach ($result as $key => $value) { 

?>
<div class="month-container">
  <div class="calendar">
    <div class="row">
      <div class="col-10">
        <b>
          <i class="far fa-calendar-alt"></i>
          <?php echo strtoupper(date("d M Y", strtotime($value['DLEAVE']))); ?>
          <i class="fas fa-long-arrow-alt-right"></i>
          <?php echo strtoupper(date("d M Y", strtotime($value['DLEAVE2']))); ?>
        </b>
      </div>
      <div class="col-2" style="text-align: right;">
        <?php if($value['MNOTES'] == 'pending'){ ?>
        <i class="fas fa-trash-alt text-danger delete_leave" data-start="<?php echo $value['DLEAVE']; ?>" data-final="<?php echo $value['DLEAVE2']; ?>" data-files="<?php echo $value['FILE']; ?>"></i>
        <?php } ?>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-12">
        <span class="badge text-bg-primary-soft">
          <b><?php echo $value['NDAYS']; ?> Days</b>
        </span>
        <?php
        foreach ($result1 as $key1 => $value1) { 
          if($value1['ID'] == $value['CCDLEAVE']){

            $colorClass = in_array($key1, array_keys($color)) ? $color[$key1] : '';
            $textDarkClass = $key1 == 1 ? 'text-dark' : '';

            if($value['NHOURS'] == '10'){
              $getType = ' (Morning)';
            }else if($value['NHOURS'] == '01'){
              $getType = ' (Evening)';
            }else{
              $getType = ' (FullDay)';
            }
        ?>
        <span class="badge text-bg-success-soft views" data-reason="<?php echo $value['CREASON']; ?>">
          <i class="fas fa-eye"></i>
          Remarks
        </span>
        <span class="badge <?php echo $colorClass.' '.$textDarkClass; ?>">
          <b><?php echo $value1['leave_type'].$getType; ?></b>
        </span>
        <hr>
        <?php if($value['MNOTES'] == 'recommended'){ ?>
        <span class="badge text-bg-info-soft">
          <b>Status: Recommended</b>
        </span>
        <?php }else if($value['MNOTES'] == 'pending'){ ?>
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
<?php $status[] = $value['id']; } if($status[0] == ''){ ?>
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
<script type="text/javascript">
$(document).ready(function() {
  var choicesItem = $('.choices__item');
  choicesItem.data('deletable', null);
  choicesItem.find('.choices__button').remove();
});

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
        var cnoee = $("#select_user").val();
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
</script>

