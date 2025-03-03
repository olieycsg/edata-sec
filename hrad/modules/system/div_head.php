<?php 

include('../../../api.php');

$sql = "SELECT * FROM sys_workflow_divisional ORDER BY ID DESC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$result2 = $conn->query($sql2);

foreach ($result as $key => $value) {
  $div[] = $value['CDIVISION'];
}

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
      <div class="card-header text-white bg-success">
        <div class="row">
          <div class="col-11">
            <b><i class="fas fa-caret-right"></i> Div. Head Setup</b>
          </div>
          <div class="col-1 text-right">
            <i class="fas fa-circle-plus zoom pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#setup"></i>
          </div>
        </div>
      </div>
      <div class="modal fade" id="setup" data-mdb-backdrop="static">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-user-tie"></i> New Head</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <select id="division" class="sec-select" data-mdb-select-init data-mdb-container="#setup" data-mdb-filter="true">
                    <option value="">- Select -</option>
                    <?php foreach ($result1 as $k1 => $v1) { if($v1['CTYPE'] == 'DIVSN'){ ?>
                    <option value="<?php echo $v1['CCODE']; ?>" <?php if(in_array($v1['CCODE'], $div)){ echo "disabled"; } ?>>
                      (<?php echo $v1['CCODE']; ?>) <?php echo $v1['CDESC']; ?>
                    </option>
                    <?php } } ?>
                  </select>
                  <label class="form-label select-label text-primary">
                    <b>Divisional Head</b>
                  </label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_head" class="btn btn-primary">
                <b><i class="fas fa-floppy-disk"></i> Save</b>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 overflow-x-scroll">
            <table class="table table-sm table-hover table-striped" style="white-space: nowrap;">
              <thead class="text-left">
                <tr>
                  <th><b>CODE</b></th>
                  <th><b>DIVISION</b></th>
                  <th><b>HEAD OF DIVISION</b></th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="text-left table-group-divider table-divider-primary-color">
                <?php 

                foreach ($result as $key => $value) { 

                  foreach ($result1 as $value1) {
                    if($value['CDIVISION'] == $value1['CCODE'] && $value1['CTYPE'] == 'DIVSN'){
                      $divname = $value1['CDESC'];
                    }
                  }

                ?>
                <tr>
                  <td><?php echo $value['CDIVISION']; ?></td>
                  <td><?php echo $divname; ?></td>
                  <td>
                    <select class="search_head sec-select" data-mdb-size="sm" data-mdb-select-init data-mdb-filter="true">
                      <option value="" data-id="<?php echo $value['CDIVISION']; ?>">- Select -</option>
                      <?php foreach ($result2 as $value2) { ?>
                      <option value="<?php echo $value2['CJOB']; ?>" data-id="<?php echo $value['CDIVISION']; ?>" data-cd="<?php echo $value2['CNOEE']; ?>" <?php echo ($value2['CJOB'] == $value['CJOB'] && $value['CJOB'] != '') ? "selected" : ""; ?>>
                        <?php echo $value2['CNAME']; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td class="text-right">
                    <i class="fas fa-trash-can text-danger zoom pointer delete" data-id="<?php echo $value['ID']; ?>" data-name="<?php echo $value['CDIVISION']; ?>"></i>
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
  $("#add_head").click(function(){
    $.ajax({
      url:'modules/system/api_main',
      type:'POST',
      data:{
        add_head: $("#division").val()
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
        $.ajax({
          url: 'modules/system/div_head',
          beforeSend: function() {    
            $('.loader').show();
            $('.no_data').hide();
            $('#show_data').hide();
          },
          success: function(data) {
            $('.loader').hide();
            $('.no_data').hide();
            $("#show_data").html(data).show();
            sec_function();
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".search_head").change(function(){
    var jb = $(this).val();
    var dv = $(this).find(':selected').attr('data-id');
    var cd = $(this).find(':selected').attr('data-cd');
    $.ajax({
      url:'modules/system/api_main',
      type:'POST',
      data:{edit_head:dv,jb:jb,cd:cd},
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
        $.ajax({
          url: 'modules/system/div_head',
          beforeSend: function() {    
            $('.loader').show();
            $('.no_data').hide();
            $('#show_data').hide();
          },
          success: function(data) {
            $('.loader').hide();
            $('.no_data').hide();
            $("#show_data").html(data).show();
            sec_function();
          }
        });
      },
    });
  });
});

$(document).ready(function() {
  $(".delete").click(function(){
    var del = $(this).attr('data-id');
    var nam = $(this).attr('data-name');
    Swal.fire({
      title: 'ARE YOU SURE ?',
      html: "<strong>("+nam+") WILL BE DELETED</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'modules/system/api_main',
          type: 'POST',
          data: {delete_head:del},
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
            $.ajax({
              url: 'modules/system/div_head',
              beforeSend: function() {    
                $('.loader').show();
                $('.no_data').hide();
                $('#show_data').hide();
              },
              success: function(data) {
                $('.loader').hide();
                $('.no_data').hide();
                $("#show_data").html(data).show();
                sec_function();
              }
            });
          },
        });
      }
    });
  });
});
</script>