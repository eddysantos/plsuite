<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/vendor/autoload.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';


class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES . "Logo.png";
        $this->Image($image_file, 10, 10, 50, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false);

        //Set text color
        $this->setTextColor(102);

        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(100, 35, 'Outbound Trailers Report', 0, false, 'C', 0, '', 0, false, 'T', 'C');

        $this->SetFont('helvetica', '', 15);
        $this->Cell(0, 0, date('m-d-Y', strtotime('today')) , 0, false, 'C', 0, '', 0, false, 'T', 'C');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


$query = "SELECT tl.pk_idlinehaul pk_idlinehaul , tl.fk_tripyear trip_year , tl.fk_idtrip trip_number , tl.pk_linehaul_number linehaul_number , t.trailer_number trailer , CONCAT( tl.destination_city, ', ', tl.destination_state) destination, CONCAT(d.nameFirst , ' ' , d.nameLast) driver , c.truckNumber truck FROM ct_trip_linehaul tl LEFT JOIN ct_trip t ON t.pkid_trip = tl.fk_idtrip AND t.trip_year = tl.fk_tripyear LEFT JOIN ct_brokers b ON tl.fkid_broker = b.pkid_broker LEFT JOIN ct_trip_linehaul_movement tlm ON tlm.fkid_linehaul = tl.pk_idlinehaul LEFT JOIN ct_drivers d ON tlm.fkid_driver = d.pkid_driver LEFT JOIN ct_truck c ON tlm.fkid_tractor = c.pkid_truck WHERE tl.origin_zip IN('78041' , '78045') AND date(tl.date_begin) = CURDATE() GROUP BY pk_idlinehaul";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $cb['query']['code'] = "500";
  $cb['query']['query'] = $query;
  $cb['query']['message'] = "Error during TRIP_LINEHAUL query prepare [$db->errno]: $db->error";
  exit_script($cb);
}

if (!($stmt->execute())) {
  $cb['query']['code'] = "500";
  $cb['query']['query'] = $query;
  $cb['query']['message'] = "Error during TRIP_LINEHAUL query execution [$stmt->errno]: $stmt->error";
  exit_script($cb);
}

$rslt = $stmt->get_result();
$linehauls = array();

$amount = $rslt->num_rows;

if ($rslt->num_rows == 0) {
  $cb['query']['code'] = 2;
  $cb['query']['message'] = "Script called successfully but there are no rows to display. For trailer query.";
} else {
  while ($row = $rslt->fetch_assoc()) {
    $linehauls[] = $row;
  }
}

$pages = ceil($amount / 7);

error_log("Pages: " . $pages);

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Nicola Asuni');
// $pdf->SetTitle('TCPDF Example 003');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 12);
$pdf->setTextColor(102);


$trailer_w = ($pdf->getPageWidth() - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT)) * .20;
$driver_w = ($pdf->getPageWidth() - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT)) * .25;
$truck_w = ($pdf->getPageWidth() - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT)) * .13;
$destination_w = ($pdf->getPageWidth() - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT)) * .3;
$yok_w = ($pdf->getPageWidth() - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT)) * .12;

// add a page
$pdf->AddPage();

