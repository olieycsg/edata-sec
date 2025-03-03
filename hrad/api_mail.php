<?php 

include('../api.php');
session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

if(isset($_POST['request_code'])){

  $user = $_POST['request_code'];

  $sql = "SELECT * FROM access WHERE email = '$user'";
  $result = $hris->query($sql);

  if($result->num_rows > 0) {
    if($row = $result->fetch_assoc()) {
      try {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.sabahenergy@gmail.com';
        $mail->Password = 'xhhuywqkehkcgftb';
        $mail->Port = 587;    

        $mail->setFrom('noreply.sabahenergy@gmail.com', 'i-SEC System');
        $mail->addAddress($user);
        $mail->isHTML(true);
        $mail->Subject = 'Authentication Code';

        $tac = rand(10000000, 99999999);

        $sql = "UPDATE employees SET token = '$tac' WHERE EmailAddress = '$user'";

        if ($conn->query($sql) == TRUE) {

          $date = date('Y');

          $mail->Body = "
          <!DOCTYPE html>
          <html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
          <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width,initial-scale=1'>
            <meta name='x-apple-disable-message-reformatting'>
            <title></title>
            <!--[if mso]>
            <noscript>
              <xml>
                <o:OfficeDocumentSettings>
                  <o:PixelsPerInch>96</o:PixelsPerInch>
                </o:OfficeDocumentSettings>
              </xml>
            </noscript>
            <![endif]-->
            <style>
              table, td, div, h1, p {font-family: Arial, sans-serif;}
            </style>
          </head>
          <body style='margin:0;padding:0;'>
            <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;'>
              <tr>
                <td align='center' style='padding:0;'>
                  <table role='presentation' style='width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;'>
                    <tr>
                      <td align='center' style='padding:40px 0 30px 0;background:#79A6FE;'>
                        <h3>Admin Authentication Code</h3>
                      </td>
                    </tr>
                    <tr>
                      <td style='padding:36px 30px 42px 30px;'>
                        <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;'>
                          <tr>
                            <td style='padding:0 0 36px 0;color:#153643;'>
                              <h4 style='margin:0 0 20px 0;font-family:Arial,sans-serif;'>Dear Admin,</h4>
                              <p style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                                Copy and paste authentication code number below:
                              </p>
                              <br>

                              <h2>$tac</h2>
                              <br>

                              <p style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>You didn't ask for a authentication code?<br><br>
                                Don't worry, you can safely ignore this email. Someone else mistakenly entered your email address. Sorry for the interruption.
                              </p>
                              <br><br>

                              <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                                Best Regards,
                              </h6>

                              <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                                Information and Computer Services
                                <br>
                                Sabah Energy Corporation Sdn. Bhd.
                              </h6>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </body>
          </html>        ";

          $mail->send();

        } else {
          echo "ERROR: " . $sql . "<br>" . $conn->error;
        }
      }catch (Exception $e) {
        echo 'MESSAGE COULD NOT BE SENT.';
        echo 'MAILER ERROR: ' . $mail->ErrorInfo;
      }
    }
  }else{
    echo '0';
  }
}

if(isset($_POST['login_code'])){

  $user = $_POST['login_code'];
  $auth = $_POST['auth'];

  $sql = "SELECT * FROM employees WHERE EmailAddress = '$user' AND token = '$auth'";
  $result = $conn->query($sql);

  if($result->num_rows > 0) {
    if($row = $result->fetch_assoc()) {

      $sql = "UPDATE employees SET token = '' WHERE EmailAddress = '$user' AND token = '$auth'";

      if ($conn->query($sql) === TRUE) {
        $_SESSION['sid'] = $row['EmployeeID'];
        $_SESSION['email'] = $row['EmailAddress'];
      }
    }
  }else{
    echo '0';
  }
}

