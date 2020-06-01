<?php

function trailer_list(){
  $remolques = [
    'list'=> array()
  ];
  global $db;
  $trailer_list = "SELECT * FROM ct_trailer WHERE deletedTrailer IS NULL";
  $trailer_list = $db->prepare($trailer_list);
  if (!($trailer_list)) {
    $remolques['code'] = "500";
    $remolques['message'] = "Error during query prepare [$db->errno]: $db->error";
    return $remolques;
  }

  // $trailer_list->bind_param('s',);
  // if (!($trailer_list)) {
  //   $system_callback['code'] = "500";
  //   $system_callback['query'] = $query;
  //   $system_callback['message'] = "Error during variables binding [$trailer_list->errno]: $trailer_list->error";
  //   exit_script($system_callback);
  // }

  if (!($trailer_list->execute())) {
    $remolques['code'] = "500";
    $remolques['message'] = "Error during query execution [$db->errno]: $db->error";
    return $remolques;
  }
  $rslt = $trailer_list->get_result();

  if ($rslt->num_rows == 0) {
    $remolques['code'] = 2;
    $remolques['message'] = "No existen operadores registrados.";
    return $remolques;
  }

  while ($row = $rslt->fetch_assoc()) {
    if ($row['address_int_number'] == "") {
      $interior = "";
    } else {
      $interior = " , $row[address_int_number]";
    }

    array_push($remolques['list'], $row);
  }
  $remolques['code'] = 1;
  return $remolques;
}



 ?>
