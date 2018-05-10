<?php
session_start();

if (!isset($_SESSION['user_info'])) {
  header('location:/problog/');
}

include('../Resources/PHP/Usuarios/fetchUsers.php');
 ?>

 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <link rel="stylesheet" href="../Resources/Bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet" href="../Resources/CSS/main.css">
     <link rel="stylesheet" href="../Resources/CSS/adminUsuarios.css">
     <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
     <link rel="stylesheet" href="../Resources/FontAwesome/css/font-awesome.min.css">
     <title></title>
   </head>
   <body>
      <div class="container-fluid" style="padding: 15px;;">
       <div class="brand align-items-center d-inline">
         <span class="badge badge-rojo badge-prolog">B</span>
            ProBlog!
       </div>
       <div class="d-inline-block text-center w-100 uHeader">
         <button class="btn btn-outline-primary float-right" type="button" name="button" id="returnButton" role="button">Regresar</button>
         Administración de Usuarios
       </div>
      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Nombre Usuario</th>
            <th>Tipo Usuario</th>
            <th>Privilegios</th>
            <th>Cliente</th>
            <th><button class="btn btn-outline-primary pull-right" type="button" name="button" data-toggle="modal" data-target="#add-user-modal" role="button"><i class="fa fa-user-plus"></i></button></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?php echo $user['Nombre'] . " " . $user['Apellido'] ?></td>
              <td><?php echo $user['NombreUsuario']?></td>
              <td><?php echo $user['TipoUsuario']?></td>
              <td><?php echo $user['Privilegios']?></td>
              <td><?php echo $user['RS_Cliente'];?><br><small><?php echo $user['RFC_Cliente'];?></small></td>
              <td class="">
                <div class="pull-right">
                  <button class="btn btn-outline-warning" type="button" name="editUser" data-toggle="modal" data-target="edit-user-modal" userid="<?php echo $user['UserId']?>" role="button"><i class="fa fa-pencil-square-o"></i></button>
                  <button class="btn btn-outline-success" type="button" name="button" role="button"><i class="fa fa-unlock-alt"></i></button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>


      <!-- START: ADD USER MODAL -->
      <div class="modal fade" tabindex="-1" id="add-user-modal" reason="addUser" role="dialog" aria-labelledby="addNewUser" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agregar Usuario Nuevo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-group" id="formNewUser" onsubmit="return false">
                <label for="primerNombre">Nombre</label>
                <input class="form-control" type="text" name="primerNombre" id="primerNombre" value="">
                <label for="primerApellido">Apellido</label>
                <input class="form-control" type="text" name="primerApellido" id="primerApellido" value="">
                <div class="form-group" id="form-group-email">
                  <label class="form-control-label" for="correoElectronico">Correo Electrónico</label><i class="fa fa-spin fa-circle-o-notch ml-2 invisible"></i>
                  <input class="form-control" type="email" name="correoElectronico" id="correoElectronico" value="">
                  <small class="form-control-feedback"></small>
                </div>
                <label for="defaultCliente">Cliente</label><i class="fa fa-spin fa-circle-o-notch ml-2 invisible" id="loadingClients"></i>
                <input class="form-control" type="text" name="clientDefault" id="clientDefault" value="" autocomplete="off">
                <input class="form-control form-control-sm" type="text" name="clientRFC" id="clientRFC" value="" readonly>
                <div class="clientList" id="clientListUsers" style="display: none"></div>
                <label for="tipoUsuario">Tipo de Usuario</label>
                <select class="form-control" name="tipoUsuario" id="tipoUsuario">
                  <option value="Cliente" selected>Cliente</option>
                  <option value="Interno">Interno</option>
                </select>
                <label for="tipoPrivilegios">Privilegios</label>
                <select class="form-control" name="tipoPrivilegios" id="tipoPrivilegios" disabled>
                  <option value="Básico" selected>Básico</option>
                  <option value="Administrador">Administrador</option>
                </select>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-danger" name="cancelar" id="cancelarUser" role="button">Cancelar</button>
              <button type="button" class="btn btn-outline-primary" name="submit" id="submitUser" role="button" disabled>Guardar</button>
            </div>
          </div>
        </div>
      </div>
      <!-- END: ADD USER MODAL -->

      <!-- START: EDIT USER MODAL -->
      <div class="modal fade" tabindex="-1" id="edit-user-modal" reason="addUser" role="dialog" aria-labelledby="addNewUser" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Datos Usuario</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="form-group" id="formEditUser" onsubmit="return false">
                <label for="primerNombre">Nombre</label>
                <input class="form-control" type="text" name="primerNombre" id="primerNombre" value="">
                <label for="primerApellido">Apellido</label>
                <input class="form-control" type="text" name="primerApellido" id="primerApellido" value="">
                <label class="form-control-label" for="correoElectronico">Correo Electrónico</label>
                <input class="form-control" type="email" name="correoElectronico" id="correoElectronico" value="" readonly>
                <input type="text" name="idUsuario" id="idUsuario" value="" hidden>
                <label for="defaultCliente">Cliente</label><i class="fa fa-spin fa-circle-o-notch ml-2 invisible" id="loadingClients"></i>
                <input class="form-control" type="text" name="clientDefault" id="clientDefault" value="" autocomplete="off">
                <input class="form-control form-control-sm" type="text" name="clientRFC" id="clientRFC" value="" readonly>
                <div class="clientList" id="clientListUsers" style="display: none"></div>
                <label for="tipoUsuario">Tipo de Usuario</label>
                <select class="form-control" name="tipoUsuario" id="tipoUsuario">
                  <option value="Cliente" selected>Cliente</option>
                  <option value="Interno">Interno</option>
                </select>
                <label for="tipoPrivilegios">Privilegios</label>
                <select class="form-control" name="tipoPrivilegios" id="tipoPrivilegios" disabled>
                  <option value="Básico" selected>Básico</option>
                  <option value="Administrador">Administrador</option>
                </select>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-danger" name="cancelar" id="cancelarUser" role="button">Cancelar</button>
              <button type="button" class="btn btn-outline-primary" name="editUser" id="editUser" role="button" disabled>Guardar Cambios</button>
            </div>
          </div>
        </div>
      </div>
      <!-- END: EDIT USER MODAL -->

      <script src="../Resources/JQuery/jquery-3.1.1.min.js" charset="utf-8"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
      <script src="../Resources/Bootstrap/js/bootstrap.min.js" charset="utf-8"></script>
      <script src="../Resources/JS/functions.js" charset="utf-8"></script>
      <script src="../Resources/JS/clientList.js" charset="utf-8"></script>
<script src="../Resources/JS/usuarios.js" charset="utf-8"></script>
   </body>
 </html>
