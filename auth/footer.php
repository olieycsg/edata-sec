</body>
<script type="text/javascript" src="../hrad/js/mdb.umd.min.js"></script>
<script type="text/javascript" src="../hrad/plugins/js/all.min.js"></script>
<script type="text/javascript">
const sidenav = document.getElementById("main-sidenav");
const sidenavInstance = mdb.Sidenav.getInstance(sidenav);

let innerWidth = null;

const setMode = (e) => {
  if (window.innerWidth === innerWidth) {
    return;
  }

  innerWidth = window.innerWidth;

  if (window.innerWidth < 1400) {
    sidenavInstance.changeMode("over");
    sidenavInstance.hide();
  } else {
    sidenavInstance.changeMode("side");
    sidenavInstance.show();
  }
};

setMode();
window.addEventListener("resize", setMode);

function sec_function(){  
  document.querySelectorAll('.sec-select').forEach((selectEl) => {
    mdb.Select.getOrCreateInstance(selectEl)  
  });
  document.querySelectorAll('.modal').forEach((ModalEl) => {
    mdb.Modal.getOrCreateInstance(ModalEl)
  });
  document.querySelectorAll('.sec-tooltip').forEach((tooltipEl) => {
    new mdb.Tooltip(tooltipEl);
  });
  document.querySelectorAll('.form-outline').forEach((formOutline) => {
    new mdb.Input(formOutline).init();
  });
}

function sec_remove(){
  document.querySelectorAll('.modal').forEach((modalRl) => {
    const modalExt = mdb.Modal.getOrCreateInstance(modalRl);
    modalExt.hide();
  });
}

</script>
</html>
