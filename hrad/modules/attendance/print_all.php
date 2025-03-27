<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");
  
include('/var/www/html/api.php');
require('/var/www/html/pdf/fpdf.php');

$divi = $_GET['divi'];

class PDF extends FPDF {
  function Footer() {
    $this->SetY(-15);
    $this->SetFont('Arial','BI',8);
    $this->Cell(0,10,'Auto-Generated System. Cross-check Face Recognition data with the Leave System.',0,0,'L');
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Copyright '.chr(169).' '.date("Y"),0,0,'R');
  }
}

$pdf = new PDF();
$pdf->AliasNbPages();

$s2 = "SELECT * FROM sys_general_dcmisc WHERE CTYPE = 'DIVSN' AND CCODE = '$divi'";
$r2 = $conn->query($s2);

if ($v2 = $r2->fetch_assoc()) {
  $ndivi = $v2['CDESC'];
}

$sql = "SELECT e.CNAME, a.* FROM attendance a JOIN employees_demas e ON a.CNOEE = e.CNOEE WHERE e.CDIVISION = '$divi' AND e.DRESIGN = '0000-00-00' GROUP BY a.CNOEE ORDER BY e.CNAME ASC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM attendance";
$result1 = $conn->query($sql1);

$date = [];
foreach ($result1 as $val1) {
  $date[] = strtoupper(date("d F Y", strtotime($val1['edate'])));
}

foreach ($result as $key => $val) {
  $pdf->AddPage('L', 'A4');
  $cnoee = $val['cnoee'];
  $cname = $val['CNAME'];

  $pdf->SetFont('Arial','B',12);
  $pdf->SetXY(10,10);
  $pdf->Cell(188,7,'SABAH ENERGY CORPORATION SDN. BHD.',0,0,'L');
  $pdf->SetFont('Arial','I',9);
  $pdf->SetXY(10,16);
  $pdf->Cell(188,4.8,'TIME AND ATTENDANCE REPORT --- '.$date[0].' TO '.end($date),0,0,'L');
  $pdf->SetFont('Arial','',10);
  $pdf->SetXY(10,21);
  $pdf->Cell(277,8,$cname.' / '.$ndivi,0,0,'L');
  $pdf->Image('/var/www/html/img/sec.png',277,10,10);

  $pdf->SetFont('Arial','B',8);
  $pdf->SetXY(10,29);
  $pdf->Cell(40,8,'Date',1,0,'C');
  $x = $pdf->GetX(); 
  $y = $pdf->GetY();
  $pdf->MultiCell(60,4,"Clock In & Out\n(7:30 to 9:00) & (16:30 to 18:00)",1,'C');
  $pdf->SetXY($x + 60,$y);
  $pdf->Cell(30,8,'Lateness',1,0,'C');
  $pdf->Cell(30,8,'Early Out',1,0,'C');
  $pdf->Cell(40,8,'Type',1,0,'C');
  $pdf->Cell(80,8,'Remarks',1,1,'C');
  
  $sql1 = "SELECT * FROM attendance WHERE cnoee = '$cnoee' ";
  $result1 = $conn->query($sql1);

  $pdf->SetFont('Arial','',8);
  $lastKey = mysqli_num_rows($result1) - 1;
  $key1 = 0;
  foreach ($result1 as $key1 => $val1) {
    $clockIn = ($val1['cint'] == "00:00:00") ? "x" : date('H:i',strtotime($val1['cint']));
    $clockOut = ($val1['cout'] == "00:00:00") ? "x" : date('H:i',strtotime($val1['cout']));
    $clockText = ($val1['cint'] == "00:00:00" && $val1['cout'] == "00:00:00") ? "-" : "$clockIn - $clockOut";
    $lateText = ($val1['late'] != '00:00:00') ? ((($h = intval(explode(':', $val1['late'])[0])) ? $h.'h' : '').(($m = intval(explode(':', $val1['late'])[1])) ? ($h ? ' ' : '').$m.'m' : '')) : '';
    $earlyText = ($val1['early'] != '00:00:00') ? ((($h = intval(explode(':', $val1['early'])[0])) ? $h.'h' : '').(($m = intval(explode(':', $val1['early'])[1])) ? ($h ? ' ' : '').$m.'m' : '')) : '';


    $border = ($key1 == $lastKey) ? 'RBL' : 'RL';

    $pdf->Cell(40,4.8,date("D, d M Y",strtotime($val1['edate'])),$border,0,'C');
    $pdf->Cell(60,4.8,$clockText,1,0,'C');
    $pdf->Cell(30,4.8,$lateText,$border,0,'C');
    $pdf->Cell(30,4.8,$earlyText,$border,0,'C');
    $pdf->Cell(40,4.8,$val1['type'],1,0,'C');
    $pdf->Cell(80,4.8,$val1['remarks'],$border,1,'L');

    if ($val1['late'] != '00:00:00') {
      list($hours, $minutes, $seconds) = explode(':', $val1['late']);
      $totalM1[$key] += ($hours * 60) + $minutes;
    }

    if ($val1['early'] != '00:00:00') {
      list($hours, $minutes, $seconds) = explode(':', $val1['early']);
      $totalM2[$key] += ($hours * 60) + $minutes;
    }
  }

  $totalH1 = floor($totalM1[$key]  / 60);
  $totalMin1 = $totalM1[$key]  % 60;
  $lateText1 = ($totalH1 || $totalMin1) ? (($totalH1 ? $totalH1.'h ' : '').($totalMin1 ? $totalMin1.'m' : '')) : '-';

  $totalH2 = floor($totalM2[$key] / 60);
  $totalMin2 = $totalM2[$key] % 60;
  $earlyText1 = ($totalH2 || $totalMin2) ? (($totalH2 ? $totalH2.'h ' : '').($totalMin2 ? $totalMin2.'m' : '')) : '-';

  $totalMin3 = $totalMin1 + $totalMin2;
  $totalH3 = $totalH1 + $totalH2 + floor($totalMin3 / 60);
  $totalMin3 = $totalMin3 % 60;
  $total3 = ($totalH3 || $totalMin3) ? (($totalH3 ? $totalH3.'h ' : '').($totalMin3 ? $totalMin3.'m' : '')) : '-';

  $pdf->SetFont('Arial','BI',8);
  $pdf->Cell(100,4.1,'Cumulative Time',0,0,'R');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(30,4.1,$lateText1,1,0,'C');
  $pdf->Cell(30,4.1,$earlyText1,1,0,'C');
  $pdf->Cell(40,4.1,$total3,1,0,'C');
}

$pdf->Output();
$conn->close();

?>