<header>
  <nav id="main-sidenav" data-mdb-sidenav-init class="sidenav sidenav-sm shadow-8" data-mdb-hidden="false" data-mdb-accordion="true">
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
        <span class="sidenav-subheading text-muted text-uppercase fw-bold">Administration</span>
      </li>
      <li class="sidenav-item">
        <a class="sidenav-link" href="module">
          <i class="fas fa-key fa-fw me-3 text-black"></i>
          <b>Module</b>
        </a>
      </li>
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