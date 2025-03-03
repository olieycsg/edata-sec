<?php 

include('../../../api.php');

$sql = "SELECT * FROM sys_general_dctype ORDER BY CTYPE ASC";
$result = $conn->query($sql);

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
      <div class="card-header text-white bg-primary">
        <div class="row">
          <div class="col-11">
            <b><i class="fas fa-caret-right"></i> Codes List</b>
          </div>
          <div class="col-1 text-right">
            <i class="fas fa-circle-plus zoom pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#setup"></i>
          </div>
        </div>
      </div>
      <div class="modal fade" id="setup" data-mdb-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <b><i class="fas fa-caret-right"></i> New Code</b>
              <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="code" class="form-control active" maxlength="5" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Code</b>
                    </label>
                  </div>
                </div>
                <div class="col-12" style="margin-top: 15px;">
                  <div class="form-outline" data-mdb-input-init>
                    <input type="text" id="desc" class="form-control active" oninput="this.value = this.value.toUpperCase()" placeholder="...">
                    <label class="form-label text-primary active">
                      <b>Description</b>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button id="add_code" class="btn btn-primary">
                <b><i class="fas fa-floppy-disk"></i> Save</b>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <select id="search_code" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach ($result as $key => $value) { ?>
              <option value="<?php echo $value['CTYPE']; ?>" data-id="<?php echo $value['ID']; ?>" data-desc="<?php echo $value['CDESC']; ?>">
                <?php echo $value['CTYPE']." | ".$value['CDESC']; ?>
              </option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="show_ajax_datas"></span>
<div class="row text-center sub_loader" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status"></div>
      </div>
    </div>
  </div>
</div>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <img src="../../img/nodata.png" class="img-fluid"> 
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$('.sub_loader').hide();
$(document).ready(function() {
  $("#search_code").change(function(){
    var code = $(this).val();
    var desc = $("#search_code option:selected").attr('data-desc');
    var id = $("#search_code option:selected").attr('data-id');
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
      }
    });
  });
});

$(document).ready(function() {
  $("#add_code").click(function(){
    var add_code = $("#code").val();
    var desc = $("#desc").val();
    if(add_code == ""){
      Swal.fire("Code Required");
    }else if(desc == ""){
      Swal.fire("Description Required");
    }else{
      $.ajax({
        url:'modules/system/api_main',
        type:'POST',
        data:{add_code:add_code,desc:desc},
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
            url: 'modules/system/ajax/ajax_codes_list?code='+add_code+'&desc='+desc+'&id='+response,
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
  $("#code").blur(function(){
    $.ajax({
      url:'modules/system/api_main',
      type:'POST',
      data:{
        check_code: $(this).val()
      },
      beforeSend: function() {    
        Swal.fire({
          title: 'CHECKING',
          allowEscapeKey: false,
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function(response){
        Swal.close();
        if(response.trim() == '1'){
          Swal.fire("CODE EXIST").then(function() {
            $("#code").val("");
          });
        }
      },
    });
  });
});
</script>