//Report header..
$pdf->Cell($trailer_w, 20, 'TRAILER', 0, false, 'C', 0, '', 0, false, 'M', 'M');
$pdf->Cell($driver_w, 20, 'DRIVER', 0, false, 'C', 0, '', 0, false, 'M', 'M');
$pdf->Cell($truck_w, 20, 'TRUCK', 0, false, 'C', 0, '', 0, false, 'M', 'M');
$pdf->Cell($destination_w, 20, 'DESTINATION', 0, false, 'C', 0, '', 0, false, 'M', 'M');
$pdf->Cell($yok_w, 20, 'YARD OK', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
// $pdf->Cell(0, 5, '', 0, 1, 'C', 0, '', 0, false, 'M', 'M');

//Add lines to the report.

// set font
$pdf->SetFont('helvetica', '', 12);
$pdf->setTextColor(0);

$y = $pdf->GetY();


for ($j=0; $j < 7; $j++) {
  if (array_key_exists($j, $linehauls)) {
    $pdf->Cell($trailer_w, 10, $linehauls[$j]['trailer'], 0, false, 'C', 0, '', 0, false);
    $pdf->Cell($driver_w, 10, $linehauls[$j]['driver'], 0, false, 'C', 0, '', 0, false);
    $pdf->Cell($truck_w, 10, $linehauls[$j]['truck'], 0, false, 'C', 0, '', 0, false);
    $pdf->Cell($destination_w, 10, $linehauls[$j]['destination'], 0, false, 'C', 0, '', 0, false);
    $pdf->Cell($yok_w, 12, '', 'B', 1, 'C', 0, '', 0, false, 'C', 'B');
    $pdf->setXY(PDF_MARGIN_LEFT, $y += 10);
  } else {
    $pdf->Cell(0, 20, '', '', 1, 'C', 0, '', 0, false, 'C', 'B');
  }

}

$pdf->SetFont('helvetica', 'B', 12);
$pdf->setTextColor(102);

$pdf->Cell(0,20,"COMMENTS", 0, 1, 'L', 0, 0, 0, false, 'T', 'B');

for ($i=0; $i < 6; $i++) {
  $pdf->Cell(0,7,'', 1, 1, 'L', 0, 0, 0, false, 'T', 'B');
}

$pdf->Cell(0,20,"", 0, 1, 'L', 0, 0, 0, false, 'T', 'B');


$sign_w = ($pdf->getPageWidth() - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT)) / 3;


