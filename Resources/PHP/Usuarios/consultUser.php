<?php

include('../loginDatabase.php');

$qry = "SELECT Nombre, Apellido, NombreUsuario, pkIdUsers FROM Users WHERE pkIdUsers = ?";
$stmt = $login->prepare($qry) or die ("Error en Prepare: " . $login->error);
$stmt->bind_param('s',$_POST['idUsuario']);

$stmt->execute();
$rslt = $stmt->get_result();


if ($rslt->num_rows > 0) {
  $jsonParse = $rslt->fetch_assoc();
  $jsonParse = json_encode($jsonParse);
  echo $jsonParse;
} else {
  echo "Error";
}



 ?>
