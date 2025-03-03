<?php

include('../../api.php');

$uid = $_POST['email'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

if($uid == null){

  $response = array('success' => false, 'message' => 'Email Required');
  echo json_encode($response);

}else{

  $sql = "SELECT * FROM employees WHERE EmailAddress = '$uid'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    if ($row = $result->fetch_assoc()) {
      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
        $mail->addAddress($uid);
        $mail->isHTML(true);
        $mail->Subject = 'PASSWORD RESET';

        $tac = rand(100000, 999999);

        $sql = "UPDATE employees SET tac = '$tac' WHERE EmailAddress = '$uid'";

        if ($conn->query($sql) == TRUE) {

          $response = array('success' => true);
          echo json_encode($response);

          $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '".$row['EmployeeID']."'";
          $result1 = $conn->query($sql1);

          if ($row1 = $result1->fetch_assoc()) {

            $name = $row1['CNAME'];
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
              <table role='presentation' style='width:100%; border:0; border-spacing:0; background:#ffffff;'>
                <tr>
                  <td align='center' style='padding:0;'>
                    <table role='presentation' style='width:602px; border:1px solid #cccccc; border-spacing:0; text-align:center;'>
                      <tr>
                        <td align='center' style='padding:40px 0 30px 0;background:#79A6FE;'>
                          <h2>
                            <strong>TAC NUMBER GENERATED</strong>
                          </h2>
                        </td>
                      </tr>
                      <tr>
                        <td style='padding:10px 30px 42px 30px;'>
                          <table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;'>
                            <tr>
                              <td style='padding:0 0 36px 0; color:#153643;'>
                                <tr>
                                  <td align='left' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; color: black; font-family: sans-serif;'>
                                    Dear $name,
                                  </td>
                                </tr>
                                <tr>
                                  <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                    Copy and paste TAC number below, please disregard it if you did not request it.
                                    <br><br>
                                    <h1>$tac</h1>
                                    <br><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; font-family: sans-serif;' color: black;>
                                    Data Application Team<br>
                                    Information & Computer Services
                                  </td>
                                </tr>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td style='padding:30px; background:#ee4c50;'>
                          <table role='presentation' style='width:100%; border:0; border-spacing:0; font-size:9px; font-family:sans-serif;'>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; font-family: sans-serif;' color: black;>
                                COPYRIGHT &reg; 2021 - $date <br>ALL RIGHT RESERVED
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
          }
        }
      }catch (Exception $e) {
        echo 'MESSAGE COULD NOT BE SENT.';
        echo 'MAILER ERROR: ' . $mail->ErrorInfo;
      }
    }
  } else {
    $response = array('success' => false, 'message' => 'Email Not Found');
    echo json_encode($response);
  }
  $conn->close();
}
?>
