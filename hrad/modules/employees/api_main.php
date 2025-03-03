<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../api.php'); 

if(isset($_POST['check_cnoee'])){
  $sql = "SELECT * FROM employees WHERE EmployeeID = '".$_POST['check_cnoee']."'";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
    echo "1";
  }
}

if(isset($_POST['add_profile'])){
  $pass = sha1($_POST['pass']);

  $sql = "INSERT INTO employees (EmployeeID, EmailAddress, Password, JabberAccount) VALUES ('".$_POST['add_profile']."', '".$_POST['emel']."', '$pass', '".$_POST['user']."')";
  if ($conn->query($sql) === TRUE) {} else {}

  $sql = "INSERT INTO employees_demas (CNOEE, CNAME) VALUES ('".$_POST['add_profile']."', '".$_POST['name']."')";
  if ($conn->query($sql) === TRUE) {} else {}
}

if(isset($_POST['upload_profile'])){
  unlink("file/".$_POST['image']);
  $code = $_POST['upload_profile'];
  $uploadDir = 'file/';
  $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $fileName = uniqid('file_').'_'.uniqid().'.'.$extension;
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
    $sql = "UPDATE employees_demas SET CIMAGE = '$fileName' WHERE CNOEE = '$code'";
    if ($conn->query($sql) === TRUE) {}
  }
}

