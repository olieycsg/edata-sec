<?php 

include('../../../../api.php');
$code = $_GET['code'];
$desc = $_GET['desc'];
$id = $_GET['id'];

$sql = "SELECT * FROM sys_general_dcmisc WHERE CTYPE = '$code' ORDER BY CCODE ASC";
$result = $conn->query($sql);

include('header.php');

?>
<style type="text/css">
  .text-left{
    text-align: left;
  }
  .text-right{
    text-align: right;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <div class="row">
          <div class="col-10">
            <b>
              <i class="fas fa-trash-can zoom pointer main_delete"></i> CODE 
              <i class="fas fa-long-arrow-alt-right"></i> 
              <i class="fas fa-pen-to-square zoom pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#edit_setup"></i> 
              <span class="zoom pointer sec-tooltip" style="text-decoration: underline; color: yellow; font-style: italic;" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $desc; ?>">
                <?php echo $code; ?>
              </span>
            </b>
          </div>
          <div class="col-2 text-right">
            <i class="fas fa-circle-plus zoom pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#subsetup"></i>
          </div>
        </div>
      </div>
      <div class="modal fade" id="edit_setup" data-mdb-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-caret-right"></i> Edit Code</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="e_code" class="form-control active" value="<?php echo $code; ?>" disabled>
                    <label class="form-label text-primary active">
                      <b>Code</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="e_desc" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="..." value="<?php echo $desc; ?>">
                    <label class="form-label text-primary active">
                      <b>Description</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="edit_code" class="btn btn-primary">
                <b><i class="fas fa-floppy-disk"></i> Save</b>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="subsetup" data-mdb-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-caret-right"></i> New Sub-Code</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" class="form-control active" value="<?php echo $code; ?>" disabled>
                    <label class="form-label text-primary active">
                      <b>Code</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="CCODE" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Sub-Code</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="CDESC" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Description</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="CLABEL" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Label</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_subcode" class="btn btn-primary">
                <b><i class="fas fa-floppy-disk"></i> Save</b>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body overflow-x-scroll">
        <table class="table table-sm table-hover table-striped" style="white-space: nowrap;">
          <thead class="text-left">
            <tr>
              <th><b>SUB-CODE</b></th>
              <th><b>DESCRIPTION</b></th>
              <th><b>LABEL</b></th>
              <th class="text-right"></th>
            </tr>
          </thead>
          <tbody class="text-left">
            <?php foreach ($result as $key => $value){ ?>
            <tr>
              <td><b><?php echo $value['CCODE']; ?></b></td>
              <td><b><?php echo $value['CDESC']; ?></b></td>
              <td><b><?php echo $value['CLABEL']; ?></b></td>
              <td class="text-right">
                <i class="fas fa-edit text-primary zoom pointer update_subsetup" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_subsetup" data-id="<?php echo $value['ID']; ?>" data-code="<?php echo $value['CCODE']; ?>" data-desc="<?php echo $value['CDESC']; ?>" data-label="<?php echo $value['CLABEL']; ?>"></i>
                <b>|</b>
                <i class="fas fa-trash-alt text-danger zoom pointer sub_delete" data-id="<?php echo $value['ID']; ?>" data-sub="<?php echo $value['CCODE']; ?>"></i>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="modal fade edit_subsetup" data-mdb-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-caret-right"></i> Update Sub-Code</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" class="form-control active" value="<?php echo $code; ?>" disabled>
                    <input id="u_id" hidden>
                    <label class="form-label text-primary active">
                      <b>Code</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="u_code" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Sub-Code</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="u_desc" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Description</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="u_label" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Label</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary edit_subcode">
                <b><i class="fas fa-floppy-disk"></i> Save</b>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $("#edit_code").click(function(){
    var id = '<?php echo $id; ?>';
    var code = $("#e_code").val();
    var desc = $("#e_desc").val();
    if(desc == ""){
      Swal.fire("Description Required");
    }else{
      $.ajax({
        url:'modules/system/api_main',
        type:'POST',
        data:{edit_code:id,desc:desc},
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
          $('.loader').show();
          $.ajax({
            url: 'modules/system/codes_list',
            beforeSend: function() {
              $('.loader').show();
              $('.no_data').hide();
              $('#show_data').hide();
            },
            success: function(data) {
              $('.loader').hide();
              $('.no_data').hide();
              $("#show_data").html(data).show();
              $.ajax({
                url: 'modules/system/ajax/ajax_codes_list?code='+code+"&desc="+desc+'&id='+id,
                beforeSend: function() {
                  $('.sub_loader').show();
                  $('.no_data').hide();
                  $('#show_ajax_datas').hide();
                },
                success: function(data) {
                  $('.sub_loader').hide();
                  $('.no_data').hide();
                  $("#show_ajax_datas").html(data).show();
                  sec_function();
                }
              });
            }
          });
        },
      });
    }
  });
});

