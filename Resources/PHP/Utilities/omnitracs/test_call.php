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
$password = "apiSecure1";

$wsse_header = new WsseAuthHeader($username, $password);

$omni_wsdl = "https://services.omnitracs.com/qtracsWebWS/services/QTWebSvcs/wsdl/QTWebSvcs.wsdl";
$omni = new soapClient($omni_wsdl, ['trace'=>true]);
// $functions = $omni->__getFunctions();

$omni->__setSoapHeaders(array($wsse_header));

// var_dump($functions);

// $vehicle = array('vehicle'=>array(
//   'id'=>'T049',
//   'scac'=>''
// ));

$subscriber = array('vehicle'=>array(
  'id'=>'T021',
  'scac'=>''
));

// $id = new SoapParam('T049', 'id');
// $scac = new SoapParam('', 'scac');

try {
  $ping = $omni->getVehicleInformation($subscriber);
} catch (SoapFault $e) {
  var_dump($e);
  $lastRequest = $omni->__getLastRequest();
  var_dump($lastRequest);
  die();
}

var_dump($ping);

// $response = $ping->getVehicleInformationReturn;
// // var_dump($response);
// echo "Vehicle ID: " . $response->vehicle->id . "\n";
// echo "Latitude: " . $response->latitude . "\n";
// echo "Longitude: " . $response->longitude . "\n";

?>