if(isset($_POST['clear_profile'])){
  unlink("file/".$_POST['image']);
  $code = $_POST['clear_profile'];
  $sql = "UPDATE employees_demas SET CIMAGE = '' WHERE CNOEE = '$code'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_profile'])){
  $sql = "UPDATE employees SET EmailAddress = '".$_POST['emel']."', JabberAccount = '".$_POST['user']."' WHERE EmployeeID = '".$_POST['edit_profile']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_personal'])){
  $sql = "UPDATE employees_demas SET CNAME = '".$_POST['CNAME']."', CBADGE = '".$_POST['CBADGE']."', CCDACCESS = '".$_POST['CCDACCESS']."', CALIAS = '".$_POST['CALIAS']."', CTITLE = '".$_POST['CTITLE']."', CCOUNTRY = '".$_POST['CCOUNTRY']."', CNOICNEW = '".$_POST['CNOICNEW']."', CNOICOLD = '".$_POST['CNOICOLD']."', DBIRTH = '".$_POST['DBIRTH']."', CCOLORIC = '".$_POST['CCOLORIC']."', CNOPASSPOR = '".$_POST['CNOPASSPOR']."', DXPASSPORT = '".$_POST['DXPASSPORT']."', CSEX = '".$_POST['CSEX']."', CPLACEBIRT = '".$_POST['CPLACEBIRT']."', CRELIGION = '".$_POST['CRELIGION']."', CRACE = '".$_POST['CRACE']."', CSTMARTL = '".$_POST['CSTMARTL']."', CBLOOD = '".$_POST['CBLOOD']."', NHEIGHT = '".$_POST['NHEIGHT']."', NWEIGHT = '".$_POST['NWEIGHT']."', CCDPOST = '".$_POST['CCDPOST']."', CNOPHONE = '".$_POST['CNOPHONE']."', CNOPHONEH = '".$_POST['CNOPHONEH']."', CADDRS1 = '".$_POST['CADDRS']."' WHERE CNOEE = '".$_POST['edit_personal']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_services'])){
  $sql = "UPDATE employees_demas SET DHIRE = '".$_POST['DHIRE']."', DCONFIRM = '".$_POST['DCONFIRM']."', DRESIGN = '".$_POST['DRESIGN']."', IMTHSPROB = '".$_POST["IMTHSPROB"]."', IDYSPROB = '".$_POST["IDYSPROB"]."', IMTHSPROB2 = '".$_POST["IMTHSPROB2"]."', IDYSPROB2 = '".$_POST["IDYSPROB2"]."', IMTHSNOTC = '".$_POST["IMTHSNOTC"]."', IDYSNOTC = '".$_POST["IDYSNOTC"]."', CCATEGORY = '".$_POST["CCATEGORY"]."', CGRADE = '".$_POST["CGRADE"]."', CCLASS = '".$_POST["CCLASS"]."', CJOB = '".$_POST["CJOB"]."', CPOSITION = '".$_POST["CPOSITION"]."', CSCALE = '".$_POST["CSCALE"]."', CTYPEMPL = '".$_POST["CTYPEMPL"]."', CCOSTCENTR = '".$_POST["CCOSTCENTR"]."', CCHARGE = '".$_POST["CCHARGE"]."', CPAYRATE = '".$_POST["CPAYRATE"]."', CMETDCOMP = '".$_POST["CMETDCOMP"]."', YPAYBASIC = '".$_POST["YPAYBASIC"]."', CSUPERIOR = '".$_POST["CSUPERIOR"]."', CSHFTGROUP = '".$_POST["CSHFTGROUP"]."', CCOMPANY = '".$_POST["CCOMPANY"]."', CSUPERVISO = '".$_POST["CSUPERVISO"]."', CBRANCH = '".$_POST["CBRANCH"]."', CDIVISION = '".$_POST["CDIVISION"]."', CDEPARTMEN = '".$_POST["CDEPARTMEN"]."', CENTLLVE = '".$_POST["CENTLLVE"]."', CENTLMEDIC = '".$_POST["CENTLMEDIC"]."', DREFENTL = '".$_POST['DREFENTL']."', CSHIFT = '".$_POST['CSHIFT']."' WHERE CNOEE = '".$_POST['edit_services']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_payroll'])){
  $sql = "UPDATE employees_demas SET CPAYMODE = '".$_POST["CPAYMODE"]."', CPERIODLAS = '".$_POST["CPERIODLAS"]."', CCYCLELAST = '".$_POST["CCYCLELAST"]."', LDEDHRD = '".$_POST["LDEDHRD"]."', CBANK = '".$_POST["CBANK"]."', CNOACC = '".$_POST["CNOACC"]."', CNOASB = '".$_POST["CNOASB"]."', CNOTBHAJI = '".$_POST["CNOTBHAJI"]."', CNOUNION = '".$_POST["CNOUNION"]."', CNOPERMIT = '".$_POST["CNOPERMIT"]."', DCONTRCT = '".$_POST['DCONTRCT']."', DXCONTRCT = '".$_POST['DXCONTRCT']."', DPERMIT = '".$_POST['DPERMIT']."', DXPERMIT = '".$_POST['DXPERMIT']."', CTBLORP = '".$_POST["CTBLORP"]."', CINCRTBL = '".$_POST["CINCRTBL"]."', CBONUSTBL = '".$_POST["CBONUSTBL"]."', CTBLZAKAT = '".$_POST["CTBLZAKAT"]."', CNOEIS = '".$_POST["CNOEIS"]."', CTBLEIS = '".$_POST["CTBLEIS"]."', CCATEIS = '".$_POST["CCATEIS"]."', CICEIS = '".$_POST["CICEIS"]."', CNOEPF = '".$_POST["CNOEPF"]."', CTBLEPF = '".$_POST["CTBLEPF"]."', CINITEPF = '".$_POST["CINITEPF"]."', CCATEPF = '".$_POST["CCATEPF"]."', CNKEPF = '".$_POST["CNKEPF"]."', CICEPF = '".$_POST["CICEPF"]."', CREFEPF = '".$_POST["CREFEPF"]."', CNOSOCSO = '".$_POST["CNOSOCSO"]."', CTBLSOCSO = '".$_POST["CTBLSOCSO"]."', CCATSOCSO = '".$_POST["CCATSOCSO"]."', CICSOCSO = '".$_POST["CICSOCSO"]."', CREFSOCSO = '".$_POST["CREFSOCSO"]."', CNOTAX = '".$_POST["CNOTAX"]."', CTBLTAX = '".$_POST["CTBLTAX"]."', CBRANCHTAX = '".$_POST["CBRANCHTAX"]."', CREFTAX = '".$_POST["CREFTAX"]."', LERPAYTAX = '".$_POST["LERPAYTAX"]."' WHERE CNOEE = '".$_POST['edit_payroll']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_spouse'])){
  $sql = "UPDATE employees_demas SET CSPSNAME = '".$_POST["CSPSNAME"]."', DMARRIED = '".$_POST['DMARRIED']."', INOCHILD = '".$_POST["INOCHILD"]."',INOCHILDTA = '".$_POST["INOCHILDTA"]."', CSPSICNEW = '".$_POST["CSPSICNEW"]."', CSPSICOLD = '".$_POST["CSPSICOLD"]."', CSPSICCOL = '".$_POST["CSPSICCOL"]."', CSPSNOEPF = '".$_POST["CSPSNOEPF"]."', CSPSNOTAX = '".$_POST["CSPSNOTAX"]."', CSPSBRNTAX = '".$_POST["CSPSBRNTAX"]."', CSPSOCCUP = '".$_POST["CSPSOCCUP"]."', CSPSEMPER = '".$_POST["CSPSEMPER"]."', CKINNAME = '".$_POST["CKINNAME"]."', CKINRELATN = '".$_POST["CKINRELATN"]."', CKINPHONEH = '".$_POST["CKINPHONEH"]."', CKINPHONEO = '".$_POST["CKINPHONEO"]."', CKINOCCUP = '".$_POST["CKINOCCUP"]."', CKINADDRS1 = '".$_POST["CKINADDRS"]."' WHERE CNOEE = '".$_POST['edit_spouse']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['add_in_service'])){
  $sql = "INSERT INTO employee_inservice (CNOEE, CSEQUENCE, CTYPREC, DREC, CSHFTGROUP, CJOB, CPOSITION, CCATEGORY, CGRADE, CTYPEMPL, CCLASS, CCOSTCENTR, CSCALE, CCHARGE, YPAYBASIC, YINCREMENT, CPAYRATE, CMETDCOMP, CSUPERIOR, CSUPERVISO, CCOMPANY, CBRANCH, CENTLLVE, CENTLMEDIC, CDEPARTMEN, CDIVISION, IMTHSPROB, IMTHSNOTC, CRREASON) VALUES ('".$_POST['add_in_service']."', '".$_POST["CSEQUENCE"]."', '".$_POST["CTYPREC"]."', '".$_POST['DREC']."', '".$_POST["CSHFTGROUP"]."', '".$_POST["CJOB"]."', '".$_POST["CPOSITION"]."', '".$_POST["CCATEGORY"]."', '".$_POST["CGRADE"]."', '".$_POST["CTYPEMPL"]."', '".$_POST["CCLASS"]."', '".$_POST["CCOSTCENTR"]."', '".$_POST["CSCALE"]."', '".$_POST["CCHARGE"]."', '".$_POST["YPAYBASIC"]."', '".$_POST["YINCREMENT"]."', '".$_POST["CPAYRATE"]."', '".$_POST["CMETDCOMP"]."', '".$_POST["CSUPERIOR"]."', '".$_POST["CSUPERVISO"]."', '".$_POST["CCOMPANY"]."', '".$_POST["CBRANCH"]."', '".$_POST["CENTLLVE"]."', '".$_POST["CENTLMEDIC"]."', '".$_POST["CDEPARTMEN"]."', '".$_POST["CDIVISION"]."', '".$_POST["IMTHSPROB"]."', '".$_POST["IMTHSNOTC"]."', '".$_POST["CRREASON"]."')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_in_service'])){
  $sql = "UPDATE employee_inservice SET CTYPREC = '".$_POST["CTYPREC"]."', DREC = '".$_POST['DREC']."', CSHFTGROUP = '".$_POST["CSHFTGROUP"]."', CJOB = '".$_POST["CJOB"]."', CPOSITION = '".$_POST["CPOSITION"]."', CCATEGORY = '".$_POST["CCATEGORY"]."', CGRADE = '".$_POST["CGRADE"]."', CTYPEMPL = '".$_POST["CTYPEMPL"]."', CCLASS = '".$_POST["CCLASS"]."', CCOSTCENTR = '".$_POST["CCOSTCENTR"]."', CSCALE = '".$_POST["CSCALE"]."', CCHARGE = '".$_POST["CCHARGE"]."', YPAYBASIC = '".$_POST["YPAYBASIC"]."', YINCREMENT = '".$_POST["YINCREMENT"]."', CPAYRATE = '".$_POST["CPAYRATE"]."', CMETDCOMP = '".$_POST["CMETDCOMP"]."', CSUPERIOR = '".$_POST["CSUPERIOR"]."', CSUPERVISO = '".$_POST["CSUPERVISO"]."', CCOMPANY = '".$_POST["CCOMPANY"]."', CBRANCH = '".$_POST["CBRANCH"]."', CENTLLVE = '".$_POST["CENTLLVE"]."', CENTLMEDIC = '".$_POST["CENTLMEDIC"]."', CDEPARTMEN = '".$_POST["CDEPARTMEN"]."', CDIVISION = '".$_POST["CDIVISION"]."', IMTHSPROB = '".$_POST["IMTHSPROB"]."', IMTHSNOTC = '".$_POST["IMTHSNOTC"]."', CRREASON = '".$_POST["CRREASON"]."' WHERE ID = '".$_POST['edit_in_service']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_in_service'])){
  $sql = "DELETE FROM employee_inservice WHERE ID = '".$_POST['delete_in_service']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE employee_inservice DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE employee_inservice ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_qualification'])){

  $sql = "INSERT INTO employee_dquali (CNOEE, CCDQUALI, CDESC, LHIGHEST, IYEARFROM, IYEARTO, CGRADE, CSCHOOL, CMAJOR, CSTATUS, CNOCERT) VALUES ('".$_POST['add_qualification']."', '".$_POST['CCDQUALI']."', '".$_POST['CDESC']."', '".$_POST['LHIGHEST']."', '".$_POST['IYEARFROM']."', '".$_POST['IYEARTO']."', '".$_POST['CGRADE']."', '".$_POST['CSCHOOL']."', '".$_POST['CMAJOR']."', '".$_POST['CSTATUS']."', '".$_POST['CNOCERT']."')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_qualification'])){
  $sql = "UPDATE employee_dquali SET CCDQUALI = '".$_POST["CCDQUALI"]."', CDESC = '".$_POST["CDESC"]."', LHIGHEST = '".$_POST["LHIGHEST"]."', IYEARFROM = '".$_POST["IYEARFROM"]."', IYEARTO = '".$_POST["IYEARTO"]."', CGRADE = '".$_POST["CGRADE"]."', CSCHOOL = '".$_POST["CSCHOOL"]."', CMAJOR = '".$_POST["CMAJOR"]."', CSTATUS = '".$_POST["CSTATUS"]."', CNOCERT = '".$_POST["CNOCERT"]."' WHERE ID = '".$_POST['edit_qualification']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_qualification'])){
  $sql = "DELETE FROM employee_dquali WHERE ID = '".$_POST['delete_qualification']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE employee_dquali DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE employee_dquali ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_job_history'])){
  $sql = "INSERT INTO employee_djobhis (CNOEE, DFROM, DTO, CCOMPANY, CPOSITION, CCDEXPRNCE, YPAYLAST, IMONTHS, CREASON, CRATEPAY, CADDRS1, MNOTES) VALUES ('".$_POST['add_job_history']."', '".$_POST['DFROM']."', '".$_POST['DTO']."', '".mysqli_real_escape_string($conn, $_POST['CCOMPANY'])."', '".mysqli_real_escape_string($conn, $_POST['CPOSITION'])."', '".mysqli_real_escape_string($conn, $_POST['CCDEXPRNCE'])."', '".mysqli_real_escape_string($conn, $_POST['YPAYLAST'])."', '".mysqli_real_escape_string($conn, $_POST['IMONTHS'])."', '".mysqli_real_escape_string($conn, $_POST['CREASON'])."', '".mysqli_real_escape_string($conn, $_POST['CRATEPAY'])."', '".mysqli_real_escape_string($conn, $_POST['CADDRS1'])."', '".mysqli_real_escape_string($conn, $_POST['MNOTES'])."')";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_job_history'])){
  $sql = "UPDATE employee_djobhis SET DFROM = '".$_POST['DFROM']."', DTO = '".$_POST['DTO']."', CCOMPANY = '".$_POST["CCOMPANY"]."', CPOSITION = '".$_POST["CPOSITION"]."', CCDEXPRNCE = '".$_POST["CCDEXPRNCE"]."', YPAYLAST = '".$_POST["YPAYLAST"]."', IMONTHS = '".$_POST["IMONTHS"]."', CREASON = '".$_POST["CREASON"]."', CRATEPAY = '".$_POST["CRATEPAY"]."', CADDRS1 = '".$_POST["CADDRS1"]."', MNOTES = '".$_POST["MNOTES"]."' WHERE ID = '".$_POST['edit_job_history']."'";
  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_job_history'])){
  $sql = "DELETE FROM employee_djobhis WHERE ID = '".$_POST['delete_job_history']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE employee_djobhis DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE employee_djobhis ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_family'])){
  $sql = "INSERT INTO employee_dfamily (CNOEE, CNAME, CRELATION, CSEX, CSTATUS, DBIRTH, CNOIC, CNOBIRTHCE, LTAXABLE) VALUES ('".$_POST['add_family']."', '".$_POST["CNAME"]."', '".$_POST["CRELATION"]."', '".$_POST["CSEX"]."', '".$_POST["CSTATUS"]."', '".$_POST['DBIRTH']."', '".$_POST["CNOIC"]."', '".$_POST["CNOBIRTHCE"]."', '".$_POST["LTAXABLE"]."')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_family'])){
  $sql = "UPDATE employee_dfamily SET CNAME = '".$_POST["CNAME"]."', CRELATION = '".$_POST["CRELATION"]."', CSEX = '".$_POST["CSEX"]."', CSTATUS = '".$_POST["CSTATUS"]."', DBIRTH = '".$_POST['DBIRTH']."', CNOIC = '".$_POST["CNOIC"]."', CNOBIRTHCE = '".$_POST["CNOBIRTHCE"]."', LTAXABLE = '".$_POST["LTAXABLE"]."' WHERE ID = '".$_POST['edit_family']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_family'])){
  $sql = "DELETE FROM employee_dfamily WHERE ID = '".$_POST['delete_family']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE employee_dfamily DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE employee_dfamily ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_skill'])){
  $sql = "INSERT INTO employee_dskill (CNOEE, CCDSKILL, CLEVEL, CSTATUS, CDESC, IYEARFROM, IYEARTO, CTHRU) VALUES ('".$_POST['add_skill']."', '".$_POST['CCDSKILL']."', '".$_POST['CLEVEL']."', '".$_POST['CSTATUS']."', '".$_POST['CDESC']."', '".$_POST['IYEARFROM']."', '".$_POST['IYEARTO']."', '".$_POST['CTHRU']."')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_skill'])){
  $sql = "UPDATE employee_dskill SET CCDSKILL = '".$_POST['CCDSKILL']."', CLEVEL = '".$_POST['CLEVEL']."', CSTATUS = '".$_POST['CSTATUS']."', CDESC = '".$_POST['CDESC']."', IYEARFROM = '".$_POST['IYEARFROM']."', IYEARTO = '".$_POST['IYEARTO']."', CTHRU = '".$_POST['CTHRU']."' WHERE ID = '".$_POST['edit_skill']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_skill'])){
  $sql = "DELETE FROM employee_dskill WHERE ID = '".$_POST['delete_skill']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE employee_dskill DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE employee_dskill ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_membership'])){
  $sql = "INSERT INTO employee_dmember (CNOEE, CCDMEMBER, CDESC, CNOMEMBER, DSINCE, LCORPORATE, LTRANSFER) VALUES ('".$_POST['add_membership']."', '".$_POST['CCDMEMBER']."', '".$_POST['CDESC']."', '".$_POST['CNOMEMBER']."', '".$_POST['DSINCE']."', '".$_POST['LCORPORATE']."', '".$_POST['LTRANSFER']."')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_membership'])){
  $sql = "UPDATE employee_dmember SET CCDMEMBER = '".$_POST['CCDMEMBER']."', CDESC = '".$_POST['CDESC']."', CNOMEMBER = '".$_POST['CNOMEMBER']."', DSINCE = '".$_POST['DSINCE']."', LCORPORATE = '".$_POST['LCORPORATE']."', LTRANSFER = '".$_POST['LTRANSFER']."' WHERE ID = '".$_POST['edit_membership']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_membership'])){
  $sql = "DELETE FROM employee_dmember WHERE ID = '".$_POST['delete_membership']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE employee_dmember DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE employee_dmember ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['add_loan'])){
  $sql = "INSERT INTO employee_fxloan (CNOEE, CREFLOAN, DLOAN, CCDLOAN, CBANK, YAMTLOAN, NINTEREST, CPERIOD, CPERIOD2, YAMTPAYMNT, YAMTLAST, YAMTPAID, CCDDED, CCYCLE, NBONDMTHS, DSBOND, DXBOND, YAMTBOND, MNOTES) VALUES ('".$_POST['add_loan']."', '".$_POST['CREFLOAN']."', '".$_POST['DLOAN']."', '".$_POST['CCDLOAN']."', '".$_POST['CBANK']."', '".$_POST['YAMTLOAN']."', '".$_POST['NINTEREST']."', '".$_POST['CPERIOD']."', '".$_POST['CPERIOD2']."', '".$_POST['YAMTPAYMNT']."', '".$_POST['YAMTLAST']."', '".$_POST['YAMTPAID']."', '".$_POST['CCDDED']."', '".$_POST['CCYCLE']."', '".$_POST['NBONDMTHS']."', '".$_POST['DSBOND']."', '".$_POST['DXBOND']."', '".$_POST['YAMTBOND']."', '".$_POST['MNOTES']."')";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['edit_loan'])){
  $sql = "UPDATE employee_fxloan SET CREFLOAN = '".$_POST['CREFLOAN']."', DLOAN = '".$_POST['DLOAN']."', CCDLOAN = '".$_POST['CCDLOAN']."', CBANK = '".$_POST['CBANK']."', YAMTLOAN = '".$_POST['YAMTLOAN']."', NINTEREST = '".$_POST['NINTEREST']."', CPERIOD = '".$_POST['CPERIOD']."', CPERIOD2 = '".$_POST['CPERIOD2']."', YAMTPAYMNT = '".$_POST['YAMTPAYMNT']."', YAMTLAST = '".$_POST['YAMTLAST']."', YAMTPAID = '".$_POST['YAMTPAID']."', CCDDED = '".$_POST['CCDDED']."', CCYCLE = '".$_POST['CCYCLE']."', NBONDMTHS = '".$_POST['NBONDMTHS']."', DSBOND = '".$_POST['DSBOND']."', DXBOND = '".$_POST['DXBOND']."', YAMTBOND = '".$_POST['YAMTBOND']."', MNOTES = '".$_POST['MNOTES']."' WHERE ID = '".$_POST['edit_loan']."'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['delete_loan'])){
  $sql = "DELETE FROM employee_fxloan WHERE ID = '".$_POST['delete_loan']."'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE employee_fxloan DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE employee_fxloan ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

$conn->close();
?>