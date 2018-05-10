<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$fkIdRuta = $_POST['fkIdRuta'];

$qry = "SELECT * FROM cud_rutas WHERE fkIdRuta = ? AND Estado <> 'undefined'";
$stmt = $db->prepare($qry) or die ("Error en Prepare: " . $db->error);
$stmt->bind_param('s',$fkIdRuta);


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
    $estado = $row['Estado'];
    $millas = $row['Millas'];
    $kilometros = $row['Metros'] / 1000;

    $response['code'] = 1;
    $response['data'] .= "<tr class='selectRoute' role='button'>
      <td>$estado</td>
      <td>$millas</td>
      <td>$kilometros</td>
    </tr>";
  }
} else {
  $response['code'] = 1;
  $response['data'] .= "<tr>
    <td colspan='3'>No se encontraron detalles</td>
  </tr>";
}

$resp = json_encode($response);
echo $resp;
 ?>
