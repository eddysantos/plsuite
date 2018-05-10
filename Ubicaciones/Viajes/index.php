<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/session.php';
require $root . '/plsuite/Resources/PHP/Utilities/header.php';
 ?>
<!DOCTYPE html>
<body>
  <div class="container-fluid text-center mt-3">
    <a class="btn btn-outline-secondary nb-id" href="dashboard.php" type="button" name="button" role="button">All Trips</a>
    <a class="btn btn-outline-secondary nb-id disabled" type="button" name="button" role="button">Add Trip</a>
  </div>
  <div class="container-fluid w-75 border mt-5">
    <h1>Status Operadores</h1>
    <table class="table table-hover">
      <thead class="text-center">
        <th>Operador</th>
        <th>Tipo Operador</th>
        <th>Tractor Asignado</th>
        <th>Fecha Status</th>
        <th>Comentarios</th>
      </thead>
      <tbody class="text-center">
        <td>Fecha 1</td>
        <td>Operador 1</td>
        <td>Tractor 1</td>
        <td>Origen 1</td>
        <td>Destino 1</td>
      </tbody>
    </table>
  </div>

  <div class="container-fluid w-75 border mt-5">
    <h1>Ultimos Viajes</h1>
    <table class="table table-hover">
      <thead class="text-center">
        <th>Operador</th>
        <th>Tipo Operador</th>
        <th>Tractor Asignado</th>
        <th>Fecha Status</th>
        <th>Comentarios</th>
      </thead>
      <tbody class="text-center">
        <td>Fecha 1</td>
        <td>Operador 1</td>
        <td>Tractor 1</td>
        <td>Origen 1</td>
        <td>Destino 1</td>
      </tbody>
    </table>
  </div>
</body>

<?php
require $root . '/plsuite/Resources/PHP/Utilities/footer.php';
 ?>
