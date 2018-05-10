<?php

require '../../PHPExcel/Classes/PHPExcel.php';
include('../loginDatabase.php');
date_default_timezone_set('America/Monterrey');

if (isset($_GET['fechaDesde']) && isset($_GET['fechaHasta'])) {
  $fechaDesde = $_GET['fechaDesde'];
  $fechaHasta = $_GET['fechaHasta'];
  $idUsuario = $_GET['id'];
} else {
  $fechaDesde = date('Y-m-d', strtotime('-1 Monday'));
  $fechaHasta = new DateTime();
  $fechaDesde = $fechaDesde;
  $fechaHasta = $fechaHasta->format('Y-m-d');
  $idUsuario = $_GET['id'];
}

$registros = array();
$qry =
" SELECT
    l.FechaEntrada AS Fecha,
    l.TimeStampEntrada AS Entrada,
    l.TimeStampSalida AS Salida,
    CONCAT(u.Nombre, ' ', u.Apellido) AS NombreOperador,
    TIMESTAMPDIFF(MINUTE, TimeStampEntrada, TimeStampSalida) AS DuracionTurno
  FROM
    Operator_TimeLog l
  LEFT JOIN Users u ON l.Operador = u.NombreUsuario
  WHERE
    u.NombreUsuario = ? AND
    l.FechaEntrada BETWEEN ? AND ?
  ";

$stmt = $login->prepare($qry);
$stmt->bind_param('sss',
  $idUsuario,
  $fechaDesde,
  $fechaHasta
);
$stmt->execute();
$rslt = $stmt->get_result();

/*while ($row = $rslt->fetch_assoc()) {
  $registros[]=$row;
}*/

$dataStyle = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  //'borders' => array(
    //'outline' => array(
      //'style' => PHPExcel_Style_Border::BORDER_THIN
    //),
  //),
);

$headerStyle = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'borders' => array(
    'bottom' => array(
      'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
    ),
  ),
);

$xls = new PHPExcel();

$xlsCurrent = $xls->getActiveSheet();

$xlsCurrent->fromArray(
  $registros,
  NULL,
  'B3'
);

$x = 2;
while ($row = $rslt->fetch_assoc()) {
  $x++;
  $xlsCurrent->setCellValue("B".$x, $row['Fecha']);
  $xlsCurrent->getStyle("B".$x)->applyFromArray($dataStyle);
  $xlsCurrent->setCellValue("C".$x, $row['Entrada']);
  $xlsCurrent->getStyle("C".$x)->applyFromArray($dataStyle);
  $xlsCurrent->setCellValue("D".$x, $row['Salida']);
  $xlsCurrent->getStyle("D".$x)->applyFromArray($dataStyle);
  $xlsCurrent->setCellValue("E".$x, $row['NombreOperador']);
  $xlsCurrent->getStyle("E".$x)->applyFromArray($dataStyle);
  $xlsCurrent->setCellValue("F".$x, $row['DuracionTurno']);
  $xlsCurrent->getStyle("F".$x)->applyFromArray($dataStyle);
}

$xlsCurrent->setCellValue('B2','Fecha');
$xlsCurrent->getStyle("B2")->applyFromArray($headerStyle);
$xlsCurrent->setCellValue('C2','Entrada');
$xlsCurrent->getStyle("C2")->applyFromArray($headerStyle);
$xlsCurrent->setCellValue('D2','Salida');
$xlsCurrent->getStyle("D2")->applyFromArray($headerStyle);
$xlsCurrent->setCellValue('E2','Operador');
$xlsCurrent->getStyle("E2")->applyFromArray($headerStyle);
$xlsCurrent->setCellValue('F2','Duración');
$xlsCurrent->getStyle("F2")->applyFromArray($headerStyle);


$xlsCurrent->setShowGridlines(false);
$xlsCurrent->getColumnDimension('B')->setAutoSize(true);
$xlsCurrent->getColumnDimension('C')->setAutoSize(true);
$xlsCurrent->getColumnDimension('D')->setAutoSize(true);
$xlsCurrent->getColumnDimension('E')->setAutoSize(true);
$xlsCurrent->getColumnDimension('F')->setAutoSize(true);

$writeXLS = new PHPExcel_Writer_Excel2007($xls);

$hoy = new DateTime();
$hoy = $hoy->format('Ymdhsi');
$file = "../../Exports/Export_" . $hoy . ".xlsx";


$writeXLS->save($file);

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/x-zip-compressed');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
 ?>
