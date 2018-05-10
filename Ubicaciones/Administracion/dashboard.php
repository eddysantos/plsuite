<?php
session_start();

$root = $_SERVER['document_root'];

if (
  !(ISSET($_COOKIE['Nombre']) AND
  ISSET($_COOKIE['Apellido']) AND
  ISSET($_COOKIE['Usuario']) AND
  ISSET($_COOKIE['idUsuario']))
) {
  header('location:../../index.php');
}

if (isset($_POST['addButton'])) {
  include('../../Resources/PHP/Usuarios/agregarUsuario.php');
}

include('../../Resources/PHP/loginDatabase.php');

$users = array();

$qry = "SELECT pkIdUsers, Nombre, Apellido, TipoUsuario, NombreUsuario, Status FROM Users";
$stmt = $login->query($qry);
while ($row = $stmt->fetch_assoc()) {
  $users[]=$row;
}

 ?>

 <!DOCTYPE html>
 <html lang="en">
   <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="../../Resources/Bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="../../Resources/Bootstrap/FontAwesome/css/font-awesome.min.css">
     <link rel="stylesheet" media="screen and (min-device-width: 701px)" href="../../Resources/CSS/main.css">
     <link rel="stylesheet" media="screen and (max-device-width: 700px)" href="../../Resources/CSS/mainMobile.css">
     <link href="https://fonts.googleapis.com/css?family=Sansita" rel="stylesheet">
   </head>
   <body>

      <nav class="navbar navbar-toggleable-md">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#menuNavBar" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
         <div class="navbar-brand text-primary">
           Control Usuarios
         </div>
         <div class="collapse navbar-collapse invisible" id="menuNavBar">
         <ul class="navbar-nav mr-auto">
           <li class="nav-item active"><a href="" class="nav-link">Agregar Usuario</a></li>
         </ul>
        </div>
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link btn btn-secondary" role="button" id="opcionesMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Menú</a>
            <div class="dropdown-menu float-left" style="left: -100px" aria-labelledby="opcionesMenu">
            <a href="" class="dropdown-item btn btn-secondary nav-link" role="button" data-toggle="modal" data-target="#newUserModal">+ Agregar Usuario</a>
            <a href="/timetracker/Ubicaciones/Rutas/DashRutas.php" class="dropdown-item btn btn-secondary nav-link">Rutas</a>
            <a class="dropdown-item nav-link" onclick="cerrar_sesion();" role="button">Cerrar Sesión</a>
          </div>
          </li>
        </ul>
      </nav>
      <div class="container-fluid">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Operador</th>
              <th>Nombre de Usuario</th>
              <th>Tipo Usuario</th>
              <th>Status</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?php echo $user['Nombre']. " " . $user['Apellido']; ?></td>
                <td><?php echo $user['NombreUsuario']; ?></td>
                <td><?php echo $user['TipoUsuario'] ?></td>
                <td><?php echo $user['Status']; ?></td>
                <td>
                  <button class="btn <?php if($user['Status'] == "Activo"){ echo "btn-outline-success";} else { echo "btn-outline-danger";} ?>" role="button" id="user_status_<?php echo $user['pkIdUsers']?>">
                    <i class="fa <?php if($user['Status'] == "Activo"){ echo "fa-unlock-alt";} else { echo "fa-lock";} ?>"></i>
                  </button>
                  <button class="btn btn-outline-warning" role="button" onclick="reset_pwd_modal(<?php echo $user['pkIdUsers']?>);">
                    <i class="fa fa-refresh"></i>
                  </button>
                  <a href="operatorTimeCard.php?nombreOperador=<?php echo $user['Nombre']. "+" . $user['Apellido']; ?>&id=<?php echo $user['NombreUsuario']?>"><button class="btn btn-outline-info" role="button" >
                    <i class="fa fa-table"></i>
                  </button></a>
                  <button class="btn btn-outline-secondary" role="button">
                    <i class="fa fa-pencil"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>

      <!-- Modal: New User -->
      <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="NuevoUsuario" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="newUserLabel">Agregar Nuevo Usuario</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-group" action="" method="post">
                <label for="nuNombre">Nombre</label>
                <input class="form-control" type="text" name="nuNombre" id="nuNombre" value="" required>
                <label for="nuApellido">Apellido</label>
                <input class="form-control" type="text" name="nuApellido" id="nuApellido" value="" required>
                <div class="form-group">
                <label class="label-control" for="nuTipoUsuario">Tipo Usuario</label>
                <select class="form-control" name="nuTipoUsuario" id="nuTipoUsuario">
                  <option value="Operador" selected>Operador</option>
                  <option value="Administrativo">Administrativo</option>
                </select>
                </div>
                <div class="form-group" id="groupNombreUsuario">
                <label class="form-control-label" for="nuNombreUsuario">Nombre Usuario</label>
                <input class="form-control" type="text" name="nuNombreUsuario" id="nuNombreUsuario" value="" onblur="validate_username()" required>
                <p><small class="form-control-feedback" id="helperNombreUsuario"></small></p>
                </div>
                <label for="nuContrasena">Contraseña</label>
                <input class="form-control" type="text" name="nuContrasena" id="nuContrasena" value="" required>
                <label for="nuStatus">Status</label>
                <input class="form-control" type="text" name="nuStatus" id="nuStatus" value="Activo" readonly>

            </div>
            <div class="modal-footer">
              <button type="reset" class="btn btn-secondary" data-dismiss="modal" role="button">Cancelar</button>
              <button type="submit" id="addButton" name="addButton" class="btn btn-primary" disabled="false" role="button">Agregar</button>
            </div>
          </div>
          </form>
          </div>
        </div>
      <!-- Modal: New User -->

      <!-- Modal: User Reset Password -->
      <div class="modal fade" id="resetPwdModal" tabindex="-1" role="dialog" aria-labelledby="ResetPassword" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="newUserLabel">Reestablecer Contraseña</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-group" onsubmit="new_password(); return false;" method="post">
                <label for="resetPwdName">Nombre</label>
                <input class="form-control" type="text" id="resetPwdName" name="resetPwdName" value="" readonly>
                <label for="">Usuario</label>
                <input class="form-control" type="text" id="resetPwdUser" name="resetPwdUser" value="" readonly>
                <label for="newPwd">Nueva Contraseña</label>
                <input class="form-control" type="text" name="newPwd" id="newPwd" value="" autocomplete="off" required>
                <input type="text" name="newPwdUserId" id="newPwdUserId" hidden>

            </div>
            <div class="modal-footer">
              <button type="reset" class="btn btn-secondary" data-dismiss="modal" role="button">Cancelar</button>
              <button type="submit" id="addButton" name="addButton" class="btn btn-primary" role="button">Cambiar</button>
            </div>
          </div>
          </form>
          </div>
        </div>
      <!-- Modal: User Reset Password -->

     <!-- jQuery first, then Tether, then Bootstrap JS. -->
     <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
     <script src="../../Resources/Bootstrap/js/bootstrap.min.js"></script>
     <script src="../../Resources/JS/functions.js" charset="utf-8"></script>
   </body>
 </html>
