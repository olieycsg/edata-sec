<?php 

include('../../../../api.php');

if(isset($_POST['run_update'])){

  $emid = $_POST['emid'];
  $divi = $_POST['divi'];
  $year = $_POST['year'];
  $edit = $_POST['run_update'];

  $hldy = [];
  $newdiff = [];

  $sql = "SELECT * FROM eleave WHERE CNOEE = '$emid' AND DLEAVE <= '$edit' AND DLEAVE2 >= '$edit'";
  $result = $conn->query($sql);


  if($row = $result->fetch_assoc()){

    $id = $row['id'];
    $type = $row['CCDLEAVE'];
    $file = $row['FILE'];
    $leav1 = $row['DLEAVE'];
    $leav2 = $row['DLEAVE2'];
    $start = new DateTime($row['DLEAVE']);
    $end = new DateTime($row['DLEAVE2']);
    $ndays = $row['NDAYS'];
    $nhours = $row['NHOURS'];
    $shift = $row['LADVANCE'];
    $ghours = str_split($nhours, 2);

    $diff = new DateInterval('P1D');
    $range = new DatePeriod($start, $diff, $end->modify('+1 day'));

    $sql1 = "SELECT * FROM eleave_leave_type WHERE ID = '$type'";
    $result1 = $conn->query($sql1);

    $sqla = "SELECT * FROM eleave_publicholiday WHERE DATE_FORMAT(dt_holiday, '%Y') = '$year' OR type = 'fixed' ORDER BY dt_holiday ASC";
    $resulta = $conn->query($sqla);

    foreach ($resulta as $keya => $valuea) {
      if($valuea['type'] == 'fixed'){
        $hldy[] = $year."-".date("m-d", strtotime($valuea['dt_holiday']));
      }else{
        $hldy[] = $valuea['dt_holiday'];
      }
    }

    if($divi != 'ENG'){
      foreach ($range as $diffval){
        if(!in_array($diffval->format('Y-m-d'), $hldy) && $diffval->format('N') < 6) {
          $newdiff[] = $diffval->format('d M Y (D)');
        }else{
          $newdiff[] = $diffval->format('d M Y (D)');
        }
      }
    }else{
      foreach ($range as $diffval){
        if($shift == '0'){
          if(!in_array($diffval->format('Y-m-d'), $hldy) && $diffval->format('N') < 6) {
            $newdiff[] = $diffval->format('d M Y (D)');
          }else{
            $newdiff[] = $diffval->format('d M Y (D)');
          }
        }else{
          $newdiff[] = $diffval->format('d M Y (D)');
        }
      }
    }

    if($row1 = $result1->fetch_assoc()){
      $leave_type = $row1['leave_type'];
    }

  ?>
  <div class="row">
    <div class="col-md-12" style="padding: 8px;">
      <div class="note note-success">
        <b><?php echo $leave_type; ?></b>
      </div>
    </div>
  </div>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-12" style="padding: 10px;">
      <div class="form-outline" data-mdb-input-init>
        <input id="ereason" class="form-control active" placeholder="..." value="<?php echo $row['CREASON']; ?>" oninput="this.value = this.value.toUpperCase()">
        <label class="form-label text-primary">
          <b>Reason</b>
        </label>
      </div>
    </div>
  </div>
  <?php if(in_array($type, ['1','14','15','16'])) { ?>
  <div class="row">
    <?php 

    foreach ($newdiff as $keyr => $newval){
      foreach ($ghours as $keys => $nhour) {
        if($keys == $keyr){
          $vhour = $nhour;
        }
      }
    ?>
    <div class="col-md-2 col-6" style="padding: 10px;">
      <select class="sec-select update_leave_status" data-mdb-select-init>
        <option value="11" <?= $vhour == '11' ? 'selected' : '' ?>>FullDay</option>
        <option value="10" <?= $vhour == '10' ? 'selected' : '' ?>>Morning</option>
        <option value="01" <?= $vhour == '01' ? 'selected' : '' ?>>Evening</option>
      </select>
      <label class="form-label select-label text-primary">
        <b><?php echo $newval; ?></b>
      </label>
    </div>
    <?php } ?>
  </div>
  <?php }else if(in_array($type, ['6','7','8','9','11','13'])) { ?>
  <div class="row">
    <div class="col-md-4" style="padding: 10px;">
      <div class="form-outline" data-mdb-input-init>
        <input class="form-control active" value="<?php echo date("d F Y", strtotime($row['DLEAVE'])); ?>" disabled>
        <label class="form-label text-primary">
          <b>Start Date</b>
        </label>
      </div>
    </div>
    <div class="col-md-4" style="padding: 10px;">
      <div class="form-outline" data-mdb-input-init>
        <input class="form-control active" value="<?php echo date("d F Y", strtotime($row['DLEAVE2'])); ?>" disabled>
        <label class="form-label text-primary">
          <b>End Date</b>
        </label>
      </div>
    </div>
    <div class="col-md-4" style="padding: 10px;">
      <div class="form-outline" data-mdb-input-init>
        <input class="form-control active" value="<?php echo $ndays; ?> DAYS" disabled>
        <label class="form-label text-primary">
          <b>Duration</b>
        </label>
      </div>
    </div>
  </div>
  <?php }else{ ?>
  <div class="row">
    <div class="col-md-2" style="padding: 10px;">
      <div class="form-outline" data-mdb-input-init>
        <input class="form-control active" value="<?php echo date("d F Y", strtotime($row['DLEAVE'])); ?>" disabled>
        <label class="form-label text-primary">
          <b>Start Date</b>
        </label>
      </div>
    </div>
    <div class="col-md-2" style="padding: 10px;">
      <div class="form-outline" data-mdb-input-init>
        <input class="form-control active" value="<?php echo date("d F Y", strtotime($row['DLEAVE2'])); ?>" disabled>
        <label class="form-label text-primary">
          <b>End Date</b>
        </label>
      </div>
    </div>
    <div class="col-md-2" style="padding: 10px;">
      <div class="form-outline" data-mdb-input-init>
        <input class="form-control active" value="<?php echo ($type == '2') ? "98.00" : (($type == '3' || $type == '4' || $type == '5') ? $ndays : (($type == '10') ? "7.00" : (($type == '11') ? "40.00" : (($type == '12') ? "5.00": "")))); ?> DAYS" disabled>
        <label class="form-label text-primary">
          <b>Duration</b>
        </label>
      </div>
    </div>
    <?php if($type != '1' || $type != '14'){ ?>
    <div class="col-md-4" style="padding: 10px;">
      <input type="file" class="form-control control sec-tooltip" id="edit_file" <?php if($file != ''){ echo "disabled"; } ?> data-mdb-tooltip-init data-mdb-placement='top' title='Upload <?php echo ($type == '2') ? "Birth" : (($type == '3' || $type == '4' || $type == '5') ? "Medical" : (($type == '10') ? "Birth" : (($type == '12') ? "Marriage" : ""))); ?> Certificate (jpg/jpeg/png)'>
    </div>
    <div class="col-md-2 text-right" style="padding: 10px;">
      <button id="upload_image" class="btn btn-primary sec-tooltip" <?php if($file != ''){ echo "disabled"; } ?> data-mdb-tooltip-init data-mdb-placement='top' title='Upload File'>
        <i class="fas fa-cloud-arrow-up"></i>
      </button>
      <button id="delete_image" class="btn btn-danger sec-tooltip" <?php if($file == ''){ echo "disabled"; } ?> data-mdb-tooltip-init data-mdb-placement='top'title='Delete File'>
        <i class="fas fa-trash-can"></i>
      </button>
    </div>
    <?php } ?>
  </div>
  <?php if($file != ''){ ?>
  <hr class="hr hr-blurry">
  <div class="row">
    <div class="col-md-12 text-center" style="padding: 10px;">
      <img src="../../../user/webapp/file/<?php echo $file; ?>" class="img-thumbnail">
    </div>  
  </div>
  <?php } ?>
  <?php } ?>
  <input id="edit_id" value="<?php echo $id; ?>" hidden>
  <input id="edit_type" value="<?php echo $type; ?>" hidden>
  <input id="edit_hour" value="<?php echo $nhours; ?>" hidden>
  <input id="edit_days" value="<?php echo $row['NDAYS']; ?>" hidden>
  <script type="text/javascript">
  sec_function();
  $(document).ready(function(){
    $('.update_leave_status').change(function(){
      var chge_hour = [];
      var chge_days = 0;
      var balance = '<?php echo $balance; ?>'; 
      $('.update_leave_status').find('option:selected').each(function() {
        chge_hour.push($(this).val());
        if ($(this).val() === "11") {
          chge_days += 1.00;
        } else {
          chge_days += 0.5;
        }
      });
      $('#edit_hour').val(chge_hour.join(''));
      $('#edit_days').val(chge_days);
    });
  });

  $(document).ready(function() {
    $("#edit_file").change(function(){
      var file = $(this)[0].files[0];
      if (file) {
        var fileType = file.type;
        var validExtensions = ['image/jpg', 'image/jpeg', 'image/png'];
        if ($.inArray(fileType, validExtensions) == -1) {
          Swal.fire('Only jpg/jpeg/png allowed');
          $(this).val('');
        }
      }
    });
  });

  $(document).ready(function() {
    $("#upload_image").click(function(){
      var formData = new FormData();
      var file = $('#edit_file')[0].files[0];

      var img = new Image();
      img.onload = function () {
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var MAX_WIDTH = 768;
        var MAX_HEIGHT = 1024;
        var width = img.width;
        var height = img.height;

        var scaleFactor = Math.min(MAX_WIDTH / width, MAX_HEIGHT / height);
        width *= scaleFactor;
        height *= scaleFactor;

        canvas.width = MAX_WIDTH;
        canvas.height = MAX_HEIGHT;

        ctx.fillStyle = 'rgb(255,255,255)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        var x = (MAX_WIDTH - width) / 2;
        var y = (MAX_HEIGHT - height) / 2;

        ctx.drawImage(img, x, y, width, height);
        canvas.toBlob(function (blob) {

          formData.append('update_image', '<?php echo $id; ?>');
          formData.append('file', file);

          $.ajax({
            url: "modules/leave/api_main",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
              Swal.fire({
                title: 'UPLOADING',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
            },
            success: function(response) {
              Swal.close();
              $(".apply_loader").hide();
              $.ajax({
                url: "modules/leave/ajax/ajax_update",
                type: "POST",
                data: {
                  run_update: '<?php echo $edit; ?>',
                  emid: '<?php echo $emid; ?>',
                  year: '<?php echo $year; ?>',
                  divi: '<?php echo $divi; ?>'
                },
                beforeSend: function() {    
                  $(".apply_loader").show();
                  $("#show_update").hide();
                },
                success: function(response) {
                  $(".apply_loader").hide();
                  $("#show_update").html(response).show();
                },
              });
            }
          });
        });
      };
      img.src = URL.createObjectURL(file);
    });
  });

  $(document).ready(function() {
    $("#delete_image").click(function(){
      Swal.fire({
        title: 'DELETE IMAGE',
        html: "<strong>YOU WON'T BE ABLE TO REVERT THIS</strong>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00B74A',
        cancelButtonColor: '#d33',
        confirmButtonText: 'YES, PROCEED'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "modules/leave/api_main",
            type: "POST",
            data: {
              delete_image: '<?php echo $id; ?>'
            },
            beforeSend: function() {    
              Swal.fire({
                title: 'PLEASE WAIT',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
            },
            success: function(response) {
              Swal.close();
              $(".apply_loader").hide();
              $.ajax({
                url: "modules/leave/ajax/ajax_update",
                type: "POST",
                data: {
                  run_update: '<?php echo $edit; ?>',
                  emid: '<?php echo $emid; ?>',
                  year: '<?php echo $year; ?>',
                  divi: '<?php echo $divi; ?>'
                },
                beforeSend: function() {    
                  $(".apply_loader").show();
                  $("#show_update").hide();
                },
                success: function(response) {
                  $(".apply_loader").hide();
                  $("#show_update").html(response).show();
                },
              });
            },
          });
        }
      });
    });
  });
  </script>
<?php } } ?>