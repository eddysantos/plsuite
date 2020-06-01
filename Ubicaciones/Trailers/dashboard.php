<?php

$dash_active = "";
$viajes_active = "";
$operadores_active = "";
$tractores_active = "";
$cajas_active = "active";

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
 ?>
<div class="container-fluid align-items-center justify-content-between d-flex mb-3" style="margin-top: 75px">
  <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#addTrailerModal" name="button">Add Trailer</button>
</div>

<div class="container-fluid" style="overflow-y: scroll; height: calc(100vh - 190px);">
  <table class="table table-hover">
    <thead class="nb-id">
      <th>Trailer #</th>
      <th>VIN</th>
      <th>Owner</th>
      <th>Status</th>
      <th>Date Added</th>
      <th>Plates</th>
      <th></th>
    </thead>
    <tbody id="trailerDash">
    </tbody>
    <tbody>

    </tbody>
  </table>
</div>
<?php
require 'modales/addTrailer.php';
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
 <script src="js/trailers.js" charset="utf-8"></script>