$pdf->setTextColor(10);
$pdf->Cell($sign_w, 0,"", 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
$pdf->Cell($sign_w, 0,"Yard Supervisor", 'T', 1, 'C', 0, 0, 0, false, 'T', 'B');
$pdf->Cell($sign_w, 0,"", 0, 0, 'L', 0, 0, 0, false, 'T', 'B');

$pdf->SetFont('helvetica', '', 9);
$pdf->setTextColor(102);
$pdf->MultiCell($sign_w, 0,"I, hereby certify that the information above is complete and acurrate. The trailers I dispatched match with Envelopes and this linehaul report", 0, 'C', 0, 1, $pdf->GetX(), $pdf->GetY(), true, 0, false, false);

$pdf->Cell(0, 15,"", 0, 1, 'L', 0, 0, 0, false, 'T', 'B');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->setTextColor(10);
$pdf->Cell($sign_w, 0,"Operations Admin", 'T', 0, 'C', 0, 0, 0, false, 'T', 'B');
$pdf->Cell($sign_w, 0,"", 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
$pdf->Cell($sign_w, 0,"Dispatcher", 'T', 1, 'C', 0, 0, 0, false, 'T', 'B');

$pdf->SetFont('helvetica', '', 9);
$pdf->setTextColor(102);

$y = $pdf->GetY();
$pdf->MultiCell($sign_w, 0,"I, hereby certify that the information above is complete and acurrate.", 0, 'C', 0, 1, $pdf->GetX(), $y, true, 0, false, false);
$pdf->MultiCell($sign_w, 0,"I, hereby certify that the information above is complete and acurrate. The trailers I dispatched match with Envelopes and this linehaul report", 0, 'C', 0, 1, ($sign_w * 2) + 15, $y, true, 0, false, false);

for ($i=1; $i < $pages; $i++) {
  $pdf->AddPage();

  //Report header..
  $pdf->Cell($trailer_w, 20, 'TRAILER', 0, false, 'C', 0, '', 0, false, 'M', 'M');
  $pdf->Cell($driver_w, 20, 'DRIVER', 0, false, 'C', 0, '', 0, false, 'M', 'M');
  $pdf->Cell($truck_w, 20, 'TRUCK', 0, false, 'C', 0, '', 0, false, 'M', 'M');
  $pdf->Cell($destination_w, 20, 'DESTINATION', 0, false, 'C', 0, '', 0, false, 'M', 'M');
  $pdf->Cell($yok_w, 20, 'YARD OK', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
  // $pdf->Cell(0, 5, '', 0, 1, 'C', 0, '', 0, false, 'M', 'M');

  //Add lines to the report.

  // set font
  $pdf->SetFont('helvetica', '', 12);
  $pdf->setTextColor(0);

  $y = $pdf->GetY();


  for ($j=0; $j < 7; $j++) {
    if (array_key_exists($j + ($i * 7), $linehauls)) {
      $pdf->Cell($trailer_w, 10, $linehauls[$j + ($i * 7)]['trailer'], 0, false, 'C', 0, '', 0, false);
      $pdf->Cell($driver_w, 10, $linehauls[$j + ($i * 7)]['driver'], 0, false, 'C', 0, '', 0, false);
      $pdf->Cell($truck_w, 10, $linehauls[$j + ($i * 7)]['truck'], 0, false, 'C', 0, '', 0, false);
      $pdf->Cell($destination_w, 10, $linehauls[$j + ($i * 7)]['destination'], 0, false, 'C', 0, '', 0, false);
      $pdf->Cell($yok_w, 12, '', 'B', 1, 'C', 0, '', 0, false, 'C', 'B');
      $pdf->setXY(PDF_MARGIN_LEFT, $y += 10);
    } else {
      $pdf->Cell(0, 20, '', '', 1, 'C', 0, '', 0, false, 'C', 'B');
    }

  }

  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->setTextColor(102);

  $pdf->Cell(0,20,"COMMENTS", 0, 1, 'L', 0, 0, 0, false, 'T', 'B');

  for ($i=0; $i < 6; $i++) {
    $pdf->Cell(0,7,'', 1, 1, 'L', 0, 0, 0, false, 'T', 'B');
  }

  $pdf->Cell(0,20,"", 0, 1, 'L', 0, 0, 0, false, 'T', 'B');


  $sign_w = ($pdf->getPageWidth() - (PDF_MARGIN_LEFT + PDF_MARGIN_RIGHT)) / 3;


  $pdf->setTextColor(10);
  $pdf->Cell($sign_w, 0,"", 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
  $pdf->Cell($sign_w, 0,"Yard Supervisor", 'T', 1, 'C', 0, 0, 0, false, 'T', 'B');
  $pdf->Cell($sign_w, 0,"", 0, 0, 'L', 0, 0, 0, false, 'T', 'B');

  $pdf->SetFont('helvetica', '', 9);
  $pdf->setTextColor(102);
  $pdf->MultiCell($sign_w, 0,"I, hereby certify that the information above is complete and acurrate. The trailers I dispatched match with Envelopes and this linehaul report", 0, 'J', 0, 1, $pdf->GetX(), $pdf->GetY(), true, 0);

  $pdf->Cell(0, 15,"", 0, 1, 'L', 0, 0, 0, false, 'T', 'B');
  $pdf->SetFont('helvetica', 'B', 12);
  $pdf->setTextColor(10);
  $pdf->Cell($sign_w, 0,"Operations Admin", 'T', 0, 'C', 0, 0, 0, false, 'T', 'B');
  $pdf->Cell($sign_w, 0,"", 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
  $pdf->Cell($sign_w, 0,"Dispatcher", 'T', 1, 'C', 0, 0, 0, false, 'T', 'B');

  $pdf->SetFont('helvetica', '', 9);
  $pdf->setTextColor(102);

  $y = $pdf->GetY();
  $pdf->MultiCell($sign_w, 0,"I, hereby certify that the information above is complete and acurrate.", 0, 'L', 0, 1, $pdf->GetX(), $y, true, 0, false, false);
  $pdf->MultiCell($sign_w, 0,"I, hereby certify that the information above is complete and acurrate. The trailers I dispatched match with Envelopes and this linehaul report", 0, 'J', 0, 1, ($sign_w * 2) + 15, $y, true, 0, false, false);

}


// $pdf->setXY($pdf->GetX(), $pdf->GetY());



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_003.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

 ?>
