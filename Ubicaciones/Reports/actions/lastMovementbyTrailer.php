<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

// include('/var/www/html/plt/plsuite/Resources/PHP/loginDatabase.php');
date_default_timezone_set('America/Monterrey');

function check_date($date){
  if ($date == "") {
    return "";
  } else {
    return date('Y-m-d', strtotime($date));
  }
}

$trailers = [];
$trips = [];

// Get list of trailers so we can get the last trip of each one of them.

$query = "SELECT pkid_trailer id_trailer, trailerNumber trailer_number FROM ct_trailer WHERE deletedTrailer IS NOT NULL";


$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

// $stmt->bind_param('ss', $data['from'], $data['to']);
// if (!($stmt)) {
//   $system_callback['code'] = "500";
//   $system_callback['query'] = $query;
//   $system_callback['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
//   exit_script($system_callback);
// }

if (!($stmt->execute())) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($system_callback);
}

$rslt = $stmt->get_result();

while ($row = $rslt->fetch_assoc()) {
  array_push($trailers, $row['id_trailer']);
}


$query = "SELECT trip_number, trailer_number, last_trip FROM ct_trip WHERE trailer_number = ? LIMIT 1";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($system_callback);
}

$stmt->bind_param('s', $data['trailer_number']);
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

while ($row = $rslt->fetch_assoc()) {
  $record = array(
    'trip_number' => $row['trip_number'],
    'last_linehaul'=> '',
    'last_movement'=> $row['last_trip']
  );
  array_push($trips, $record);
}

$query = "SELECT pk_idlinehaul, lh_number FROM ct_trip_linehaul WHERE fk_idtrip = ?";


require $root . '/plsuite/Resources/vendor/autoload.php';

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

$xlsActive->setCellValue("A1", "LOAD NUMBER");
$xlsActive->setCellValue("B1", "TRAILER NUMBER");
$xlsActive->setCellValue("C1", "ORIGIN");
$xlsActive->setCellValue("D1", "DESTINATION");
$xlsActive->setCellValue("E1", "DEPARTURE DATE");
$xlsActive->setCellValue("F1", "ARRIVAL");
$xlsActive->setCellValue("G1", "DELIVERY DATE");
$xlsActive->setCellValue("H1", "DRIVER");
$xlsActive->setCellValue("I1", "TRUCK NUMBER");
$xlsActive->setCellValue("J1", "CLIENT");
$xlsActive->setCellValue("K1", "REFERENCE NUMBER");
$xlsActive->setCellValue("L1", "AMOUNT");
$xlsActive->setCellValue("M1", "L MILES");
$xlsActive->setCellValue("N1", "E MILES");
$xlsActive->setCellValue("O1", "RPM");
$xlsActive->setCellValue("P1", "Linehaul Status");
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
$xlsActive->getStyle("N1")->applyFromArray($headerStyle);
$xlsActive->getStyle("O1")->applyFromArray($headerStyle);
$xlsActive->getStyle("P1")->applyFromArray($headerStyle);

$x = 1;
$isEven = true;

foreach ($records AS $linehauls) {
  foreach ($linehauls as $linehaul => $lh_data) {
    $isEven = !($isEven);
    $x += 1;
    $start = "A" . $x;

    $xlsActive->setCellValue("A".$x, $lh_data['id']);
    $xlsActive->setCellValue("B".$x, $lh_data['trailer_number']);
    $xlsActive->setCellValue("C".$x, $lh_data['origin']);
    $xlsActive->setCellValue("D".$x, $lh_data['destination']);
    $xlsActive->setCellValue("E".$x, $lh_data['departure']);
    $xlsActive->getStyle("E".$x)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    $xlsActive->setCellValue("F".$x, $lh_data['arrival']);
    $xlsActive->getStyle("F".$x)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    $xlsActive->setCellValue("G".$x, $lh_data['delivery']);
    $xlsActive->getStyle("G".$x)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
    $xlsActive->setCellValue("H".$x, $lh_data['driver']);
    $xlsActive->setCellValue("I".$x, $lh_data['truck_number']);
    $xlsActive->setCellValue("J".$x, $lh_data['client']);
    $xlsActive->setCellValue("K".$x, $lh_data['client_reference']);
    $xlsActive->setCellValue("L".$x, $lh_data['amount']);
    $xlsActive->getStyle("L".$x)->getNumberFormat()->setFormatCode("$#,##0.00");
    $xlsActive->setCellValue("M".$x, $lh_data['loaded_miles']);
    $xlsActive->setCellValue("N".$x, $lh_data['empty_miles']);
    if ($lh_data['loaded_miles'] == "0" && $lh_data['empty_miles'] == "0") {
      $xlsActive->setCellValue("O".$x, 0.00);
    } else {
      $xlsActive->setCellValue("O".$x, round($lh_data['amount'] / ($lh_data['loaded_miles'] + $lh_data['empty_miles']), 2));
    }
    $xlsActive->getStyle("O".$x)->getNumberFormat()->setFormatCode("$#,##0.00");
    $xlsActive->setCellValue("P".$x, $lh_data['lh_status']);
    foreach ($lh_data['movements'] as $movement => $mov_data) {
      $x += 1;
      $xlsActive->setCellValue("C".$x, $mov_data['origin']);
      $xlsActive->getStyle("C".$x)->getFont()->setItalic(true);
      $xlsActive->setCellValue("D".$x, $mov_data['destination']);
      $xlsActive->getStyle("D".$x)->getFont()->setItalic(true);
      $xlsActive->setCellValue("H".$x, $mov_data['driver']);
      $xlsActive->setCellValue("I".$x, $mov_data['truck']);
      switch ($mov_data['type']) {
        case 'L':
        $xlsActive->setCellValue("M".$x, $mov_data['miles']);
          break;

        case 'E':
        $xlsActive->setCellValue("N".$x, $mov_data['miles']);
        default:
          break;
      }
    }
    $end = "P" . $x;
    if ($isEven) {
      $xlsActive->getStyle($start.":".$end)->applyFromArray($evenCell);
    } else {
      $xlsActive->getStyle($start.":".$end)->applyFromArray($givenCell);
    }

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
$xlsActive->getColumnDimension('N')->setWidth(10.67 + .84);
$xlsActive->getColumnDimension('N')->setWidth(10.67 + .84);
$xlsActive->getColumnDimension('P')->setWidth(12.67 + .84);
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
$xlsActive->getStyle('N1')->getAlignment()->setWrapText(true);

$writeXLS = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($xls);

$uniq = uniqid();

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="PLTrips.xlsx"');

// Write file to the browser
$writeXLS->save('php://output');

 ?>
