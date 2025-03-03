<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="SEC HRIS">
  <title>i-SEC</title>
  <link rel="icon" type="image/x-icon" href="../../img/icon.png">
  <link rel="stylesheet" href="assets/css/style.css?v1.1.1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<style type="text/css">
  body {
    margin: 0;
    font-family: 'Arial', sans-serif;
  }

  #loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  .loader {
    border: 4px solid #00007F;
    border-top: 4px solid #f3f3f3;
    border-radius: 50%;
    width: 40px;
    height:40px;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  #content {
    display: none;
  }

  .amber-color {
    background-color: #ffb300!important;
  }

  .yellow-color {
    background-color: #ff0!important;
  }

  .info-color {
    background-color: #33b5e5!important;
  }

  .pink-color {
    background-color: #e91e63!important;
  }

  .cyan-color {
    background-color: #006064!important;
  }

  .brown-color{
    background-color: #3e2723!important;
  }

  .grey-color{
    background-color: #424242!important;
  }

  .lime-color{
    background-color: #aeea00!important;
  }

  .black-color{
    background-color: #000000!important;
  }

  .light-color{
    background-color: #01579b!important;
  }

  .purple-gradient {
    background: linear-gradient(40deg,#ff6ec4,#7873f5) !important;
  }

  .green-color{
    background-color: #00e676!important;
  }

  .peach-gradient {
    background: linear-gradient(40deg,#ffd86f,#fc6262) !important;
  }

  .danger-color {
    background-color: #ff3547!important;
  }

  .success-color {
    background-color: #00c851!important;
  }

  .secondary-color {
    background-color: #93c!important;
  }

  .blue-gradient {
    background: linear-gradient(40deg,#45cafc,#303f9f)!important;
  }

  .calamity-color{
    background-color: #0b1952!important;
  }

  .other-color{
    background-color: #cc08c5!important;
  }
</style>
<div id="loader-overlay">
  <div class="loader"></div>
</div>