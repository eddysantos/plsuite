<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$estado = $_POST['estado_corto'];
$ciudad = '%' . $_POST['ciudad'] . "%";

$qry = "SELECT ciudad FROM cs_usa_city_state_catalogue WHERE estado_corto = ? AND ciudad LIKE ?";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
$stmt->bind_param('ss',$estado, $ciudad);


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

    $response['code'] = 1;
    $response['data'] .= "<li role='button' class='city-item'>
      <p>
        <span class='ciudad'>" . $row['ciudad'] . "</span>
      </p>
      <hr>
    </li>";
  }
} else {
  $response['code'] = 1;
  $response['data'] .= "<li role='button' class='city-item'>
    <p>
      <span id='ciudadOrigen'>No se encontraron resultados..</span>
    </p>
    <hr>
  </li>";
}

$resp = json_encode($response);
echo $resp;
 ?>
