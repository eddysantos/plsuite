<?php
$query = "SELECT Nombre, Apellido, pkIdUsers, NombreUsuario, Status, email FROM Users";
$stmt = $db->query($query) or die($db->error);
$stmt->fetch_assoc();
$users_list = "";

while ($row = $stmt->fetch_assoc()) {
  $email = $row['email'] == "" ? 'No email on record' : $row['email'];
  $button_color = "";
  switch ($row['Status']) {
    case 'active':
      $button_color = "text-success";
      break;

    case 'inactive':
      $button_color = "text-danger";
      break;

    default:
      $button_color = "text-success";
      break;
  }
  $users_list .= "<tr role='button' db-id='$row[pkIdUsers]'>
    <td style='width: 20px; vertical-align: middle'>
        <div class='$button_color'><i class='fas fa-circle'></i></div>
    </td>
    <td>
      <div class='d-flex justify-content-between'>
        <div class='flex-column'>
          <div><b>$row[Nombre] $row[Apellido]</b> | $row[NombreUsuario]</div>
          <div><i>$email</i></div>
        </div>
        <div class='align-self-center'>
          <button type='button' name='button' class='btn btn-outline-dark reset-pwd' style='z-index: 9999'>Reset Password</button>
        </div>
      </div>
    </td>
  </tr>";
}



 ?>
