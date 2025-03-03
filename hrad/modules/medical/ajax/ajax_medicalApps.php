<?php 

include('../../../../api.php');

$emid = $_POST['emid'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

  $divi = $row['CDIVISION'];

  $sql1 = "SELECT * FROM medical_fxmedcht ORDER BY CMCCHIT DESC";
  $result1 = $conn->query($sql1);

  $sql2 = "SELECT * FROM sys_general_dcmisc WHERE CTYPE = 'DIVSN' AND CCODE = '$divi'";
  $result2 = $conn->query($sql2);

  $sql3 = "SELECT * FROM employee_dfamily WHERE CNOEE = '$emid'";
  $result3 = $conn->query($sql3);

  if($row1 = $result1->fetch_assoc()){
    $id = $row1['CMCCHIT'] + 1;
  }

  if($row2 = $result2->fetch_assoc()){
    $divname = $row2['CDESC'];
  }

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
      <div class="card-body">
        <form id="medicalForm">
          <div class="row">
            <div class="col-4">
              <div class="form-outline" data-mdb-input-init>
                <input type="text" id="refid" class="form-control active not-allowed" value="<?php echo $id; ?>" disabled>
                <label class="form-label text-primary active">
                  <b>Reference No</b>
                </label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-outline" data-mdb-input-init>
                <input type="text" class="form-control active not-allowed" value="<?php echo $divname; ?>" disabled>
                <label class="form-label text-primary active">
                  <b>Division</b>
                </label>
              </div>
            </div>
            <div class="col-4">
              <div class="form-outline" data-mdb-input-init>
                <input type="text" id="empid" class="form-control active not-allowed" value="<?php echo $row['CNOEE']; ?>" disabled>
                <label class="form-label text-primary active">
                  <b>Employee ID</b>
                </label>
              </div>
            </div>
            <div class="col-4" style="margin-top: 15px;">
              <div class="form-outline" data-mdb-input-init>
                <input type="text" class="form-control active not-allowed" value="<?php echo $row['CNOICNEW']; ?>" disabled>
                <label class="form-label text-primary active">
                  <b>National ID</b>
                </label>
              </div>
            </div>
            <div class="col-4" style="margin-top: 15px;">
              <div class="form-outline" data-mdb-input-init>
                <input type="text" class="form-control active not-allowed" value="<?php echo strtoupper(date("d F Y")); ?>" disabled>
                <label class="form-label text-primary active">
                  <b>Date</b>
                </label>
              </div>
            </div>
            <div class="col-4" style="margin-top: 15px;">
              <div class="form-outline" data-mdb-input-init>
                <select id="clinicType" class="sec-select">
                  <option value="">- Type -</option>
                  <option value="PANEL" data-mdb-icon="../img/icon.png">PANEL</option>
                  <option value="SPECIALIST" data-mdb-icon="../img/icon.png">SPECIALIST</option>
                </select>
              </div>
            </div>
          </div>
          <hr class="hr hr-blurry">
          <div class="row">
            <div class="col-6">
              <div class="form-outline" data-mdb-input-init>
                <select id="clinic" class="sec-select">
                  <option value="">- Clinic / Hospital -</option>
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-outline" data-mdb-input-init>
                <select id="spouse" class="sec-select">
                  <option value="">- Spouse -</option>
                  <?php foreach ($result3 as $key3 => $row3) { if($row3['CRELATION'] == 'SPO'){ ?>
                  <option value="<?php echo $row3['CNAME']; ?>" data-mdb-icon="../img/icon.png"><?php echo $row3['CNAME']; ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <?php for ($i = 1; $i < 9; $i++) { ?>
            <div class="col-6" style="margin-top: 15px;">
              <div class="form-outline" data-mdb-input-init>
                <select id="dependant<?php echo $i; ?>" class="sec-select">
                  <option value="">- Dependant <?php echo $i; ?> -</option>
                  <?php foreach ($result3 as $key3 => $row3) { if ($row3['CRELATION'] == 'SON' || $row3['CRELATION'] == 'DAU') { ?>
                  <option value="<?php echo $row3['CNAME']; ?>" data-mdb-icon="../img/icon.png"><?php echo $row3['CNAME']; ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <?php } ?>
          </div>
        </form>
      </div>
      <div class="card-footer text-right">
        <button id="submit" class="btn btn-primary">
          <b><i class="fas fa-floppy-disk"></i> SUBMIT</b>
        </button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  sec_function();
  $(document).ready(function(){
    $("#clinicType").change(function(){
      $.ajax({
        url: "modules/medical/ajax/api_ajax",
        type: "POST",
        data: {
          searchClinic: $(this).val()
        },
        success: function(response) {
          $("#clinic").html(response);
        },
      });
    });
  });

  $(document).ready(function(){
    $("#submit").click(function(){
      if($("#clinic").val() == ''){
        Swal.fire("Type Required");
      }else{
        let dependants = [];
        for (let i = 1; i < 9; i++) {
          let val = $("#dependant" + i).val();
          dependants.push(val ? val : "");
        }
        $.ajax({
          url: "modules/medical/ajax/api_ajax",
          type: "POST",
          data: {
            addMedical: $("#refid").val(),
            empid: $("#empid").val(),
            clinic: $("#clinic").val(),
            spouse: $("#spouse").val(),
            dependants: dependants
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
          success: function(response) {
            Swal.close();
            $.ajax({
              url: "modules/medical/ajax/ajax_medicalApps_record",
              type: "POST",
              data: {
                emid: '<?php echo $emid; ?>',
              },
              beforeSend: function() {    
                $(".main_loader").show();
                $(".no_data_ajax").hide();
              },
              success: function(response) {
                Swal.close();
                const options = document.getElementById('mType').options;
                document.querySelector('#mType option[value="1"]').removeAttribute('selected');
                document.querySelector('#mType option[value="2"]').setAttribute('selected', 'selected');
                $(".main_loader").hide();
                $("#show_medicalApps").html(response).show();
              },
            });
          },
        });
      }
    });
  });
</script>
<?php } ?>