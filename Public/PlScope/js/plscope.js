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
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer;

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
        directionsDisplay.setMap(map_e);
        var fleetMap = new google.maps.Map(map_e,{
          zoom: 8,
          center: latlng
        });

        geocoder.geocode({'location': latlng}, function(r, s){ //This is the origin
          var address = r[1].formatted_address;
          $('#current_location').html(address);
        })

        geocoder.geocode({'address': 'zip' + r.data.dzip}, function(r,s){
          console.log(r);
          console.log(r[0].geometry.location);
        });

        dm.getDistanceMatrix({
          origins: [latlng],
          destinations: ['zip ' + r.data.dzip],
          travelMode: 'DRIVING',
          avoidHighways: true,
          avoidTolls: true,
        }, function(r, s){
          console.log(r);
        });

        var marker = new google.maps.Marker({
          map: fleetMap,
          draggable: false,
          animation: google.maps.Animation.DROP,
          position: latlng,
          title: r.data.truck_number,
          label: r.data.truck_number,
          icon: {
            path: gmaps_select_icon(r.location.speed),
            scale: 3,
            strokeColor: '#ff0000',
            fillColor: '#ff0000',
            fillOpacity: 1,
            labelOrigin: google.maps.Point(40, 33),
            rotation: r.location.rotation
          }
        })

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
