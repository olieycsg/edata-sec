<style type="text/css">
  .genealogy-scroll::-webkit-scrollbar {
    width: 5px;
    height: 8px;
  }
  .genealogy-scroll::-webkit-scrollbar-track {
    border-radius: 10px;
    background-color: #e4e4e4;
  }
  .genealogy-scroll::-webkit-scrollbar-thumb {
    background: #212121;
    border-radius: 10px;
    transition: 0.5s;
  }
  .genealogy-scroll::-webkit-scrollbar-thumb:hover {
    background: #d5b14c;
    transition: 0.5s;
  }
  .genealogy-body{
    white-space: nowrap;
    overflow-y: hidden;
    padding: 50px;
    min-height: 500px;
    padding-top: 10px;
    text-align: center;
  }
  .genealogy-tree{
    display: inline-block;
  }
  .genealogy-tree ul {
    padding-top: 20px; 
    position: relative;
    padding-left: 0px;
    display: flex;
    justify-content: center;
  }
  .genealogy-tree li {
    float: left; text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
  }
  .genealogy-tree li::before, 
  .genealogy-tree li::after{
    content: '';
    position: absolute; 
    top: 0; 
    right: 50%;
    border-top: 2px solid #ccc;
    width: 50%; 
    height: 18px;
  }
  .genealogy-tree li::after{
    right: auto; left: 50%;
    border-left: 2px solid #ccc;
  }
  .genealogy-tree li:only-child::after, 
  .genealogy-tree li:only-child::before {
    display: none;
  }
  .genealogy-tree li:only-child{ 
    padding-top: 0;
  }
  .genealogy-tree li:first-child::before, 
  .genealogy-tree li:last-child::after{
    border: 0 none;
  }
  .genealogy-tree li:last-child::before{
    border-right: 2px solid #ccc;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
  }
  .genealogy-tree li:first-child::after{
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
  }
  .genealogy-tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    border-left: 2px solid #ccc;
    width: 0; height: 20px;
  }
  .genealogy-tree li a{
    text-decoration: none;
    color: #666;
    font-family: arial, verdana, tahoma;
    font-size: 11px;
    display: inline-block;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
  }

  .genealogy-tree li a:hover+ul li::after, 
  .genealogy-tree li a:hover+ul li::before, 
  .genealogy-tree li a:hover+ul::before, 
  .genealogy-tree li a:hover+ul ul::before{
    border-color:  #fbba00;
  }

  .member-view-box{
    padding:0px 20px;
    text-align: center;
    border-radius: 4px;
    position: relative;
  }
  .member-image{
    width: 50px;
  }
  .member-image img{
    width: 50px;
    height: 50px;
    border-radius: 6px;
    background-color: transparent;
    z-index: 1;
  }

  .pointer{
    cursor: cursor;
  }
</style>
<?php 

include('../../../../api.php');
$code = $_GET['code'];

$sql = "SELECT * FROM sys_workflow_divisional WHERE CDIVISION = '$code'";
$result = $conn->query($sql);

$asql = "SELECT * FROM sys_workflow_divisional_access WHERE CDIVISION = '$code'";
$aresult = $conn->query($asql);

if($arow = $aresult->fetch_assoc()){
  $csec = $arow['CNOEE'];
  $jsec = $arow['CJOB'];
}

if($row = $result->fetch_assoc()){

  $cnoee = $row['CNOEE'];
  $cjob = $row['CJOB'];

  $sqla = "SELECT * FROM employees_demas WHERE CNOEE = '$cnoee' AND DRESIGN = '0000-00-00'";
  $resulta = $conn->query($sqla);

  $sqlb = "SELECT * FROM employees_demas WHERE CNOEE = '$csec' AND DRESIGN = '0000-00-00'";
  $resultb = $conn->query($sqlb);

  $sqlc = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CSUPERIOR = CSUPERVISO AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00'";
  $resultc = $conn->query($sqlc);

  $sqld = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
  $resultd = $conn->query($sqld);

  if($code != 'HRAD'){
    $sqle = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00'";
    $resulte = $conn->query($sqle);
  }else{
    $sqle = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND DRESIGN = '0000-00-00'";
    $resulte = $conn->query($sqle);
  }

  $sqlf = "SELECT * FROM employees_demas WHERE CSUPERVISO = '$cjob' AND CDIVISION = '$code' AND CJOB != '$jsec' AND DRESIGN = '0000-00-00'";
  $resultf = $conn->query($sqlf);

}

