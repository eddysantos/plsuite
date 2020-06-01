<?php

function truck_list(){
  $tractores = [
    'list'=> array()
  ];
  global $db;
  $truck_list = "SELECT * FROM ct_truck WHERE truckStatus = 'Active' AND portal_assignment = '$_SESSION[current_portal]'";
  $truck_list = $db->prepare($truck_list);
  if (!($truck_list)) {
    $tractores['code'] = "500";
    $tractores['message'] = "Error during query prepare [$db->errno]: $db->error";
    return $tractores;
  }

  // $truck_list->bind_param('s',);
  // if (!($truck_list)) {
  //   $system_callback['code'] = "500";
  //   $system_callback['query'] = $query;
  //   $system_callback['message'] = "Error during variables binding [$truck_list->errno]: $truck_list->error";
  //   exit_script($system_callback);
  // }

  if (!($truck_list->execute())) {
    $tractores['code'] = "500";
    $tractores['message'] = "Error during query execution [$db->errno]: $db->error";
    return $tractores;
  }
  $rslt = $truck_list->get_result();

  if ($rslt->num_rows == 0) {
    $tractores['code'] = 2;
    $tractores['message'] = "No existen operadores registrados.";
    return $tractores;
  }

  while ($row = $rslt->fetch_assoc()) {
    if ($row['address_int_number'] == "") {
      $interior = "";
    } else {
      $interior = " , $row[address_int_number]";
    }

    array_push($tractores['list'], $row);
  }
  $tractores['code'] = 1;
  return $tractores;
}



 ?>
