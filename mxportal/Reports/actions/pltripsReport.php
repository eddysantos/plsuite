<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
require $root . '/plsuite/Resources/vendor/autoload.php';
// include('/var/www/html/plt/plsuite/Resources/PHP/loginDatabase.php');
date_default_timezone_set('America/Monterrey');

function check_date($date){
  if ($date == "") {
    return "";
  } else {
    return date('Y-m-d', strtotime($date));
  }
}

$system_callback = [];
$linehaul = [];
$grouping = "";
$data = $_GET;

if ($_GET['level'] == 'linehs') {
  $grouping = "";
  // $grouping = "GROUP BY tl.pk_idlinehaul";
}

$data['from'] = $data['from'] . " 00:00";
$data['to'] = $data['to'] . " 23:59";
// $data = array(
//   'from'=>'2018-01-01',
//   'to'=>'2018-02-28'
// );

$query = "SELECT cp.pk_carta_porte_number carta_porte , cp.trailer_number trailer_number , o.pk_mx_place origin_id , o.place_alias origin , concat( o.address_street , ' ' , o.address_ext_number , ' ,' , o.address_locality , ' ,' , o.address_city , ' ,' , o.address_state , ' ,' , o.address_zip_code) direccion_origen , d.pk_mx_place destination_id , d.place_alias destination , concat( d.address_street , ' ' , d.address_ext_number , ' ,' , d.address_locality , ' ,' , d.address_city , ' ,' , d.address_state , ' ,' , d.address_zip_code) direccion_destino , cp.date_start date_start , cp.date_end date_end , CONCAT(dr.nameFirst , ' ' , dr.nameLast) driver , t.tractor_number tractor , c.client_name client , cp.movement_type type , cp.movement_class class , cp.distance distance , cp.cp_status cp_status FROM mx_carta_porte cp LEFT JOIN mx_places o ON cp.fk_mx_place_origin = o.pk_mx_place LEFT JOIN mx_places d ON cp.fk_mx_place_destination = d.pk_mx_place LEFT JOIN mx_trips t ON cp.fk_mx_trip = t.pk_mx_trip LEFT JOIN ct_drivers dr ON t.fk_driver = dr.pkid_driver LEFT JOIN mx_clients c ON t.fk_mx_client = c.pk_mx_client WHERE cp.date_start BETWEEN ? AND ? ORDER BY date_start ASC";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('ss', $data['from'], $data['to']);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($system_callback);
}

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $system_callback['code'] = 2;
  $system_callback['message'] = "Script called successfully but there are no rows to display.";
  $system_callback['data'] = $data;
  header('Location: /plsuite/Ubicaciones/Reports/');
}

while ($row = $rslt->fetch_assoc()) {
  $records[] = $row;
}

$headerStyle = array(
  'alignment' => array(
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
  ),
  'fill' => array(
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'color' => array(
            'argb' => 'FFB0CDEA',
        )
    ),
  'font' => array(
    'name' => "Calibri",
    'bold' => true,
    'color' => array('argb' => 'FF354157'),
  ),
  'borders' => array(
    'top' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
    'bottom' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
  )
);

$evenCell = array(
  'alignment' => array(
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
  ),
  'fill' => array(
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'color' => array(
            'argb' => 'FFEFEFEF',
        )
    ),
  'borders' => array(
    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
    'vertical' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
  )
);