if(isset($_POST['reset_profile'])){

  $code = $_POST['reset_profile'];
  $curr = $_POST['pass'];
  $emel = $_POST['emel'];
  $pass = sha1($_POST['pass']);

  $sql = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
  $result = $conn->query($sql);

  if($result->num_rows > 0) {
    if($row = $result->fetch_assoc()) {
      try {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.sabahenergy@gmail.com';
        $mail->Password = 'xhhuywqkehkcgftb';
        $mail->Port = 587;    

        $mail->setFrom('noreply.sabahenergy@gmail.com', 'i-SEC Admin');
        $mail->addAddress($_SESSION['email']);
        $mail->addCC($emel);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset - Admin';

        $sql = "UPDATE employees SET Password = '$pass' WHERE EmployeeID = '$code'";

        if ($conn->query($sql) == TRUE) {

          $date = date('Y');
          $name = $row['CNAME'];

          $mail->Body = "
          <!DOCTYPE html>
          <html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
          <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width,initial-scale=1'>
            <meta name='x-apple-disable-message-reformatting'>
            <title></title>
            <!--[if mso]>
            <noscript>
              <xml>
                <o:OfficeDocumentSettings>
                  <o:PixelsPerInch>96</o:PixelsPerInch>
                </o:OfficeDocumentSettings>
              </xml>
            </noscript>
            <![endif]-->
            <style>
              table, td, div, h1, p {font-family: Arial, sans-serif;}
            </style>
          </head>
          <body style='margin:0;padding:0;'>
            <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;'>
              <tr>
                <td align='center' style='padding:0;'>
                  <table role='presentation' style='width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;'>
                    <tr>
                      <td align='center' style='padding:40px 0 30px 0;background:#79A6FE;'>
                        <h3>Employee Password Reset</h3>
                      </td>
                    </tr>
                    <tr>
                      <td style='padding:36px 30px 42px 30px;'>
                        <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;'>
                          <tr>
                            <td style='padding:0 0 36px 0;color:#153643;'>
                              <h4 style='margin:0 0 20px 0;font-family:Arial,sans-serif;'>Dear Admin,</h4>
                              <h4>
                              NAME: $name
                              <br>
                              NEW PASSWORD: $curr
                              </h4>
                              <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                                Best Regards,
                              </h6>
                              <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                                Information and Computer Services
                                <br>
                                Sabah Energy Corporation Sdn. Bhd.
                              </h6>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </body>
          </html>";

          $mail->send();

        } else {
          echo "ERROR: " . $sql . "<br>" . $conn->error;
        }
      }catch (Exception $e) {
        echo 'MESSAGE COULD NOT BE SENT.';
        echo 'MAILER ERROR: ' . $mail->ErrorInfo;
      }
    }
  }else{
    echo '0';
  }
}

if(isset($_POST['auth_generate'])){

  $code = $_POST['auth_generate'];
  $emel = $_POST['emel'];

  $tac = rand(10000000, 99999999);
  $sql = "UPDATE employees SET token = '$tac' WHERE EmployeeID = '$code'";

  if ($conn->query($sql) == TRUE) {
    try {
      $mail->SMTPDebug = 2;
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'noreply.sabahenergy@gmail.com';
      $mail->Password = 'xhhuywqkehkcgftb';
      $mail->Port = 587;    

      $mail->setFrom('noreply.sabahenergy@gmail.com', 'i-SEC Admin');
      $mail->addAddress($emel);
      $mail->isHTML(true);
      $mail->Subject = 'Authorization Code';

      $mail->Body = "
      <!DOCTYPE html>
      <html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
      <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width,initial-scale=1'>
        <meta name='x-apple-disable-message-reformatting'>
        <title></title>
        <!--[if mso]>
        <noscript>
          <xml>
            <o:OfficeDocumentSettings>
              <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
          </xml>
        </noscript>
        <![endif]-->
        <style>
          table, td, div, h1, p {font-family: Arial, sans-serif;}
        </style>
      </head>
      <body style='margin:0;padding:0;'>
        <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;'>
          <tr>
            <td align='center' style='padding:0;'>
              <table role='presentation' style='width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;'>
                <tr>
                  <td align='center' style='padding:40px 0 30px 0;background:#79A6FE;'>
                    <h3>Employee Account Termination</h3>
                  </td>
                </tr>
                <tr>
                  <td style='padding:36px 30px 42px 30px;'>
                    <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;'>
                      <tr>
                        <td style='padding:0 0 36px 0;color:#153643;'>
                          <h4 style='margin:0 0 20px 0;font-family:Arial,sans-serif;'>Dear Admin,</h4>
                          <p style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                            Copy and paste authorization code number below:
                          </p>

                          <h2>$tac</h2>

                          <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                            Best Regards,
                          </h6>

                          <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                            Information and Computer Services
                            <br>
                            Sabah Energy Corporation Sdn. Bhd.
                          </h6>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </body>
      </html>        
      ";

      $mail->send();

    }catch (Exception $e) {
      echo 'MESSAGE COULD NOT BE SENT.';
      echo 'MAILER ERROR: ' . $mail->ErrorInfo;
    }
  }
}

