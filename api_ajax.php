<?php

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

include('api.php');

if(isset($_GET['token_rec'])){

  $token = explode("-", $_GET['token_rec']);
  $cn = $token[1].'-'.$token[2];
  $mc = $token[4].'-'.$token[5];
  $st = $_GET['date'];
  $fc = $_GET['auth'];

  $sqld = "SELECT * FROM eleave WHERE MNOTES = 'recommended' AND ICNTEE = 'processed' AND CNOEE = '$cn' AND DLEAVE = '$st'";
  $resultd = $conn->query($sqld);

  if ($result->num_rows > 0) {}else{

    $sql = "UPDATE eleave SET MNOTES = 'recommended', CSOURCE = '$mc', ICNTEE = 'processed' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
    if ($conn->query($sql) === TRUE) {}

    $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
    $resulta = $conn->query($sqla);

    $sqlb = "SELECT employees.EmailAddress AS email, employees_demas.CNOEE AS cnoee, employees_demas.CNAME AS cname FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CNOEE = '$fc' AND employees_demas.DRESIGN = '0000-00-00'";
    $resultb = $conn->query($sqlb);

    $sqlc = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
    $resultc = $conn->query($sqlc);

    if($rowa = $resulta->fetch_assoc()){
      $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
      $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
      $days = $rowa['NDAYS'];
      $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

      if($season != ''){
        $reason = $season;
      }else{
        $reason = '-';
      }
    }

    if($rowb = $resultb->fetch_assoc()){
      $cname = $rowb['cname'];
      $email = $rowb['email'];
    }

    if($rowc = $resultc->fetch_assoc()){
      $staff = $rowc['CNAME'];
    }

    header("Location: recommendation?cn=".$cn."&st=".$st);

    $sql1 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
    $result1 = $conn->query($sql1);

    foreach ($result1 as $key1 => $val1) {
      if($val1['CNOEE'] == $cn){
        $division = $row1['CDIVISION'];

        $sql2 = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$division'";
        $result2 = $conn->query($sql2);

        foreach ($result2 as $key2 => $val2) {
          $snoee = $val2['CNOEE'];

          $sql3 = "SELECT * FROM employees WHERE EmployeeID = '$snoee'";
          $result3 = $conn->query($sql3);

          foreach ($result3 as $key3 => $val3) {
            $smail = $val3['EmailAddress'];
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
                      <strong>Annual Leave - Approval</strong>
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
                              DATE: $start - $end
                              <br>
                              DURATION: $days DAYS
                              <br>
                              REASON: $reason
                            </td>
                          </tr>
                          <tr>
                            <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                              <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 240px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                <tr>
                                  <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                    <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_app=$cn&st=$st&mc=$mc&fc=$fc'>
                                      APPROVE
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
                              DATE: $start - $end
                              <br>
                              DURATION: $days DAYS
                              <br>
                              REASON: $reason
                              <br><br>
                            </td>
                          </tr>
                          <tr>
                            <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                              NOTE: Kindly remind the Divisional Head / DCEO / CEO to approve the leave for the employee mentioned above.
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

if(isset($_GET['token_app'])){

  $source = $_GET['mc'].",".$_GET['fc'];
  $cn = $_GET['token_app'];
  $st = $_GET['st'];
  $date = $_GET['date'];
  $auth = $_GET['auth'];
  $direct = $_GET['status'];

  if($direct == 'direct'){
    $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$auth' WHERE CNOEE = '$cn' AND DLEAVE = '$date'";
    if ($conn->query($sql) === TRUE) {}

    $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$date'";
    $resulta = $conn->query($sqla);

    $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
    $resultb = $conn->query($sqlb);

    $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$cn'";
    $resultc = $conn->query($sqlc);
  }else{
    $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$source' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
    if ($conn->query($sql) === TRUE) {}

    $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
    $resulta = $conn->query($sqla);

    $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
    $resultb = $conn->query($sqlb);

    $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$cn'";
    $resultc = $conn->query($sqlc);
  }

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $staff = $rowb['CNAME'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $email = $rowc['EmailAddress'];
  }

  if($direct == 'direct'){
    header("Location: approval?cn=".$cn."&st=".$date);
  }else{
    header("Location: approval?cn=".$cn."&st=".$st);
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
                    <strong>Annual Leave - Status</strong>
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
                            STATUS: APPROVED
                            <br>
                            LEAVE: ANNUAL LEAVE
                            <br>
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
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

if(isset($_GET['token_mat1'])){

  $token = explode("-", $_GET['token_mat1']);
  $cn = $token[1].'-'.$token[2];
  $mc = $token[4].'-'.$token[5];
  $st = $_GET['date'];
  $fc = $_GET['auth'];
  $file = $_GET['file'];

  $sql = "UPDATE eleave SET MNOTES = 'recommended', CSOURCE = '$mc', ICNTEE = 'processed' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT employees.EmailAddress AS email, employees_demas.CNOEE AS cnoee, employees_demas.CNAME AS cname FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CNOEE = '$fc' AND employees_demas.DRESIGN = '0000-00-00'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $cname = $rowb['cname'];
    $email = $rowb['email'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $staff = $rowc['CNAME'];
  }

  header("Location: recommendation?cn=".$cn."&st=".$st);

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
                    <strong>Maternity Leave - Approval</strong>
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
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
                            <br>
                            REASON: $reason
                          </td>
                        </tr>
                        <tr>
                          <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                            <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                              <tr>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$file'>
                                    SUPPORTING DOCUMENTS
                                  </a>
                                </td>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_mat2=$cn&st=$st&mc=$mc&fc=$fc'>
                                    APPROVE
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

if(isset($_GET['token_mat2'])){

  $source = $_GET['mc'].",".$_GET['fc'];
  $cn = $_GET['token_mat2'];
  $st = $_GET['st'];

  $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$source' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $staff = $rowb['CNAME'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $email = $rowc['EmailAddress'];
  }

  header("Location: approval?cn=".$cn."&st=".$st);

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
                    <strong>Maternity Leave - Status</strong>
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
                            STATUS: APPROVED
                            <br>
                            LEAVE: MATERNITY LEAVE
                            <br>
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
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

if(isset($_GET['token_med1'])){

  $token = explode("-", $_GET['token_med1']);
  $cn = $token[1].'-'.$token[2];
  $st = $_GET['date'];
  $file = $_GET['file'];

  $sql = "UPDATE eleave SET MNOTES = 'recommended', ICNTEE = 'processed' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlc = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));
    $sc = $rowa['CSOURCE'];

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowc = $resultc->fetch_assoc()){
    $staff = $rowc['CNAME'];
  }

  header("Location: other_recommendation?cn=".$cn."&st=".$st);

  try {
    include ('smtp.php');
    $mail->setFrom($smtpEmail, 'i-SEC SYSTEM');
    $mail->addAddress('lai_szechee@sabahenergycorp.com');
    $mail->addCC('adlina@sabahenergycorp.com');
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
                    <strong>Medical Leave - Approval</strong>
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
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
                            <br>
                            REASON: $reason
                          </td>
                        </tr>
                        <tr>
                          <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                            <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                              <tr>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$file'>
                                    DOCUMENTS
                                  </a>
                                </td>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_med2=$cn&st=$st&hc=$sc,2417-109'>
                                    APPROVE BY LAI
                                  </a>
                                </td>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_med2=$cn&st=$st&hc=$sc,2416-170'>
                                    APPROVE BY ADLINA
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

if(isset($_GET['token_med2'])){

  $source = $_GET['hc'];
  $cn = $_GET['token_med2'];
  $st = $_GET['st'];

  $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$source' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $staff = $rowb['CNAME'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $email = $rowc['EmailAddress'];
  }

  header("Location: approval?cn=".$cn."&st=".$st);

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
                    <strong>Medical Leave - Status</strong>
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
                            STATUS: APPROVED
                            <br>
                            LEAVE: MEDICAL LEAVE
                            <br>
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
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

if(isset($_GET['token_pat1'])){

  $token = explode("-", $_GET['token_pat1']);
  $cn = $token[1].'-'.$token[2];
  $mc = $token[4].'-'.$token[5];
  $st = $_GET['date'];
  $fc = $_GET['auth'];
  $file = $_GET['file'];

  $sql = "UPDATE eleave SET MNOTES = 'recommended', CSOURCE = '$mc', ICNTEE = 'processed' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT employees.EmailAddress AS email, employees_demas.CNOEE AS cnoee, employees_demas.CNAME AS cname FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CNOEE = '$fc' AND employees_demas.DRESIGN = '0000-00-00'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $cname = $rowb['cname'];
    $email = $rowb['email'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $staff = $rowc['CNAME'];
  }

  header("Location: recommendation?cn=".$cn."&st=".$st);

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
                    <strong>Paternity Leave - Approval</strong>
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
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
                            <br>
                            REASON: $reason
                          </td>
                        </tr>
                        <tr>
                          <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                            <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                              <tr>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$file'>
                                    SUPPORTING DOCUMENTS
                                  </a>
                                </td>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_pat2=$cn&st=$st&mc=$mc&fc=$fc'>
                                    APPROVE
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

if(isset($_GET['token_pat2'])){

  $source = $_GET['mc'].",".$_GET['fc'];
  $cn = $_GET['token_pat2'];
  $st = $_GET['st'];

  $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$source' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $staff = $rowb['CNAME'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $email = $rowc['EmailAddress'];
  }

  header("Location: approval?cn=".$cn."&st=".$st);

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
                    <strong>Paternity Leave - Status</strong>
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
                            STATUS: APPROVED
                            <br>
                            LEAVE: PATERNITY LEAVE
                            <br>
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
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

if(isset($_GET['token_mar1'])){

  $token = explode("-", $_GET['token_mar1']);
  $cn = $token[1].'-'.$token[2];
  $mc = $token[4].'-'.$token[5];
  $st = $_GET['date'];
  $fc = $_GET['auth'];
  $file = $_GET['file'];

  $sql = "UPDATE eleave SET MNOTES = 'recommended', CSOURCE = '$mc', ICNTEE = 'processed' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT employees.EmailAddress AS email, employees_demas.CNOEE AS cnoee, employees_demas.CNAME AS cname FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CNOEE = '$fc' AND employees_demas.DRESIGN = '0000-00-00'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $cname = $rowb['cname'];
    $email = $rowb['email'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $staff = $rowc['CNAME'];
  }

  header("Location: recommendation?cn=".$cn."&st=".$st);

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
                    <strong>Marriage Leave - Approval</strong>
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
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
                            <br>
                            REASON: $reason
                          </td>
                        </tr>
                        <tr>
                          <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                            <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                              <tr>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#14A44D'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/documents?file=$file'>
                                    SUPPORTING DOCUMENTS
                                  </a>
                                </td>
                                <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                  <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_mar2=$cn&st=$st&mc=$mc&fc=$fc'>
                                    APPROVE
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

if(isset($_GET['token_mar2'])){

  $source = $_GET['mc'].",".$_GET['fc'];
  $cn = $_GET['token_mar2'];
  $st = $_GET['st'];

  $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$source' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $staff = $rowb['CNAME'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $email = $rowc['EmailAddress'];
  }

  header("Location: approval?cn=".$cn."&st=".$st);

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
                    <strong>Marriage Leave - Status</strong>
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
                            STATUS: APPROVED
                            <br>
                            LEAVE: MARRIAGE LEAVE
                            <br>
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
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

if(isset($_GET['token_url1'])){

  $token = explode("-", $_GET['token_url1']);
  $cn = $token[1].'-'.$token[2];
  $mc = $token[4].'-'.$token[5];
  $st = $_GET['date'];
  $fc = $_GET['auth'];

  $sqld = "SELECT * FROM eleave WHERE MNOTES = 'recommended' AND ICNTEE = 'processed' AND CNOEE = '$cn' AND DLEAVE = '$st'";
  $resultd = $conn->query($sqld);

  if ($result->num_rows > 0) {}else{

    $sql = "UPDATE eleave SET MNOTES = 'recommended', CSOURCE = '$mc', ICNTEE = 'processed' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
    if ($conn->query($sql) === TRUE) {}

    $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
    $resulta = $conn->query($sqla);

    $sqlb = "SELECT employees.EmailAddress AS email, employees_demas.CNOEE AS cnoee, employees_demas.CNAME AS cname FROM employees_demas INNER JOIN employees WHERE employees_demas.CNOEE = employees.EmployeeID AND employees_demas.CNOEE = '$fc' AND employees_demas.DRESIGN = '0000-00-00'";
    $resultb = $conn->query($sqlb);

    $sqlc = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
    $resultc = $conn->query($sqlc);

    if($rowa = $resulta->fetch_assoc()){
      $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
      $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
      $days = $rowa['NDAYS'];
      $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

      if($season != ''){
        $reason = $season;
      }else{
        $reason = '-';
      }
    }

    if($rowb = $resultb->fetch_assoc()){
      $cname = $rowb['cname'];
      $email = $rowb['email'];
    }

    if($rowc = $resultc->fetch_assoc()){
      $staff = $rowc['CNAME'];
    }

    header("Location: recommendation?cn=".$cn."&st=".$st);

    $sql1 = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00'";
    $result1 = $conn->query($sql1);

    foreach ($result1 as $key1 => $val1) {
      if($val1['CNOEE'] == $cn){
        $division = $row1['CDIVISION'];

        $sql2 = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$division'";
        $result2 = $conn->query($sql2);

        foreach ($result2 as $key2 => $val2) {
          $snoee = $val2['CNOEE'];

          $sql3 = "SELECT * FROM employees WHERE EmployeeID = '$snoee'";
          $result3 = $conn->query($sql3);

          foreach ($result3 as $key3 => $val3) {
            $smail = $val3['EmailAddress'];
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
                      <strong>Unrecorded Leave - Approval</strong>
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
                              DATE: $start - $end
                              <br>
                              DURATION: $days DAYS
                              <br>
                              REASON: $reason
                            </td>
                          </tr>
                          <tr>
                            <td style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; padding-top: 25px; padding-bottom: 35px;'>
                              <table border='0' cellpadding='0' cellspacing='0' align='left' style='max-width: 500px; min-width: 120px; border-spacing: 0; padding: 0;'>
                                <tr>
                                  <td align='center' style='padding: 12px 24px; margin: 0; text-decoration: none; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;' bgcolor='#5096D3'>
                                    <a target='_blank' style='text-decoration: none; color: black; font-family: sans-serif; font-size: 13px; font-weight: bold; line-height: 120%;' href='https://edata-sec.com/api_ajax?token_url2=$cn&st=$st&mc=$mc&fc=$fc'>
                                      APPROVE
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
                              DATE: $start - $end
                              <br>
                              DURATION: $days DAYS
                              <br>
                              REASON: $reason
                              <br><br>
                            </td>
                          </tr>
                          <tr>
                            <td align='justify' style='border-spacing: 0; margin: 0; padding: 0; padding-right: 6.25%; width: 87.5%; font-size: 13px; font-weight: bold; line-height: 130%; padding-top: 20px; color: black; font-family: sans-serif;'>
                              NOTE: Kindly remind the Divisional Head / DCEO / CEO to approve the leave for the employee mentioned above.
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

if(isset($_GET['token_url2'])){

  $source = $_GET['mc'].",".$_GET['fc'];
  $cn = $_GET['token_url2'];
  $st = $_GET['st'];

  $sql = "UPDATE eleave SET MNOTES = 'approved', CSOURCE = '$source' WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  if ($conn->query($sql) === TRUE) {}

  $sqla = "SELECT * FROM eleave WHERE CNOEE = '$cn' AND DLEAVE = '$st'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$cn'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees WHERE EmployeeID = '$cn'";
  $resultc = $conn->query($sqlc);

  if($rowa = $resulta->fetch_assoc()){
    $start = strtoupper(date("d M Y", strtotime($rowa['DLEAVE'])));
    $end = strtoupper(date("d M Y", strtotime($rowa['DLEAVE2'])));
    $days = $rowa['NDAYS'];
    $season = strtoupper(mysqli_real_escape_string($conn, $rowa['CREASON']));

    if($season != ''){
      $reason = $season;
    }else{
      $reason = '-';
    }
  }

  if($rowb = $resultb->fetch_assoc()){
    $staff = $rowb['CNAME'];
  }

  if($rowc = $resultc->fetch_assoc()){
    $email = $rowc['EmailAddress'];
  }

  header("Location: approval?cn=".$cn."&st=".$st);

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
                    <strong>Unrecorded Leave - Status</strong>
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
                            STATUS: APPROVED
                            <br>
                            LEAVE: UNRECORDED LEAVE
                            <br>
                            DATE: $start - $end
                            <br>
                            DURATION: $days DAYS
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

?>