$givenCell = array(
  'alignment' => array(
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
  ),
  'fill' => array(
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'color' => array(
            'argb' => 'FFFFFFFF',
        )
    ),
  'borders' => array(
    'right' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
    'vertical' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
    'left' => array('borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
  )
);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$xls = new Spreadsheet();
$xlsActive = $xls->getActiveSheet();

$xlsActive->setCellValue("A1", "CARTA PORTE");
$xlsActive->setCellValue("B1", "NUMERO CAJA");
$xlsActive->setCellValue("C1", "ORIGEN");
$xlsActive->setCellValue("D1", "DESTINO");
$xlsActive->setCellValue("E1", "FECHA INICIO");
$xlsActive->setCellValue("F1", "FECHA FINAL");
$xlsActive->setCellValue("G1", "OPERADOR");
$xlsActive->setCellValue("H1", "TRACTOR");
$xlsActive->setCellValue("I1", "CLIENTE");
$xlsActive->setCellValue("J1", "TIPO");
$xlsActive->setCellValue("K1", "CLASE");
$xlsActive->setCellValue("L1", "DISTANCIA");
$xlsActive->setCellValue("M1", "STATUS");
$xlsActive->getStyle("A1")->applyFromArray($headerStyle);
$xlsActive->getStyle("B1")->applyFromArray($headerStyle);
$xlsActive->getStyle("C1")->applyFromArray($headerStyle);
$xlsActive->getStyle("D1")->applyFromArray($headerStyle);
$xlsActive->getStyle("E1")->applyFromArray($headerStyle);
$xlsActive->getStyle("F1")->applyFromArray($headerStyle);
$xlsActive->getStyle("G1")->applyFromArray($headerStyle);
$xlsActive->getStyle("H1")->applyFromArray($headerStyle);
$xlsActive->getStyle("I1")->applyFromArray($headerStyle);
$xlsActive->getStyle("J1")->applyFromArray($headerStyle);
$xlsActive->getStyle("K1")->applyFromArray($headerStyle);
$xlsActive->getStyle("L1")->applyFromArray($headerStyle);
$xlsActive->getStyle("M1")->applyFromArray($headerStyle);

$x = 1;
$isEven = true;

foreach ($records AS $carta_porte) {
  // error_log(json_encode($carta_porte));
  // die();
  $isEven = !($isEven);
  $x += 1;
  $start = "A" . $x;

  $xlsActive->setCellValue("A".$x, $carta_porte['carta_porte']);
  $xlsActive->setCellValue("B".$x, $carta_porte['trailer_number']);
  $xlsActive->setCellValue("C".$x, $carta_porte['origin']);
  $xlsActive->setCellValue("D".$x, $carta_porte['destination']);
  $xlsActive->setCellValue("E".$x, $carta_porte['date_start']);
  $xlsActive->getStyle("E".$x)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
  $xlsActive->setCellValue("F".$x, $carta_porte['date_end']);
  $xlsActive->getStyle("F".$x)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
  $xlsActive->setCellValue("G".$x, $carta_porte['driver']);
  $xlsActive->setCellValue("H".$x, $carta_porte['tractor']);
  $xlsActive->setCellValue("I".$x, $carta_porte['client']);
  $xlsActive->setCellValue("J".$x, $carta_porte['type']);
  $xlsActive->setCellValue("K".$x, $carta_porte['class']);
  $xlsActive->setCellValue("L".$x, $carta_porte['distance']);
  // $xlsActive->getStyle("L".$x)->getNumberFormat()->setFormatCode("$#,##0.00");
  $xlsActive->setCellValue("M".$x, $carta_porte['cp_status']);

  $end = "M" . $x;
  if ($isEven) {
    $xlsActive->getStyle($start.":".$end)->applyFromArray($evenCell);
  } else {
    $xlsActive->getStyle($start.":".$end)->applyFromArray($givenCell);
  }

}


$xlsActive->getColumnDimension('A')->setWidth(10.67 + .84);
$xlsActive->getColumnDimension('B')->setWidth(12.5 + .84);
$xlsActive->getColumnDimension('C')->setWidth(25.67 + .84);
$xlsActive->getColumnDimension('D')->setWidth(25.67 + .84);
$xlsActive->getColumnDimension('E')->setWidth(13.5 + .84);
$xlsActive->getColumnDimension('F')->setWidth(13.5 + .84);
$xlsActive->getColumnDimension('G')->setWidth(13.5 + .84);
$xlsActive->getColumnDimension('H')->setWidth(25.67 + .84);
$xlsActive->getColumnDimension('I')->setWidth(10.67 + .84);
$xlsActive->getColumnDimension('J')->setWidth(28.33 + .84);
$xlsActive->getColumnDimension('K')->setWidth(10.67 + .84);
$xlsActive->getColumnDimension('L')->setWidth(10.67 + .84);
$xlsActive->getColumnDimension('M')->setWidth(10.67 + .84);
$xlsActive->getStyle('A1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('B1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('C1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('D1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('H1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('I1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('J1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('K1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('L1')->getAlignment()->setWrapText(true);
$xlsActive->getStyle('M1')->getAlignment()->setWrapText(true);

$writeXLS = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($xls);

$uniq = uniqid();

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="PLTripsMex.xlsx"');

// Write file to the browser
$writeXLS->save('php://output');

 ?>
