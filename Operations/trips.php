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
    <div class="navview-content pt-2">
      <div class="container-fluid">
        <div class="d-flex flex-justify-between mb-2">
          <div data-role="buttongroup">
            <button class="button active">All</button>
            <button class="button">Open</button>
            <button class="button">Closed</button>
          </div>
          <form class="" style="max-width: 450px">
            <input type="text" data-role="input" data-search-button="true">
          </form>
        </div>
        <div class=""> <!-- TRIP LIST CONTAINER -->

          <div class="bg-lightGray bd-grayBlue p-3 mb-1 border-radius">
            <div class="w-100 d-flex flex-justify-between">
              <div class="">
                <div class="">
                  <b>200096</b> -
                  <span>NONZ976976</span>
                </div>
                <div class="pl-4">
                  <span class="fas fa-circle fg-yellow"></span>
                  <span>1<span>|
                  <span>Laredo, TX - Atlanta, GA</span>
                  <span class="fg-darkRed">[CH Robinson]</span>
                  <span class="fg-darkGray"><span>1008 Miles</span>|<span>$2.25</span> RPM</span>
                </div>
                <div class="pl-4">
                  <span class="fas fa-circle fg-yellow"></span>
                  <span>2<span>|
                  <span>Atlanta GA - Somewhere, PA</span>
                  <span class="fg-darkRed">[Royal Transportation]</span>
                  <span class="fg-darkGray"><span>1008 Miles</span>|<span>$2.25</span> RPM</span>
                </div>
                <div class="pl-4">
                  <span class="fas fa-circle fg-yellow"></span>
                  <span>3<span>|
                  <span>Somewhere, PA - Anywhere, TX</span>
                  <span class="fg-darkRed">[EPES Logistics]</span>
                  <span class="fg-darkGray"><span>1008 Miles</span>|<span>$2.25</span> RPM</span>
                </div>
              </div>
              <div class="text-right">
                <div class="">3024 Miles</div>
                <div class="">$2.25 RPM</div>
              </div>
            </div>

            <div class="m-3 p-2 bg-light border-radius">
              Aqui pondremos detalles adicionales. Esta ventana se esconderá hasta que le hagan click al renglón.
            </div>
          </div>

        </div> <!-- TRIP LIST CONTAINER -->
        <!-- <div class="">
          <table class="table subcompact">
            <thead>
              <tr>
                <th>Trip</th>
                <th>Reference</th>
                <th>Client</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>RPM</th>
                <th>Start Date</th>
                <th>Appt Date</th>
                <th>Appt Time</th>
                <th>Delivery</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
              <tr>
                <td>2001101</td>
                <td>3345639948</td>
                <td>James Transportation</td>
                <td>Laredo, TX</td>
                <td>Mechanicsburg, PA</td>
                <td>$2.00</td>
                <td>2020-11-01</td>
                <td>2020-11-05</td>
                <td>1:00</td>
                <td>2020-11-05</td>
              </tr>
            </tbody>
          </table>
        </div> -->
      </div>
    </div>
  </div>



    <!-- Metro 4 -->
    <script src="/plsuite/Resources/JQuery/jquery-3.2.1.min.js" charset="utf-8"></script>
    <script src="/plsuite/Resources/Metro/build/js/metro.min.js"></script>
    <script src="/plsuite/Resources/fa_5/js/fontawesome-all.min.js" data-auto-replace-svg="nest" charset="utf-8"></script>
</body>
</html>
