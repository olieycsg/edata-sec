<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

if($_SESSION['id'] == ""){
  
  header('location: ../');
  
}else if($_SESSION['id'] != "" && $_SESSION['role_level'] == "2"){

  header('location: logout');

}else{
  
include('../api.php'); 
include('header.php');

$sql = "SELECT * FROM 03_eleave ORDER BY DLEAVE DESC";
$result = $conn->query($sql);

?>
<header>
<?php include('sidebar.php'); ?>
</header>
<main>
  <div class="container-fluid">
    <ul class="nav nav-tabs md-tabs bg-primary">
      <li class="nav-item">
        <a class="nav-link active"><i class="far fa-calendar-check"></i> FIX DATE</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" id="start"><i class="far fa-play-circle"></i> START</a>
      </li>
    </ul>
    <div class="tab-content card pt-4">
      <div class="tab-pane fade show active">
        <div class="row">
          <div class="col-md-12">
            <table id="tbl_date" class="table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">DLEAVE</th>
                  <th scope="col">DLEAVE2</th>
                  <th scope="col">NLEAVE</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($result as $key => $value) { ?>
                <tr>
                  <td><?php echo $key + 1; ?></td>
                  <td width="13%"><?php echo $value['DLEAVE']; ?></td>
                  <td width="13%"><?php echo $value['DLEAVE2']; ?></td>
                  <td>
                    <?php echo $value['LEAVES']; ?>
                    <?php 

                    /*$period = new DatePeriod(
                      new DateTime($value['DLEAVE']),
                      new DateInterval('P1D'),
                      new DateTime($value['DLEAVE2'].'+ 1 day')
                    );

                    foreach ($period as $key1 => $value1) {
                      $newdate[$key][] = $value1->format('Y-m-d');
                    }

                    echo implode(",", $newdate[$key]);*/

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
</main>
<?php include('footer.php'); } ?>
<script type="text/javascript">
$(document).ready(function () {
  $('#tbl_date').DataTable();
  $('.dataTables_length').addClass('bs-select');
});

$(document).ready(function() {
  $("#start").click(function(){
    var fixdate = 'fixdate';
    Swal.fire({
      title: 'ARE YOU SURE?',
      html: "<strong>ALL DATE WILL BE FIXED</strong>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00B74A',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YES, PROCEED'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url:'api_main',
          type:'POST',
          data:{fixdate:fixdate},
          beforeSend: function() {    
            Swal.fire({
              title: 'FIXING DATA',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              },
            });
          },
          success: function(response){
            Swal.fire({
              position: 'center-center',
              icon: 'success',
              title: 'DATA FIXED',
              confirmButtonText: 'Close',
              allowEscapeKey: false,
              allowOutsideClick: false,
            }).then((result) => {
              location.reload();
            });
          },
        });
      }
    });
  });
});
</script>
