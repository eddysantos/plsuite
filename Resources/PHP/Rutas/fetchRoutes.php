<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

if (isset($_POST['routeSearch'])) {
  $routeSearch = "'%" . $_POST['routeSearch'] . "%'";
} else {
  $routeSearch = "%";
}

$qry = "SELECT * FROM cu_rutas WHERE Origen LIKE ? OR Destino LIKE ?";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
$stmt->bind_param('ss',$routeSearch, $routeSearch);


if (!($stmt->execute())) {
  $response['code'] = "200";
  $response['systemMessage'] = "Hubo un error al procesar el query(" . $stmt->errno . "): $stmt->error.";
}

$rslt = $stmt->get_result();

$response = array(
  'code'=>"",
  'systemMessage'=>"",
  'data'=>""
);

if ($rslt->num_rows > 0) {
  while ($row = $rslt->fetch_assoc()) {
    $id = $row['pkIdRuta'];
    $origen = $row['Origen'];
    $destino = $row['Destino'];

    $response['code'] = 1;
    $response['data'] .= "<tr class='selectRoute' role='button' routeId='$id' origen='$origen' destino='$destino'>
      <td>$id</td>
      <td>$origen</td>
      <td>$destino</td>
      <td>
        <button class='btn btn-outline-info' style='z-index: 9999' role='button' rutaId='$id' data-toggle='manual-modal' data-target='#detallesRutaModal'>
          <i class='fa fa-info-circle'></i>
        </button>
        <button class='btn btn-outline-danger' style='z-index: 9999' role='button' rutaId='$id' data-toggle='manual-modal' data-target='#deleteRouteModal'>
          <i class='fa fa-trash-o'></i>
        </button>
      </td>
    </tr>";
  }
} else {
  $response['code'] = 1;
  $response['data'] .= "<tr>
    <td colspan='3'>No se encontró ninguna ruta</td>
  </tr>";
}

$resp = json_encode($response);
echo $resp;
 ?>
