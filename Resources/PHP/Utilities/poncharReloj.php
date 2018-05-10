<?php
date_default_timezone_set('America/Monterrey');

$fechaHora = explode(" ",$_POST['fecha']);
$idOperador = $_POST['idOperador'];
$registroId = $_POST['registroId'];
$estampilla = DateTime::createFromFormat('d/m/Y H:i',$_POST['fecha']);
//$hora = DateTime::createFromFormat('',$fechaHora[1]);

$fecha = $estampilla->format('Y-m-d');
$hora = $estampilla->format('H:i');
$timeStamp = $estampilla->format('Y-m-d H:i:s');

$return['tipo'] = $tipoRegistro;
$return['fecha'] = $fecha;
$return['hora'] = $hora;

$return = json_encode($return);

//echo $fecha . "\n";
//echo $hora . "\n";

include('../loginDatabase.php');

//Buscamos el último registro del operador para poder actualizarlo.
$qry = "SELECT * FROM Operator_TimeLog WHERE Operador = '$idOperador' ORDER BY pkTimelog DESC LIMIT 1";
$stmt = $login->query($qry);
$rslt1 = $stmt->fetch_assoc();
$registroId = $rslt1['pkTimelog'];
$tipoRegistro = $rslt1['SiguienteRegistro'];

if ($tipoRegistro == 'Salida') {
  $siguienteRegistro = "Entrada";
  $qry = "UPDATE Operator_TimeLog SET TimeStampSalida = ?, HoraSalida = ?, FechaSalida = ?, SiguienteRegistro = ? WHERE pkTimelog = ?";
  $stmt = $login->prepare($qry) or die ('Error preparando query: ' . $login->error);
  $stmt->bind_param('sssss',
    $timeStamp,
    $hora,
    $fecha,
    $siguienteRegistro,
    $registroId
  ) or die ('Error en binding');
} else {
  $siguienteRegistro = "Salida";
  $qry = "INSERT INTO Operator_TimeLog (FechaEntrada, HoraEntrada, SiguienteRegistro, Operador, TimeStampEntrada) VALUES (?,?,?,?,?)";
  $stmt = $login->prepare($qry) or die ('Error preparando query');
  $stmt->bind_param('sssss',
    $fecha,
    $hora,
    $siguienteRegistro,
    $idOperador,
    $timeStamp
  ) or die ('Error en binding');
}

$stmt->execute();

if ($stmt->affected_rows > 0) {
  echo $return;
} else {
  echo "Failed\n";
  var_dump($stmt);
}

 ?>
