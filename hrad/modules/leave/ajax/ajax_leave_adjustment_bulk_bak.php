<?php 

date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../../../api.php');

$year = $_POST['year'];
$divi = $_POST['divi'];
$desc = $_POST['desc'];

if($divi == 'all'){
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND DATE_FORMAT(DHIRE, '%Y') <= '$year' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}else{
  $sql = "SELECT * FROM employees_demas WHERE DRESIGN = '0000-00-00' AND DATE_FORMAT(DHIRE, '%Y') <= '$year' AND CDIVISION = '$divi' ORDER BY CNAME ASC";
  $result = $conn->query($sql);
}

$sql1 = "SELECT * FROM eleave_fxleavea WHERE DATE_FORMAT(DADJUST, '%Y') = '$year'";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM eleave WHERE CCDLEAVE = '1' AND DATE_FORMAT(DLEAVE, '%Y') = '$year' OR DATE_FORMAT(DLEAVE2, '%Y') = '$year'";
$result2 = $conn->query($sql2);

?>
<div class="row no_data" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-body">
        <div class="row">
          <div class="col-12 overflow-x-scroll">
            <table class="table table-sm table-hover table-striped" style="white-space: nowrap;">
              <thead>
                <tr class="text-center">
                  <th style="text-align: left;"><b>EMPLOYEE NAME</b></th>
                  <th style="text-align: left;"><b>HIRE DATE</b></th>
                  <th style="text-align: left;"><b>GRADE</b></th>
                  <th style="text-align: right;"><b>B/F <?php echo $year - 1; ?></b></th>
                  <th style="text-align: right;">
                    <b>
                    <?php 

                    if($year == date('Y')){
                      echo date("m/Y"); 
                    }else{
                      echo '12/'.$year;
                    }

                    ?>
                    </b>
                  </th>
                  <th style="text-align: right; color: red;"><b>TAKEN</b></th>
                  <th style="text-align: right; color: green;"><b>BALANCE</b></th>
                  <th style="text-align: right;"><b>EXPIRE <?php echo $year + 1; ?></b></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($result as $key => $value) { ?>
                <tr class="text-center">
                  <!-- name -->
                  <td style="text-align: left;">
                    <b><?php echo $value['CNAME']; ?></b>
                  </td>
                  <!-- hire -->
                  <td style="text-align: left;">
                    <?php echo strtoupper(date("d M Y", strtotime($value['DHIRE']))); ?>
                  </td>
                  <!-- grade -->
                  <td style="text-align: left;">
                    <?php echo $value['CGRADE'] ?: '-'; ?>
                  </td>
                  <!-- b/f -->
                  <td style="text-align: right;">
                    <b>
                    <?php 

                    $found = false;
                    foreach ($result1 as $key1 => $value1) {
                      if ($value1['CNOEE'] == $value['CNOEE']) {
                        echo $value1['NDAYS'];
                        $found = true;
                        $bf = $value1['NDAYS'];
                        break;
                      }
                    }

                    if (!$found) {
                      $bf = 0;
                      echo "0.00";
                    }

                    ?>
                    </b>
                  </td>
                  <!-- entitle -->
                  <td style="text-align: right;">
                    <?php

                    $date1 = new DateTime($value['DHIRE']);
                    $date2 = new DateTime();
                    $date3 = new DateTime(($year + 1).'-01-01');

                    $interval1 = $date1->diff($date2);
                    $interval2 = $date1->diff($date3);

                    if($year == date('Y', strtotime($value['DHIRE']))){
                      if(date('d', strtotime($value['DHIRE'])) == '01'){
                        $month = 13 - date('m', strtotime($value['DHIRE'])); 
                      }else{
                        $month = 12 - date('m', strtotime($value['DHIRE'])); 
                      }
                    }else if($year == date('Y')){
                      $month = ($interval1->y * 12 + $interval1->m);
                    }else{
                      $month = $interval2->y * 12 + $interval2->m;
                    }

                    $grade = ['NE1', 'NE2', 'NE3', 'NE4', 'NE5', 'NE6'];

                    if(!in_array($value['CGRADE'], $grade)){
                      if($month > 60){
                        if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (30/12), 2, '.', ',');
                        }else{
                          echo $entitle = "30.00";
                        }
                      }else{
                        if($year == date('Y', strtotime($value['DHIRE']))){
                          if(date('d', strtotime($value['DHIRE'])) == '1'){
                            echo $entitle = number_format((float)$month * (24/12), 2, '.', ',');
                          }else{
                            if(date('m', strtotime($value['DHIRE'])) == '11'){
                              echo $entitle = number_format((float)$month * (24/12), 2, '.', ',');
                            }else if(date('m', strtotime($value['DHIRE'])) == '12'){
                              echo $entitle = number_format((float)0, 2, '.', ',');
                            }else{
                              echo $entitle = number_format((float)($month - 1) * (24/12), 2, '.', ',');
                            }
                          }
                        }else if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (24/12), 2, '.', ',');
                        }else{
                          echo $entitle = "24.00";
                        }
                      }
                    }else if(in_array($value['CGRADE'], $grade)){
                      if($month > 60){
                        if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (21/12), 2, '.', ',');
                        }else{
                          echo $entitle = "21.00";
                        }
                      }else{
                        if($year == date('Y', strtotime($value['DHIRE']))){
                          if(date('d', strtotime($value['DHIRE'])) == '1'){
                            echo $entitle = number_format((float)$month * (14/12), 2, '.', ',');
                          }else{
                            if(date('m', strtotime($value['DHIRE'])) == '11'){
                              echo $entitle = number_format((float)$month * (14/12), 2, '.', ',');
                            }else if(date('m', strtotime($value['DHIRE'])) == '12'){
                              echo $entitle = number_format((float)0, 2, '.', ',');
                            }else{
                              echo $entitle = number_format((float)($month - 1) * (14/12), 2, '.', ',');
                            }
                          }
                        }else if($year == date('Y')){
                          $currMonth = date("m") - 1;
                          echo $entitle = number_format((float)$currMonth * (14/12), 2, '.', ',');
                        }else{
                          echo $entitle = "14.00";
                        }
                      }
                    }

                    ?>
                  </td>
                  <!-- taken -->
                  <td style="text-align: right; color: red;">
                    <b>
                    <?php 

                    foreach ($result2 as $key2 => $value2) {
                      if($value2['CNOEE'] == $value['CNOEE']){
                        if($value2['DLEAVE'] == $value2['DLEAVE2'] && $value2['NDAYS'] == '1.00' && $value2['CCDLEAVE'] == '1'){
                          $taken[$key] += 1;
                        }else if($value2['DLEAVE'] == $value2['DLEAVE2'] && $value2['NDAYS'] == '0.50' && $value2['CCDLEAVE'] == '1'){
                          $taken[$key] += 0.5;
                        }else if($value2['DLEAVE'] != $value2['DLEAVE2'] && $value2['CCDLEAVE'] == '1'){
                          $startDate = new DateTime($value2['DLEAVE']);
                          $endDate = new DateTime($value2['DLEAVE2']);

                          $interval = new DateInterval('P1D');
                          $endDate->add($interval);
                          $period = new DatePeriod($startDate, $interval, $endDate);

                          $nhours = $value2['NHOURS'];
                          $substrings = str_split($nhours, 2);

                          $data = [];

                          foreach ($period as $date) {
                            $formattedDate = $date->format('Y-m-d');
                            $index = $date->diff($startDate)->days;

                            if ($date->format('N') >= 6) {
                              continue;
                            }
                            
                            $substring = $substrings[$index % count($substrings)];
                            $data[$index] = [
                              'NHOURS' => $substring
                            ];
                          }

                          ksort($data);
                          foreach ($data as $item) {
                            if($item['NHOURS'] == '11'){
                              $taken[$key] += 1;
                            }else if($item['NHOURS'] == '10'){
                              $taken[$key] += 0.5;
                            }else if($item['NHOURS'] == '01'){
                              $taken[$key] += 0.5;
                            }
                          }
                        }
                      }
                    }

                    if($taken[$key] != '' || $taken[$key] != 0){
                      $annual = $taken[$key];
                      echo number_format((float)$taken[$key], 2, '.', ',');
                    }else{
                      $annual = 0;
                      echo "0.00";
                    }

                    ?>
                    </b>
                  </td>
                  <!-- balance -->
                  <?php $balance = number_format((float)($bf + $entitle) - $annual, 2, '.', ','); ?>
                  <td style="text-align: right; color: <?php if($balance <= 0){ echo "red"; }else{ echo "green"; } ?>;">
                    <b><?php echo ($balance != 0) ? $balance : '0.00'; ?></b>
                  </td>
                  <!-- expire -->
                  <td style="text-align: right;">
                    <?php

                    if(!in_array($value['CGRADE'], $grade)){
                      if($month > 60){
                        if($balance > 60){
                          echo $balance - 60;
                        }else{
                          echo "0.00";
                        }
                      }else{
                        if($balance > 48){
                          echo $balance - 48;
                        }else{
                          echo "0.00";
                        }
                      }
                    }else if(in_array($value['CGRADE'], $grade)){
                      if($month > 60){
                        if($balance > 42){
                          echo $balance - 42;
                        }else{
                          echo "0.00";
                        }
                      }else{
                        if($balance > 28){
                          echo $balance - 28;
                        }else{
                          echo "0.00";
                        }
                      }
                    }

                    ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
</script>