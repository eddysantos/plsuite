<?php

include('../loginDatabase.php');


$qry = "SELECT * FROM Users WHERE NombreUsuario = ?";
$stmt = $login->prepare($qry) or die('Error en preparar el statement: ' . $stmt->error);
$stmt->bind_param('s',$_POST['NombreUsuario']) or die ('Error al relacionar parametros: ' . $stmt->error);
$stmt->execute() or die ('Error de ejecución: ' . $stmt->error);
$rslt = $stmt->get_result();

//echo $_POST['NombreUsuario'];

if ($rslt->num_rows == '0') {
  echo "Success";
} else {
  echo "Failed";
}

$login->close();
 ?>
