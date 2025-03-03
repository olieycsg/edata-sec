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
      <div class="card-header text-white bg-primary">
        <div class="row">
          <div class="col-11">
            <b><i class="fas fa-caret-right"></i> New Employee</b>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-circle-info trailing text-primary"></i>
              <input id="emid" class="form-control active" placeholder="e.g 1234-123">
              <label class="form-label text-primary" for="emid">
                <b>Employee ID</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-envelope trailing text-primary"></i>
              <input id="emel" class="form-control active" placeholder="e.g oliey@sabahenergycorp.com">
              <label class="form-label text-primary" for="emel">
                <b>Email</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-user-tie trailing text-primary"></i>
              <input id="user" class="form-control active" placeholder="e.g oliey">
              <label class="form-label text-primary" for="user">
                <b>Username</b>
              </label>
            </div>
          </div>
          <div class="col-md-8" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-circle-info trailing text-primary"></i>
              <input id="name" class="form-control active" placeholder="e.g OLIVIANUS PIUS" oninput="this.value = this.value.toUpperCase()">
              <label class="form-label text-primary" for="name">
                <b>Employee Name</b>
              </label>
            </div>
          </div>
          <div class="col-md-4" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-lock trailing text-danger"></i>
              <input id="pass" class="form-control" placeholder="New Password" disabled>
            </div>
          </div>
          <div class="col-md-12 text-right" style="padding: 10px;">
            <button id="generate" class="btn btn-success" data-mdb-ripple-init>
              <b><i class="fas fa-repeat"></i> Generate</b>
            </button>
            <button id="add_profile" class="btn btn-primary" data-mdb-ripple-init>
              <b><i class="fas fa-floppy-disk"></i> REGISTER</b>
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
  $("#emid").change(function(){
    var emid = $(this).val();
    $.ajax({
      url:'modules/employees/api_main',
      type:'POST',
      data:{check_cnoee:emid},
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
      success: function(response) {
        Swal.close();
        if (response.trim() === '1') {
          Swal.fire("ID EXIST");
          $("#emid").val('');
        }
      }
    });
  });
});

$(document).ready(function() {
  $("#add_profile").click(function(){
    var emid = $("#emid").val();
    var emel = $("#emel").val();
    var user = $("#user").val();
    var name = $("#name").val();
    var pass = $("#pass").val();
    if(emid == ""){
      Swal.fire("Employee ID Required");
    }else if(emel == ""){
      Swal.fire("Email Required");
    }else if(user == ""){
      Swal.fire("Username Required");
    }else if(name == ""){
      Swal.fire("Employee Name Required");
    }else if(pass == ""){
      Swal.fire("Password Required");
    }else{
      $.ajax({
        url:'modules/employees/api_main',
        type:'POST',
        data:{add_profile:emid,emel:emel,user:user,name:name,pass:pass},
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
          Swal.fire({
            title: "Employee Registered"
          }).then((result) => {
            $.ajax({
              url: 'modules/employees/main?code='+emid,
              beforeSend: function() {    
                $('.loader').show();
                $('.no_data').hide();
                $('#show_data').hide();
              },
              success: function(data) {
                $('.loader').hide();
                $('.no_data').hide();
                $("#show_data").html(data).show();
              }
            });
          });
        },
      });
    }
  });
});

$(document).ready(function() {
  $("#generate").click(function(){
    function generatePassword(length) {
      var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
      var password = "";
      for (var i = 0; i < length; i++) {
        var randomIndex = Math.floor(Math.random() * charset.length);
        password += charset[randomIndex];
      }
      return password;
    }
    
    let timerInterval;
    Swal.fire({
      title: "Generating New Password",
      timer: 2000,
      timerProgressBar: true,
      didOpen: () => {
        Swal.showLoading();
      },
      willClose: () => {
        clearInterval(timerInterval);
      }
    }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {
        var password = generatePassword(10);
        $('#pass').val(password);
      }
    });
  });
});
</script>