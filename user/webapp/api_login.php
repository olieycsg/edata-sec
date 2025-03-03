<?php

include('../../api.php');

$uid = $_POST['email'];
$pid = sha1($_POST['password']);

if($uid == '' || $pid == ''){

  $response = array('success' => false, 'message' => 'Email and Password required');
  echo json_encode($response);

}else{
  $sql = "SELECT * FROM employees WHERE EmailAddress = '$uid' AND Password = '$pid'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
      $response = array(
        'success' => true,
        'name' => $row['FirstName'],
        'emid' => $row['EmployeeID'],
      );
      echo json_encode($response);
    }
  } else {
    $response = array('success' => false, 'message' => 'Invalid Email or Password');
    echo json_encode($response);
  }

  $conn->close();
}

?>