$(document).ready(function() {
  $(".main_delete").click(function(){
    var code = '<?php echo $code; ?>';
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<b>YOU WON'T BE ABLE TO REVERT THIS<br>ALL ASSOCIATED DATA WILL BE DELETED<br><br>CODE <i class='fas fa-long-arrow-alt-right'></i> "+code+"</b>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url:'modules/system/api_main',
          type:'POST',
          data:{delete_code:code},
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
          success: function(response) {
            location.reload();
          },
        });
      }
    });
  });
});

$(document).ready(function() {
  $("#add_subcode").click(function(){
    var add_subcode = $("#CCODE").val();
    var subdesc = $("#CDESC").val();
    var sublabel = $("#CLABEL").val();
    var code = '<?php echo $code; ?>';
    var desc = '<?php echo $desc; ?>';
    var id = '<?php echo $id; ?>';
    if(add_subcode == ""){
      Swal.fire("Sub-Code Required");
    }else if(subdesc == ""){
      Swal.fire("Description Required");
    }else if(sublabel == ""){
      Swal.fire("Label Required");
    }else{
      $.ajax({
        url:'modules/system/api_main',
        type:'POST',
        data:{add_subcode:add_subcode,subdesc:subdesc,sublabel:sublabel,code:code},
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
            url: 'modules/system/ajax/ajax_codes_list?code='+code+'&desc='+desc+'&id='+id,
            beforeSend: function() {
              $('.sub_loader').show();
              $('.no_data').hide();
              $('#show_ajax_datas').hide();
            },
            success: function(data) {
              $('.sub_loader').hide();
              $('.no_data').hide();
              $("#show_ajax_datas").html(data).show();
              sec_function();
            }
          });
        },
      });
    }
  });
});

$(document).ready(function() {
  $(".sub_delete").click(function(){
    var subcode = $(this).attr('data-id');
    var code = '<?php echo $code; ?>';
    var desc = '<?php echo $desc; ?>';
    var id = '<?php echo $id; ?>';
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<b>YOU WON'T BE ABLE TO REVERT THIS<br><br>CODE <i class='fas fa-long-arrow-alt-right'></i> "+$(this).attr('data-sub')+"</b>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url:'modules/system/api_main',
          type:'POST',
          data:{delete_subcode:subcode},
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
          success: function(response) {
            Swal.close();
            sec_remove();
            $.ajax({
              url: 'modules/system/ajax/ajax_codes_list?code='+code+'&desc='+desc+'&id='+id,
              beforeSend: function() {
                $('.sub_loader').show();
                $('.no_data').hide();
                $('#show_ajax_datas').hide();
              },
              success: function(data) {
                $('.sub_loader').hide();
                $('.no_data').hide();
                $("#show_ajax_datas").html(data).show();
                sec_function();
              }
            });
          },
        });
      }
    });
  });
});

$(document).ready(function() {
  $(".update_subsetup").click(function(){
    var target = $(this).data('mdb-target');
    $(target).modal('show');
    $("#u_id").val($(this).data('id'));
    $("#u_code").val($(this).data('code'));
    $("#u_desc").val($(this).data('desc'));
    $("#u_label").val($(this).data('label'));
  });
});

$(document).ready(function() {
  $(".edit_subcode").click(function(){
    var edit_subcode = $("#u_id").val();
    var subcode = $("#u_code").val();
    var subdesc = $("#u_desc").val();
    var sublabel = $("#u_label").val();
    var code = '<?php echo $code; ?>';
    var desc = '<?php echo $desc; ?>';
    var id = '<?php echo $id; ?>';
    if(edit_subcode == ""){
      Swal.fire("Sub-Code Required");
    }else if(subdesc == ""){
      Swal.fire("Description Required");
    }else if(sublabel == ""){
      Swal.fire("Label Required");
    }else{
      $.ajax({
        url:'modules/system/api_main',
        type:'POST',
        data:{edit_subcode:edit_subcode,subcode:subcode,subdesc:subdesc,sublabel:sublabel},
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
            url: 'modules/system/ajax/ajax_codes_list?code='+code+'&desc='+desc+'&id='+id,
            beforeSend: function() {
              $('.sub_loader').show();
              $('.no_data').hide();
              $('#show_ajax_datas').hide();
            },
            success: function(data) {
              $('.sub_loader').hide();
              $('.no_data').hide();
              $("#show_ajax_datas").html(data).show();
              sec_function();
            }
          });
        },
      });
    }
  });
});
</script>