<?php


function build_address($name, $street, $int, $ext, $locality, $city, $state, $zip, $country = "México"){
  $full_address = "";

  $street_address = "";
  if ($country == "USA") {
    $street_address .= $ext . " " . $street . " " . $int;
  } else {
    $int = $int == "" ? "" : " - $int";
    $street_address .= $street . " " . $ext . $int;
  }
  $street_address .= "\n";

  $locality = $locality == "" ? "" : $locality . "\n";

  $full_address = $name . "\n" . $street_address . $locality . $city . ", " . $state . ", " . $zip . "\n" . utf8_decode($country);

  return $full_address;
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION['user_info'])) {
  if (!$_SESSION['user_info']['cred_mexican_portal']) {
    header("location:/plsuite/access_denied.php");
  } else {
    $_SESSION['current_portal'] = "mx";
  }
} else {
  header("location:/plsuite/");
}

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/vendor/autoload.php';
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

$id = $_GET['pk_carta_porte'];

$info_cp = [];
$query = "SELECT cp.pk_carta_porte pkCartaPorte , cp.pk_carta_porte_number cpNumber , cp.fk_mx_trip fk_mx_trip , cp.trailer_number trailer_number , cp.trailer_plates trailer_plates , cp.movement_type movement_type , cp.date_start date_start, cp.movement_class movement_class , cp.fk_mx_place_origin fk_mx_place_origin , cp.fk_mx_place_destination fk_mx_place_destination , t.tractor_number tractor_number , t.tractor_plates tractor_plates , dr.nameFirst first_name , dr.nameLast last_name , c.client_name client_name , c.address_street client_street , c.address_ext_number client_ext_number , c.address_int_number client_int_number , c.address_locality client_locality , c.address_city client_city , c.address_state client_state , c.address_zip_code client_zip_code , c.address_country client_country , o.place_name origin_name , o.address_street origin_street , o.address_ext_number origin_ext_number , o.address_int_number origin_int_number , o.address_locality origin_locality , o.address_city origin_city , o.address_state origin_state , o.address_zip_code origin_zip , d.place_name destination_name , d.address_street destination_street , d.address_ext_number destination_ext_number , d.address_int_number destination_int_number , d.address_locality destination_locality , d.address_city destination_city , d.address_state destination_state , d.address_zip_code destination_zip FROM mx_carta_porte cp LEFT JOIN mx_trips t ON t.pk_mx_trip = cp.fk_mx_trip LEFT JOIN mx_clients c ON c.pk_mx_client = t.fk_mx_client LEFT JOIN mx_places d ON d.pk_mx_place = cp.fk_mx_place_destination LEFT JOIN mx_places o ON o.pk_mx_place = cp.fk_mx_place_origin LEFT JOIN ct_drivers dr ON dr.pkid_driver = t.fk_driver WHERE pk_carta_porte = ?";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $info_cp['code'] = "500";
  $info_cp['query'] = $query;
  $info_cp['message'] = "Error during query prepare [$db->errno]: $db->error";
  exit_script($info_cp);
}

$stmt->bind_param('s', $id);
if (!($stmt)) {
  $info_cp['code'] = "500";
  $info_cp['query'] = $query;
  $info_cp['message'] = "Error during variables binding [$stmt->errno]: $stmt->error";
  exit_script($info_cp);
}

if (!($stmt->execute())) {
  $info_cp['code'] = "500";
  $info_cp['query'] = $query;
  $info_cp['message'] = "Error during query execution [$db->errno]: $db->error";
  exit_script($info_cp);
}

$rslt = $stmt->get_result();

if ($rslt->num_rows == 0) {
  $info_cp['code'] = 2;
  $info_cp['message'] = "No se encontraron movimientos para esta operación.";
  exit_script($info_cp);
}

while ($row = $rslt->fetch_assoc()) {
  $info_cp  = $row;
}

$client = "";
$remitente = "";
$destinatario = "";

