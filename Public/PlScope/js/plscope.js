function gmaps_select_icon(speed){
  if (speed == 0) {
    return google.maps.SymbolPath.CIRCLE;
  } else {
    return google.maps.SymbolPath.FORWARD_OPEN_ARROW;
  }
}

$(document).ready(function(){

  $(function(){
    $('#plscope_map').trigger('refresh_map');
  })

  $('#plscope_map').on('refresh_map', function(){

    //We need to populate the trip info on the site.
    var linehaul_reference = $('#linehaul_reference').val();
    var location = {};
    var geocoder = new google.maps.Geocoder;
    var distanceMatrix = new google.maps.DistanceMatrixService;
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;
    var eta = {
      route_time: 0,
      driver_remaining_driving: 0,
      driver_sleep_time: 0,
      total_eta: 0,
      driver_status: ""
    }

    var pull_location = $.ajax({
      method: 'POST',
      data: {linehaul: linehaul_reference},
      url: 'actions/fetch_naviscope.php',
      beforeSend: function(){
        $('#status-message-container').find('.status-message-body').html('Loading trip data. Please wait.');
        $('#status-message-container').fadeIn();
      }
    });

    pull_location.done(function(r){
      r = JSON.parse(r);
      console.log(r);

      //setup the driver clock times for furhter eta calculation.
      eta.driver_status = r.clock.v_status;
      eta.driver_remaining_driving = r.clock.DayDrive;
      if (eta.driver_status == 'Sleeper Berth' || eta.driver_status == 'Off Duty') {
        eta.driver_sleep_time = r.clock.ContiguousActivityDuration;
      }



      if (r.code == 1) {
        $('#status-message-container').fadeOut();
        for (var data in r.data) {
          if (r.data.hasOwnProperty(data)) {
            var tar = $('#' + data);
            tar.html(r.data[data]);
          }
        }
        $('#location_last_ping').html(r.location.tran_ts);


        latlng = {
          lat: parseFloat(r.location.lat),
          lng: parseFloat(r.location.lon)
        }

        var map_e = $('#plscope_map')[0];
        var fleetMap = new google.maps.Map(map_e,{
          zoom: 8,
          center: latlng
        });
        directionsDisplay.setMap(fleetMap);

        geocoder.geocode({'location': latlng}, function(r_geo, s){ //This is the origin
          var address = r_geo[1].formatted_address;
          $('#current_location').html(address);
        });

        if (!r.clock.faultstring) {
          distanceMatrix.getDistanceMatrix({
            origins: [latlng],
            destinations: ['zip' + r.data.dzip],
            travelMode: 'DRIVING',
            avoidHighways: false,
            avoidTolls: true,
          }, function(r_dm, s){
            eta.route_time = r_dm.rows[0].elements[0].duration.value / 60;
            if (eta.route_time > eta.driver_remaining_driving) {
              eta.cycles = Math.floor(eta.route_time / 660);
              if (eta.cycles == 0) {
                eta.cycles = 1;
              }
            } else {
              eta.cycles = 0;
            }

            if (r.location.NDrivers == 2) {
              eta.cycles = 0;
            }

            eta.total_eta = eta.route_time + ((eta.cycles * 720) - eta.driver_sleep_time);
            eta.eta_minutes = Math.floor(eta.total_eta % 60);
            eta.eta_hours = Math.floor(eta.total_eta / 60);
            var today = new Date();
            eta.date = new Date(today.getTime() + eta.total_eta * 60000);
            eta.date = eta.date.toLocaleString("en-US");

            $('#eta_time').html(eta.eta_hours + " Hours, " + eta.eta_minutes + " Minutes.");
            $('#eta_date').html(eta.date);
            console.log(eta);
          })

        } else {
          $('#eta_time').html("Clock not available.");
          $('#eta_date').html(r.clock.faultstring);
        }


        directionsService.route({
          origin: latlng,
          destination: 'zip' + r.data.dzip,
          travelMode: 'DRIVING'
        }, function(r_ds,s){
          // console.log(r);
          directionsDisplay.setDirections(r_ds);
        });

        // geocoder.geocode({'address': 'zip' + r.data.dzip}, function(r,s){
        //   console.log(r);
        //   console.log(r[0].geometry.location);
        // });

        // dm.getDistanceMatrix({
        //   origins: [latlng],
        //   destinations: ['zip ' + r.data.dzip],
        //   travelMode: 'DRIVING',
        //   avoidHighways: true,
        //   avoidTolls: true,
        // }, function(r, s){
        //   console.log(r);
        // });

        // var marker = new google.maps.Marker({
        //   map: fleetMap,
        //   draggable: false,
        //   animation: google.maps.Animation.DROP,
        //   position: latlng,
        //   title: r.data.truck_number,
        //   label: r.data.truck_number,
        //   icon: {
        //     path: gmaps_select_icon(r.location.speed),
        //     scale: 3,
        //     strokeColor: '#ff0000',
        //     fillColor: '#ff0000',
        //     fillOpacity: 1,
        //     labelOrigin: google.maps.Point(40, 33),
        //     rotation: r.location.rotation
        //   }
        // })
        setTimeout(function(){$('#plscope_map').trigger('refresh_map')}, 300000);
      } else if (r.code == 2) {
        $('#status-message-container').find('.status-message-body').html(r.message);
      }
    }).fail(function(x,y,z){
      console.error(z);
    });

  });
  $('#refresh_map').click(function(){
    $('#plscope_map').trigger('refresh_map');
  });

});
