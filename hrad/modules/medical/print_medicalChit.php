<?php 

include('../../../api.php');
require('../../../pdf/fpdf.php');

$emid = $_GET['emid'];
$refd = $_GET['refd'];

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid'";
$result = $conn->query($sql);

if($row = $result->fetch_assoc()){

  $divi = $row['CDIVISION'];

  $sql1 = "SELECT * FROM medical_fxmedcht WHERE ID = '$refd'";
  $result1 = $conn->query($sql1);

  $sql2 = "SELECT * FROM sys_general_dcmisc WHERE CTYPE = 'DIVSN' AND CCODE = '$divi'";
  $result2 = $conn->query($sql2);

  if($row1 = $result1->fetch_assoc()){
    $ref = $row1['CMCCHIT'];
    $dchit = $row1['DCHIT'];
    $spouse = $row1['CSPOUSE'];
    $cclinic = $row1['CCLINIC'];
    $d1 = $row1['CDEPENDANT'];
    $d2 = $row1['CDEPENDAN2'];
    $d3 = $row1['CDEPENDAN3'];
    $d4 = $row1['CDEPENDAN4'];
    $d5 = $row1['CDEPENDAN5'];
    $d6 = $row1['CDEPENDAN6'];
    $d7 = $row1['CDEPENDAN7'];
    $d8 = $row1['CDEPENDAN8'];
  }

  if($row2 = $result2->fetch_assoc()){
    $divname = $row2['CDESC'];
  }

  $sql3 = "SELECT * FROM sys_general_dcmisc WHERE CCODE = '$cclinic'";
  $result3 = $conn->query($sql3);

  if($row3 = $result3->fetch_assoc()){
    $clinic = $row3['CDESC'];
  }

  class PDF extends FPDF {
    function Footer(){
      $this->SetTextColor(0, 0, 0);
      $this->SetY(-15);
      $this->SetFont('Arial', 'BI', 7);
      $this->Cell(0, 10, '1ST COPY - TO BE PRESENTED TO THE MEDICAL OFFICER / CLINIC', 0, 0, 'L');
      $this->Cell(0, 10, '2ND COPY - SEC FILE', 0, 0, 'R');
    }
  }

  $pdf = new PDF();
  $pdf->AliasNbPages();
  $pdf->AddPage('P','A4');

  $pdf->Image('img/logo.png',15,15,60);
  $pdf->SetFont('Arial','B',22);
  $pdf->SetTextColor(53,115,167);
  $pdf->SetXY(138,18);
  $pdf->Cell(40,15,'MEDICAL CHIT',0,0,'L');

  $pdf->ln(20);
  $pdf->SetFont('Arial','B',9);
  $pdf->SetX(15);
  $pdf->SetTextColor(255,255,255);
  $pdf->SetFillColor(0,100,0);
  $pdf->Cell(180,7,'DEAR '.$clinic,0,0,'L',true);

  $pdf->ln();
  $pdf->SetFont('Arial','',8);
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(241,241,241);
  $pdf->SetX(15);
  $pdf->SetTextColor(0,0,0);
  $pdf->MultiCell(180,8,'KINDLY PROVIDE THE NECESSARY MEDICAL CONSULTATION / TREATMENT',0,'J',true);

  $pdf->ln();
  $pdf->SetFont('Arial','B',9);
  $pdf->SetX(15);
  $pdf->SetTextColor(255,255,255);
  $pdf->SetFillColor(53,115,167);
  $pdf->Cell(180,7,'EMPLOYEE',0,0,'L',true);

  $pdf->ln();
  $pdf->SetFont('Arial','',8);
  $pdf->SetX(15);
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(241,241,241);
  $pdf->SetX(15);
  $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
  $pdf->SetX(45);
  $pdf->Cell(135,7,': '.$row['CNAME'],0,0,'L',true);
  $pdf->ln();
  $pdf->SetX(15);
  $pdf->Cell(30,7,'REF NO',0,0,'L',true);
  $pdf->SetX(45);
  $pdf->Cell(45,7,': '.$ref,0,0,'L',true);
  $pdf->SetX(90);
  $pdf->Cell(20,7,'DATE',0,0,'L',true);
  $pdf->SetX(110);
  $pdf->Cell(85,7,': '.strtoupper(date("d F Y", strtotime($dchit))),0,0,'L',true);
  $pdf->ln();
  $pdf->SetX(15);
  $pdf->Cell(30,7,'NATIONAL ID',0,0,'L',true);
  $pdf->SetX(45);
  $pdf->Cell(45,7,': '.$row['CNOICNEW'],0,0,'L',true);
  $pdf->SetX(90);
  $pdf->Cell(20,7,'DIVISION',0,0,'L',true);
  $pdf->SetX(110);
  $pdf->Cell(85,7,': '.$divname,0,0,'L',true);

  $pdf->ln(15);
  $pdf->SetFont('Arial','B',9);
  $pdf->SetX(15);
  $pdf->SetTextColor(255,255,255);
  $pdf->SetFillColor(53,115,167);
  $pdf->Cell(180,7,'SPOUSE',0,0,'L',true);

  $pdf->ln();
  $pdf->SetFont('Arial','',8);
  $pdf->SetX(15);
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(241,241,241);
  if($spouse != ''){
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$spouse,0,0,'L',true);
  }else{
    $pdf->Cell(180,8,'N/A',0,0,'L',true);
  }

  $pdf->ln(15);
  $pdf->SetFont('Arial','B',9);
  $pdf->SetX(15);
  $pdf->SetTextColor(255,255,255);
  $pdf->SetFillColor(53,115,167);
  $pdf->Cell(180,7,'DEPENDANTS',0,0,'L',true);

  $pdf->ln();
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(241,241,241);
  $pdf->SetTextColor(0,0,0);
  $pdf->SetX(15);
  if($d1 != ''){
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d1,0,0,'L',true);
  }else{
    $pdf->Cell(180,8,'N/A',0,0,'L',true);
  }

  if($d2 != ''){
    $pdf->ln();
    $pdf->SetX(15);
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d2,0,0,'L',true);
  }

  if($d3 != ''){
    $pdf->ln();
    $pdf->SetX(15);
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d3,0,0,'L',true);
  }

  if($d4 != ''){
    $pdf->ln();
    $pdf->SetX(15);
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d4,0,0,'L',true);
  }

  if($d5 != ''){
    $pdf->ln();
    $pdf->SetX(15);
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d5,0,0,'L',true);
  }

  if($d6 != ''){
    $pdf->ln();
    $pdf->SetX(15);
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d6,0,0,'L',true);
  }

  if($d7 != ''){
    $pdf->ln();
    $pdf->SetX(15);
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d7,0,0,'L',true);
  }

  if($d8 != ''){
    $pdf->ln();
    $pdf->SetX(15);
    $pdf->Cell(180,8,'FULL NAME',0,0,'L',true);
    $pdf->SetX(45);
    $pdf->Cell(135,7,': '.$d8,0,0,'L',true);
  }

  $pdf->SetFont('Arial','',8);
  $pdf->SetXY(15,195);
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(255,255,255);
  $pdf->Cell(180,8,'YOURS FAITHFULLY',0,0,'L',true);
  $pdf->SetXY(15,215);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(79,8,'SR. MANAGER, HUMAN RESOURCE & ADMINISTRATION','T',0,'L',true);
  $pdf->SetXY(15,221);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(79,6,'FOR SABAH ENERGY CORPORATION SDN. BHD.',0,0,'L',true);

  $pdf->SetXY(15,237);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(79,7,'MEDICAL CONSULTATION / TREATMENT WAS PROVIDED ON :-',0,0,'L',true);
  $pdf->SetXY(15,242);
  $pdf->Cell(79,7,'DATE : ',0,0,'L',true);
  $pdf->SetXY(15,247);
  $pdf->Cell(79,7,'TIME : ',0,0,'L',true);

  $pdf->Output();

}

$conn->close();

?>