<?php

$root = $_SERVER['DOCUMENT_ROOT'] . "/plsuite";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Metro 4 -->
    <link rel="stylesheet" href="/plsuite/Resources/Metro/build/css/metro-all.css">
    <!-- <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4.3.2/css/metro-all.min.css"> -->

</head>
<body class="d-flex flex-column h-vh-100">
  <?php require $root . "/Resources/SiteUtils/SiteMenu.html" ?>
  <div class="compacted" data-role="navview" data-compact="md" style="flex-grow: 1">
    <?php require "OperationsMenu.html" ?>
    <div class="navview-content" style="overflow: scroll">
      
    </div>
  </div>



    <!-- Metro 4 -->
    <script src="/plsuite/Resources/Metro/build/js/metro.min.js"></script>
</body>
</html>
