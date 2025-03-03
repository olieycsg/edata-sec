<script src="assets/js/bundle.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/charts/analytics-chart.js"></script>
<script src="assets/js/data-tables/data-tables.js"></script>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
  setTimeout(function() {
    document.getElementById("loader-overlay").style.display = "none";
    document.getElementById("content").style.display = "block";
  }, 1000);
});

$(document).ready(function() {
  var choicesItem = $('.choices__item');
  choicesItem.data('deletable', null);
  choicesItem.find('.choices__button').remove();
});

document.addEventListener('contextmenu', function (e) {
  e.preventDefault();
});

document.addEventListener('keydown', function (e) {
  if (e.key === 'F12' || e.keyCode === 123) {
    e.preventDefault();
  }
});

$(document).ready(function(){
  $(".load").click(function(){
    Swal.fire({
      title: 'LOADING',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  });
});

$(document).ready(function(){
  $(".turnon").click(function(){
    Swal.fire({
      title: 'ACTIVATING',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  });
});

$(document).ready(function(){
  $(".load").change(function(){
    Swal.fire({
      title: 'LOADING',
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  });
});
</script>
</html>