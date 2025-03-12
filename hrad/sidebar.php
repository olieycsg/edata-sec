<?php 

$sql = "SELECT * FROM module WHERE sid = '".$_SESSION['sid']."'";
$result = $hris->query($sql);

$upper = ["1" => "Employee", "2" => "Leave", "3" => "Payroll", "4" => "Medical", "5" => "Memo", "6" => "Training", "7" => "Performance", "8" => "System", "9" => "Reports", "10" => "Attendance"];

$lower = ["1" => "employee", "2" => "leave", "3" => "payroll", "4" => "medical", "5" => "memo", "6" => "training", "7" => "performance", "8" => "system", "9" => "reports", "10" => "attendance"];

?>
<header>
  <nav id="main-sidenav" data-mdb-sidenav-init class="sidenav sidenav-sm shadow-8" data-mdb-accordion="true">
    <a class="d-flex justify-content-center pt-4 pb-2" data-mdb-ripple-init data-mdb-ripple-color="primary">
      <img img src="../img/logo.png" style="width: 140px;">
    </a>
    <hr class="hr">
    <ul class="sidenav-menu pb-5 text-black">
      <li class="sidenav-item">
        <a class="sidenav-link" href="dashboard">
          <i class="fas fa-home fa-fw me-3 text-black"></i>
          <b>Dashboard</b>
        </a>
      </li>

      <li class="sidenav-item pt-3">
        <span class="sidenav-subheading text-muted text-uppercase fw-bold">Administrator</span>
      </li>
      <?php 
      foreach ($result as $key => $value) {
        if ($value['module'] <= 7 && $value['status'] == '1' && isset($lower[$value['module']])) {
          $module_name = $lower[$value['module']];

      ?>
      <li class="sidenav-item">
        <a class="sidenav-link" href="dashboard?module=<?php echo $module_name; ?>">
          <i class="fas fa-caret-right fa-fw me-3 text-black"></i>
          <b><?php echo ucfirst($module_name); ?></b>
        </a>
      </li>
      <?php } } ?>

      <li class="sidenav-item pt-3">
        <span class="sidenav-subheading text-muted text-uppercase fw-bold">Settings</span>
      </li>
      <?php 
      foreach ($result as $key => $value) {
        if ($value['module'] >= 8 && $value['module'] <= 10 && $value['status'] == '1' && isset($lower[$value['module']])) {
          $module_name = $lower[$value['module']];

      ?>
      <li class="sidenav-item">
        <a class="sidenav-link" href="dashboard?module=<?php echo $module_name; ?>">
          <i class="fas fa-caret-right fa-fw me-3 text-black"></i>
          <b><?php echo ucfirst($module_name); ?></b>
        </a>
      </li>
      <?php } } ?>

      <li class="sidenav-item pt-3">
        <span class="sidenav-subheading text-muted text-uppercase fw-bold">Application</span>
      </li>
      <li class="sidenav-item">
        <a class="sidenav-link" href="logout">
          <i class="fas fa-power-off fa-fw me-3 text-black"></i>
          <b>Logout</b>
        </a>
      </li>
    </ul>
  </nav>
  <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-1">
    <div class="container-fluid">
      <button data-mdb-toggle="sidenav" data-mdb-target="#main-sidenav" class="btn shadow-0 p-0 me-3 d-block d-xxl-none" data-mdb-ripple-init aria-controls="#main-sidenav" aria-haspopup="true">
        <i class="fas fa-bars fa-lg"></i>
      </button>
      <span class="d-none d-md-flex w-auto my-auto text-black">
        <b>System Administrator</b>
      </span>
      <ul class="navbar-nav ms-auto d-flex flex-row">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" id="navbarDropdownMenuLink" role="button" data-mdb-dropdown-init aria-expanded="false">
            <img src="../img/user.png" class="rounded-circle" height="22" loading="lazy">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="logout"><b><i class="fas fa-power-off text-black"></i> Logout</b></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <section class="text-center text-md-start">
    <div class="p-5" style="height: 135px; background-color: #1266F1;"></div>
  </section>
</header>