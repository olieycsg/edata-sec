<?php 

include('../../../../api.php');

ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);

if(isset($_POST['searchClinic'])){

  $class = $_POST['searchClinic'];

  $sql = "SELECT * FROM sys_general_dcmisc WHERE CCLASS = '$class'";
  $result = $conn->query($sql);

  foreach ($result as $key => $row) {
    echo "<option value='{$row['CCODE']}'>{$row['CDESC']}</option>";
  }
}

if(isset($_POST['addMedical'])){

  $refid = $_POST['addMedical'];
  $clinic = $_POST['clinic'];
  $date = date("Y-m-d H:i:s");
  $empid = $_POST['empid'];
  $spouse = $_POST['spouse'];
  $dependants = isset($_POST['dependants']) ? $_POST['dependants'] : [];
  $dependants = array_pad($dependants, 8, '');

  $sql = "INSERT INTO medical_fxmedcht (CMCCHIT, CCLINIC, DCHIT, CNOEE, CSPOUSE, CDEPENDANT, CDEPENDAN2, CDEPENDAN3, CDEPENDAN4, CDEPENDAN5, CDEPENDAN6, CDEPENDAN7, CDEPENDAN8, CSTATUS, MNOTES) VALUES ('$refid', '$clinic', '$date', '$empid', '$spouse', '{$dependants[0]}', '{$dependants[1]}', '{$dependants[2]}', '{$dependants[3]}', '{$dependants[4]}', '{$dependants[5]}', '{$dependants[6]}', '{$dependants[7]}',  'APPROVED', '')";

  if ($conn->query($sql)) {}
}

if(isset($_POST['editMedical'])){

  $refid = $_POST['editMedical'];
  $clinic = $_POST['clinic'];
  $date = date("Y-m-d H:i:s");
  $empid = $_POST['empid'];
  $spouse = $_POST['spouse'];
  $dependants = isset($_POST['dependants']) ? $_POST['dependants'] : [];
  $dependants = array_pad($dependants, 8, '');

  $sql = "UPDATE medical_fxmedcht SET CCLINIC = '$clinic', DCHIT = '$date', CNOEE = '$empid', CSPOUSE = '$spouse', CDEPENDANT = '{$dependants[0]}', CDEPENDAN2 = '{$dependants[1]}', CDEPENDAN3 = '{$dependants[2]}', CDEPENDAN4 = '{$dependants[3]}', CDEPENDAN5 = '{$dependants[4]}', CDEPENDAN6 = '{$dependants[5]}', CDEPENDAN7 = '{$dependants[6]}', CDEPENDAN8 = '{$dependants[7]}', CSTATUS = 'APPROVED', MNOTES = '' WHERE CMCCHIT = '$refid'";

  if ($conn->query($sql)) {}
}

if(isset($_POST['cancelMedical'])){

  $id = $_POST['cancelMedical'];
  $sql = "UPDATE medical_fxmedcht SET CSTATUS = 'CANCELLED' WHERE ID = '$id'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['approveMedical'])){

  $id = $_POST['approveMedical'];
  $sql = "UPDATE medical_fxmedcht SET CSTATUS = 'APPROVED' WHERE ID = '$id'";

  if ($conn->query($sql) === TRUE) {}
}

if(isset($_POST['deleteMedical'])){

  $id = $_POST['deleteMedical'];
  $sql = "DELETE FROM medical_fxmedcht WHERE ID = '$id'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE medical_fxmedcht DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE medical_fxmedcht ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}

if(isset($_POST['setupnewDoctor'])){
  $code = $_POST['setupnewDoctor'];
  $label = $_POST['label'];
  $type = $_POST['clinic'];
  $desc = $_POST['desc'];
  $sql = "INSERT INTO sys_general_dcmisc (CTYPE, CCODE, CCLASS, CDESC, CLABEL) VALUES ('DOCTR', '$code', '$type', '$desc', '$label')";
  if ($conn->query($sql)) {}
}

if(isset($_POST['esetupnewDoctor'])){
  $code = $_POST['esetupnewDoctor'];
  $label = $_POST['elabel'];
  $type = $_POST['eclinic'];
  $desc = $_POST['edesc'];
  $id = $_POST['eid'];
  $sql = "UPDATE sys_general_dcmisc SET CCODE = '$code', CCLASS = '$type', CDESC = '$desc', CLABEL = '$label' WHERE ID = '$id'";
  if ($conn->query($sql)) {}
}


if(isset($_POST['setupdeleteDoctor'])){

  $id = $_POST['setupdeleteDoctor'];
  $sql = "DELETE FROM sys_general_dcmisc WHERE ID = '$id'";
  if ($conn->query($sql) === TRUE) {
    $sql = "ALTER TABLE sys_general_dcmisc DROP ID";
    if ($conn->query($sql) === TRUE) {
      $sql = "ALTER TABLE sys_general_dcmisc ADD ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      if ($conn->query($sql) === TRUE) {}
    }
  }
}


?>