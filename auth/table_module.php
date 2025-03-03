<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

if($_SESSION['uid'] == ""){
  
  header('location: index');
  
}else{
  
include('api.php'); 

$sql = "SELECT * FROM module ORDER BY module ASC, status DESC";
$result = $conn->query($sql);

$hql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$hesult = $hris->query($hql);

$options = ["Employee" => 1, "Leave" => 2, "Payroll" => 3, "Medical" => 4, "Memo" => 5, "Training" => 6, "Performance" => 7, "System" => 8, "Reports" => 9];

if($result->num_rows > 0) {
  foreach ($result as $key => $row) {
    $id[] = $key;
?>
<tr>
  <td><?php echo count($id); ?></td>
  <td>
    <?php 

    foreach ($hesult as $hey => $how) {
      if($how['CNOEE'] == $row['sid']){
        echo $how['CNAME']; 
      }
    }

    ?> 
  </td>
  <td class="text-left">
    <?php 

    foreach ($options as $label => $value) { 
      if($value == $row['module']){
        echo $label;
      }
    }

    ?>
  </td>
  <td class="text-center">
    <?php echo ($row['status'] == '1') ? '<i class="fas fa-signal text-success pointer offline" data-id='.$row['id'].'></i>' : '<i class="fas fa-signal text-danger pointer online" data-id='.$row['id'].'></i>' ?>
   </td>
  <td class="text-right">
    <i class="far fa-trash-can text-danger pointer delete" data-id="<?php echo $row['id']; ?>"></i>
  </td>
<?php } }else{ ?>
<tr>
  <td>-</td>
  <td>-</td>
  <td class="text-left">-</td>
  <td class="text-center">-</td>
  <td class="text-right">-</td>
</tr>
<?php } } ?>
<script type="text/javascript">
$(document).ready(function(){
  $(".offline").click(function(){
    var id = $(this).attr('data-id');
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<strong class='text-danger'>REVOKE MODULE</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "api_main",
          type:'POST',
          data:{offline_module:id},
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
            $.ajax({
              url: "table_module",
              type:'POST',
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
              success: function(server){
                Swal.close();
                sec_remove();
                $("#result").html(server);
              }
            });
          }
        });
      }
    });
  });
});

$(document).ready(function(){
  $(".online").click(function(){
    var id = $(this).attr('data-id');
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<strong class='text-success'>GRANTED MODULE</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "api_main",
          type:'POST',
          data:{online_module:id},
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
            $.ajax({
              url: "table_module",
              type:'POST',
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
              success: function(server){
                Swal.close();
                sec_remove();
                $("#result").html(server);
              }
            });
          }
        });
      }
    });
  });
});

$(document).ready(function(){
  $(".delete").click(function(){
    var id = $(this).attr('data-id');
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<strong>YOU WON'T BE ABLE TO REVERT THIS</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "api_main",
          type:'POST',
          data:{delete_module:id},
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
          success: function(response){
            $.ajax({
              url: "table_module",
              type:'POST',
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
              success: function(server){
                Swal.close();
                sec_remove();
                $("#result").html(server);
              }
            });
          }
        });
      }
    });
  });
});
</script>