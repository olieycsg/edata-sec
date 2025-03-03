<?php 

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

if($row = $result->fetch_assoc()){

$titles = ["DATO", "DATUK", "DATIN", "MADAM", "TUAN", "PUAN", "ENCIK", "CIK", "MR", "MS"];
$maritals = ['M' => 'MARRIED', 'S' => 'SINGLE', 'D' => 'DIVORCED', 'W' => 'WIDOW/WIDOWER'];

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
  .pointer{
    cursor: cursor;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOEE" class="form-control active" value="<?php echo $row['CNOEE']; ?>" disabled>
              <label class="form-label text-primary">
                <b>Employee ID</b>
              </label>
            </div>
          </div>
          <div class="col-md-9" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNAME" class="form-control active" value="<?php echo $row['CNAME']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Name</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CBADGE" class="form-control active" value="<?php echo $row['CBADGE']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Badge</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CCDACCESS" class="form-control active" value="<?php echo $row['CCDACCESS']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Access</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CALIAS" class="form-control active" value="<?php echo $row['CALIAS']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Alias</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CTITLE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach ($titles as $title){ ?>
              <option value="<?php echo $title; ?>" data-mdb-icon="../img/icon.png" <?php echo ($row['CTITLE'] == $title) ? 'selected' : '' ?>>
                <?php echo $title; ?>
              </option>
              <?php } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Salutation</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CCOUNTRY" class="sec-select" data-mdb-select-init>
              <option value="" disabled selected>- Select -</option>
              <option value="MAL" data-mdb-icon="../img/icon.png" <?php if($row['CCOUNTRY'] == "MAL"){ echo "selected"; } ?>>MALAYSIAN</option>
            </select>
            <label class="form-label select-label text-primary">
              <b>Nationality</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="number" id="CNOICNEW" class="form-control active" value="<?php echo $row['CNOICNEW']; ?>" placeholder="e.g 123465121234" oninput="javascript: if (this.value.length > 12) this.value = this.value.slice(0, 12);">
              <label class="form-label text-primary">
                <b>New NRIC</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOICOLD" class="form-control active" value="<?php echo $row['CNOICOLD']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Old NRIC</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DBIRTH" type="date" class="form-control active" value="<?php if($row['DBIRTH'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DBIRTH'])); } ?>">
              <label class="form-label text-primary">
                <b>Birth Date</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CCOLORIC" class="form-control active" value="<?php echo $row['CCOLORIC']; ?>" placeholder="e.g. BL" oninput="javascript: if (this.value.length > 2) this.value = this.value.slice(0, 2); this.value = this.value.toUpperCase();">
              <label class="form-label text-primary">
                <b>NRIC Color</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>              
              <input id="CNOPASSPOR" class="form-control active" value="<?php echo $row['CNOPASSPOR']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Passport</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DXPASSPORT" type="date" class="form-control active" value="<?php if($row['DXPASSPORT'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DXPASSPORT'])); } ?>">
              <label class="form-label text-primary">
                <b>Passport Expiry</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CSEX" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <option value="M" data-mdb-icon="../img/icon.png" <?php if($row['CSEX'] == "M"){ echo "selected"; } ?>>MALE</option>
              <option value="F" data-mdb-icon="../img/icon.png" <?php if($row['CSEX'] == "F"){ echo "selected"; } ?>>FEMALE</option>
            </select>
            <label class="form-label select-label text-primary">
              <b>Gender</b>
            </label>
          </div>
          <div class="col-md-9" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CPLACEBIRT" class="form-control active" value="<?php echo $row['CPLACEBIRT']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Birth Place</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CRELIGION" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'RELIG'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CRELIGION'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Religion</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CRACE" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'RACE'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CRACE'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Race</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <select id="CSTMARTL" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach ($maritals as $maritalkey => $maritalvalue){ ?>
              <option value="<?php echo $maritalkey; ?>" data-mdb-icon="../img/icon.png" <?php echo ($row['CSTMARTL'] == $maritalkey) ? 'selected' : '' ?>>
                <?php echo $maritalvalue; ?>
              </option>
              <?php } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Marital Status</b>
            </label>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CBLOOD" class="form-control active" value="<?php echo $row['CBLOOD']; ?>" placeholder="e.g. B+" oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Blood</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="NHEIGHT" class="form-control active" type="number" value="<?php echo $row['NHEIGHT']; ?>" placeholder="e.g. 175" onchange="this.value = parseFloat(this.value).toFixed(1);" step="any">
              <label class="form-label text-primary">
                <b>Height (CM)</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="NWEIGHT" type="number" class="form-control active" value="<?php echo $row['NWEIGHT']; ?>" placeholder="e.g. 55.8" onchange="this.value = parseFloat(this.value).toFixed(1);" step="any">
              <label class="form-label text-primary">
                <b>Weight (KG)</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="number" id="CCDPOST" class="form-control active" value="<?php echo $row['CCDPOST']; ?>" placeholder="e.g. 88450"  oninput="javascript: if (this.value.length > 5) this.value = this.value.slice(0, 5);">
              <label class="form-label text-primary">
                <b>Postcode</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOPHONE" class="form-control active" value="<?php echo $row['CNOPHONE']; ?>" placeholder="e.g. 088xxxxxx" oninput="javascript: if (this.value.length > 9) this.value = this.value.slice(0, 9);">
              <label class="form-label text-primary">
                <b>Phone</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CNOPHONEH" class="form-control active" value="<?php echo $row['CNOPHONEH']; ?>" placeholder="e.g. 012xxxxxxx" oninput="javascript: if (this.value.length > 11) this.value = this.value.slice(0, 11);">
              <label class="form-label text-primary">
                <b>Handphone</b>
              </label>
            </div>
          </div>
          <div class="col-md-12" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CADDRS" class="form-control active" value="<?php echo $row['CADDRS1']; ?> <?php echo $row['CADDRS2']; ?> <?php echo $row['CADDRS3']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Address</b>
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-right" style="padding: 10px;">
            <button id="save" class="btn btn-primary" data-mdb-ripple-init>
              <b><i class="fas fa-floppy-disk"></i> Save</b>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $("#save").click(function() {
    $.ajax({
      url: "modules/employees/api_main",
      type: "POST",
      data: {
        edit_personal: $("#CNOEE").val(),
        CNAME: $("#CNAME").val(),
        CBADGE: $("#CBADGE").val(),
        CCDACCESS: $("#CCDACCESS").val(),
        CALIAS: $("#CALIAS").val(),
        CTITLE: $("#CTITLE").val(),
        CCOUNTRY: $("#CCOUNTRY").val(),
        CNOICNEW: $("#CNOICNEW").val(),
        CNOICOLD: $("#CNOICOLD").val(),
        DBIRTH: $("#DBIRTH").val(),
        CCOLORIC: $("#CCOLORIC").val(),
        CNOPASSPOR: $("#CNOPASSPOR").val(),
        DXPASSPORT: $("#DXPASSPORT").val(),
        CSEX: $("#CSEX").val(),
        CPLACEBIRT: $("#CPLACEBIRT").val(),
        CRELIGION: $("#CRELIGION").val(),
        CRACE: $("#CRACE").val(),
        CSTMARTL: $("#CSTMARTL").val(),
        CBLOOD: $("#CBLOOD").val(),
        NHEIGHT: $("#NHEIGHT").val(),
        NWEIGHT: $("#NWEIGHT").val(),
        CCDPOST: $("#CCDPOST").val(),
        CNOPHONE: $("#CNOPHONE").val(),
        CNOPHONEH: $("#CNOPHONEH").val(),
        CADDRS: $("#CADDRS").val()
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
          url: 'modules/employees/personal?code=<?php echo $code; ?>',
          beforeSend: function() {    
            $('.sub_loader').show();
            $('.no_data').hide();
            $('#show_get_data').hide();
          },
          success: function(data) {
            $.ajax({
              url: 'modules/employees/api_ajax',
              type: 'POST',
              dataType: 'html',
              data: { actv_employee: '<?php echo $code; ?>'},
              success: function(response) {
                $('#show_employee').html(response);
              }
            });
            $('.sub_loader').hide();
            $('.no_data').hide();
            $("#show_get_data").html(data).show();
          }
        });
      },
    });
  });
});
</script>
<?php } ?>