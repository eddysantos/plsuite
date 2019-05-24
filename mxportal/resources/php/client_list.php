<?php

function client_list(){
  $clientes = [
    'list'=> array()
  ];
  global $db;
  $client_list = "SELECT * FROM mx_clients";
  $client_list = $db->prepare($client_list);
  if (!($client_list)) {
    $clientes['code'] = "500";
    $clientes['message'] = "Error during query prepare [$db->errno]: $db->error";
    return $clientes;
  }

  // $client_list->bind_param('s',);
  // if (!($client_list)) {
  //   $system_callback['code'] = "500";
  //   $system_callback['query'] = $query;
  //   $system_callback['message'] = "Error during variables binding [$client_list->errno]: $client_list->error";
  //   exit_script($system_callback);
  // }

  if (!($client_list->execute())) {
    $clientes['code'] = "500";
    $clientes['message'] = "Error during query execution [$db->errno]: $db->error";
    return $clientes;
  }
  $rslt = $client_list->get_result();

  if ($rslt->num_rows == 0) {
    $clientes['code'] = 2;
    $clientes['message'] = "No existen clientes registrados.";
    return $clientes;
  }

  while ($row = $rslt->fetch_assoc()) {
    if ($row['address_int_number'] == "") {
      $interior = "";
    } else {
      $interior = " , $row[address_int_number]";
    }

    array_push($clientes['list'], $row);
  }
  $clientes['code'] = 1;
  return $clientes;
}



 ?>
