<?php

include('../loginDatabase.php');

$qry = "UPDATE Users SET Contrasena = ? WHERE pkIdUsers = ?";
$stmt = $login->prepare($qry) or die ("Error en Prepare: " . $login->error);
$stmt->bind_param('ss',
  $_POST['newPwd'],
  $_POST['idUsuario']
);

$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo "Exito";
} else {
  echo "Error";
}



 ?>
