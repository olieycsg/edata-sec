<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../api.php'); 

if(isset($_POST['init_employee'])){
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
  
  echo '<select id="search_employee" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">';
  echo '<option value="" disabled selected>- Select -</option>';
  echo '<option value="add_employee" data-mdb-icon="img/nouser.png">ADD NEW EMPLOYEE</option>';
  
  foreach ($result as $key => $value) {
    echo '<option value="'.$value['CNOEE'].'" data-mdb-icon="../img/icon.png">'.$value['CNOEE'].' : '.$value['CNAME'].'</option>';
  }
  
  echo '</select>';
  echo '<label class="form-label select-label text-primary"><b>Employee</b></label>';
}

if(isset($_POST['actv_employee'])){
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
  
  echo '<select id="search_employee" class="sec-select" data-mdb-visible-options="10" data-mdb-select-init data-mdb-filter="true">';
  echo '<option value="" disabled selected>- Select -</option>';
  echo '<option value="add_employee" data-mdb-icon="img/nouser.png">ADD NEW EMPLOYEE</option>';
  
  foreach ($result as $key => $value) {
    $selected = ($value['CNOEE'] == $_POST['actv_employee']) ? 'selected' : ''; 
    echo '<option value="'.$value['CNOEE'].'" '.$selected.' data-mdb-icon="../img/icon.png">'.$value['CNOEE'].' : '.$value['CNAME'].'</option>';
  }

  echo '</select>';
  echo '<label class="form-label select-label text-primary"><b>Employee</b></label>';
}

?>
<script type="text/javascript">
sec_function();
$(document).ready(function() {
  $("#search_employee").change(function(){
    var code = $(this).val();
    if(code == 'add_employee'){
      $.ajax({
        url: 'modules/employees/register',
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
    }else{
      $.ajax({
        url: 'modules/employees/main?code='+code,
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
    }
  });
});
</script>

