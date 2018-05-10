$(document).ready(function(){
  $('#ciudades_origen li, #ciudades_destino li').click(function(){
    var city = $(this).find('.ciudad').html();

  });

  $('#inputCiudadOrigen, #inputCiudadDestino').keyup(function(){
    var city = $(this).val();
    var state = $("#" + $(this).attr('state')).val();
    var input = $(this).attr('id');

    target = $(this).attr('resultsto');
    if (city != "" && state != "") {
      $.ajax({
        method: 'POST',
        data: {estado_corto: state, ciudad: city},
        url: '/plsuite/Resources/PHP/Rutas/fetchCity.php',
        success: function(result){
          data = jQuery.parseJSON(result);
          if (data.code == 1) {
            $(target).html(data.data);
            $(target).parent().fadeIn();
            selectCity(input, target);
          }
        },
        error: function(exception){
          console.error("Something went terribly wrong! -> " + exception);
        }
      });
    } else {
      $(target).parent().fadeOut();
    }
  });

  $("#generarRuta").click(function(){
    origen = "";
    destino = "";

    if ($("#checkOrigenDetalle").prop('checked') || $('#checkDestinoDetalle').prop('checked')) {
      if (!$('#detalleOrigen').val() == "") {
        origen = $('#detalleOrigen').val() + ", ";
      }
      if (!$('#detalleDestino').val() == "") {
        destino = $('#detalleDestino').val() + ", ";
      }
    }
    origen += $('#inputCiudadOrigen').val() + ", " + $('#estado_origen').val();
    destino += $('#inputCiudadDestino').val() + ", " + $('#estado_destino').val();
    rOrigen = $('#inputCiudadOrigen').val() + ", " + $('#estado_origen').val();
    rDestino = $('#inputCiudadDestino').val() + ", " + $('#estado_destino').val();
    console.log(rOrigen);
    console.log(rDestino);
    var oEstado = $('#estado_origen').val();
    var dEstado = $('#estado_destino').val();

    if (oEstado == dEstado) {
      $.ajax({
        method: 'POST',
        data: {origin: origen, dest: destino, state: oEstado},
        url: '/plsuite/Resources/PHP/Rutas/calculateIntraStatedistance.php',
        success: function(result){
          console.log(result);
          fetchRoutes();
          $('#nuevaRutaModal').modal('hide');
        },
        error: function(exception){
          console.error(exception);
        }
      });
    } else {
      $.ajax({
        method: 'POST',
        data: {origen: rOrigen, destino: rDestino},
        url: '/plsuite/Resources/PHP/Rutas/addNewRoute.php',
        success: function(result){
          data = jQuery.parseJSON(result);
          if (data.code != 1) {
            alert(data.systemMessage);
            return;
          }

          window.origen = origen;
          window.destino = destino;
          window.rutaId = data.systemMessage;
          window.addRouteDetails = function(routeDetails, routeId){
            $.ajax({
              method: 'POST',
              data: {routeDetails, routeId: routeId},
              url: '/plsuite/Resources/PHP/Rutas/addRouteDetails.php',
              success: function(result){
                fetchRoutes();
                $('#nuevaRutaModal').modal('hide');
              },
              error: function(exception){
                console.error(exception);
              }
            })
          }
          window.open('calculateRoute.php', "Calculando Nueva Ruta");
        },
        error: function(exception){
          console.error("Something went terribly wrong!: " + exception);
        }
      })
    }


  });

});

function selectCity(input, target){
  $('.city-item').click(function(){
    var cityVal = $(this).find('.ciudad').html();
    $("#" + input).val(cityVal);
    console.log(target);
    $(target).parent().fadeOut();
  });
}