?>
<div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
    <div class="card shadow-8">
      <div class="card-header text-white bg-success">
        <b><i class="fas fa-caret-right"></i> Workflow Viewer Chart</b>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="body genealogy-body genealogy-scroll">
              <div class="genealogy-tree">
                <ul class="active">
                  <?php if($rowa = $resulta->fetch_assoc()) { ?>
                  <li>
                    <a href="javascript:void(0);">
                      <div class="member-view-box sec-tooltip" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $rowa['CNAME']; ?>">
                        <div class="member-image">
                          <?php 
                          if($rowa['CIMAGE'] == ''){ 
                            if($rowa['CSEX'] == 'M'){ 
                          ?>
                            <img src="img/male.png" class="card-avatar">
                            <?php }else if($rowa['CSEX'] == 'F'){ ?>
                            <img src="img/female.png" class="card-avatar">
                          <?php 
                            } 
                          }else{ 
                          ?>
                            <img src="../../hrad/modules/employees/file/<?php echo $rowa['CIMAGE']; ?>" class="card-avatar">
                          <?php } ?>
                        </div>
                        <div class="member-title">
                          <b class="title text-success" style="font-style: italic; font-size: 9px;">
                          <?php 
                          if($rowa['CJOB'] == 'CEO'){
                            echo 'CEO';
                          }else if($rowa['CJOB'] == 'DCEO/COO'){
                            echo 'DCEO';
                          }else{
                            echo 'DIV HEAD';
                          }
                          ?>
                          </b>
                      </div>
                      </div>
                    </a>
                    <ul class="active">
                      <?php if($rowb = $resultb->fetch_assoc()) { ?>
                      <li>
                        <a href="javascript:void(0);">
                          <div class="member-view-box sec-tooltip" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $rowb['CNAME']; ?>">
                            <div class="member-image">
                              <?php 
                              if($rowb['CIMAGE'] == ''){ 
                                if($rowb['CSEX'] == 'M'){ 
                              ?>
                                <img src="img/male.png" class="card-avatar">
                                <?php }else if($rowb['CSEX'] == 'F'){ ?>
                                <img src="img/female.png" class="card-avatar">
                              <?php 
                                } 
                              }else{ 
                              ?>
                                <img src="../../hrad/modules/employees/file/<?php echo $rowb['CIMAGE']; ?>" class="card-avatar">
                              <?php } ?>
                            </div>
                            <div class="member-title">
                              <b class="title text-info" style="font-style: italic; font-size: 9px;">SECRETARY</b>
                            </div>
                          </div>
                        </a>
                      </li>
                      <?php } ?>

                      <?php while($rowc = $resultc->fetch_assoc()) { ?>
                      <li>
                        <a href="javascript:void(0);">
                          <div class="member-view-box sec-tooltip" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $rowc['CNAME']; ?>">
                            <div class="member-image">
                              <?php 
                              if($rowc['CIMAGE'] == ''){ 
                                if($rowc['CSEX'] == 'M'){ 
                              ?>
                                <img src="img/male.png" class="card-avatar">
                                <?php }else if($rowc['CSEX'] == 'F'){ ?>
                                <img src="img/female.png" class="card-avatar">
                              <?php 
                                } 
                              }else{ 
                              ?>
                                <img src="../../hrad/modules/employees/file/<?php echo $rowc['CIMAGE']; ?>" class="card-avatar">
                              <?php } ?>
                            </div>
                          </div>
                        </a>
                        <?php
                        $hasSubordinates = false;
                        $subordinates = [];
                        $resultd->data_seek(0);
                        while($rowd = $resultd->fetch_assoc()) {
                          if($rowd['CSUPERIOR'] == $rowc['CJOB']) {
                            $hasSubordinates = true;
                            $subordinates[] = $rowd;
                          }
                        }
                        if ($hasSubordinates) {
                        ?>
                        <ul class="active">
                          <?php foreach ($subordinates as $rowd) { ?>
                          <li>
                            <a href="javascript:void(0);">
                              <div class="member-view-box sec-tooltip" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $rowd['CNAME']; ?>">
                                <div class="member-image">
                                  <?php 
                                  if($rowd['CIMAGE'] == ''){ 
                                    if($rowd['CSEX'] == 'M'){ 
                                  ?>
                                    <img src="img/male.png" class="card-avatar">
                                    <?php }else if($rowd['CSEX'] == 'F'){ ?>
                                    <img src="img/female.png" class="card-avatar">
                                  <?php 
                                    } 
                                  }else{ 
                                  ?>
                                    <img src="../../hrad/modules/employees/file/<?php echo $rowd['CIMAGE']; ?>" class="card-avatar">
                                  <?php } ?>
                                </div>
                              </div>
                            </a>
                            <?php
                            $hasSubordinates = false;
                            $subordinates2 = [];
                            $resulte->data_seek(0);
                            while($rowe = $resulte->fetch_assoc()) {
                              if($rowe['CSUPERIOR'] == $rowd['CJOB']) {
                                $hasSubordinates = true;
                                $subordinates2[] = $rowe;
                              }
                            }
                            if ($hasSubordinates) {
                            ?>
                            <ul class="active">
                              <?php foreach ($subordinates2 as $rowe) { ?>
                              <li>
                                <a href="javascript:void(0);">
                                  <div class="member-view-box sec-tooltip" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $rowe['CNAME']; ?>">
                                    <div class="member-image">
                                      <?php 
                                      if($rowe['CIMAGE'] == ''){ 
                                        if($rowe['CSEX'] == 'M'){ 
                                      ?>
                                        <img src="img/male.png" class="card-avatar">
                                        <?php }else if($rowe['CSEX'] == 'F'){ ?>
                                        <img src="img/female.png" class="card-avatar">
                                      <?php 
                                        } 
                                      }else{ 
                                      ?>
                                        <img src="../../hrad/modules/employees/file/<?php echo $rowe['CIMAGE']; ?>" class="card-avatar">
                                      <?php } ?>
                                    </div>
                                  </div>
                                </a>
                                <?php
                                $hasSubordinates = false;
                                $subordinates3 = [];
                                $resultf->data_seek(0);
                                while($rowf = $resultf->fetch_assoc()) {
                                  if($rowf['CSUPERIOR'] == $rowe['CJOB']) {
                                    $hasSubordinates = true;
                                    $subordinates3[] = $rowf;
                                  }
                                }
                                if ($hasSubordinates) {
                                ?>
                                <ul class="active">
                                  <?php foreach ($subordinates3 as $rowf) { ?>
                                  <li>
                                    <a href="javascript:void(0);">
                                      <div class="member-view-box sec-tooltip" data-mdb-ripple-init data-mdb-tooltip-init data-mdb-placement="top" title="<?php echo $rowf['CNAME']; ?>">
                                        <div class="member-image">
                                          <?php 
                                          if($rowf['CIMAGE'] == ''){ 
                                            if($rowf['CSEX'] == 'M'){ 
                                          ?>
                                            <img src="img/male.png" class="card-avatar">
                                            <?php }else if($rowf['CSEX'] == 'F'){ ?>
                                            <img src="img/female.png" class="card-avatar">
                                          <?php 
                                            } 
                                          }else{ 
                                          ?>
                                            <img src="../../hrad/modules/employees/file/<?php echo $rowf['CIMAGE']; ?>" class="card-avatar">
                                          <?php } ?>
                                        </div>
                                      </div>
                                    </a>
                                  </li>
                                  <?php } ?>
                                </ul>
                                <?php } ?>
                              </li>
                              <?php } ?>
                            </ul>
                            <?php } ?>
                          </li>
                          <?php } ?>
                        </ul>
                        <?php } ?>
                      </li>
                      <?php } ?>
                    </ul>
                  </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
sec_function();
</script>