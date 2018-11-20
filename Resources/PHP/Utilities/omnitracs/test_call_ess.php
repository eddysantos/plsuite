<?php

// require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
require '/Applications/MAMP/htdocs/plsuite/Resources/PHP/loginDatabase.php';

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

$omni_wsdl = "https://services.omnitracs.com/otsWebWS/services/OTSWebSvcs/wsdl/OTSWebSvcs.wsdl";
$omni = new soapClient($omni_wsdl, ['trace'=>true]);
$functions = $omni->__getFunctions();

$omni->__setSoapHeaders(array($wsse_header));

// var_dump($functions);

// $vehicle = array('vehicle'=>array(
//   'id'=>'T049',
//   'scac'=>''
// ));

// $subscriber = array(
//   new SoapParam('2', 'subscriberId'),
//   new SoapParam(0, 'transactionIdIn')
// );
$subscriber = array(
  'subscriberId'=>'2',
  'transactionIdIn'=>170387
);

// $id = new SoapParam('T049', 'id');
// $scac = new SoapParam('', 'scac');

try {
  $ping = $omni->dequeue2($subscriber);
} catch (SoapFault $e) {
  var_dump($e);
  $lastRequest = $omni->__getLastRequest();
  var_dump($lastRequest);
  die();
}


// foreach ($transactions as $transaction) {
//   $tractor = $transaction->equipment;
//   var_dump($tractor);
// }

$query = "INSERT INTO omni_pos_log(tran_id, tran_ts, driverid1, driverid2, tractor, lat, lon, speed) VALUES (?,?,?,?,?,?,?,?)";
$insert_pos_log = $db->prepare($query);

if (!$insert_pos_log) {
  die("Error preparing: " . $db->error);
}

$count = $ping->dequeue2Return->count;
$final_transaction = $ping->dequeue2Return->transactionIdOut;
$transactions = new SimpleXMLElement($ping->dequeue2Return->transactions);

echo "Count is: " . $count . "\n";
echo "Final Transaction is: " . $final_transaction . "\n";

foreach ($transactions as $transaction) {
  // var_dump($transaction);
  $position_id =  $transaction->attributes()->ID;

  if ($transaction->{'T.2.12.0'}) {
    $event_ts = $transaction->{'T.2.12.0'}->eventTS;
    $tractor = $transaction->{'T.2.12.0'}->equipment->attributes()->ID;
    $driver = $transaction->{'T.2.12.0'}->driverID;
    $driver2 = $transaction->{'T.2.12.0'}->driverID2;
    $lat = $transaction->{'T.2.12.0'}->position->attributes()->lat;
    $lon = $transaction->{'T.2.12.0'}->position->attributes()->lon;
    $posTS = $transaction->{'T.2.12.0'}->position->attributes()->posTS;
    $speed = $transaction->{'T.2.12.0'}->speed;
  } else {
    continue;
  }

  $insert_pos_log->bind_param('ssssssss', $position_id, $event_ts, $driver, $driver2, $tractor, $lat, $lon, $speed);
  $insert_pos_log->execute();

  if (!$insert_pos_log) {
    die("Error executing query: " . $insert_pos_log->error);
  }
}

// $response = $ping->getVehicleInformationReturn;
// // var_dump($response);
// echo "Vehicle ID: " . $response->vehicle->id . "\n";
// echo "Latitude: " . $response->latitude . "\n";
// echo "Longitude: " . $response->longitude . "\n";

?>