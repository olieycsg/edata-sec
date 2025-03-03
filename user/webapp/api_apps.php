<?php

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

include('../../api.php');

if(isset($_POST['recommend_leave'])){

  $id = $_POST['recommend_leave'];

  $sql = "SELECT * FROM eleave WHERE id = '$id'";
  $result = $conn->query($sql);

  if($row = $result->fetch_assoc()){

    $cnoee = $row['CNOEE'];
    $date_strt = strtoupper(date("d M Y", strtotime($row['DLEAVE'])));
    $date_ends = strtoupper(date("d M Y", strtotime($row['DLEAVE2'])));
    $ndays = $row['NDAYS'];
    $cleave = $row['CCDLEAVE'];
    $reason = $row['CREASON'];

    if($ndays < 1){
      if($row['NHOURS'] == '10'){
        $nhours = $ndays." DAYS (MORNING)";
      }
      if($row['NHOURS'] == '01'){
        $nhours = $ndays." DAYS (AFTERNOON)";
      }
    }else{
      $nhours = $ndays." DAYS (FULLDAY)";
    }

    $sql1 = "SELECT * FROM employees";
    $result1 = $conn->query($sql1);

    $sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
    $result2 = $conn->query($sql2);

    foreach ($result2 as $key2 => $val2) {
      if($val2['CNOEE'] == $cnoee){
        $staff = $val2['CNAME'];
        $cdivision = $val2['CDIVISION'];
        $csuperior = $val2['CSUPERIOR'];
      }
    }

    foreach ($result2 as $key3 => $val3) {
      if($val3['CJOB'] == $csuperior && $val3['CDIVISION'] == $cdivision){
        $csuper = $val3['CNOEE'];
        foreach ($result1 as $key1 => $val1) {
          if($val1['EmployeeID'] == $csuper){
            $emails = $val1['EmailAddress'];
            /*$email = 'olivianus@sabahenergycorp.com';*/
          }
        }
      }
    }

    $sql4 = "SELECT * FROM eleave_leave_type WHERE ID = '$cleave'";
    $result4 = $conn->query($sql4);

    if($row4 = $result4->fetch_assoc()){
      $ctype = strtoupper($row4['leave_type']);
    }

    $sql = "UPDATE eleave SET MNOTES = 'recommended', CSOURCE = '$csuper', ICNTEE = 'processed' WHERE id = '$id'";
    if ($conn->query($sql) == TRUE) {}
  }

  try {
    include ('smtp.php');
    $mail->setFrom($smtpEmail, 'i-SEC APPS');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'HRIS LEAVE SYSTEM';

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
                    <strong>SEC LEAVE NOTIFICATION</strong>
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
                            DEAR DIVISION HEAD,
                          </td>
                        </tr>
                        <tr>
                          <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                            STAFF: $staff
                            <br>
                            LEAVE: $ctype
                            <br>
                            DATE: $date_strt - $date_ends
                            <br>
                            DURATION: $nhours
                            <br>
                            REASON: $reason
                          </td>
                        </tr>
                        <tr>
                          <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: blue; font-family: sans-serif;'>
                            KINDLY APPROVE LEAVE VIA THE I-SEC APP
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
  }catch (Exception $e) {
    echo 'MESSAGE COULD NOT BE SENT.';
    echo 'MAILER ERROR: ' . $mail->ErrorInfo;
  }
}

if(isset($_POST['approve_leave'])){

  $id = $_POST['approve_leave'];

  $sql = "SELECT * FROM eleave WHERE id = '$id'";
  $result = $conn->query($sql);

  if($row = $result->fetch_assoc()){

    $cnoee = $row['CNOEE'];
    $date_strt = strtoupper(date("d M Y", strtotime($row['DLEAVE'])));
    $date_ends = strtoupper(date("d M Y", strtotime($row['DLEAVE2'])));
    $ndays = $row['NDAYS'];
    $cleave = $row['CCDLEAVE'];
    $reason = $row['CREASON'];

    if($ndays < 1){
      if($row['NHOURS'] == '10'){
        $nhours = $ndays." DAYS (MORNING)";
      }
      if($row['NHOURS'] == '01'){
        $nhours = $ndays." DAYS (AFTERNOON)";
      }
    }else{
      $nhours = $ndays." DAYS (FULLDAY)";
    }

    $sql1 = "SELECT * FROM employees WHERE EmployeeID = '$cnoee'";
    $result1 = $conn->query($sql1);

    if($row1 = $result1->fetch_assoc()){
      $email = $row1['EmailAddress'];
      /*$email = 'olivianus@sabahenergycorp.com';*/
    }

    $sql2 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
    $result2 = $conn->query($sql2);

    foreach ($result2 as $key2 => $val2) {
      if($val2['CNOEE'] == $cnoee){
        $staff = $val2['CNAME'];
        $cdivision = $val2['CDIVISION'];
        $csuperior = $val2['CSUPERIOR'];
        $csuperviso = $val2['CSUPERVISO'];
      }
    }

    foreach ($result2 as $key3 => $val3) {
      if($val3['CJOB'] == $csuperior && $val3['CDIVISION'] == $cdivision){
        $csuper = $val3['CNOEE'];
      }
      if($val3['CJOB'] == $csuperviso && $val3['CDIVISION'] == $cdivision){
        $cvisor = $val3['CNOEE'];
      }
    }

    $source = $csuper.",".$cvisor;

    $sql4 = "SELECT * FROM eleave_leave_type WHERE ID = '$cleave'";
    $result4 = $conn->query($sql4);

    if($row4 = $result4->fetch_assoc()){
      $ctype = strtoupper($row4['leave_type']);
    }

    $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$source' WHERE id = '$id'";
    if ($conn->query($sql) == TRUE) {}
  }

  try {
    include ('smtp.php');
    $mail->setFrom($smtpEmail, 'i-SEC APPS');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'HRIS LEAVE SYSTEM';

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
                    <strong>SEC LEAVE NOTIFICATION</strong>
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
                            DEAR $staff,
                          </td>
                        </tr>
                        <tr>
                          <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                            LEAVE: $ctype
                            <br>
                            DATE: $date_strt - $date_ends
                            <br>
                            DURATION: $nhours
                            <br>
                            REASON: $reason
                          </td>
                        </tr>
                        <tr>
                          <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: blue; font-family: sans-serif;'>
                            LEAVE STATUS: APPROVE
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
  }catch (Exception $e) {
    echo 'MESSAGE COULD NOT BE SENT.';
    echo 'MAILER ERROR: ' . $mail->ErrorInfo;
  }
}
$conn->close();

?>

