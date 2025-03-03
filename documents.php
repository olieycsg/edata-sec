<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta http-equiv="Cache-control" content="public">
  <title>i-SEC | DOCUMENTS</title>
  <link rel="icon" type="image/x-icon" href="../img/icon.png">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
  <link rel="stylesheet" href="src/css/mdb.min.css">
</head>
<body>
<div class="row">
  <div class="col-md-12">
    <img src="user/webapp/file/<?php echo $_GET['file']; ?>" class="img-thumbnail">
  </div>
</div>
</body>
</html>
<script type="text/javascript">
document.addEventListener('contextmenu', function (e) {
  e.preventDefault();
});

document.addEventListener('keydown', function (e) {
  if (e.key === 'F12' || e.keyCode === 123) {
    e.preventDefault();
  }
});
</script>