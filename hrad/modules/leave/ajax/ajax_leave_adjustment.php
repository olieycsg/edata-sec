 <?php 

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../../api.php');

$get_employee = $_POST['employee'];

$sql = "SELECT * FROM eleave_fxleavea WHERE CNOEE = '$get_employee' ORDER BY DADJUST DESC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$get_employee'";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave_leave_type";
$result2 = $conn->query($sql2);

if($row1 = $result1->fetch_assoc()){
  $hire = $row1['DHIRE'];
  $date = date("Y-m-d");

  $date1 = new DateTime($hire);
  $date2 = new DateTime($date);
  $interval = $date1->diff($date2);
}

$year = date("Y");

?>
<style type="text/css">
  .text-left{
    text-align: left;
  }
  .text-right{
    text-align: right;
  }
  .text-center{
    text-align: center;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 col-6">
            <div class="form-outline" data-mdb-input-init>
              <input class="form-control active" value="<?php echo strtoupper(date("d F Y", strtotime($hire))); ?>" readonly>
              <label class="form-label" style="color: #3B71CA;"><b>Hire Date</b></label>
            </div>
          </div>
          <div class="col-md-9 col-6">
            <div class="form-outline" data-mdb-input-init>
              <input class="form-control active" value="<?php echo $interval->y . " Years " . $interval->m." Months ".$interval->d." Days"; ?>" readonly>
              <label class="form-label" style="color: #3B71CA;"><b>In Service</b></label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="card-body" style="margin-top: 20px;">
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-12 overflow-x-scroll">
            <table class="table table-sm table-hover table-striped" style="white-space: nowrap;">
              <thead class="text-left">
                <tr>
                  <th>LEAVE DATE</th>
                  <th>LEAVE TYPE</th>
                  <th>LEAVE DAYS</th>
                  <th>BALANCE</th>
                  <th>ADJUSTMENT</th>
                  <th class="text-right"><i class="fas fa-circle-plus text-primary pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#setup"></i></th>
                </tr>
              </thead>
              <tbody class="text-left">
                <?php foreach ($result as $key => $value) { ?>
                <tr>
                  <td>
                    <?php echo strtoupper(date("d M Y", strtotime($value['DADJUST']))); ?>
                  </td>
                  <td>
                    <?php 
                    foreach ($result2 as $key2 => $value2) {
                      if($value2['ID'] == $value['CCDLEAVE']){
                        echo $value2['leave_type'];
                      }
                    }
                    ?>
                  </td>
                  <td><?php echo $value['NDAYS']; ?></td>
                  <td><?php echo $value['NBALANCE']; ?></td>
                  <td>
                    <?php 
                    if($value['CTYPADJUST'] == '1'){
                      echo "ADJUST B/F";
                    }else if($value['CTYPADJUST'] == '2'){
                      echo "ADJUST DIFFERENCE";
                    }else if($value['CTYPADJUST'] == '3'){
                      echo "ADJUST BALANCE";
                    }
                    ?>
                  </td>
                  <td class="text-right">
                    <i class="fas fa-edit text-primary zoom pointer update_setup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $value['ID']; ?>" data-adjust="<?php echo $value['DADJUST']; ?>" data-leave="<?php echo $value['CCDLEAVE']; ?>" data-type="<?php echo $value['CTYPADJUST']; ?>" data-days="<?php echo $value['NDAYS']; ?>" data-balance="<?php echo $value['NBALANCE']; ?>"></i>
                    <i class="fas fa-trash-alt text-danger zoom pointer delete" data-delete="<?php echo $value['ID']; ?>"></i>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal fade" id="setup" data-mdb-backdrop="static">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <b><i class="fas fa-calendar-days"></i> New Adjustment</b>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-5">
                    <select id="dates" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                      <?php for($i = $year; $i >= $year - 3; $i--){ ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                      <?php } ?>
                    </select>
                    <label class="form-label select-label text-primary">
                      <b>Leave Date</b>
                    </label>
                  </div>
                  <div class="col-7">
                    <select id="leave" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                      <?php foreach ($result2 as $key2 => $value2) { ?>
                      <option value="<?php echo $value2['ID']; ?>"><?php echo $value2['leave_type']; ?></option>
                      <?php } ?>
                    </select>
                    <label class="form-label select-label text-primary">
                      <b>Leave Type</b>
                    </label>
                  </div>
                  <div class="col-5" style="margin-top: 15px;">
                    <div class="form-outline" data-mdb-input-init>
                      <input id="days" type="number" class="form-control active" value="1.00" placeholder="..." onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                      <label class="form-label select-label text-primary">
                        <b>Days</b>
                      </label>
                    </div>
                  </div>
                  <div class="col-7" style="margin-top: 15px;">
                    <select id="adjust" class="sec-select" data-mdb-select-init data-mdb-container="#setup">
                      <option value="1">B/Forward</option>
                      <option value="2">Difference</option>
                      <option value="3">Balance</option>
                    </select>
                    <label class="form-label select-label text-primary">
                      <b>Adjustment</b>
                    </label>
                  </div>
                  <div class="col-5" style="margin-top: 15px;">
                    <div class="form-outline" data-mdb-input-init>
                      <input id="balance" type="number" class="form-control active" value="1.00" placeholder="..." onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                      <label class="form-label select-label text-primary">
                        <b>Balance</b>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button id="add_adjustment" class="btn btn-primary">
                  <b><i class="fas fa-floppy-disk"></i> Save</b>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade edit_setup" data-mdb-backdrop="static">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <b><i class="fas fa-calendar-days"></i> Update Adjustment</b>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input id="eid" hidden>
                <div class="row">
                  <div class="col-5">
                    <select id="edates" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                      <?php for($i = $year; $i >= $year - 3; $i--){ ?>
                      <option value="<?php echo $i.'-01-01 00:00:00'; ?>"><?php echo $i; ?></option>
                      <?php } ?>
                    </select>
                    <label class="form-label select-label text-primary">
                      <b>Leave Date</b>
                    </label>
                  </div>
                  <div class="col-7">
                    <select id="eleave" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                      <?php foreach ($result2 as $key2 => $value2) { ?>
                      <option value="<?php echo $value2['ID']; ?>"><?php echo $value2['leave_type']; ?></option>
                      <?php } ?>
                    </select>
                    <label class="form-label select-label text-primary">
                      <b>Leave Type</b>
                    </label>
                  </div>
                  <div class="col-5" style="margin-top: 15px;">
                    <div class="form-outline" data-mdb-input-init>
                      <input id="edays" type="number" class="form-control active" value="1.00" placeholder="..." onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                      <label class="form-label select-label text-primary">
                        <b>Days</b>
                      </label>
                    </div>
                  </div>
                  <div class="col-7" style="margin-top: 15px;">
                    <select id="eadjust" class="sec-select" data-mdb-select-init data-mdb-container=".edit_setup">
                      <option value="1">B/Forward</option>
                      <option value="2">Difference</option>
                      <option value="3">Balance</option>
                    </select>
                    <label class="form-label select-label text-primary">
                      <b>Adjustment</b>
                    </label>
                  </div>
                  <div class="col-5" style="margin-top: 15px;">
                    <div class="form-outline" data-mdb-input-init>
                      <input id="ebalance" type="number" class="form-control active" value="1.00" placeholder="..." onchange="this.value = parseFloat(this.value).toFixed(2);" step="any">
                      <label class="form-label select-label text-primary">
                        <b>Balance</b>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button id="edit_adjustment" class="btn btn-primary">
                  <b><i class="fas fa-floppy-disk"></i> Save</b>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

sec_function();
$(".sub_loader").hide();
$(".nodata").show();

$(document).ready(function() {
  $("#add_adjustment").click(function(){
    $.ajax({
      url: "modules/leave/api_main",
      type:'POST',
      data:{
        add_adjustment: '<?php echo $get_employee; ?>',
        dates: $('#dates').val(),
        leave: $('#leave').val(),
        days: $('#days').val(),
        adjust: $('#adjust').val(),
        balc: $('#balance').val()
      },
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
        sec_remove();
        $(".sub_loader").show();
        $(".nodata").hide();
        $("#all_leave_adjustment").hide();
        var employee = $("#employee").val();
        $.ajax({
          url: "modules/leave/ajax/ajax_leave_adjustment",
          type:'POST',
          data:{employee:employee},
          success: function(response){
            $(".sub_loader").hide();
            $(".nodata").hide();
            $("#all_leave_adjustment").show().html(response);
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".update_setup").click(function(){
    var target = $(this).data('mdb-target');
    $(target).modal('show');
    $("#edates option[value='"+$(this).data('adjust')+"']").attr('selected', 'selected');
    $("#eleave option[value='"+$(this).data('leave')+"']").attr('selected', 'selected');
    $("#eadjust option[value='"+$(this).data('type')+"']").attr('selected', 'selected');
    $("#edays").val($(this).data('days'));
    $("#ebalance").val($(this).data('balance'));
    $("#eid").val($(this).data('id'));
  });
});

$(document).ready(function() {
  $('[data-mdb-dismiss="modal"]').click(function() {
    $("#edates option").removeAttr('selected');
    $("#eleave option").removeAttr('selected');
    $("#eadjust option").removeAttr('selected');
    $("#edays").val('');
    $("#ebalance").val('');
    $("#eid").val('');
  });
});

$(document).ready(function() {
  $("#edit_adjustment").click(function(){
    $.ajax({
      url: "modules/leave/api_main",
      type:'POST',
      data:{
        edit_adjustment: '<?php echo $get_employee; ?>',
        dates: $('#edates').val(),
        leave: $('#eleave').val(),
        days: $('#edays').val(),
        adjust: $('#eadjust').val(),
        balc: $('#ebalance').val(),
        id: $('#eid').val()
      },
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
        sec_remove();
        $(".sub_loader").show();
        $(".nodata").hide();
        $("#all_leave_adjustment").hide();
        var employee = $("#employee").val();
        $.ajax({
          url: "modules/leave/ajax/ajax_leave_adjustment",
          type:'POST',
          data:{employee:employee},
          success: function(response){
            $(".sub_loader").hide();
            $(".nodata").hide();
            $("#all_leave_adjustment").show().html(response);
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".delete").click(function(){
    var dele = $(this).attr('data-delete');
    Swal.fire({
      title: 'ARE YOU SURE?',
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
          type:'POST',
          data:{delete_adjustment:dele},
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
            sec_remove();
            $(".sub_loader").show();
            $(".nodata").hide();
            $("#all_leave_adjustment").hide();
            var employee = $("#employee").val();
            $.ajax({
              url: "modules/leave/ajax/ajax_leave_adjustment",
              type:'POST',
              data:{employee:employee},
              success: function(response){
                $(".sub_loader").hide();
                $(".nodata").hide();
                $("#all_leave_adjustment").show().html(response);
              }
            });
          },
        });
      }
    });
  });
});
</script>