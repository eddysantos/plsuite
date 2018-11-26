<?php

// require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';
require '/home/esantos/plt/plsuite/Resources/PHP/loginDatabase.php';

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

//Get Last transaction for elimination.

// $query = "SELECT tran_id FROM omni_pos_log ORDER BY tran_ts DESC LIMIT 1";
// $last_transaction_get = $db->query($query) or die("Error querying last tran: " . $db->error);
// $last_transaction_get = $last_transaction_get->fetch_assoc() or die("Error fetching results: " . $db->$last_transaction_get);

$last_transaction = $last_transaction_get['tran_id'];

$query = "INSERT INTO omni_pos_log(tran_id, tran_ts, driverid1, driverid2, tractor, lat, lon, speed) VALUES (?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE tran_id = ?, tran_ts = ?, driverid1 = ?, driverid2 = ?, tractor = ?, lat = ?, lon = ?, speed = ?";
$insert_pos_log = $db->prepare($query);

if (!$insert_pos_log) {
  die("Error preparing: " . $db->error);
}

$final_transaction = "";
// $final_transaction = $last_transaction;

do {
  $subscriber = array(
    'subscriberId'=>'2',
    'transactionIdIn'=>$final_transaction
  );

  try {
    $ping = $omni->dequeue2($subscriber);
  } catch (SoapFault $e) {
    var_dump($e);
    $lastRequest = $omni->__getLastRequest();
    var_dump($lastRequest);
    die();
  }

  $count = $ping->dequeue2Return->count;
  $final_transaction = $ping->dequeue2Return->transactionIdOut;
  $transactions = new SimpleXMLElement($ping->dequeue2Return->transactions);
  $branch = "";


  foreach ($transactions as $transaction) {
    $validate = false;
    echo "Count is: " . $count . "\n";
    echo "Final Transaction is: " . $final_transaction . "\n\n";
    // var_dump($transaction);
    $position_id =  $transaction->attributes()->ID;

    if (!$validate) {
      if ($transaction->{'T.2.12.0'}) {
        $validate = $transaction->{'T.2.12.0'};
        $branch = "T2120";
      }
    }

    if (!$validate) {
      if ($transaction->{'T.2.06.0'}) {
        $validate = $transaction->{'T.2.06.0'};
        $branch = "T2060";
      }
    }

    echo $branch . "\n";

    switch ($branch) {
      case 'T2120':
      try {
        $event_ts = $transaction->{'T.2.12.0'}->eventTS;
        $tractor = $transaction->{'T.2.12.0'}->equipment->attributes()->ID;
        $driver = $transaction->{'T.2.12.0'}->driverID;
        $driver2 = $transaction->{'T.2.12.0'}->driverID2;
        $lat = $transaction->{'T.2.12.0'}->position->attributes()->lat;
        $lon = $transaction->{'T.2.12.0'}->position->attributes()->lon;
        $posTS = $transaction->{'T.2.12.0'}->position->attributes()->posTS;
        $speed = $transaction->{'T.2.12.0'}->speed;
        $heading = $transaction->{'T.2.12.0'}->heading;
      } catch (\Exception $e) {
        var_dump($e);
        die();
      }

      break;

      case 'T2060':
      var_dump($transaction);
      try {
        $event_ts = $transaction->{'T.2.06.0'}->eventTS;
        $tractor = $transaction->{'T.2.06.0'}->equipment->attributes()->ID;
        $driver = $transaction->{'T.2.06.0'}->driverID;
        $driver2 = $transaction->{'T.2.06.0'}->driverID2;
        $lat = $transaction->{'T.2.06.0'}->position->attributes()->lat;
        $lon = $transaction->{'T.2.06.0'}->position->attributes()->lon;
        $posTS = $transaction->{'T.2.06.0'}->position->attributes()->posTS;
        $speed = $transaction->{'T.2.06.0'}->speed;
        $heading = $transaction->{'T.2.06.0'}->heading;
      } catch (\Exception $e) {
        var_dump($e);
        die();
      }

      break;

      default:
      echo "Skipping this loop!\n";
      continue 2;
      break;
    }

    $event_ts = date('Y-m-d H:i:s', strtotime($event_ts));
    // echo $event_ts;
    // die();

    $insert_pos_log->bind_param('ssssssssssssssss', $position_id, $event_ts, $driver, $driver2, $tractor, $lat, $lon, $speed, $position_id, $event_ts, $driver, $driver2, $tractor, $lat, $lon, $speed) or die('Error binding: ' . $insert_pos_log->error);
    $insert_pos_log->execute() or die('Error executing: ' . $insert_pos_log->error);

    if (!$insert_pos_log) {
      die("Error executing query: " . $insert_pos_log->error);
    }
  }

  die();

} while ($count > 0);

?>
