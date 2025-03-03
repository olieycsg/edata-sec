<?php

session_start();
include('../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM sys_general_dcmisc";
$result1 = $conn->query($sql1);

if($row = $result->fetch_assoc()){

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
        <p class="note note-success navbar">
          <strong>Spouse</strong>
        </p>
        <div class="row">
          <div class="col-md-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSNAME" class="form-control active" value="<?php echo $row['CSPSNAME']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Name</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="DMARRIED" type="date" class="form-control active" value="<?php if($row['DMARRIED'] != '0000-00-00'){ echo date("Y-m-d", strtotime($row['DMARRIED'])); } ?>">
              <label class="form-label text-primary">
                <b>Married On</b>
              </label>
            </div>
          </div>
          <div class="col-md-3" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="number" id="INOCHILD" class="form-control active" value="<?php echo $row['INOCHILD']; ?>" placeholder="e.g. 1">
              <label class="form-label text-primary">
                <b>Children</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="INOCHILDTA" class="form-control active" value="<?php echo $row['INOCHILDTA']; ?>" placeholder="...">
              <label class="form-label text-primary">
                <b>No Tax</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="number" id="CSPSICNEW" class="form-control active" value="<?php echo $row['CSPSICNEW']; ?>" placeholder="e.g 123456121234" oninput="javascript: if (this.value.length > 12) this.value = this.value.slice(0, 12);">
              <label class="form-label text-primary">
                <b>New NRIC</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSICOLD" class="form-control active" value="<?php echo $row['CSPSICOLD']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Old NRIC</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSICCOL" class="form-control active" value="<?php echo $row['CSPSICCOL']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>NRIC Color</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSNOEPF" class="form-control active" value="<?php echo $row['CSPSNOEPF']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>EPF</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSNOTAX" class="form-control active" value="<?php echo $row['CSPSNOTAX']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Tax</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSBRNTAX" class="form-control active" value="<?php echo $row['CSPSBRNTAX']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Tax Branch</b>
              </label>
            </div>
          </div>
          <div class="col-md-5" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSOCCUP" class="form-control active" value="<?php echo $row['CSPSOCCUP']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Occupation</b>
              </label>
            </div>
          </div>
          <div class="col-md-5" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CSPSEMPER" class="form-control active" value="<?php echo $row['CSPSEMPER']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Employer</b>
              </label>
            </div>
          </div>
        </div>
        <br>
        <p class="note note-success navbar">
          <strong>Next of KIN</strong>
        </p>
      	<div class="row">
          <div class="col-md-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CKINNAME" class="form-control active" value="<?php echo $row['CKINNAME']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Name</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <select id="CKINRELATN" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">
              <option value="" disabled selected>- Select -</option>
              <?php foreach($result1 as $row1){ if($row1['CTYPE'] == 'RELAT'){ ?>
              <option value="<?php echo $row1['CCODE'] ?>" data-mdb-icon="../img/icon.png" <?php if($row['CKINRELATN'] == $row1['CCODE']){ echo "selected"; } ?>>
                <?php echo $row1['CDESC']; ?>
              </option>
              <?php } } ?>
            </select>
            <label class="form-label select-label text-primary">
              <b>Relation</b>
            </label>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="number" id="CKINPHONEH" class="form-control active" value="<?php echo $row['CKINPHONEH']; ?>" placeholder="e.g. 012xxxxxxx" oninput="javascript: if (this.value.length > 11) this.value = this.value.slice(0, 11);">
              <label class="form-label text-primary">
                <b>Mobile</b>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input type="number" id="CKINPHONEO" class="form-control active" value="<?php echo $row['CKINPHONEO']; ?>" placeholder="e.g. 088xxxxxx" oninput="javascript: if (this.value.length > 9) this.value = this.value.slice(0, 9);">
              <label class="form-label text-primary">
                <b>Office</b>
              </label>
            </div>
          </div>
          <div class="col-md-10" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CKINOCCUP" class="form-control active" value="<?php echo $row['CKINOCCUP']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Occupation</b>
              </label>
            </div>
          </div>
          <div class="col-md-12" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <input id="CKINADDRS" class="form-control active" value="<?php echo $row['CKINADDRS1']; ?>" placeholder="..." oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary">
                <b>Address</b>
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-right">
            <button id="save" class="btn btn-primary zoom pointer">
              <b><i class="fas fa-save"></i> SAVE</b>
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
        edit_spouse: '<?php echo $code; ?>',
        CSPSNAME: $("#CSPSNAME").val(),
        DMARRIED: $("#DMARRIED").val(),
        INOCHILD: $("#INOCHILD").val(),
        INOCHILDTA: $("#INOCHILDTA").val(),
        CSPSICNEW: $("#CSPSICNEW").val(),
        CSPSICOLD: $("#CSPSICOLD").val(),
        CSPSICCOL: $("#CSPSICCOL").val(),
        CSPSNOEPF: $("#CSPSNOEPF").val(),
        CSPSNOTAX: $("#CSPSNOTAX").val(),
        CSPSBRNTAX: $("#CSPSBRNTAX").val(),
        CSPSOCCUP: $("#CSPSOCCUP").val(),
        CSPSEMPER: $("#CSPSEMPER").val(),
        CKINNAME: $("#CKINNAME").val(),
        CKINRELATN: $("#CKINRELATN").val(),
        CKINPHONEH: $("#CKINPHONEH").val(),
        CKINPHONEO: $("#CKINPHONEO").val(),
        CKINOCCUP: $("#CKINOCCUP").val(),
        CKINADDRS: $("#CKINADDRS").val()
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
          url: 'modules/employees/spouse?code=<?php echo $code; ?>',
          beforeSend: function() {    
            $('.sub_loader').show();
            $('.no_data').hide();
            $('#show_get_data').hide();
          },
          success: function(data) {
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