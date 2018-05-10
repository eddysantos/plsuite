<?php

include('/var/www/html/timetracker/Resources/PHP/loginDatabase.php');

$qry = "INSERT INTO Users (Nombre, Apellido, TipoUsuario, NombreUsuario, Contrasena, Status) VALUES (?,?,?,?,?,?)";
$stmt = $login->prepare($qry);
$stmt->bind_param('ssssss',
  $_POST['nuNombre'],
  $_POST['nuApellido'],
  $_POST['nuTipoUsuario'],
  $_POST['nuNombreUsuario'],
  $_POST['nuContrasena'],
  $_POST['nuStatus']
);
$stmt->execute();

$login->close();

 ?>
