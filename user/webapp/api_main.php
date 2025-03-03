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

if(isset($_POST['add_leave_one'])){

  $cjob = $_POST['cjob'];
  $cnoee = $_POST['cnoee'];
  $cname = $_POST['cname'];
  $staff = $_POST['staff'];
  $start = $_POST['start'];
  $email = $_POST['email'];
  $mnoee = $_POST['mnoee'];
  $fnoee = $_POST['fnoee'];
  $nhours = $_POST['add_leave_one'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($nhours != '11'){
    $ndays = '0.50';
  }else{
    $ndays = '1.00';
  }

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $sqla = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
  $resulta = $conn->query($sqla);

  foreach ($resulta as $keya => $vala) {
    if($vala['CNOEE'] == $cnoee){
      $division = $vala['CDIVISION'];

      $sqlb = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$division'";
      $resultb = $conn->query($sqlb);

      foreach ($resultb as $keyb => $valb) {
        $snoee = $valb['CNOEE'];

        $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$snoee'";
        $resultc = $conn->query($sqlc);

        foreach ($resultc as $keyc => $valc) {
          $smail = $valc['EmailAddress'];
        }
      }
    }
  }

  if($cjob == "CEO"){
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE) VALUES ('$cnoee', '$start', '$start', '1', '$ndays', '$nhours', '$reason', 'approved', 'processed')";

    if ($conn->query($sql) === TRUE) {
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
        $mail->addAddress('felecity@sabahenergycorp.com');
        $mail->addCC('nelly@sabahenergycorp.com');
        $mail->isHTML(true);
        $mail->Subject = 'SECRETARY MODULE - LEAVE SYSTEM';

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
                        <strong>Annual Leave - Secretary Copy</strong>
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
                                DEAR SECRETARY,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STATUS : APPROVED
                                <br>
                                STAFF: $staff
                                <br>
                                LEAVE: ANNUAL LEAVE
                                <br>
                                DATE: $date_start - $date_start
                                <br>
                                DURATION: $ndays DAYS
                                <br>
                                REASON: $reason
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
  }else if($cjob != "CEO"){
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE) VALUES ('$cnoee', '$start', '$start', '1', '$ndays', '$nhours', '$reason', 'pending', 'unprocessed')";

    if ($conn->query($sql) === TRUE) {
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      if($_POST['direct'] == '1'){
        $token = $cnoee;
        $direct = 'token_app';
        $recomm = 'APPROVE';
        $approv = 'Approval';
      }else if($_POST['direct'] == '0'){
        $token = uniqid().'-'.$cnoee.'-'.uniqid().'-'.$mnoee.'-'.uniqid();
        $direct = 'token_rec';
        $recomm = 'RECOMMEND TO HOD';
        $approv = 'Recommendation';
      }

      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
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
                        <strong>Annual Leave - $approv</strong>
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
                                DEAR $cname,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: ANNUAL LEAVE
                                <br>
                                DATE: $date_start - $date_start
                                <br>
                                DURATION: $ndays DAYS
                                <br>
                                REASON: $reason
                              </td>
                            </tr>
                            <tr>
                              <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                                <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 240px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                  <tr>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?$direct=$token&date=$start&auth=$fnoee&status=direct'>
                                        $recomm
                                      </a>
                                    </td>
                                  </tr>
                                </table>
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

      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
        $mail->addAddress($smail);
        $mail->isHTML(true);
        $mail->Subject = 'SECRETARY MODULE - LEAVE SYSTEM';

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
                        <strong>Annual Leave - Secretary Copy</strong>
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
                                DEAR SECRETARY,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: ANNUAL LEAVE
                                <br>
                                DATE: $date_start - $date_start
                                <br>
                                DURATION: $ndays DAYS
                                <br>
                                REASON: $reason
                                <br><br>
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                NOTE: Please remind the recommender to recommend leave for the employee mentioned above.
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
  }
}

if(isset($_POST['delete_leave_one'])){

  if($_POST['files'] != ''){
    unlink("file/".$_POST['files']);
  }

  $delete = $_POST['delete_leave_one'];
  $cnoee = $_POST['cnoee'];

  $sql = "DELETE FROM eleave WHERE DLEAVE = '$delete' AND CNOEE = '$cnoee'";
  if ($conn->query($sql) === TRUE) {

    $sql = "ALTER TABLE eleave DROP id";
    if ($conn->query($sql) === TRUE) {

      $sql = "ALTER TABLE eleave ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_leave_two'])){

  $start = $_POST['add_leave_two'];
  $final = $_POST['final'];
  $cnoee = $_POST['cnoee'];
  $nhour = $_POST['nhours'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['remarks']));

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $cjob = $_POST['cjob'];
  $cname = $_POST['cname'];
  $staff = $_POST['staff'];
  $email = $_POST['email'];
  $mnoee = $_POST['mnoee'];
  $fnoee = $_POST['fnoee'];

  $chunks = str_split($nhour, 2);
  $chunkVal = [];
  foreach ($chunks as $chunk) {
    if($chunk == '11'){
      $chunkVal[] = 1.00;
    }else{
      $chunkVal[] = 0.50;
    }
  }

  $ndays = array_sum($chunkVal);

  $sqla = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
  $resulta = $conn->query($sqla);

  foreach ($resulta as $keya => $vala) {
    if($vala['CNOEE'] == $cnoee){
      $division = $vala['CDIVISION'];

      $sqlb = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$division'";
      $resultb = $conn->query($sqlb);

      foreach ($resultb as $keyb => $valb) {
        $snoee = $valb['CNOEE'];

        $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$snoee'";
        $resultc = $conn->query($sqlc);

        foreach ($resultb as $keyb => $valb) {
          $smail = $valb['EmailAddress'];
        }
      }
    }
  }

  if($cjob == "CEO"){
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE) VALUES ('$cnoee', '$start', '$final', '1', '$ndays', '$nhour', '$reason', 'approved', 'processed')";

    if ($conn->query($sql) === TRUE) {
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      $date_final = strtoupper(date("d M Y", strtotime($final)));
      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
        $mail->addAddress('felecity@sabahenergycorp.com');
        $mail->addCC('nelly@sabahenergycorp.com');
        $mail->isHTML(true);
        $mail->Subject = 'SECRETARY MODULE - LEAVE SYSTEM';

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
                        <strong>Annual Leave - Secretary Copy</strong>
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
                                DEAR SECRETARY,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STATUS : APPROVED
                                <br>
                                STAFF: $staff
                                <br>
                                LEAVE: ANNUAL LEAVE
                                <br>
                                DATE: $date_start - $date_final
                                <br>
                                DURATION: $ndays DAYS
                                <br>
                                REASON: $reason
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
  }else if($cjob != "CEO"){
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE) VALUES ('$cnoee', '$start', '$final', '1', '$ndays', '$nhour', '$reason', 'pending', 'unprocessed')";

    if ($conn->query($sql) === TRUE) {
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      $date_final = strtoupper(date("d M Y", strtotime($final)));

      if($_POST['direct'] == '1'){
        $token = $cnoee;
        $direct = 'token_app';
        $recomm = 'APPROVE';
        $approv = 'Approval';
      }else{
        $token = uniqid().'-'.$cnoee.'-'.uniqid().'-'.$mnoee.'-'.uniqid();
        $direct = 'token_rec';
        $recomm = 'RECOMMEND TO HOD';
        $approv = 'Recommendation';
      }

      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
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
                        <strong>Annual Leave - $approv</strong>
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
                                DEAR $cname,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: ANNUAL LEAVE
                                <br>
                                DATE: $date_start - $date_final
                                <br>
                                DURATION: $ndays DAYS
                                <br>
                                REASON: $reason
                              </td>
                            </tr>
                            <tr>
                              <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                                <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 240px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                  <tr>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?$direct=$token&date=$start&auth=$fnoee&status=direct'>
                                        $recomm
                                      </a>
                                    </td>
                                  </tr>
                                </table>
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

      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
        $mail->addAddress($smail);
        $mail->isHTML(true);
        $mail->Subject = 'SECRETARY MODULE - LEAVE SYSTEM';

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
                        <strong>Annual Leave - Secretary Copy</strong>
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
                                DEAR SECRETARY,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: ANNUAL LEAVE
                                <br>
                                DATE: $date_start - $date_final
                                <br>
                                DURATION: $ndays DAYS
                                <br>
                                REASON: $reason
                                <br><br>
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                NOTE: Please remind the recommender to recommend leave for the employee mentioned above.
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
  }
}

if(isset($_POST['add_other_leave_one'])){
  $cnoee = $_POST['cnoee'];
  $start = date("Y-m-d", strtotime($_POST['start']));
  $leave = $_POST['leave'];
  $nhours = $_POST['add_other_leave_one'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($nhours != '11'){
    $ndays = '0.50';
  }else{
    $ndays = '1.00';
  }

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE) VALUES ('$cnoee', '$start', '$start', '$leave', '$ndays', '$nhours', '$reason', '', '')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['add_other_leave_two'])){
  $cnoee = $_POST['cnoee'];
  $leave = $_POST['leave'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $start = new DateTime($_POST['add_other_leave_two']);
  $end = new DateTime($_POST['end']);

  $interval = $start->diff($end);
  $daysCount = $interval->days;

  $begin = date("Y-m-d", strtotime($_POST['add_other_leave_two']));
  $final = date("Y-m-d", strtotime($_POST['end']));

  $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE) VALUES ('$cnoee', '$begin', '$final', '$leave', '$daysCount', '11', '$reason', '', '')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_leave_one'])){

  $delete = $_POST['delete_leave_one'];
  $cnoee = $_POST['cnoee'];

  $sql = "DELETE FROM eleave WHERE DLEAVE = '$delete' AND CNOEE = '$cnoee'";
  if ($conn->query($sql) === TRUE) {

    $sql = "ALTER TABLE eleave DROP id";
    if ($conn->query($sql) === TRUE) {

      $sql = "ALTER TABLE eleave ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_maternity'])){
  $cnoee = $_POST['cnoee'];
  $start = date("Y-m-d", strtotime($_POST['add_maternity']));
  $end = date("Y-m-d", strtotime($_POST['end']));
  $ent = $_POST['ent'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $cname = $_POST['cname'];
  $staff = $_POST['staff'];
  $email = $_POST['email'];
  $mnoee = $_POST['mnoee'];
  $fnoee = $_POST['fnoee'];

  $uploadDir = 'file/';
  $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $fileName = uniqid().uniqid().'.'.$extension;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE, FILE) VALUES ('$cnoee', '$start', '$end', '2', '98', '11', '$reason', 'pending', 'unprocessed', '$fileName')";
    if ($conn->query($sql) === TRUE) {
      $token = uniqid().'-'.$cnoee.'-'.uniqid().'-'.$mnoee.'-'.uniqid();
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      $date_end = strtoupper(date("d M Y", strtotime($end)));
      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
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
                        <strong>Maternity Leave - Recommendation</strong>
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
                                DEAR $cname,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: MATERNITY LEAVE
                                <br>
                                DATE: $date_start - $date_end
                                <br>
                                DURATION: $ent DAYS
                                <br>
                                REASON: $reason
                              </td>
                            </tr>
                            <tr>
                              <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                                <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                  <tr>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$fileName'>
                                        SUPPORTING DOCUMENTS
                                      </a>
                                    </td>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_mat1=$token&date=$start&auth=$fnoee&file=$fileName'>
                                        RECOMMEND TO HOD
                                      </a>
                                    </td>
                                  </tr>
                                </table>
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
  }
}

if(isset($_POST['add_medical'])){

  $cnoee = $_POST['cnoee'];
  $start = date("Y-m-d", strtotime($_POST['add_medical']));
  $end = date("Y-m-d", strtotime($_POST['end']));
  $ent = $_POST['ent'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $staff = $_POST['staff'];
  $email = $_POST['email'];
  $hnoee = $_POST['hnoee'];

  $sql1 = "SELECT * FROM employees_demas WHERE CNOEE = '$hnoee'";
  $result1 = $conn->query($sql1);

  if($row1 = $result1->fetch_assoc()){
    $cname = $row1['CNAME'];
  }

  $uploadDir = 'file/';
  $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $fileName = uniqid().uniqid().'.'.$extension;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, CSOURCE, ICNTEE, FILE) VALUES ('$cnoee', '$start', '$end', '3', '$ent', '11', '$reason', 'pending', '$hnoee', 'unprocessed', '$fileName')";
    if ($conn->query($sql) === TRUE) {
      $token = uniqid().'-'.$cnoee.'-'.uniqid().'-'.$hnoee.'-'.uniqid();
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      $date_end = strtoupper(date("d M Y", strtotime($end)));
      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
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
                        <strong>Medical Leave - Recommendation</strong>
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
                                DEAR $cname,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: MEDICAL LEAVE
                                <br>
                                DATE: $date_start - $date_end
                                <br>
                                DURATION: $ent DAYS
                                <br>
                                REASON: $reason
                              </td>
                            </tr>
                            <tr>
                              <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                                <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                  <tr>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$fileName'>
                                        DOCUMENTS
                                      </a>
                                    </td>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_med1=$token&date=$start&file=$fileName'>
                                        RECOMMEND TO HRAD
                                      </a>
                                    </td>
                                  </tr>
                                </table>
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
  }
}

if(isset($_POST['add_paternity'])){
  $cnoee = $_POST['cnoee'];
  $start = date("Y-m-d", strtotime($_POST['add_paternity']));
  $end = date("Y-m-d", strtotime($_POST['end']));
  $ent = $_POST['ent'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $cname = $_POST['cname'];
  $staff = $_POST['staff'];
  $email = $_POST['email'];
  $mnoee = $_POST['mnoee'];
  $fnoee = $_POST['fnoee'];

  $uploadDir = 'file/';
  $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $fileName = uniqid().uniqid().'.'.$extension;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE, FILE) VALUES ('$cnoee', '$start', '$end', '10', '7', '11', '$reason', 'pending', 'unprocessed', '$fileName')";
    if ($conn->query($sql) === TRUE) {
      $token = uniqid().'-'.$cnoee.'-'.uniqid().'-'.$mnoee.'-'.uniqid();
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      $date_end = strtoupper(date("d M Y", strtotime($end)));
      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
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
                        <strong>Paternity Leave - Recommendation</strong>
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
                                DEAR $cname,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: PATERNITY LEAVE
                                <br>
                                DATE: $date_start - $date_end
                                <br>
                                DURATION: $ent DAYS
                                <br>
                                REASON: $reason
                              </td>
                            </tr>
                            <tr>
                              <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                                <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                  <tr>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$fileName'>
                                        SUPPORTING DOCUMENTS
                                      </a>
                                    </td>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_pat1=$token&date=$start&auth=$fnoee&file=$fileName'>
                                        RECOMMEND TO HOD
                                      </a>
                                    </td>
                                  </tr>
                                </table>
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
  }
}

if(isset($_POST['add_marriage'])){
  $cnoee = $_POST['cnoee'];
  $start = date("Y-m-d", strtotime($_POST['add_marriage']));
  $end = date("Y-m-d", strtotime($_POST['end']));
  $ent = $_POST['ent'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $cname = $_POST['cname'];
  $staff = $_POST['staff'];
  $email = $_POST['email'];
  $mnoee = $_POST['mnoee'];
  $fnoee = $_POST['fnoee'];

  $uploadDir = 'file/';
  $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $fileName = uniqid().uniqid().'.'.$extension;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
    $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE, FILE) VALUES ('$cnoee', '$start', '$end', '12', '5', '11', '$reason', 'pending', 'unprocessed', '$fileName')";
    if ($conn->query($sql) === TRUE) {
      $token = uniqid().'-'.$cnoee.'-'.uniqid().'-'.$mnoee.'-'.uniqid();
      $date_start = strtoupper(date("d M Y", strtotime($start)));
      $date_end = strtoupper(date("d M Y", strtotime($end)));
      try {
        include ('smtp.php');
        $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
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
                        <strong>Marriage Leave - Recommendation</strong>
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
                                DEAR $cname,
                              </td>
                            </tr>
                            <tr>
                              <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                                STAFF: $staff
                                <br>
                                LEAVE: MARRIAGE LEAVE
                                <br>
                                DATE: $date_start - $date_end
                                <br>
                                DURATION: $ent DAYS
                                <br>
                                REASON: $reason
                              </td>
                            </tr>
                            <tr>
                              <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                                <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                  <tr>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$fileName'>
                                        SUPPORTING DOCUMENTS
                                      </a>
                                    </td>
                                    <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                      <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_mar1=$token&date=$start&auth=$fnoee&file=$fileName'>
                                        RECOMMEND TO HOD
                                      </a>
                                    </td>
                                  </tr>
                                </table>
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
  }
}

if(isset($_POST['add_url'])){
  $cnoee = $_POST['cnoee'];
  $start = date("Y-m-d", strtotime($_POST['start']));
  $reason = $_POST['reason'];
  $nhours = $_POST['add_url'];
  $season = strtoupper(mysqli_real_escape_string($conn, $_POST['reason']));

  if($season != ''){
    $reason = $season;
  }else{
    $reason = '-';
  }

  $cname = $_POST['cname'];
  $staff = $_POST['staff'];
  $email = $_POST['email'];
  $mnoee = $_POST['mnoee'];
  $fnoee = $_POST['fnoee'];

  if($nhours != '11'){
    $ndays = '0.50';
  }else{
    $ndays = '1.00';
  }

  $sql = "INSERT INTO eleave (CNOEE, DLEAVE, DLEAVE2, CCDLEAVE, NDAYS, NHOURS, CREASON, MNOTES, ICNTEE) VALUES ('$cnoee', '$start', '$start', '14', '$ndays', '$nhours', '$reason', 'pending', 'unprocessed')";
  if ($conn->query($sql) === TRUE) {
    $token = uniqid().'-'.$cnoee.'-'.uniqid().'-'.$mnoee.'-'.uniqid();
    $date_start = strtoupper(date("d M Y", strtotime($start)));
    $date_end = strtoupper(date("d M Y", strtotime($start)));

    $sqla = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
    $resulta = $conn->query($sqla);

    foreach ($resulta as $keya => $vala) {
      if($vala['CNOEE'] == $cnoee){
        $division = $vala['CDIVISION'];

        $sqlb = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$division'";
        $resultb = $conn->query($sqlb);

        foreach ($resultb as $keyb => $valb) {
          $snoee = $valb['CNOEE'];

          $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$snoee'";
          $resultc = $conn->query($sqlc);

          foreach ($resultc as $keyc => $valc) {
            $smail = $valc['EmailAddress'];
          }
        }
      }
    }

    try {
      include ('smtp.php');
      $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
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
                      <strong>Unrecorded Leave - Recommendation</strong>
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
                              DEAR $cname,
                            </td>
                          </tr>
                          <tr>
                            <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                              STAFF: $staff
                              <br>
                              LEAVE: UNRECORDED LEAVE
                              <br>
                              DATE: $date_start - $date_end
                              <br>
                              DURATION: 1 DAYS
                              <br>
                              REASON: $reason
                            </td>
                          </tr>
                          <tr>
                            <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                              <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                <tr>
                                  <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                    <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_url1=$token&date=$start&auth=$fnoee'>
                                      RECOMMEND TO HOD
                                    </a>
                                  </td>
                                </tr>
                              </table>
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

    try {
      include ('smtp.php');
      $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
      $mail->addAddress($smail);
      $mail->isHTML(true);
      $mail->Subject = 'SECRETARY MODULE - LEAVE SYSTEM';

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
                      <strong>Annual Leave - Secretary Copy</strong>
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
                              DEAR SECRETARY,
                            </td>
                          </tr>
                          <tr>
                            <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                              STAFF: $staff
                              <br>
                              LEAVE: UNRECORDED LEAVE
                              <br>
                              DATE: $date_start - $date_end
                              <br>
                              DURATION: 1 DAYS
                              <br>
                              REASON: $reason
                              <br><br>
                            </td>
                          </tr>
                          <tr>
                            <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                              NOTE: Please remind the recommender to recommend leave for the employee mentioned above.
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
}

$conn->close();

?>

