<?php

/*ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
error_reporting(E_ALL);*/

include('../../../api.php');

$name = $_POST['name'];

$sql = "SELECT * FROM attendance WHERE name = '$name'";
$result = $conn->query($sql);

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Date</th>
              <th scope="col" style="text-align: center;">Clock (In | Out)<br>(7:30 to 9:00) & (16:30 to 18:00)</th>
              <th scope="col" style="text-align: center;">Lateness</th>
              <th scope="col" style="text-align: center;">Early Out</th>
              <th scope="col">Type</th>
              <th scope="col">Remarks</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($result as $key => $val) { ?>
            <tr style="color: blue!important;">
              <td><?php echo date("d M Y", strtotime($val['edate'])); ?></td>
              <td style="text-align: center;">
                <?php echo ($val['cint'] == "00:00:00" && $val['cout'] == "00:00:00") ? "-" : (($val['cint'] == "00:00:00" ? "x" : date('H:i', strtotime($val['cint'])))." - ".($val['cout'] == "00:00:00" ? "x" : date('H:i', strtotime($val['cout'])))); ?>
              </td>
              <td style="text-align: center;">
                <?php echo ($val['late'] != '00:00:00') ? (intval(explode(':', $val['late'])[0]) ? intval(explode(':', $val['late'])[0]).'h ' : '').intval(explode(':', $val['late'])[1]).'m' : ''; ?>
              </td>
              <td style="text-align: center;">
                <?php echo ($val['early'] != '00:00:00') ? (intval(explode(':', $val['early'])[0]) ? intval(explode(':', $val['early'])[0]).'h ' : '').intval(explode(':', $val['early'])[1]).'m' : ''; ?>
              </td>
              <td>
                <?php echo $val['type']; ?>
              </td>
              <td>
                <?php echo $val['remarks']; ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>