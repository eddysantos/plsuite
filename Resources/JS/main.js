function loadingScreenRpmc(message){
  console.log("Loading screen active!");
  $('body').append("<div class='overlay d-flex align-items-center' style='z-index: 2000'><div class='overlay-loading justify-content-center d-flex align-items-center'><p><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i></p><p>" + message +"</p><div></div>")
}

function getCityStateListener(){


}


function first_and_last(pk_trip){
  var return_value;
  if (typeof pk_trip === 'undefined') {
    return false;
  }

  var get_info = $.ajax({
    method: 'POST',
    url: '/plsuite/Resources/PHP/Utilities/firstAndLastMovement.php',
    data: {pk_trip: pk_trip}
  });

  return get_info.done(function(r){
    r = JSON.parse(r);
    if (r.code == 1) {
      return_value = r.data
    } else {
      return_value = 'Error on the query:' + r.message;
    }
  }).fail(function(x){
    return_value = "Something went wrong. Please contact IT.";
    console.warn(x);
  });
}

function gmaps_select_icon(speed){
  if (speed == 0) {
    return google.maps.SymbolPath.CIRCLE;
  } else {
    return google.maps.SymbolPath.FORWARD_OPEN_ARROW;
  }
}

$(document).ready(function(){
  FontAwesomeConfig = { autoReplaceSvg: 'nest' }

  $('#rpmCalculator').on('hidden.bs.modal', function(e){
    $(this).find('input').val('');
    $('#sortableMovementsrpmc').html('');
      $('.ratepermilerpmc').removeClass('bg-dark').removeClass('text-white');
  })

  $('#toggleFleetMap').click(function(){

    var latlng = {
      lat: 0,
      lng: 0
    };
    var locations = [];
    var bounds = new google.maps.LatLngBounds(null);
    var map_e = $('#fleetView')[0];
    var tuck_selector_template = $('<div class="border mr-3 mb-2 fw-item rounded text-center" role="button"></div>');

    fleetMap = new google.maps.Map(map_e,{
        zoom: 4.75,
        center: {lat: 38.9949553, lng: -97.7107527}
      }
    );

    $('#fleetViewMap').modal('show');
    var pull_positions = $.ajax({
      method: 'POST',
      url: '/plsuite/Resources/PHP/Utilities/fleet_location/allAroundLocation.php'
    });

    pull_positions.done(function(r){
      r = JSON.parse(r);
      console.log(r);

      for (var truck in r.trucks) {
        if (r.trucks.hasOwnProperty(truck)) {
          latlng.lat = r.trucks[truck].lat;
          latlng.lng = r.trucks[truck].lng;
          tuck_selector_template.html(truck);
          tuck_selector_template.clone().data('lat', r.trucks[truck].lat).data('lng', r.trucks[truck].lng).appendTo('#fleetMapList');
          var position = new google.maps.Marker({
            map: fleetMap,
            draggable: false,
            animation: google.maps.Animation.DROP,
            position: latlng,
            title: truck,
            label: truck,
            icon: {
              path: gmaps_select_icon(r.trucks[truck].speed),
              scale: 3,
              strokeColor: '#ff0000',
              fillColor: '#ff0000',
              fillOpacity: 1,
              labelOrigin: google.maps.Point(40, 33),
              rotation: r.trucks[truck].rotation
            }
          })
          locations.push(position);
          // bounds.extend(latlng);
        }
      }
        // fleetMap.setCenter(latlng);
        // fleetMap.fitBounds(bounds);
      console.log(locations);
      var fleetCluster = new MarkerClusterer(fleetMap, locations, {
        imagePath: '/plsuite/Resources/gmapslibs/markerclusterer/images/m'
      })

    }).fail(function(a, b){
      console.log(a + ": " + b);
    })



  })

  $('#fleetMapList').on('click', 'div', function(){
    latlng = {
      lat: $(this).data('lat'),
      lng: $(this).data('lng')
    }
    fleetMap.setCenter(latlng);
    fleetMap.setZoom(19);
  });

  $('.zipInputrpmc').on('blur',function(){

    el = $(this)
    var txt = el.val();

    message = "Loading...";

    var getCityState = $.ajax({
      method: 'POST',
      beforeSend: function(){
        loadingScreenRpmc(message);
      },
      data: {txt: txt},
      url: '/plsuite/Ubicaciones/Viajes/actions/fetchCityState.php',
    });

    getCityState.done(function(result){
      var rsp = JSON.parse(result);
      //console.log(rsp);
      if (rsp.code == 1) {
        el.parent().siblings().find('.stateInput').val(rsp.data.state);
        el.parent().siblings().find('.cityInput').val(rsp.data.city);
      } else {
        console.log(rsp.message);
      }
      $('.overlay').remove();
    }).fail(function(jqXHR, textStatus, errorThrown){
      console.log(textStatus + ': ' + errorThrown);
      $('.overlay').remove();
    });

  });

  $('#sortableMovementsrpmc').on('blur', '.zipInputrpmc',function(){

    el = $(this)
    var txt = el.val();

    message = "Loading...";

    var getCityState = $.ajax({
      method: 'POST',
      beforeSend: function(){
        loadingScreenRpmc(message);
      },
      data: {txt: txt},
      url: '/plsuite/Ubicaciones/Viajes/actions/fetchCityState.php',
    });

    getCityState.done(function(result){
      var rsp = JSON.parse(result);
      //console.log(rsp);
      if (rsp.code == 1) {
        el.parent().siblings().find('.stateInput').val(rsp.data.state);
        el.parent().siblings().find('.cityInput').val(rsp.data.city);
      } else {
        console.log(rsp.message);
      }
      $('.overlay').remove();
    }).fail(function(jqXHR, textStatus, errorThrown){
      console.log(textStatus + ': ' + errorThrown);
      $('.overlay').remove();
    });

  });

  $('.addMovementrpmc').click(function(){
    amount = $('.extra.movementrpmc').length;

    if (amount == 0) {
      space = $(this).parent();
    } else {
      space = $('.extra.movementrpmc:last')
    }

    space = $('#sortableMovementsrpmc');


    toAdd = "<div class='form-group row extra movementrpmc'><label for='' class='col-form-label col-3 text-right'><i class='fa fa-ban mr-2 text-danger deleteExtraMovementrpmc' role='button'></i>Extra Movement</label><div class='form-group col-3'><input class='form-control zipInputrpmc' type='text' autocomplete='new-password'  value='' placeholder='Zip Code'></div><div class='form-group col-2'><input class='form-control stateInput disabled' disabled type='text' autocomplete='new-password' value='' placeholder='State'></div><div class='form-group col-3'><input class='form-control cityInput disabled' disabled type='text' autocomplete='new-password' value='' placeholder='City'></div><div class='form-group col-1'></div></div>";




    space.append(toAdd);
    $('.deleteExtraMovementrpmc').unbind().click(function(){
      $(this).parents('.extra.movementrpmc').remove();
    });

    getCityStateListener();
  });

  $('.calculateMilesrpmc').click(function(){
    var zipCodes = []
    $('.movementrpmc').find('.zipInputrpmc').each(function(){
      if ($(this).val() == 0 || $(this).val() == undefined) {
        cont = false;
        return false;
      }
      cont = true;
      zipCodes.push($(this).val());
    });

    if (!(cont)) {
      return false;
    }

    zipCodes = JSON.stringify(zipCodes);
    message = "Calculating total distance, please wait...";

    var calculateMiles = $.ajax({
      method: 'POST',
      data: {zips: zipCodes},
      beforeSend: function(){
        loadingScreenRpmc(message)
      },
      url: '/plsuite/Ubicaciones/Viajes/actions/findRoute.php'
    });

    calculateMiles.done(function(result){
      $('.overlay').remove();
      rsp = JSON.parse(result);
      $('.googleMilesrpmc').val(rsp.totalMiles);
      if ($('.linehaulRaterpmc').val() != "") {
        $('.ratepermilerpmc').val(Math.round(($('.linehaulRaterpmc').val() / rsp.totalMiles)*100)/100).addClass('bg-dark').addClass('text-white');
      }

      var allZipInputs = $('.movementrpmc').find('.zipInputrpmc');

      // for (var key in rsp.routes) {
      //   if (rsp.routes.hasOwnProperty(key)) {
      //     if (rsp['routes'][key]['route_code'] != 2) {
      //       break;
      //     }
      //     routeCode = rsp['routes'][key]['route_code'];
      //     routeDestination = rsp['routes'][key]['destination'];
      //     routeDistance = rsp['routes'][key]['distance'];
      //     allZipInputs.each(function(){
      //       if ($(this).val() == routeDestination) {
      //           var dads = $(this).parents('.movement.row');
      //           dads.find('.googleMiles').val(routeDistance);
      //           dads.find('.existsInDatabase').val('false');
      //           // dads.find('label, input').addClass('is-invalid').addClass('text-danger');
      //       }
      //     })
      //   }
      // }

    }).fail(function(jqXHR, textStatus, errorThrown){
      console.error(textStatus + ': ' + errorThrown);
    })

  })

  $('.linehaulRaterpmc').keyup(function(){
    lhrate = $(this).val() != "";
    gmrpc = $('.googleMilesrpmc').val() != "";

    if (lhrate && gmrpc) {
      var rpm = ($(this).val() / $('.googleMilesrpmc').val()).toFixed(2);
      $('.ratepermilerpmc').val(rpm).addClass('bg-dark').addClass('text-white');
    }
  })

  $('.modal').on('hidden.bs.modal', function(){
    $(this).find('input[type=text]').val('');
    $(this).find('.is-valid').removeClass('is-valid');
    $(this).find('.is-invalid').removeClass('is-invalid');
    $(this).find('.popup-list').fadeOut();
    $(this).find('textarea').val('');
    $(this).find('[db-id]').attr('db-id', '');
    $(this).find('select').prop('selectedIndex',0)
  });
});
