$(document).ready(function(){
  fetchRoutes();

  $('.deleteRoute').click(function(){
    rutaid = $('#rutaAEliminar').html();
    if (rutaid == "") {
      $('#deleteRouteModal').modal('hide');
    }
    selection = $(this).html();
    if (selection == "Si") {
      $.ajax({
        method: 'POST',
        data: {idRuta: rutaid},
        url: '/plsuite/Resources/PHP/Rutas/deleteRoute.php',
        success: function(result){
          $('#deleteRouteModal').modal('hide');
          fetchRoutes();
        },
        error: function(exception){
          alert('No se pudo eliminar la ruta!');
          $('#deleteRouteModal').modal('hide');
          console.error(exception);
        }
      });
    } else {
      $('#deleteRouteModal').modal('hide');
    }
  });

  $('.toggleDirDetalle').change(function(){
    if ($(this).prop('checked')) {
      $($(this).attr('target')).fadeIn();
    } else {
      $('#checkOrigenDetalle').fadeOut();
      $('#checkDestinoDetalle').fadeOut();
    }
  });

  $('#nuevaRutaModal').on('hidden.bs.modal', function(){
    $('#newRouteForm')[0].reset();

  });
});

$.fn.exists = function () {
    return this.length !== 0;
}

function buildReport(){
  $('#buildReport').click(function(){

    if ($('#reportElements').children().length == 0) {
      alert('No ha seleccionado las rutas para el reporte ..');
    } else {
      rutas = {}
      var repElem = $('.reportElement').each(function(){
        var a = $(this).attr('idRuta');
        var b = $(this).find('.badge').attr('value');
        rutas[a] = b;
      });

      $.post('/plsuite/Resources/PHP/Rutas/buildReport.php', rutas, function(data){
        info = jQuery.parseJSON(data);
        $('#resultRoutes').html("");
        $('.reportElement').clone().appendTo('#resultRoutes');
        $('#resultRoutes').children().attr('id', "");
        $('#resultRoutes').find('button').remove();
        $('#insertReportResults').html(info.data);
        $('#resultadosReporteModal').modal('show');
        $('#lastResults').fadeIn();
      });
    }



  });
}

function fetchRoutes(srchRoute){
  $.ajax({
    method: 'POST',
    data: {routeSearch: srchRoute},
    url: '/plsuite/Resources/PHP/Rutas/fetchRoutes.php',
    success: function(result){
      data = jQuery.parseJSON(result);

      if (data.code != 1) {
        alert(data.systemMessage);
        return;
      } else {
        $('#dumpRoutes').html(data.data);
      }
    },
    error: function(exception){
      console.error(exception);
    },
    complete: function(){
      routesListener();
    }
  });
}

function reportElementListener(){
  $('.decMult').click(function(){
    var curMul = $(this).siblings('.badge').html();
    var newMul = parseFloat($(this).siblings('.badge').html()) - 1;
    if (newMul == 0) {
      $(this).closest('.reportElement').remove();
    } else {
      $(this).siblings('.badge').attr('value', newMul).html(newMul);
    }
  })
}

function routesListener(){
  $('[data-toggle="manual-modal"]').click(function(event){
    event.stopPropagation();
    var targ = $(this).attr('data-target');
    var routeId = $(this).attr('rutaId');
    console.log(targ);
    //return;

    if (targ == "#deleteRouteModal") {
      $('#rutaAEliminar').html(routeId);
      $('#deleteRouteModal').modal('show');
    } else {
      $.ajax({
        method: 'POST',
        data: {fkIdRuta: routeId},
        url: '/plsuite/Resources/PHP/Rutas/fetchRouteDetails.php',
        success: function(result){
          data = jQuery.parseJSON(result);
          if (data.code != 1) {
            alert(data.systemMessage);
            return;
          } else {
            $('#dumpRouteDetails').html(data.data);
          }
        },
        error: function(exception){
          console.error(exception);
        },
        complete: function(){
          $('#detallesRutaModal').modal('show');
        }
      });
    }
  });

  $('.selectRoute').click(function(){
    var idRuta = $(this).attr('routeId');
    var origen = $(this).attr('origen');
    var destino = $(this).attr('destino');
    var rElem = "<div class='reportElement' id='build" + idRuta + "' idRuta='" + idRuta + "'><span>" + origen + " - " + destino +
      "</span><span id='multiplier' value='1' class='float-right badge badge-default'>1</span><button class='btn btn-sm btn-outline-danger float-right mr-2 decMult' role='button'><i class='fa fa-minus'></i></button></div>";

    if ($('#build' + idRuta).exists()) {
      var curMul = $('#build' + idRuta).find('.badge').attr('value');
      var newMul = parseFloat(curMul) + 1;
      $('#build' + idRuta).find('.badge').attr('value', newMul);
      $('#build' + idRuta).find('.badge').html(newMul);
    } else {
      $('#reportElements').append(rElem);
      $('.decMult').unbind();
      $('#buildReport').unbind();
      reportElementListener();
      buildReport();
    }
  });
}