//Build client addresss variable
$cliente = build_address(
  $info_cp['client_name'],
  $info_cp['client_street'],
  $info_cp['client_int_number'],
  $info_cp['client_ext_number'],
  $info_cp['client_locality'],
  $info_cp['client_city'],
  $info_cp['client_state'],
  $info_cp['client_zip_code'],
  $info_cp['client_country']
);
$remitente = build_address(
  $info_cp['origin_name'],
  $info_cp['origin_street'],
  $info_cp['origin_int_number'],
  $info_cp['origin_ext_number'],
  $info_cp['origin_locality'],
  $info_cp['origin_city'],
  $info_cp['origin_state'],
  $info_cp['origin_zip']
);
$destinatario = build_address(
  $info_cp['destination_name'],
  $info_cp['destination_street'],
  $info_cp['destination_int_number'],
  $info_cp['destination_ext_number'],
  $info_cp['destination_locality'],
  $info_cp['destination_city'],
  $info_cp['destination_state'],
  $info_cp['destination_zip']
);

$carta_porte = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$carta_porte->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$carta_porte->setImageScale(PDF_IMAGE_SCALE_RATIO);
$carta_porte->setPrintHeader(false);
$carta_porte->setPrintFooter(false);

$carta_porte->addPage();

$carta_porte->setLeftMargin(10);
$carta_porte->setRightMargin(10);
$carta_porte->setFontSize(10);
$margins = $carta_porte->GetMargins();
$x_margins = $margins['left'] + $margins['right'];
$width = $carta_porte->getPageWidth();

//Encabezado de la carta porte
$carta_porte->Image( "$root/plsuite/Resources/images/logo_plaa.jpg", $margins['top'], $margins['left'], 35, 35, "", "", "T", true, 300, "L", false, false, 0, 0, false, false);
$carta_porte->Cell( ($width - 65) * .8, 0, "ID Carta Porte:", 0, 0, "R", 0, 0, 0, false, "T", "C");
$carta_porte->Cell( ($width - 65) * .2, 0, $info_cp['cpNumber'], 0, 1, "C", 0, 0, 0, false, "T", "C");
$carta_porte->Cell( 35 + ($width - 65) * .8, 25, "Fecha Viaje:", 0, 0, "R", 0, 0, 0, false, "T", "T");
$carta_porte->Cell( ($width - 65) * .2, 35, date('d/m/Y', strtotime($info_cp['date_start'])), 0, 1, "C", 0, 0, 0, false, "T", "T");

//Información de cliente (a quien se le factura)
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell( ($width - $x_margins) * .5, 0, "Facturado A:", 0, 0, "L", 0, 0, 0, false, "T", "T");
$carta_porte->Cell( ($width - $x_margins) * .3, 0, "Tipo de Movimiento:", 0, 0, "L", 0, 0, 0, false, "T", "T");
$carta_porte->setFont('helvetica');
$carta_porte->Cell( ($width - $x_margins) * .2, 0, $info_cp['movement_type'], 0, 1, "L", 0, 0, 0, false, "T", "T");
$carta_porte->MultiCell( $width * .5, 0, $cliente, 0, "L", 0, 1, "", "", true, 0, false, false, "");

//Información de Remitente y Destinatario
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell(($width - $x_margins) * .5, 15, "Remitente:", 0, 0, "L", 0, 0, 0, false, "T", "B");
$carta_porte->Cell(($width - $x_margins) * .5, 15, "Destinatario:", 0, 1, "L", 0, 0, 0, false, "T", "B");
$carta_porte->setFont('helvetica');
$carta_porte->MultiCell(($width - $x_margins) * .5, 0, utf8_encode($remitente), 0, "L", 0, 0, "", "", true, 0, false, false, "");
$carta_porte->MultiCell(($width - $x_margins) * .5, 0, utf8_encode($destinatario), 0, "L", 0, 1, "", "", true, 0, false, false, "");

//Información de Operador, Tractor y Caja

//OPERADOR
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell((($width - $x_margins) * .5) * .3, 15, "Operador:", 0, 0, "L", 0, 0, 0, false, "T", "B");
$carta_porte->setFont('helvetica');
$carta_porte->Cell((($width - $x_margins) * .5) * .7, 15, "$info_cp[first_name] $info_cp[last_name]" , 0, 1, "L", 0, 0, 0, false, "T", "B");

