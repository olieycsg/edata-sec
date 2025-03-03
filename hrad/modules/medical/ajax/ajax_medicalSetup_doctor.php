<?php 

session_start();
include('../../../../api.php');

$sql = "SELECT * FROM sys_general_dcmisc WHERE CCLASS IN ('PANEL', 'SPECIALIST') ORDER BY ID DESC";
$result = $conn->query($sql);

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <table class="table table-bordered table-sm">
          <tbody>
            <tr>
              <td colspan="2"></td>
              <td style="text-align: center;">
                <i class="fas fa-circle-plus text-primary sec-tooltip pointer" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addView" data-mdb-placement="top" title="Add New"></i>
              </td>
            </tr>
            <?php foreach ($result as $key => $row) { ?>
            <tr>
              <td><?php echo $row['CCODE']; ?></td>
              <td><?php echo $row['CDESC']; ?></td>
              <td width="10%" class="pointer" style="text-align: center;">
                <i class="fas fa-pen-to-square sec-tooltip text-primary update" data-mdb-ripple-init data-mdb-modal-init data-mdb-target=".edit_setup" data-id="<?php echo $row['ID']; ?>" data-code="<?php echo $row['CCODE']; ?>" data-desc="<?php echo $row['CDESC']; ?>" data-label="<?php echo $row['CLABEL']; ?>" data-clinic="<?php echo $row['CCLASS']; ?>" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Update"></i>
                <?php if($_SESSION['sid'] == '2522-186'){ ?>
                <i class="far fa-trash-can sec-tooltip text-danger delete" data-id="<?php echo $row['ID']; ?>" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Delete"></i>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="modal fade" id="addView">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <b><i class="fas fa-circle-plus"></i> New Panel Doctor</b>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="panelDoctor">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-4" style="padding: 10px;">
                      <div class="form-outline" data-mdb-input-init>
                        <input name="setupnewDoctor" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                        <label class="form-label text-primary">
                          <b>Code</b>
                        </label>
                      </div>
                    </div>
                    <div class="col-md-4" style="padding: 10px;">
                      <div class="form-outline" data-mdb-input-init>
                        <input name="label" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                        <label class="form-label text-primary">
                          <b>Label</b>
                        </label>
                      </div>
                    </div>
                    <div class="col-md-4" style="padding: 10px;">
                      <select name="clinic" class="sec-select" data-mdb-select-init>
                        <option value="">- Select -</option>
                        <option value="PANEL" data-mdb-icon="../img/icon.png">PANEL</option>
                        <option value="NON-PANEL" data-mdb-icon="../img/icon.png">NON-PANEL</option>
                        <option value="SPECIALIST" data-mdb-icon="../img/icon.png">SPECIALIST</option>
                      </select>
                      <label class="form-label select-label text-primary active">
                        <b>Clinic Type</b>
                      </label>
                    </div>
                    <div class="col-md-12" style="padding: 10px;">
                      <div class="form-outline" data-mdb-input-init>
                        <input name="desc" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                        <label class="form-label text-primary">
                          <b>Description</b>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <div class="modal-footer">
                <button id="save" class="btn btn-primary" data-mdb-ripple-init>
                  <b><i class="fas fa-floppy-disk"></i> SAVE</b>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade edit_setup" data-mdb-backdrop="static">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <b><i class="fas fa-caret-right"></i> Edit Record</b>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
              </div>
              <form id="epanelDoctor">
                <div class="modal-body">
                  <div class="row">
                    <input name="eid" hidden>
                    <div class="col-md-4" style="padding: 10px;">
                      <div class="form-outline" data-mdb-input-init>
                        <input name="esetupnewDoctor" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                        <label class="form-label text-primary">
                          <b>Code</b>
                        </label>
                      </div>
                    </div>
                    <div class="col-md-4" style="padding: 10px;">
                      <div class="form-outline" data-mdb-input-init>
                        <input name="elabel" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                        <label class="form-label text-primary">
                          <b>Label</b>
                        </label>
                      </div>
                    </div>
                    <div class="col-md-4" style="padding: 10px;">
                      <select name="eclinic" class="sec-select" data-mdb-select-init>
                        <option value="">- Select -</option>
                        <option value="PANEL" data-mdb-icon="../img/icon.png">PANEL</option>
                        <option value="NON-PANEL" data-mdb-icon="../img/icon.png">NON-PANEL</option>
                        <option value="SPECIALIST" data-mdb-icon="../img/icon.png">SPECIALIST</option>
                      </select>
                      <label class="form-label select-label text-primary active">
                        <b>Clinic Type</b>
                      </label>
                    </div>
                    <div class="col-md-12" style="padding: 10px;">
                      <div class="form-outline" data-mdb-input-init>
                        <input name="edesc" class="form-control active" placeholder="..." oninput="this.value = this.value.toUpperCase()">
                        <label class="form-label text-primary">
                          <b>Description</b>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <div class="modal-footer">
                <button id="update" class="btn btn-primary">
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
  $(document).ready(function(){
    $("#save").click(function(e){
      e.preventDefault();
      var formData = $("#panelDoctor").serialize();
      var setup = 1;
      var isValid = true;

      $("[name='setupnewDoctor'], [name='label'], [name='desc'], [name='clinic']").each(function () {
        let isEmpty = $(this).val() === null || $(this).val() === "";
        $(this).toggleClass("is-invalid", isEmpty);
        isValid = isValid && !isEmpty;
      });

      if (!isValid) {
        e.preventDefault();
      }else{
        $.ajax({
          url: "modules/medical/ajax/api_ajax",
          type: "POST",
          data: formData,
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
          success: function(response) {
            Swal.close();
            $.ajax({
              url: "modules/medical/ajax/ajax_medicalSetup_doctor",
              type: "POST",
              data: {
                type: setup
              },
              beforeSend: function() {    
                $(".main_loader").show();
                $(".no_data_ajax").hide();
              },
              success: function(response) {
                Swal.close();
                sec_remove();
                $(".main_loader").hide();
                $("#show_medicalSetup").html(response).show();
              },
            });
          },
        });
      }
    });
  });

  $(document).ready(function(){
    $(".delete").click(function(){
      var setup = 1;
      Swal.fire({
        title: 'ARE YOU SURE?',
        text: "YOU WON'T BE ABLE TO REVERT THIS",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00B74A',
        cancelButtonColor: '#d33',
        confirmButtonText: 'YES, PROCEED'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "modules/medical/ajax/api_ajax",
            type: "POST",
            data: {
              setupdeleteDoctor: $(this).attr('data-id')
            },
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
                url: "modules/medical/ajax/ajax_medicalSetup_doctor",
                type: "POST",
                data: {
                  type: setup
                },
                beforeSend: function() {    
                  $(".main_loader").show();
                  $(".no_data_ajax").hide();
                },
                success: function(response) {
                  Swal.close();
                  $(".main_loader").hide();
                  $("#show_medicalSetup").html(response).show();
                },
              });
            },
          });
        }
      });
    });
  });

  $(document).ready(function() {
    $(".update").click(function(){
      var target = $(this).data('mdb-target');
      $(target).modal('show');

      var id = $(this).data('id');
      var code = $(this).data('code');
      var desc = $(this).data('desc');
      var label = $(this).data('label');
      var clinic = $(this).data('clinic');

      $("[name='esetupnewDoctor']").val(code);
      $("[name='edesc']").val(desc);
      $("[name='elabel']").val(label);
      $("[name='eclinic'] option[value='"+clinic+"']").attr('selected', 'selected');
      $("#eid").val(id);

    });
  });

  $(document).ready(function() {
    $('[data-mdb-dismiss="modal"]').click(function() {
      $("[name='esetupnewDoctor']").val('');
      $("[name='edesc']").val('');
      $("[name='elabel']").val('');
      $("[name='eclinic'] option").removeAttr('selected');
      $("#eid").val('');
    });
  });

  $(document).ready(function(){
    $("#update").click(function(e){
      e.preventDefault();
      var formData = $("#epanelDoctor").serialize();
      var setup = 1;
      var isValid = true;

      $("[name='esetupnewDoctor'], [name='elabel'], [name='edesc'], [name='eclinic']").each(function () {
        let isEmpty = $(this).val() === null || $(this).val() === "";
        $(this).toggleClass("is-invalid", isEmpty);
        isValid = isValid && !isEmpty;
      });

      if (!isValid) {
        e.preventDefault();
      }else{
        $.ajax({
          url: "modules/medical/ajax/api_ajax",
          type: "POST",
          data: formData,
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
          success: function(response) {
            Swal.close();
            $.ajax({
              url: "modules/medical/ajax/ajax_medicalSetup_doctor",
              type: "POST",
              data: {
                type: setup
              },
              beforeSend: function() {    
                $(".main_loader").show();
                $(".no_data_ajax").hide();
              },
              success: function(response) {
                Swal.close();
                sec_remove();
                $(".main_loader").hide();
                $("#show_medicalSetup").html(response).show();
              },
            });
          },
        });
      }
    });
  });

  /*$(document).ready(function(){
    $(".approve").click(function(){
      Swal.fire({
        title: 'ARE YOU SURE?',
        text: "YOU WON'T BE ABLE TO REVERT THIS",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00B74A',
        cancelButtonColor: '#d33',
        confirmButtonText: 'YES, PROCEED'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "modules/medical/ajax/api_ajax",
            type: "POST",
            data: {
              approveMedical: $(this).attr('data-id')
            },
            success: function(response) {
              $.ajax({
                url: "modules/medical/ajax/ajax_medicalApps_table",
                type: "POST",
                data: {
                  emid: '<?php echo $emid; ?>',
                  year: $("#mYear").val()
                },
                beforeSend: function() {    
                  Swal.fire({
                    title: 'LOADING',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                      Swal.showLoading();
                    },
                  });
                },
                success: function(response) {
                  Swal.close();
                  $("#show_mTable").html(response);
                },
              });
            },
          });
        }
      });
    });
  });

  $(document).ready(function(){
    $(".delete").click(function(){
      Swal.fire({
        title: 'ARE YOU SURE?',
        text: "YOU WON'T BE ABLE TO REVERT THIS",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00B74A',
        cancelButtonColor: '#d33',
        confirmButtonText: 'YES, PROCEED'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "modules/medical/ajax/api_ajax",
            type: "POST",
            data: {
              deleteMedical: $(this).attr('data-id')
            },
            beforeSend: function() {    
              Swal.fire({
                title: 'LOADING',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
            },
            success: function(response) {
              $.ajax({
                url: "modules/medical/ajax/ajax_medicalApps_table",
                type: "POST",
                data: {
                  emid: '<?php echo $emid; ?>',
                  year: $("#mYear").val()
                },
                beforeSend: function() {    
                  Swal.fire({
                    title: 'LOADING',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                      Swal.showLoading();
                    },
                  });
                },
                success: function(response) {
                  Swal.close();
                  $("#show_mTable").html(response);
                },
              });
            },
          });
        }
      });
    });
  });

  $(document).ready(function(){
    $(".update").click(function(){
      $.ajax({
        url: "modules/medical/ajax/ajax_medicalApps_update",
        type: "POST",
        data: {
          emid: '<?php echo $emid; ?>',
          refd: $(this).attr('data-id')
        },
        beforeSend: function() {    
          $(".main_loader").show();
          $(".no_data_ajax").hide();
        },
        success: function(response) {
          Swal.close();
          const options = document.getElementById('mType').options;
          document.querySelector('#mType option[value="1"]').setAttribute('selected', 'selected');
          document.querySelector('#mType option[value="2"]').removeAttribute('selected');
          document.querySelectorAll('.sec-tooltip').forEach((tooltipEl) => {
            const tooltipInstance = mdb.Tooltip.getInstance(tooltipEl);
            if (tooltipInstance) {
              tooltipInstance.dispose();
            }
          });

          $(".main_loader").hide();
          $("#show_medicalApps").html(response).show();
        },
      });
    });
  });*/
</script>