<?php

function driver_list(){
  $operadores = [
    'list'=> array()
  ];
  global $db;
  $driver_list = "SELECT * FROM ct_drivers WHERE isDriver = 'Yes' AND status = 'Active' AND portal_assignment = '$_SESSION[current_portal]'";
  $driver_list = $db->prepare($driver_list);
  if (!($driver_list)) {
    $operadores['code'] = "500";
    $operadores['message'] = "Error during query prepare [$db->errno]: $db->error";
    return $operadores;
  }

  // $driver_list->bind_param('s',);
  // if (!($driver_list)) {
  //   $system_callback['code'] = "500";
  //   $system_callback['query'] = $query;
  //   $system_callback['message'] = "Error during variables binding [$driver_list->errno]: $driver_list->error";
  //   exit_script($system_callback);
  // }

  if (!($driver_list->execute())) {
    $operadores['code'] = "500";
    $operadores['message'] = "Error during query execution [$db->errno]: $db->error";
    return $operadores;
  }
  $rslt = $driver_list->get_result();

  if ($rslt->num_rows == 0) {
    $operadores['code'] = 2;
    $operadores['message'] = "No existen operadores registrados.";
    return $operadores;
  }

  while ($row = $rslt->fetch_assoc()) {
    if ($row['address_int_number'] == "") {
      $interior = "";
    } else {
      $interior = " , $row[address_int_number]";
    }

    array_push($operadores['list'], $row);
  }
  $operadores['code'] = 1;
  return $operadores;
}



 ?>
