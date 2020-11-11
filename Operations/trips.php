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
            <button class="button">Pending</button>
            <button class="button">Scheduled</button>
            <button class="button">In Transit</button>
            <button class="button">Delivered</button>
          </div>
          <form class="" style="max-width: 450px">
            <input type="text" data-role="input" data-search-button="true">
          </form>
        </div>
        <div class="">
          <div class="border bd-grayBlue p-1 border-radius">
            <div class="w-100 d-flex flex-justify-between">
              <div class="">
                <div class="">
                  <b>2000013</b>
                  <span class="fg-red">(<i>CH Robinson</i>)</span>
                </div>
                <div class="">
                  Laredo, TX - Atlanta, GA
                </div>
                <div class="">
                  <span class="">1008 Miles</span>|<span>$2.25</span> RPM
                </div>
              </div>
              <div class="">
                <div class=""><b>Start Date:</b> 2020-01-01</div>
                <div class=""><b>Appt Time:</b> 2020-01-01</div>
              </div>
              <div class="text-right">
                <div class="">Miguel Velez</div>
                <div class="">T015</div>
                <div class="">NONZ976976</div>
              </div>
            </div>
          </div>
        </div>
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
    <script src="/plsuite/Resources/Metro/build/js/metro.min.js"></script>
</body>
</html>
