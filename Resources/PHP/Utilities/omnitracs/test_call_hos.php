<?php


class WsseAuthHeader extends SoapHeader {

private $wss_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';

function __construct($user, $pass, $ns = null) {
    if ($ns) {
        $this->wss_ns = $ns;
    }

    $auth = new stdClass();
    $auth->Username = new SoapVar($user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
    $auth->Password = new SoapVar($pass, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);

    $username_token = new stdClass();
    $username_token->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns);

    $security_sv = new SoapVar(
        new SoapVar($username_token, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns),
        SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'Security', $this->wss_ns);
    parent::__construct($this->wss_ns, 'Security', $security_sv, true);
}
}

$username = "PROLOGAPI@PROLOGTRAN";
$password = "api5ecurE1";

$wsse_header = new WsseAuthHeader($username, $password);

$omni_wsdl = "https://hos.omnitracs.com/QHOSWSNA/driver.asmx?WSDL";
// $omni_wsdl = "https://intinfo.omnitracs.com/download/attachments/26610480/driver.asmx?version=1";
$omni = new soapClient($omni_wsdl, ['trace'=>true]);
// $functions = $omni->__getFunctions();
// var_dump($functions);
// $functions = $omni->__getTypes();
// var_dump($functions);
// die();
$omni->__setSoapHeaders(array($wsse_header));

// var_dump($functions);

// $vehicle = array('vehicle'=>array(
//   'id'=>'T049',
//   'scac'=>''
// ));

// $params = array('ExportDriverClock'=>array(
//   'request'=>array(
//     'drivers'=>array(
//       'GLARRANAGA'
//     ),
//     'timeResolutionInSeconds'=>0
//   )
// ));
$params = array('request'=>array(
  'Drivers'=>array(
    'HERIBERTOG'
  ),
  'RuleSet'=>'USA',
  'TimeResolutionInSeconds'=>false
));

// $id = new SoapParam('T049', 'id');
// $scac = new SoapParam('', 'scac');

try {
  $ping = $omni->ExportDriver($params);
} catch (SoapFault $e) {
  var_dump($e);
  $lastRequest = $omni->__getLastRequest();
  var_dump($lastRequest);
  die();
}

var_dump($ping->ExportDriverResult->DriverExport->DriverExportData);

// $response = $ping->getVehicleInformationReturn;
// // var_dump($response);
// echo "Vehicle ID: " . $response->vehicle->id . "\n";
// echo "Latitude: " . $response->latitude . "\n";
// echo "Longitude: " . $response->longitude . "\n";

?>
