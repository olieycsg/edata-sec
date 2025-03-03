<?php 

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

include('../../api.php');
include('header.php');

$emid = $_SESSION['emid'];
$year = date("Y");

$sql = "SELECT * FROM employees_demas WHERE CNOEE = '$emid' AND DRESIGN = '0000-00-00'";
$result = $conn->query($sql);

$sqla = "SELECT * FROM employees_demas WHERE CNOEE != '$emid' AND DRESIGN = '0000-00-00' ORDER BY CNAME ASC";
$resulta = $conn->query($sqla);

if($row = $result->fetch_assoc()){

  $sql1 = "SELECT * FROM sys_general_dcmisc";
  $result1 = $conn->query($sql1);

  while($row1 = $result1->fetch_assoc()){
    if($row1['CTYPE'] == 'DIVSN' && $row1['CCODE'] == $row['CDIVISION']){
      $divi = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'DEPTM' && $row1['CCODE'] == $row['CDEPARTMEN']){
      $dept = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'RELIG' && $row1['CCODE'] == $row['CRELIGION']){
      $religion = $row1['CDESC'];
    }
    if($row1['CTYPE'] == 'RACE' && $row1['CCODE'] == $row['CRACE']){
      $race = $row1['CDESC'];
    }
  }

  $marital = ['M' => 'MARRIED', 'S' => 'SINGLE', 'D' => 'DIVORCED', 'W' => 'WIDOW/WIDOWER'];

  foreach ($marital as $maritalkey => $maritalvalue){
    if($row['CSTMARTL'] == $maritalkey){
      $marriage = $maritalvalue;
    }
  }

  $sql2 = "SELECT * FROM employee_inservice WHERE CNOEE = '$emid' ORDER BY CSEQUENCE ASC";
  $result2 = $conn->query($sql2);

  $rectype = array('A' => 'ACTING', 'C' => 'CONFIRMATION', 'D' => 'DEMOTION', 'E' => 'REINSTATEMENT', 'H' => 'HIRE', 'I' => 'INCREMENT', 'N' => 'PROMOTION CONFIRMATION', 'O' => 'PERMANENT APPOINTMENT', 'P' => 'PROMOTION', 'J' => 'SALARY ADJUSTMENTS', 'Q' => 'SUSPENSION', 'R' => 'REVISION', 'S' => 'REDESIGNATION', 'T' => 'TRANSFER', 'X' => 'CEASE');

  foreach ($resulta as $keya => $vala) {
    if($row['CSUPERIOR'] == $vala['CJOB']){
      $superior = $vala['CNAME'];
    }
    if($row['CSUPERVISO'] == $vala['CJOB']){
      $superviso = $vala['CNAME'];
    }
  }

?>
<style>
  .card {
    margin: auto;
    overflow-y: auto;
    position: relative;
    z-index: 1;
    overflow-x: hidden;
    background-color: rgba(255, 255, 255, 1);
    display: flex;
    transition: 0.3s;
    flex-direction: column;
    border-radius: 10px;
    box-shadow: 0 0 0 8px rgba(255, 255, 255, 0.2);
  }

  .card[data-state="#profile"] {
    height: auto;
    .card-main {
      padding-top: 0;
    }
  }

  .card[data-state="#service"] {
    height: auto;
  }

  .card[data-state="#related"] {
    height: auto;
  }

  .card.is-active {
    .card-header {
      height: 80px;
    }

    .card-cover {
      height: 100px;
      top: -50px;
    }

    .card-avatar {
      transform: none;
      left: 20px;
      width: 50px;
      height: 50px;
      bottom: 10px;
    }

    .card-fullname,
    .card-jobtitle {
      left: 86px;
      transform: none;
    }

    .card-fullname {
      bottom: 18px;
      font-size: 19px;
    }

    .card-jobtitle {
      bottom: 16px;
      letter-spacing: 1px;
      font-size: 10px;
    }
  }

  .card-header {
    position: relative;
    display: flex;
    height: 200px;
    flex-shrink: 0;
    width: 100%;
    transition: 0.3s;

    * {
      transition: 0.3s;
    }
  }

  .card-cover {
    width: 100%;
    height: 100%;
    position: absolute;
    height: 160px;
    top: -20%;
    left: 0;
    will-change: top;
    background-size: cover;
    background-position: center;
    filter: blur(30px);
    transform: scale(1.2);
    transition: 0.5s;
  }

  .card-avatar {
    width: 100px;
    height: 100px;
    box-shadow: 0 8px 8px rgba(0, 0, 0, 0.2);
    border-radius: 50%;
    object-position: center;
    object-fit: cover;
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%) translateY(-64px);
  }

  .card-fullname {
    position: absolute;
    bottom: 0;
    font-size: 22px;
    font-weight: 700;
    text-align: center;
    white-space: nowrap;
    transform: translateY(-10px) translateX(-50%);
    left: 50%;
  }

  .card-jobtitle {
    position: absolute;
    bottom: 0;
    font-size: 11px;
    white-space: nowrap;
    font-weight: 500;
    opacity: 0.7;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin: 0;
    left: 50%;
    transform: translateX(-50%) translateY(-7px);
  }

  .card-main {
    position: relative;
    flex: 1;
    display: flex;
    padding-top: 10px;
    flex-direction: column;
  }

  .card-subtitle {
    font-weight: 700;
    font-size: 13px;
    margin-bottom: 8px;
  }

  .card-content {
    padding: 20px;
  }

  .card-desc {
    line-height: 1.6;
    color: #636b6f;
    font-size: 14px;
    margin: 0;
    font-weight: 400;
    font-family: "DM Sans", sans-serif;
  }

  .card-buttons {
    display: flex;
    background-color: #fff;
    margin-top: auto;
    position: sticky;
    bottom: 0;
    left: 0;

    button {
      flex: 1 1 auto;
      user-select: none;
      background: 0;
      font-size: 13px;
      border: 0;
      padding: 15px 5px;
      cursor: pointer;
      color: #5c5c6d;
      transition: 0.3s;
      font-family: "Jost", sans-serif;
      font-weight: 500;
      outline: 0;
      border-bottom: 3px solid transparent;

      &.is-active,
      &:hover {
        color: #2b2c48;
        border-bottom: 3px solid #8a84ff;
        background: linear-gradient(
          to bottom,
          rgba(127, 199, 231, 0) 0%,
          rgba(207, 204, 255, 0.2) 44%,
          rgba(211, 226, 255, 0.4) 100%
        );
      }
    }
  }

  .card-section {
    display: none;
    &.is-active {
      display: block;
      animation: fadeIn 0.6s both;
    }
  }

  @keyframes fadeIn {
    0% {
      opacity: 0;
      transform: translatey(40px);
    }
    100% {
      opacity: 1;
    }
  }

  .card-timeline {
    margin-top: 30px;
    position: relative;
    &:after {
      background: linear-gradient(
        to top,
        rgba(134, 214, 243, 0) 0%,
        rgba(81, 106, 204, 1) 100%
      );
      content: "";
      left: 42px;
      width: 2px;
      top: 0;
      height: 100%;
      position: absolute;
      content: "";
    }
  }

  .card-item {
    position: relative;
    padding-left: 60px;
    padding-right: 20px;
    padding-bottom: 30px;
    z-index: 1;
    &:last-child {
      padding-bottom: 5px;
    }

    &:after {
      content: attr(data-year);
      width: 10px;
      position: absolute;
      top: 0;
      left: 37px;
      width: 8px;
      height: 8px;
      line-height: 0.6;
      border: 2px solid #fff;
      font-size: 11px;
      text-indent: -35px;
      border-radius: 50%;
      color: rgba(#868686, 0.7);
      background: linear-gradient(
        to bottom,
        lighten(#516acc, 20%) 0%,
        #516acc 100%
      );
    }
  }

  .card-item-title {
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 5px;
  }

  .card-item-desc {
    font-size: 13px;
    color: #6f6f7b;
    line-height: 1.5;
    font-family: "DM Sans", sans-serif;
  }

  .card-contact-wrapper {
    margin-top: 20px;
  }

  .card-contact {
    display: flex;
    align-items: center;
    font-size: 13px;
    color: #6f6f7b;
    font-family: "DM Sans", sans-serif;
    line-height: 1.6;
    cursor: pointer;

    & + & {
      margin-top: 16px;
    }

    svg {
      flex-shrink: 0;
      width: 30px;
      min-height: 34px;
      margin-right: 12px;
      transition: 0.3s;
      padding-right: 12px;
      border-right: 1px solid #dfe2ec;
    }
  }
</style>
<div id="content">
  <body class="nk-body">
    <div class="nk-app-root">
      <div class="nk-main">
        <div class="nk-wrap">
          <?php include('navbar.php'); ?>
          <div class="nk-content">
            <div class="container">
              <div class="nk-content-inner">
                <div class="nk-content-body">
                  <div class="card" data-state="#about">
                    <div class="card-header">
                      <div class="card-cover" style="background-image: url('../../img/nodata.png')"></div>
                      <?php 
                      if($row['CIMAGE'] == ''){ 
                        if($row['CSEX'] == 'M'){ 
                      ?>
                        <img src="assets/images/male.png" class="card-avatar" data-enlargable>
                        <?php }else if($row['CSEX'] == 'F'){ ?>
                        <img src="assets/images/female.png" class="card-avatar" data-enlargable>
                      <?php 
                        } 
                      }else{ 
                      ?>
                        <img src="../../hrad/modules/employees/file/<?php echo $row['CIMAGE']; ?>" class="card-avatar" data-enlargable>
                      <?php } ?>
                      <h1 class="card-fullname"><?php echo $row['CNAME']; ?></h1>
                      <h2 class="card-jobtitle"><?php echo $row['CPOSITION']; ?></h2>
                    </div>
                    <div class="card-main">
                      <div class="card-section is-active" id="profile">
                        <div class="card-content">
                          <ul class="list-group list-group-borderless small">
                            <li class="list-group-item">
                              <span class="title fw-medium w-40 d-inline-block">Nationality</span>
                              <span class="text">: <?php echo ($row['CCOUNTRY'] == 'MAL') ? 'MALAYSIAN' : '-' ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">National ID</span>
                              <span class="text">: <?php echo ($row['CNOICNEW'] != '') ? $row['CNOICNEW'] : '-' ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Birth Date</span>
                              <span class="text">: <?php echo strtoupper(date("d M Y", strtotime($row['DBIRTH']))); ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Gender</span>
                              <span class="text">: <?php echo ($row['CSEX'] == "M") ? 'MALE' : 'FEMALE'; ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Religion</span>
                              <span class="text">: <?php echo strtoupper($religion); ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Race</span>
                              <span class="text">: <?php echo strtoupper($race); ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Marital</span>
                              <span class="text">: <?php echo strtoupper($marriage); ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Blood</span>
                              <span class="text">: <?php echo strtoupper($row['CBLOOD']); ?></span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Height</span>
                              <span class="text">: <?php echo ($row['NHEIGHT'] != '') ? $row['NHEIGHT'] : '-'; ?> CM</span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Weight</span>
                              <span class="text">: <?php echo ($row['NWEIGHT'] != '') ? $row['NWEIGHT'] : '-'; ?> KG</span>
                              <br>
                              <span class="title fw-medium w-40 d-inline-block">Phone</span>
                              <span class="text">: <?php echo ($row['CNOPHONEH'] != '') ? $row['CNOPHONEH'] : '-'; ?></span>
                              <hr>
                              <span class="title fw-medium w-40 d-inline-block">Address</span>
                              <br>
                              <span class="text"><?php echo ($row['CADDRS1'] != '') ? strtoupper($row['CADDRS1']).' '.strtoupper($row['CADDRS2']).' '.strtoupper($row['CADDRS3']) : '-'; ?>
                                
                              </span>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="card-section" id="service">
                        <div class="card-content">
                          <div class="card-subtitle">IN SERVICE RECORD</div>
                          <div class="card-timeline">
                            <?php while($row2 = $result2->fetch_assoc()){ ?>
                            <div class="card-item" data-year="<?php echo strtoupper(date("Y", strtotime($row2['DREC']))); ?>">
                              <div class="card-item-title">
                                <?php 
                                foreach ($rectype as $recvalue => $reclabel) {
                                  if($recvalue == $row2['CTYPREC']){
                                    echo $reclabel;
                                  }
                                }
                                foreach ($result1 as $key1 => $row1) {
                                  if($row1['CTYPE'] == 'DIVSN' && $row1['CCODE'] == $row2['CDIVISION']){ 
                                    $hdivi = $row1['CDESC'];
                                  }
                                }
                                ?>
                              </div>
                              <div class="card-item-desc">
                                <b style="font-style: italic;" class="text-info">
                                  <?php echo strtoupper(date("d M Y", strtotime($row2['DREC']))); ?>
                                </b>
                                <br>
                                <span style="font-style: italic; font-size: 10px;">
                                  <?php echo ($row2['CPOSITION'] != '') ? $row2['CPOSITION'] : '-'; ?>
                                  <br>
                                  <?php echo ($hdivi != '') ? $hdivi : '-'; ?>
                                </span>
                              </div>
                            </div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <div class="card-section" id="related">
                        <div class="card-content">
                          <ul class="list-group list-group-borderless small">
                            <li class="list-group-item">
                              <div class="media-text">
                                <a class="title"><?php echo $divi; ?></a>
                                <b class="text smaller text-info" style="font-style: italic;">Division</b>
                              </div>
                              <div class="media-text">
                                <a class="title"><?php echo $dept; ?></a>
                                <b class="text smaller text-info" style="font-style: italic;">Department</b>
                              </div>
                              <?php if($row['CJOB'] != 'CEO'){ ?>
                                <hr>
                                <?php if($superior != $superviso){ ?>
                                <div class="media-text">
                                  <a class="title"><?php echo $superior; ?></a>
                                  <b class="text smaller text-warning" style="font-style: italic;">Leave Recommender</b>
                                </div>
                                <?php } ?>
                                <div class="media-text">
                                  <a class="title"><?php echo $superviso; ?></a>
                                  <b class="text smaller text-success" style="font-style: italic;">Leave Approver</b>
                                </div>
                              <?php } ?>
                            </li>
                          </ul>
                          <hr>
                          <?php 
                          foreach ($resulta as $keya => $vala) { 
                            if($row['CDIVISION'] == $vala['CDIVISION']){ 
                          ?>
                          <a href="profile?emid=<?php echo $vala['CNOEE']; ?>" class="text-dark load">
                            <ol class="list-group" style="margin-bottom: 5px;">
                              <li class="list-group-item">
                                <div class="media-group">
                                  <?php if($vala['CIMAGE'] == null){ ?>
                                  <div class="media media-md media-middle media-circle">
                                    <?php if($vala['CSEX'] == 'M'){ ?>
                                    <img src="assets/images/male.png" alt="">
                                    <?php }else if($vala['CSEX'] == 'F'){ ?>
                                    <img src="assets/images/female.png" alt="">
                                    <?php } ?>
                                  </div>
                                  <?php }else{ ?>
                                  <div class="media media-md media-middle media-circle">
                                    <img src="../../hrad/modules/employees/file/<?php echo $vala['CIMAGE']; ?>">
                                  </div>
                                  <?php } ?>
                                  <div class="media-text">
                                    <b style="font-size: 13px;"><?php echo $vala['CNAME']; ?></b>
                                    <span class="small text text-info" style="font-size: 10px; font-style: italic;"><?php echo $vala['CPOSITION']; ?></span>
                                  </div>
                                </div>
                              </li>
                            </ol>
                          </a>
                          <?php } } ?>
                        </div>
                      </div>
                      <div class="card-buttons">
                        <button data-section="#profile" class="is-active">PROFILE</button>
                        <button data-section="#service">IN SERVICE</button>
                        <button data-section="#related">RELATED</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</div>
<?php include('footer.php'); ?>
<script type="text/javascript">
const buttons = document.querySelectorAll(".card-buttons button");
const sections = document.querySelectorAll(".card-section");
const card = document.querySelector(".card");

const handleButtonClick = (e) => {
  const targetSection = e.target.getAttribute("data-section");
  const section = document.querySelector(targetSection);
  targetSection !== "#about"
    ? card.classList.add("is-active")
    : card.classList.remove("is-active");
  card.setAttribute("data-state", targetSection);
  sections.forEach((s) => s.classList.remove("is-active"));
  buttons.forEach((b) => b.classList.remove("is-active"));
  e.target.classList.add("is-active");
  section.classList.add("is-active");
};

buttons.forEach((btn) => {
  btn.addEventListener("click", handleButtonClick);
});

$('img[data-enlargable]').addClass('img-enlargable').click(function(){
  var src = $(this).attr('src');
  $('<div>').css({
    background: 'RGBA(255, 255, 255, 1) url('+src+') no-repeat center',
    backgroundSize: 'contain',
    width:'100%', height:'100%',
    position:'fixed',
    zIndex:'10000',
    top:'0', left:'0',
    cursor: 'zoom-out'
  }).click(function(){
    $(this).remove();
  }).appendTo('body');
});
</script>
<?php } ?>