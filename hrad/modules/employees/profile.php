<?php

session_start();
include('../../../api.php'); 
$code = $_GET['code'];
$emel = $_SESSION['email'];

$sql = "SELECT * FROM employees WHERE EmployeeID = '$code'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

  $sqla = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
  $resulta = $conn->query($sqla);

  if($rowa = $resulta->fetch_assoc()){
    $image = $rowa['CIMAGE'];
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
  .pointer{
    cursor: cursor;
  }
</style>
<div class="row" style="margin-top: 20px;">
  <div class="col-md-3" style="margin-bottom: 20px;">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row text-center">
          <div class="col-md-12">
            <?php if($image != ''){ ?>
            <img src="modules/employees/file/<?php echo $image; ?>" class="rounded-circle img-thumbnail" style="border: 5px solid #14A44D;">
            <?php }else{ ?>
            <img src="img/nouser.png" class="rounded-circle img-thumbnail" style="border: 5px solid #14A44D;">
            <?php } ?>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <div class="form-outline" data-mdb-input-init>
              <input type="file" class="form-control" id="profile">
            </div>
          </div>
          <div class="col-md-6 col-6" style="padding: 10px;">
            <button id="upload_profile" class="btn btn-success btn-block" data-mdb-ripple-init>
              <b><i class="fas fa-cloud-arrow-up"></i> Upload</b>
            </button>
          </div>
          <div class="col-md-6 col-6" style="padding: 10px;">
            <button id="clear_profile" class="btn btn-danger btn-block" data-mdb-ripple-init>
              <b><i class="fas fa-trash-can"></i> Delete</b>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-envelope trailing text-success"></i>
              <input id="emel" class="form-control" placeholder="e.g olivianus@sabahenergycorp.com" value="<?php echo $email = $row['EmailAddress']; ?>">
              <label class="form-label text-primary" for="emel">
                <b>Email</b>
              </label>
            </div>
          </div>
          <div class="col-md-6" style="padding: 10px;">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-user-tie trailing text-primary"></i>
              <input id="user" class="form-control" placeholder="e.g oliey" value="<?php echo $row['JabberAccount']; ?>">
              <label class="form-label text-primary" for="user">
                <b>Username</b>
              </label>
            </div>
          </div>
          <div class="col-md-12 text-right" style="padding: 10px;">
            <button id="edit_profile" class="btn btn-primary" data-mdb-ripple-init>
              <b><i class="fas fa-floppy-disk"></i> Update</b>
            </button>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-lock trailing text-danger"></i>
              <input id="pass" class="form-control" placeholder="Password Reset" disabled>
            </div>
          </div>
          <div class="col-md-12 text-right" style="padding: 10px;">
            <button id="generate" class="btn btn-success" data-mdb-ripple-init>
              <b><i class="fas fa-repeat"></i> Generate</b>
            </button>
            <button id="reset" class="btn btn-primary" data-mdb-ripple-init>
              <b><i class="fas fa-key"></i> Reset</b>
            </button>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="card-body">
      <p class="note note-danger">
        <strong>Image:</strong> Image Profile is set by default to resolution <b>600px by 600px</b>. Manual image resize <a href="https://imageresizer.com/" target="_blank" style="text-decoration: underline; font-style: italic;"><b>Click Here</b></a>
      </p>
    </div>
  </div>
</div>
<div class="row" style="margin-top: 10px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-danger">
        <div class="row">
          <div class="col-11">
            <b><i class="fas fa-caret-right"></i> Account Termination</b>
          </div>
          <div class="col-1 text-right">
            <i class="fas fa-envelope-open-text pointer" id="auth_generate"></i>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12" style="text-align: justify;">
            <b><i class="fas fa-triangle-exclamation text-danger"></i> Please be aware that upon termination of access, employee will no longer be able to log in to the HRIS or access any data or resources within the system. Final warning, all associated data will be permanently deleted and cannot be recovered.</b>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12">
            <div class="form-outline" data-mdb-input-init>
              <i class="fas fa-circle-check trailing text-danger"></i>
              <input type="text" id="auth" class="form-control active" placeholder="e.g ********">
              <label class="form-label text-primary" for="auth">
                <b>Authorization Code</b>
              </label>
            </div>
          </div>
          <div class="col-md-12 text-right" style="padding: 10px;">
            <button id="terminate" class="btn btn-danger" data-mdb-ripple-init>
              <b><i class="fas fa-power-off"></i> Terminate</b>
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
  $("#edit_profile").click(function(){
    var code = '<?php echo $code; ?>';
    var emel = $("#emel").val();
    var user = $("#user").val();
    if(emel == ""){
      Swal.fire("Email Required");
    }else if(user == ""){
      Swal.fire("Username Required");
    }else{
      $.ajax({
        url:'modules/employees/api_main',
        type:'POST',
        data:{edit_profile:code,emel:emel,user:user},
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
          $.ajax({
            url: 'modules/employees/profile?code=<?php echo $code; ?>',
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
    }
  });
});

$(document).ready(function() {
  $("#upload_profile").click(function(){
    var code = '<?php echo $code; ?>';
    var formData = new FormData();
    var file = $('#profile')[0].files[0];
    var image = '<?php echo $image; ?>';

    if (!file) {
      Swal.fire({
        title: '<b class="text-danger">No file selected</b>'
      });
      return false;
    } else if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
      Swal.fire({
        title: '<b class="text-danger">Only JPG/PNG allowed</b>'
      });
      return false;
    }

    var img = new Image();
    img.onload = function () {
      var width = img.width;
      var height = img.height;

      if (width > 800 || height > 800) {
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var MAX_WIDTH = 800;
        var MAX_HEIGHT = 800;

        var scaleFactor = Math.min(MAX_WIDTH / width, MAX_HEIGHT / height);
        width *= scaleFactor;
        height *= scaleFactor;

        canvas.width = MAX_WIDTH;
        canvas.height = MAX_HEIGHT;

        ctx.fillStyle = 'rgb(255,255,255)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        var x = (MAX_WIDTH - width) / 2;
        var y = (MAX_HEIGHT - height) / 2;

        ctx.drawImage(img, x, y, width, height);
        canvas.toBlob(function (blob) {
          var extension = file.type === 'image/jpeg' ? '.jpg' : '.png';
          var fileWithExtension = new File([blob], 'filename' + extension, { type: file.type });

          formData.append('upload_profile', code);
          formData.append('image', image);
          formData.append('file', fileWithExtension);

          uploadImage(formData);
        }, file.type);
      } else {
        formData.append('upload_profile', code);
        formData.append('image', image);
        formData.append('file', file);

        uploadImage(formData);
      }
    };
    img.src = URL.createObjectURL(file);

    function uploadImage(formData) {
      $.ajax({
        url:'modules/employees/api_main',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {
          Swal.fire({
            title: 'SAVING',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
          });
        },
        success: function (response) {
          Swal.close();
          $.ajax({
            url: 'modules/employees/profile?code=<?php echo $code; ?>',
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
        }
      });
    }
  });
});


$(document).ready(function() {
  $("#clear_profile").click(function(){
    var code = '<?php echo $code; ?>';
    var image = '<?php echo $image; ?>';
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<b>YOU WON'T BE ABLE TO REVERT THIS</b>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url:'modules/employees/api_main',
          type:'POST',
          data:{clear_profile:code,image:image},
          beforeSend: function() {    
            Swal.fire({
              title: 'CLEARING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response){
            Swal.close();
            $.ajax({
              url: 'modules/employees/profile?code=<?php echo $code; ?>',
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
      }
    });
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

$(document).ready(function() {
  $("#reset").click(function(){
    var emel = '<?php echo $email; ?>';
    var code = '<?php echo $code; ?>';
    var pass = $("#pass").val();
    if(pass == ''){
      Swal.fire("No Password");
    }else{
      Swal.fire({
        title: 'ARE YOU SURE?',
        html: "<b>THE PASSWORD WILL BE CHANGED</b>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00B74A',
        cancelButtonColor: '#d33',
        confirmButtonText: 'YES, PROCEED'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url:'api_mail',
            type:'POST',
            data:{reset_profile:code,pass:pass,emel:emel},
            beforeSend: function() {    
              Swal.fire({
                title: 'RESETTING',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
            },
            success: function(response){
              Swal.close();
              Swal.fire({
                title: '<b>New Password sent to <br>'+emel+'</b>',
              }).then(function() {
                $.ajax({
                  url: 'modules/employees/profile?code=<?php echo $code; ?>',
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
              });

            },
          });
        }
      });
    }
  });
});

$(document).ready(function() {
  $("#auth_generate").click(function(){
    var code = '<?php echo $code; ?>';
    var emel = '<?php echo $emel; ?>';
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<b>AUTHORIZATION CODE WILL BE GENERATED</b>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url:'api_mail',
          type:'POST',
          data:{auth_generate:code,emel:emel},
          beforeSend: function() {    
            Swal.fire({
              title: 'REQUESTING',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response){
            Swal.close();
            Swal.fire({
              title: '<b>Authorization Code sent to <br>'+emel+'</b>',
            }).then(function() {
              $.ajax({
                url: 'modules/employees/profile?code=<?php echo $code; ?>',
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
            });

          },
        });
      }
    });
  });
});

$(document).ready(function() {
  $("#terminate").click(function(){
    var emel = '<?php echo $emel; ?>';
    var code = '<?php echo $code; ?>';
    var auth = $("#auth").val();
    if(auth == ''){
      Swal.fire("Authorization Code Required");
    }else{
      Swal.fire({
        title: 'FINAL WARNING!',
        html: "<b>EMPLOYEE ACCOUNT WILL BE TERMINATED</b>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00B74A',
        cancelButtonColor: '#d33',
        confirmButtonText: 'YES, PROCEED'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url:'api_mail',
            type:'POST',
            data:{terminate_profile:code,auth:auth,emel:emel},
            beforeSend: function() {    
              Swal.fire({
                title: 'TERMINATING',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                },
              });
            },
            success: function(response){
              Swal.close();
              if(response.trim() === '0'){
                Swal.fire("Authorization Code Invalid");
              }else{
                Swal.fire({
                  title: "Employee Account Terminated"
                }).then(() => {
                  location.reload();
                });
              }
            },
          });
        }
      });
    }
  });
});
</script>
<?php } ?>