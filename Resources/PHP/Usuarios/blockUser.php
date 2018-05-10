<?php

include('../loginDatabase.php');

$qry = "UPDATE Users SET Status = ? WHERE pkIdUsers = ?";
$stmt = $login->prepare($qry) or die ("Error en Prepare: " . $login->error);
$stmt->bind_param('ss',
  $_POST['status'],
  $_POST['idUsuario']
);

$stmt->execute();
if ($stmt->affected_rows > 0) {
  echo "Exito";
} else {
  echo "Se intento poner el status:" . $_POST['status'] . "\n";
}



 ?>