if(isset($_POST['terminate_profile'])){

  $code = $_POST['terminate_profile'];
  $emel = $_POST['emel'];
  $auth = $_POST['auth'];
  $time = date("d F Y h:i A");

  $sql = "SELECT * FROM employees WHERE EmployeeID = '$code'";
  $result = $conn->query($sql);

  if($row = $result->fetch_assoc()){
    if($row['token'] == $auth){

      unlink("modules/employees/file/".$row['image']);

      $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$code'";
      $result1 = $conn->query($sql1);

      if($row1 = $result1->fetch_assoc()){
        $name = $row1['CNAME'];
      }

      $sql = "DELETE FROM employees WHERE EmployeeID = '$code'";
      if ($conn->query($sql) === TRUE) {
        $sql = "DELETE FROM employees_demas WHERE CNOEE = '$code'";
        if ($conn->query($sql) === TRUE) {
          $sql = "ALTER TABLE employees_demas DROP ID";
          if ($conn->query($sql) === TRUE) {
            $sql = "ALTER TABLE employees_demas ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
            if ($conn->query($sql) === TRUE) {}
          }
        }
      }

      try {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.sabahenergy@gmail.com';
        $mail->Password = 'xhhuywqkehkcgftb';
        $mail->Port = 587;    

        $mail->setFrom('noreply.sabahenergy@gmail.com', 'i-SEC Admin');
        $mail->addAddress($emel);
        $mail->isHTML(true);
        $mail->Subject = 'Employee Account Termination';

        $mail->Body = "
        <!DOCTYPE html>
        <html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
        <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width,initial-scale=1'>
          <meta name='x-apple-disable-message-reformatting'>
          <title></title>
          <!--[if mso]>
          <noscript>
            <xml>
              <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
              </o:OfficeDocumentSettings>
            </xml>
          </noscript>
          <![endif]-->
          <style>
            table, td, div, h1, p {font-family: Arial, sans-serif;}
          </style>
        </head>
        <body style='margin:0;padding:0;'>
          <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;'>
            <tr>
              <td align='center' style='padding:0;'>
                <table role='presentation' style='width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;'>
                  <tr>
                    <td align='center' style='padding:40px 0 30px 0;background:#79A6FE;'>
                      <h3>Report of Account Termination</h3>
                    </td>
                  </tr>
                  <tr>
                    <td style='padding:36px 30px 42px 30px;'>
                      <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;'>
                        <tr>
                          <td style='padding:0 0 36px 0;color:#153643;'>
                            <h4 style='margin:0 0 20px 0;font-family:Arial,sans-serif;'>Dear Admin,</h4>

                            <h4>
                            Name: $name<br>
                            Employee ID: $code<br>
                            Authorization Code: $auth<br>
                            Termination: $time<br><br>
                            Terminated By: $emel
                            </h4>

                            <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                              Best Regards,
                            </h6>

                            <h6 style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;text-align:justify;color:black;'>
                              Information and Computer Services
                              <br>
                              Sabah Energy Corporation Sdn. Bhd.
                            </h6>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>        
        ";
        $mail->send();
      }catch (Exception $e) {
        echo 'MESSAGE COULD NOT BE SENT.';
        echo 'MAILER ERROR: ' . $mail->ErrorInfo;
      }
    }else{
      echo '0';
    }
  }
}
?>