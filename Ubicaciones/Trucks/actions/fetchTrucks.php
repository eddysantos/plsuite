<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root . '/plsuite/Resources/PHP/Utilities/initialScript.php';

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8_encode($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

$system_callback = [];

// for ($i=0; $i < 1000; $i++) {
//   echo "Chingas a $i";
// }

$query = "SELECT t.pkid_truck AS pkid_truck, t.truckNumber AS truckNumber, t.TruckVIN AS TruckVIN, t.truckStatus AS truckStatus, t.date_added AS date_added, t.truckPlates AS truckPlates, t.truckOwnedBy AS truckOwnedBy, d.nameFirst AS FirstName, d.nameLast AS LastName  FROM ct_truck t LEFT JOIN ct_drivers d ON t.truckOwnedBy = d.pkid_driver WHERE deletedTruck IS NULL";

$stmt = $db->prepare($query);
if (!($stmt)) {
  $system_callback['code'] = "500";
  $system_callback['query'] = $query;
  $system_callback['message'] = "Error during query prepare [$db->errno]: $db->error";
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
  //$system_callback['data'] .= $row;
  exit_script($system_callback);
}

while ($row = $rslt->fetch_assoc()) {
  if ($row['truckOwnedBy'] == "0") {
    $owner = "Prolog Transportation";
  } else {
    $owner = "$row[FirstName] $row[LastName]";
  }
  $system_callback['data'] .=
  "<tr role='button' truckid='$row[pkid_truck]'>
    <td>$row[truckNumber]</td>
    <td>$row[TruckVIN]</td>
    <td>$owner</td>
    <td>$row[truckStatus]</td>
    <td>" . date('Y-m-d', strtotime($row['date_added'])) . "</td>
    <td>$row[truckPlates]</td>
    <td class='text-right'>
      <button type='button' class='btn btn-outline-secondary' z-index=9999 name='button' driverid='$row[pkid_truck]'> <i class='fas fa-pencil-alt'></i> </button>
      <button type='button' class='btn btn-outline-danger deleteTruck' z-index=9999 name='button' truckid='$row[pkid_truck]'> <i class='far fa-trash-alt'></i> </button>
    </td>
  </tr>";
}



$system_callback['code'] = 1;
$system_callback['message'] = "Script called successfully!";
exit_script(utf8ize($system_callback));

 ?>