//NUMERO TRACTOR
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell((($width - $x_margins) * .5) * .3, 0, "Tractor:", 0, 0, "L", 0, 0, 0, false, "T", "B");
$carta_porte->setFont('helvetica');
$carta_porte->Cell((($width - $x_margins) * .5) * .7, 0, $info_cp['tractor_number'], 0, 0, "L", 0, 0, 0, false, "T", "B");
//PLACAS TRACTOR
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell((($width - $x_margins) * .5) * .3, 0, "Placas:", 0, 0, "L", 0, 0, 0, false, "T", "B");
$carta_porte->setFont('helvetica');
$carta_porte->Cell((($width - $x_margins) * .5) * .7, 0, $info_cp['tractor_plates'], 0, 1, "L", 0, 0, 0, false, "T", "B");

//NUMERO CAJA
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell((($width - $x_margins) * .5) * .3, 0, "Remolque:", 0, 0, "L", 0, 0, 0, false, "T", "B");
$carta_porte->setFont('helvetica');
$carta_porte->Cell((($width - $x_margins) * .5) * .7, 0, $info_cp['trailer_number'], 0, 0, "L", 0, 0, 0, false, "T", "B");
//PLACAS CAJA
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell((($width - $x_margins) * .5) * .3, 0, "Placas:", 0, 0, "L", 0, 0, 0, false, "T", "B");
$carta_porte->setFont('helvetica');
$carta_porte->Cell((($width - $x_margins) * .5) * .7, 15, $info_cp['trailer_plates'], 0, 1, "L", 0, 0, 0, false, "T", "T");

//TABLA DE CONTENIDO DE LA CARGA
  //ENCABEZADO DE TABLA
$carta_porte->setFont('helvetica', "B");
$carta_porte->setFillColor(240,240,240);
$carta_porte->Cell((($width - $x_margins) * .20), 0, "Bultos", 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "Contenido", 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "Peso", 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "Concepto", 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "Importe", 1, 1, "C", 1, 0, 0, false, "T", "C");
  //CONTENIDO DE TABLA
$carta_porte->setFont('helvetica');
$carta_porte->setFillColor(255,255,255);
$carta_porte->Cell((($width - $x_margins) * .20), 10, $bultos, 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 10, "Carga General", 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 10, $peso, 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 10, "Flete", 1, 0, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 10, "$1.00", 1, 1, "C", 1, 0, 0, false, "T", "C");

//IMPORTES DE CARTA PORTE
$carta_porte->setFont('helvetica', "B");
$carta_porte->Cell((($width - $x_margins) * .80), 13, "Subtotal:", "T", 0, "R", 1, 0, 0, false, "T", "B");
$carta_porte->Cell((($width - $x_margins) * .20), 13, "$1.00", "T", 1, "C", 1, 0, 0, false, "T", "B");
$carta_porte->Cell((($width - $x_margins) * .80), 0, "IVA 16%:", 0, 0, "R", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "$0.16", 0, 1, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .80), 0, "Ret 4%:", 0, 0, "R", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "$0.04", 0, 1, "C", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .80), 0, "Total:", 0, 0, "R", 1, 0, 0, false, "T", "C");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "$1.12", 0, 1, "C", 1, 0, 0, false, "T", "C");


//SELLO CP
$carta_porte->Cell((($width - $x_margins) * .30), 30, "", 0, 1, "R", 1, 0, 0, false, "T", "B");
$carta_porte->Cell((($width - $x_margins) * .30), 0, "", 0, 0, "R", 1, 0, 0, false, "T", "B");
$carta_porte->Cell((($width - $x_margins) * .40), 0, "Sello", "T", 1, "C", 1, 0, 0, false, "T", "B");

//FECHA y FIRMA
$carta_porte->Cell(0, 30, "", 0, 1, "R", 1, 0, 0, false, "T", "B");
$carta_porte->Cell((($width - $x_margins) * .40), 0, "Fecha y Hora de Recibido", "T", 0, "C", 1, 0, 0, false, "T", "B");
$carta_porte->Cell((($width - $x_margins) * .20), 0, "", 0, 0, "R", 1, 0, 0, false, "T", "B");
$carta_porte->Cell((($width - $x_margins) * .40), 0, "Recibió: Nombre y Firma", "T", 1, "C", 1, 0, 0, false, "T", "B");


// header('Content-type: application/pdf');
// header('Content-Disposition: inline; filename="' . $filename . '"');
// header('Content-Transfer-Encoding: binary');
// header('Content-Length: ' . filesize($file));
// header('Accept-Ranges: bytes');

$carta_porte->Output("CP_$info_cp[cpNumber].pdf");




 ?>
