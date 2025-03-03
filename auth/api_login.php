<?php 

include('api.php');
session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_POST['login_code'])){

  $user = $_POST['login_code'];
  $auth = sha1($_POST['auth']);

  $sql = "SELECT * FROM superadmin WHERE username = '$user' AND password = '$auth'";
  $result = $conn->query($sql);

  if($result->num_rows > 0) {
    if($row = $result->fetch_assoc()) {
      $_SESSION['uid'] = $row['username'];
    }
  }else{
    echo '0';
  }
}
?>