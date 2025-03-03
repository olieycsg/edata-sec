<input id="emp_id" value="<?php echo $_GET['id']; ?>" hidden>
<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

if($_SESSION['id'] == ""){
  
  header('location: ../');
  
}else if($_SESSION['id'] != "" && $_SESSION['role_level'] == "2"){

  header('location: logout');

}else{

include('../api.php');
include('header.php');

$target_dir = "profile/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$image_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if($image_type == "jpg" || $image_type == "png" || $image_type == "jpeg") {
  if ($_FILES["file"]["size"] > 1000000) {
?>
  <script type="text/javascript">
  var emp_id = $("#emp_id").val();
  Swal.fire({
    icon: 'error',
    title: 'ERROR',
    text: 'SORRY, AN IMAGE IS TOO LARGE',
  }).then((result) => {
    window.location.href = "index?module=employees&tab=panel0&id="+emp_id;
  });
  </script>

  <?php }else{

  $sql = "SELECT * FROM employees_demas WHERE CNOEE = '".$_GET['id']."'";
                                                                      
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    if($row = $result->fetch_assoc()) {
      if($row['CIMAGE'] != ""){
        unlink($target_dir.$row['CIMAGE']);
      }

      $temp = explode(".", basename($_FILES["file"]["name"]));
      $new_name = rand().rand().'.'.end($temp);
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$new_name)) {

        $sql_1 = "UPDATE employees_demas SET CIMAGE = '$new_name', u_by = '".$_SESSION['id']."', u_date = '".date('Y-m-d H:i:s')."' WHERE CNOEE = '".$_GET['id']."'";

        if ($conn->query($sql_1) === TRUE) {
?>      
        <script type="text/javascript">
        var emp_id = $("#emp_id").val();
        Swal.fire({
          icon: 'success',
          title: 'GREAT',
          text: 'IMAGE UPLOADED',
        }).then((result) => {
          window.location.href = "index?module=employees&tab=panel0&id="+emp_id;
        });
        </script>

        <?php } else { ?>

        <script type="text/javascript">
        var emp_id = $("#emp_id").val();
        Swal.fire({
          icon: 'error',
          title: 'ERROR',
          text: 'SORRY, THERE WAS AN ERROR UPLOADING YOUR FILE',
        }).then((result) => {
          window.location.href = "index?module=employees&tab=panel0&id="+emp_id;
        });
        </script>
    <?php } } } } } } else { ?>
    <script type="text/javascript">
    var emp_id = $("#emp_id").val();
    Swal.fire({
      icon: 'error',
      title: 'ERROR',
      text: 'SORRY, ONLY JPG, JPEG AND PNG FILES ARE ALLOWED',
    }).then((result) => {
      window.location.href = "index?module=employees&tab=panel0&id="+emp_id;
    });
    </script>
<?php
  }
}
include('footer.php');

?>