<?php 

include('../../../../api.php');

$emid = $_POST['emid'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="form-outline" data-mdb-input-init style="text-color: white!important">
          <select id="mYear" class="sec-select" data-mdb-select-init data-mdb-visible-options="10">
            <?php for ($i = date("Y"); $i >= date("Y", strtotime($row['DHIRE'])) ; $i--) { ?>
            <option value="<?php echo $i; ?>" data-mdb-icon="../img/icon.png"><?php echo $i; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div id="show_mTable"></div>
  </div>
</div>
<script type="text/javascript">
  sec_function();
  $(document).ready(function(){
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
  });

  $(document).ready(function(){
    $("#mYear").change(function(){
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
    });
  });
</script>
<?php } ?>