<?php

include('../../api.php');

$tac = $_POST['tac'];
$pid = sha1($_POST['pass']);
$exp = rand(100000, 999999);

if ($tac == null || $pid == null) {
  $response = array('success' => false, 'message' => 'TAC and New Password required');
  echo json_encode($response);
} else {

  $passwordRegex = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#\$%^&*()_+\-=\[\]{};:\'"|,.<>\/?]).{8,}$/';
  if (!preg_match($passwordRegex, $_POST['pass'])) {
    
    $response = array('success' => false, 'message' => "Minimum length of 8 characters\n\nAt least one uppercase letter\nAt least one lowercase letter\nAt least one digit\nAt least one special character");
    echo json_encode($response);

  } else {
    $sql = "SELECT * FROM employees WHERE tac = '$tac'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      if ($row = $result->fetch_assoc()) {

        $sql = "UPDATE employees SET Password = '$pid' WHERE tac = '$tac'";
        if ($conn->query($sql) == TRUE) {

          $sql = "UPDATE employees SET tac = '$exp' WHERE Password = '$pid'";
          if ($conn->query($sql) == TRUE) {
            $response = array('success' => true);
            echo json_encode($response);
          }
        }
      }
    } else {
      $response = array('success' => false, 'message' => 'Invalid TAC');
      echo json_encode($response);
    }

    $conn->close();
  }
}
?>
