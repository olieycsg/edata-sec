<?php 

session_start();
include('../../../../api.php');

$emid = $_POST['emid'];
$year = $_POST['year'];

$sql = "SELECT * FROM medical_fxmedcht WHERE CNOEE = '$emid' AND DATE_FORMAT(DCHIT, '%Y') = '$year' ORDER BY DCHIT DESC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc WHERE CCLASS = 'PANEL' OR CCLASS = 'SPECIALIST'";
$result1 = $conn->query($sql1);

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <?php if($result->num_rows > 0) { ?>
        <table class="table table-bordered">
          <tbody>
            <?php foreach ($result as $key => $row) { ?>
            <tr>
              <td><?php echo strtoupper(date("d M Y", strtotime($row['DCHIT']))); ?></td>
              <td>
                <a href="modules/medical/print_medicalChit?emid=<?php echo $emid; ?>&refd=<?php echo $row['ID']; ?>" target="_blank">
                  <i class="fas fa-file-pdf zoom text-danger sec-tooltip" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Print Medical Chit"></i>
                </a>
                <?php echo $row['CMCCHIT']; ?>
              </td>
              <td>
                <?php 
                foreach ($result1 as $key1 => $row1) {
                  if($row1['CCODE'] == $row['CCLINIC']){
                    echo $row1['CDESC'];
                  } 
                }
                ?>
              </td>
              <td class="<?php echo ($row['CSTATUS'] == 'CANCELLED' ? 'text-danger' : 'text-success') ?>" style="text-align: center;">
                <b><?php echo $row['CSTATUS']; ?></b>
              </td>
              <td width="10%" class="pointer" style="text-align: center;">
                <?php if($row['CSTATUS'] == 'CANCELLED'){ ?>
                <i class="far fa-circle-check sec-tooltip text-success approve" data-id="<?php echo $row['ID']; ?>" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Approve Medical Chit"></i>
                <?php }else{ ?>
                <i class="far fa-circle-xmark sec-tooltip text-danger cancel" data-id="<?php echo $row['ID']; ?>" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Cancel Medical Chit"></i>
                <?php } ?>
                <i class="fas fa-pen-to-square sec-tooltip text-primary update" data-id="<?php echo $row['ID']; ?>" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Update Medical Chit"></i>
                <?php if($_SESSION['sid'] == '2522-186'){ ?>
                <i class="far fa-trash-can sec-tooltip text-danger delete" data-id="<?php echo $row['ID']; ?>" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="Delete Medical Chit"></i>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php }else{ ?>
        <b class="text-danger" style="font-style: italic;">NO DATA FOUND</b>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  sec_function();
  $(document).ready(function(){
    $(".cancel").click(function(){
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
              cancelMedical: $(this).attr('data-id')
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
  });
</script>