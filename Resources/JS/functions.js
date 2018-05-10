function validate_username(){
  var username = $('#nuNombreUsuario').val();
  if (username == "") {
    $('#groupNombreUsuario').removeClass('has-success').removeClass('has-danger');
    $('#helperNombreUsuario').html("");
    return;
  }
  $.ajax({
    method:'POST',
    data:{NombreUsuario: username},
    url:'/plsuite/Resources/PHP/Usuarios/validateUser.php',
    success: function(result){
      console.log(result);
      if (result == "Success") {
        $('#groupNombreUsuario').addClass('has-success').removeClass('has-danger');
        $('#helperNombreUsuario').html("Nombre de Usuario Disponible");
        $('#addButton').prop('disabled',false);
      } else {
        $('#groupNombreUsuario').addClass('has-danger').removeClass('has-success');
        $('#helperNombreUsuario').html("Nombre de Usuario No Disponible");
        $('#addButton').prop('disabled',true);
      }
    },
    error: function(exception){
      console.log(exception);
    },
  });
}

function cerrar_sesion(){
  var salir = confirm("¿Desea cerrar sesión?");
  if (salir == true) {
    console.log('Cerrando sesión..');
    location.replace('/plsuite/cerrarSesion.php');
    console.log('Cerrar sesion!');
    return false;
  }
  console.log('A ver ahora...');
}

function punch_card(id, tipo){
  var time = $('#clock').html();
  confirmacion = confirm("¿Deseas ponchar con la hora " + time + "?");
  if (confirmacion == true) {
    $.ajax({
      method: 'POST',
      data:{fecha: time, idOperador: id},
      url: '/plsuite/Resources/PHP/Utilities/poncharReloj.php',
      //dataType: "json",
      success: function(result){
        if (result != 'Failed' && !result.includes('Error')) {
          var horaFecha = JSON.parse(result);
          console.log(result);
          if (tipo == 'Salida') {
            $('#btnMarcarEntrada').toggle();
            $('#btnMarcarSalida').toggle();
            $('#spanFechaSalida').html(horaFecha.fecha);
            $('#spanHoraSalida').html(horaFecha.hora);
          } else {
            $('#btnMarcarEntrada').toggle();
            $('#btnMarcarSalida').toggle();
            $('#spanFechaEntrada').html(horaFecha.fecha);
            $('#spanHoraEntrada').html(horaFecha.hora);
            $('#spanFechaSalida').html("");
            $('#spanHoraSalida').html("");
          }
        } else {
          console.log(result);
        }
      },
      error: function(exception){
        console.log(exception);
      },
    });
  }
}

/*function user_status(id){
  var $btni = $('#user_status_' + id).find('i');

  if ($btni.hasClasS('fa-unlock-alt')) {
    var status_actual = "Activo";
    var accion = "Bloquear"
  } else {
    var status_actual = "Inactivo";
    var accion = "Desbloquear";
  }
  $.ajax({
    method: 'POST',
    url: '/Websites/TimeTracking/plsuite/Resources/PHP/Usuarios/editUser.php'
    data: {idUsuario: id, accion:accion}
  })
}*/

function reset_pwd_modal(id){
  $.ajax({
    method: 'POST',
    url: '/plsuite/Resources/PHP/Usuarios/consultUser.php',
    data:{idUsuario: id},
    success: function(result){
      if (result != 'Error') {
        var datosUsuario = JSON.parse(result);
        $('#resetPwdName').val(datosUsuario.Nombre + " " + datosUsuario.Apellido);
        $('#resetPwdUser').val(datosUsuario.NombreUsuario);
        $('#newPwdUserId').val(datosUsuario.pkIdUsers);



      } else {
        console.log('Error');
      }
    },
    error: function(exception){
      console.log('Exception');
    }
  })


  $('#resetPwdModal').modal('show');
}

function new_password(){
  var idUsuario = $('#newPwdUserId').val();
  var newPwd = $('#newPwd').val();

  $.ajax({
    method: 'POST',
    url: '/plsuite/Resources/PHP/Usuarios/changePassword.php',
    data: {idUsuario: idUsuario, newPwd: newPwd},
    success: function(result){
      if (result == "Exito") {
        alert("La contraseña ha sido cambiada con exito.");
        $('#resetPwdModal').modal('hide');
      } else {
        alert("Hubo un error al actualizar la contraseña.");
        console.log(result);
      }
    },
    error: function(exception){
      console.log(exception);
    }
  })
}

$('button').on('click', '.fa-lock, .fa-unlock-alt', function(){
  var $icon = $(this);
  var $button = $(this).closest('button');
  var id = $button.attr('id').slice(12);

  if ($icon.hasClass('fa-unlock-alt')) {

    $.ajax({
      method: 'POST',
      url: '/plsuite/Resources/PHP/Usuarios/blockUser.php',
      data: {idUsuario: id, status: 'Inactivo'},
      success: function(result){
        if (result == "Exito") {
          alert("El acceso del usuario ha sido bloqueado!");
          $button.find('i').toggleClass('fa-unlock-alt fa-lock');
          $button.toggleClass('btn-outline-success btn-outline-danger');
        }
      },
      error: function(exception){
        alert("No se pudo bloquear el acceso!");
        console.log(exception);
      }
    })

  } else {

    $.ajax({
      method: 'POST',
      url: '/plsuite/Resources/PHP/Usuarios/blockUser.php',
      data: {idUsuario: id, status: 'Activo'},
      success: function(result){
        if (result == "Exito") {
          alert("El acceso del usuario ha sido desbloqueado!");
          $button.find('i').toggleClass('fa-unlock-alt fa-lock');
          $button.toggleClass('btn-outline-success btn-outline-danger');
        }
      },
      error: function(exception){
        alert("No se pudo desbloquear el acceso!");
        console.log(exception);
      }
    })

  }
});

$('#exportarDetalles').on('click', function(){

  var fechaDesde = $('#fechaDesde').val();
  var fechaHasta = $('#fechaHasta').val();
  var id = $('#id').val();

  $.ajax({
    method: 'POST',
    url: '/plsuite/Resources/PHP/Utilities/createXLS.php',
    data: {fechaDesde: fechaDesde, fechaHasta: fechaHasta, id: id},
    success: function(result){
      console.log(result);
    },
    error: function(exception){
      console.log(exception);
    },
  });
});
