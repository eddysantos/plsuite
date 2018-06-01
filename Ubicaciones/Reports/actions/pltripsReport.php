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

$system_callback = [];
$linehaul = [];
$grouping = "";
$data = $_GET;

if ($_GET['level'] == 'linehs') {
  $grouping = "";
  // $grouping = "GROUP BY tl.pk_idlinehaul";
}


// $data = array(
//   'from'=>'2018-01-01',
//   'to'=>'2018-02-28'
// );

$query = "SELECT t.pkid_trip trip , t.trip_year trip_year , tl.pk_idlinehaul linehaul, tl.pk_linehaul_number lh_number, tr.trailerNumber trailer , tl.origin_city linehaul_ocity , tl.origin_state linehaul_ostate , tl.destination_city linehaul_dcity , tl.destination_state linehaul_dstate , tl.date_departure departure , tl.date_arrival arrival , tl.date_delivery delivery , tlm.pk_movement_number mov_number, tlm.origin_city movement_ocity , tlm.origin_state movement_ostate , tlm.destination_city movement_dcity , tlm.destination_state movement_dstate , d.nameFirst driver_firstn , d.nameLast driver_lastn , con.truckNumber truck_number , b.brokerName broker , tl.broker_reference reference_number , tl.trip_rate trip_rate , tlm.miles_google miles , tlm.movement_type mov_type FROM ct_trip t LEFT JOIN ct_trailer tr ON t.fkid_trailer = tr.pkid_trailer LEFT JOIN ct_trip_linehaul tl ON t.trip_year = tl.fk_tripyear AND t.pkid_trip = tl.fk_idtrip LEFT JOIN ct_trip_linehaul_movement tlm ON tl.pk_idlinehaul = tlm.fkid_linehaul LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_truck con ON tlm.fkid_tractor = con.pkid_truck LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker WHERE ( tl.date_arrival >= ? AND tl.date_arrival <= ?) OR (tl.date_arrival IS NULL) $grouping ORDER BY trip DESC, lh_number ASC , departure DESC , arrival DESC , delivery DESC , mov_number ASC";

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
  // echo json_encode($row) . "\n";

      $records[$row['trip']][$row['linehaul']]['id'] = $row['trip_year'] . str_pad($row['trip'], 4, 0, STR_PAD_LEFT) . $row['lh_number'];
      $records[$row['trip']][$row['linehaul']]['trailer_number'] = $row['trailer'];
      $records[$row['trip']][$row['linehaul']]['origin'] = $row['linehaul_ocity'] . ", " . $row['linehaul_ostate'];
      $records[$row['trip']][$row['linehaul']]['destination'] = $row['linehaul_dcity'] . ", " . $row['linehaul_dstate'];
      $records[$row['trip']][$row['linehaul']]['departure'] = check_date($row['departure']);
      $records[$row['trip']][$row['linehaul']]['arrival'] = check_date($row['arrival']);
      $records[$row['trip']][$row['linehaul']]['delivery'] = check_date($row['delivery']);
      $records[$row['trip']][$row['linehaul']]['loaded_miles'] += 0;
      $records[$row['trip']][$row['linehaul']]['empty_miles'] += 0;
      $records[$row['trip']][$row['linehaul']]['client'] = $row['broker'];
      $records[$row['trip']][$row['linehaul']]['client_reference'] = $row['reference_number'];
      $records[$row['trip']][$row['linehaul']]['amount'] = $row['trip_rate'];

      if ($row['mov_type'] == 'E') {
        $records[$row['trip']][$row['linehaul']]['empty_miles'] += $row['miles'];
      }

      if ($row['mov_type'] == 'L') {
        $records[$row['trip']][$row['linehaul']]['loaded_miles'] += $row['miles'];
      }

      if ($_GET['level'] == 'linehs') {
        $records[$row['trip']][$row['linehaul']]['driver'] = $row['driver_firstn'] . " " . $row['driver_lastn'];
        $records[$row['trip']][$row['linehaul']]['truck_number'] = $row['truck_number'];
        continue;
      }

      $records[$row['trip']][$row['linehaul']]['movements'][$row['movement_id']] = array(
        'id' => $row['movement_id'],
        'origin' => $row['movement_ocity'] . ", " . $row['movement_ostate'],
        'destination' => $row['movement_dcity'] . ", " . $row['movement_dstate'],
        'driver' => $row['driver_firstn'] . " " . $row['driver_lastn'],
        'truck' => $row['truck_number'],
        'miles' => $row['miles'],
        'type' => $row['mov_type']
      );


}


// require '/var/www/html/plt/plsuite/Resources/PHPExcel/Classes/PHPExcel.php';
// require $root . '/plsuite/Resources/PHPSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php';

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
    $end = "O" . $x;
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
