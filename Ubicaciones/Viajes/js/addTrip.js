function loadingScreen(message){
  //console.log("Loading screen active!");
  $('body').append("<div class='overlay d-flex align-items-center' style='z-index: 2000'><div class='overlay-loading justify-content-center d-flex align-items-center'><p><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i></p><p>" + message +"</p><div></div>")
}

function validateForm(form_inputs, current_target){
  var isValid = true;
  var iter = form_inputs.length;
  form_inputs.each(function(){
    if ($(this).attr('db-id') === "") {
      $(this).addClass('is-invalid');
      $(this).siblings('.invalid-feedback').html('Must select option from dropwdown list');
      $('.popup-list').fadeOut();
      return false;
    } else if ($(this).attr('db-id') !== undefined) {
      $(this).removeClass('is-invalid').addClass('is-valid');
      $(this).siblings('.invalid-feedback').html('');
      $('.popup-list').fadeOut();
    }


    if ($(this).val() === "") {
      $(this).addClass('is-invalid');
      $(this).siblings('.invalid-feedback').html('This field must be filled out');
      return false;
    } else {
      $(this).removeClass('is-invalid').addClass('is-valid');
      $(this).siblings('.invalid-feedback').html('');
    }
    if ($(this).is(current_target)) {
      return false;
    }
  });

  form_inputs.each(function(i){
    if ($(this).attr('db-id') === "") {
      if ($(this).attr('db-id') !== undefined) {
      }
      isValid = false;
      return isValid;
    }

    if ($(this).val() === "") {
      isValid = false;
      return isValid;
    }
  });

  return isValid;
}

function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

function construct_movement(movements){

  // var template = $("<div class='movement'><p class='d-inline'><span class='ocity'></span>, </p><p class='d-inline'><span class='ostate'></span> <span class='ozip'></span></p><p class='d-inline'> - </p><p class='d-inline'><span class='dcity'></span>, </p><p class='d-inline'><span class='dstate'></span> <span class='dzip'></span> (<span class='distance'></span> <span class='mov-type'></span> Miles)</p></div>");
  var template = $("<div class='movement'><p class='d-inline'><span class='origin'></span><p class='d-inline'> - </p><p class='d-inline'><span class='destination'></span></span> (<span class='distance'></span> <span class='mov-type'></span> Miles)</p></div>");

  var html_movement = $("<div></div>");

  var ocity = "";
  var ostate = "";
  var ozip = "";
  var dcity = "";
  var dstate = "";
  var dzip = "";
  var distance = "";
  var origin = "";
  var destination = "";

  for (var movement in movements) {
    if (movements.hasOwnProperty(movement) && movement != 'total_distance') {
      // html_movement = template;

      origin = movements[movement].origin;
      destination = movements[movement].destination;

      // ocity = movements[movement].origin.city;
      // ostate = movements[movement].origin.state;
      // ozip = movements[movement].origin.zip;
      //
      // dcity = movements[movement].destination.city;
      // dstate = movements[movement].destination.state;
      // dzip = movements[movement].destination.zip;

      distance = movements[movement].distance;

      template.find('.origin').html(origin);
      template.find('.destination').html(destination);
      template.find('.distance').html(distance);
      // template.find('.ozip').html(ozip);
      // template.find('.dcity').html(dcity);
      // template.find('.dstate').html(dstate);
      // template.find('.dzip').html(dzip);
      // template.find('.distance').html(distance);
      template.clone().uniqueId().appendTo(html_movement);
    }

  }
  $('#movement-confirmation').html(html_movement);

  // return new_movement;

}

function add_driver(driver, dbid){
  $('#listed-drivers').append("<p class='d-flex justify-content-between'><span db-id='" + dbid + "'>" + driver + "</span><span class='remove-driver' role='button'><i class='fas fa-user-times text-danger'></i></span></p>");
  $('.popup-list').slideUp();
  $('#driver-popup-list-modal').html('');
  $("[id-display='#driver-popup-list-modal']").val("").attr('value', "").prop('value', "").change();
}

function catchLocation(id = 'testvalue'){
  var place = google_autocompletes[id].goo_ac.getPlace();
  $('#' + id).val(place.formatted_address).attr('value', place.formatted_address).prop('value', place.formatted_address);

  var locations = [];
  var distances = {
    total_distance: 0
  };

  $('#lh-details-pane').find('.google-location-input').each(function(i){
    var thisval = $(this).val();
    var validate = thisval != "";

    if (validate) {
      locations.push(thisval);
    } else {
      return locations = false;
    }

  });

  if (locations) {
    console.log("This is happening...");
    $('body').prepend('<div class="remove-this" style="position: absolute; background-color: transparent; width: 100%; height: 100%; z-index: 9999"><div>');
    $('.total_distance').html('<i class="fas fa-spinner fa-spin align-self-center"></i>');
    // return false;
    var parse_zip = /\d+/g;
    depTime = new Date();
    depTime.setMinutes(depTime.getMinutes() + 20);

    var route_details = {
      origins: [],
      destinations: [],
      travelMode: 'DRIVING',
      drivingOptions: {
        departureTime: depTime,
        trafficModel: google.maps.TrafficModel.PESSIMISTIC
      },
      unitSystem: google.maps.UnitSystem.IMPERIAL,
    }

    var distanceService = new google.maps.DistanceMatrixService();

    for (var i = 0; i < locations.length - 1; i++) {
      origin = locations[i];
      destination = locations[i + 1];
      if (origin == destination) {
        continue;
      }
      route_details.origins.push(origin);
      route_details.destinations.push(destination);
    }

    // console.log(destinations);

    distanceService.getDistanceMatrix(route_details, function(r, s){
      for (var destination in route_details.destinations) {
        if (route_details.destinations.hasOwnProperty(destination)) {
          // var origin_element = $('[zip="' + route_details.origins[destination].match(parse_zip)[0] + '"]');
          // var destination_element = $('[zip="' + route_details.destinations[destination].match(parse_zip)[0] + '"]');

          distances[destination] = {
            origin: route_details.origins[destination],
            destination: route_details.destinations[destination],
            distance: parseInt(Math.round(r.rows[destination].elements[destination].distance.value / 1609.34))
          }
          distances['total_distance'] += parseInt(distances[destination].distance);

        }
      }
      $('.remove-this').remove();
      construct_movement(distances);
      $('.total_distance').html(distances['total_distance']).change();

    });

  }

}

$(document).ready(function(){

  /** This listeners control the popup snippet to select trailers, drivers, tractors and brokers.  **/
  $('.popup-input').keydown(function(e){
    if (e.keyCode === 13 || e.keyCode === 9) {
      e.preventDefault();
      var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";


      var dbid = $(targetFocus).attr('db-id');
      var inputTarget = $(targetFocus).parent().attr('id');
      var type = $(targetFocus).parent().attr('type');
      var target = $(targetFocus).parent().attr('target');
      var name = $(targetFocus).html();
      var plates = $(targetFocus).attr('plates');

      switch (type) {
        case 'multiple':
        add_driver(name, dbid);
        break;
        default:
        if (plates) {
          $("[id-display='#" + inputTarget+ "']").attr('plates', plates);
        }
        $("[id-display='#" + inputTarget+ "']").attr("value", $(targetFocus).html()).attr('db-id', $(targetFocus).attr('db-id'));
        $("[id-display='#" + inputTarget+ "']").prop("value", $(targetFocus).html()).change();
        $('.popup-list').slideUp();
      }


    }
  });
  $('.popup-input').keyup(function(e){
    if (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 13 || e.keyCode === 9){return false;}
    data = {}
    pop = $(this).attr('id-display');
    data.txt = $(this).val();


    if (data.txt == "") {
      $('.popup-list').slideUp();
      return false;
    } else {

      if (pop.indexOf('trailer') >= 0){
        url = "actions/fetchTrailersPopup.php"
      }
      if (pop.indexOf('truck') >= 0){
        url = "actions/fetchTrucksPopup.php"
      }
      if (pop.indexOf('driver') >= 0){
        url = "actions/fetchDriversPopup.php"
      }
      if (pop.indexOf('broker') >= 0) {
        url = "actions/fetchBrokersPopup.php"
      }

      $.ajax({
        method: 'POST',
        data: data,
        url: url,
        success: function(result){
          resp = JSON.parse(result);

          switch (resp.code) {
            case 1:
              $(pop).html(resp.data).slideDown();
              break;
            case 2:
            $(pop).html("<p>No se encontraron resulados...</p>").slideDown();
              break;
            default:
            console.error(resp.message);
            $(pop).html("").slideUp();

          }
        },
        error: function(exception){
          console.error(exception);
        }
      })
    }
  })
  $('.popup-list').on('click', 'p', function(){
    var dbid = $(this).attr('db-id');
    var inputTarget = $(this).parent().attr('id');
    var name = $(this).html();
    if (inputTarget == "driver-popup-list-modal") {
        add_driver(name, dbid);
        return false;
    }

    if (inputTarget == "trailer-popup-list") {
      $("[id-display='#" + inputTarget+ "']").attr('plates', $(this).attr('plates'));
    }
    $("[id-display='#" + inputTarget+ "']").attr("value", $(this).html()).attr('db-id', $(this).attr('db-id')).blur();
    $("[id-display='#" + inputTarget+ "']").prop("value", $(this).html()).blur();
    $('.popup-list').slideUp();

  });
  $('.popup-list').on('mouseenter', 'p', function(){
    $('.hovered').attr('class', '');
    $(this).attr('class', 'hovered');
  });
  $('.popup-list').on('mouseleave', 'p', function(){
    $(this).attr('class', '')
  });




  /** This listeners control the inputs validation and modifications that need to happen during the new trip entry. **/
  $('#lh-details-pane').on('calculate_distance', '.google-location-input', function(){
    el = $(this);
    var txt = el.val();
    var locations = [];
    var skip = false;

    var distances = {
      total_distance: 0
    };
    //Validate non-repeat number.

    // var pull_city_state = $.ajax({
    //   method: 'POST',
    //   data: {txt: txt},
    //   url: 'actions/fetchCityState.php'
    // });

    // pull_city_state.done(function(result){
    //   rsp = JSON.parse(result);
    //   // console.log(rsp);
    //   if (rsp.code == 1) {
    //     el.parents('.row').find('.stateInput').val(rsp.data.state).change();
    //     el.parents('.row').find('.cityInput').val(rsp.data.city).change();
    //   } else {
    //     el.parents('.row').find('.stateInput').val("").change();
    //     el.parents('.row').find('.cityInput').val("").change();
    //   }

      $('#lh-details-pane').find('.google-location-input').each(function(i){
        var thisval = $(this).val();
        var validate = thisval != "";

        if (validate) {
          locations.push(thisval);
        } else {
          return locations = false;
        }

      });

      if (locations) {
        $('body').prepend('<div class="remove-this" style="position: absolute; background-color: transparent; width: 100%; height: 100%; z-index: 9999"><div>');
        $('.total_distance').html('<i class="fas fa-spinner fa-spin align-self-center"></i>');
        // return false;
        var parse_zip = /\d+/g;
        depTime = new Date();
        depTime.setMinutes(depTime.getMinutes() + 20);

        var route_details = {
          origins: [],
          destinations: [],
          travelMode: 'DRIVING',
          drivingOptions: {
            departureTime: depTime,
            trafficModel: google.maps.TrafficModel.PESSIMISTIC
          },
          unitSystem: google.maps.UnitSystem.IMPERIAL,
        }

        var distanceService = new google.maps.DistanceMatrixService();

        for (var i = 0; i < locations.length - 1; i++) {
          origin = locations[i];
          destination = locations[i + 1];
          if (origin == destination) {
            continue;
          }
          route_details.origins.push(origin);
          route_details.destinations.push(destination);
        }

        // console.log(destinations);

        distanceService.getDistanceMatrix(route_details, function(r, s){
          for (var destination in route_details.destinations) {
            if (route_details.destinations.hasOwnProperty(destination)) {
              // var origin_element = $('[zip="' + route_details.origins[destination].match(parse_zip)[0] + '"]');
              // var destination_element = $('[zip="' + route_details.destinations[destination].match(parse_zip)[0] + '"]');

              distances[destination] = {
                origin: route_details.origins[destination],
                destination: route_details.destinations[destination],
                distance: parseInt(Math.round(r.rows[destination].elements[destination].distance.value / 1609.34))
              }
              distances['total_distance'] += parseInt(distances[destination].distance);

            }
          }
          $('.remove-this').remove();
          construct_movement(distances);
          $('.total_distance').html(distances['total_distance']).change();

        });

      }
  });
  $('#trip-details-content').on('change', 'input, select', function(e){
    var value = $(this).val();
    var dbid = $(this).attr('db-id');
    var activate_next = false;

    if ($(this).hasClass('driverid')) {
      var count = $('#listed-drivers p').length;
      if (count > 0) {
        value = "some value";
        dbid = "some value";
      }
    }
    var validation = (value == "" || typeof(value) == undefined || (dbid == "" && undefined != typeof(dbid)));

    if (validation) {
      $(this).addClass('is-invalid').removeClass('is-valid').data('is-valid', false);
    } else {
      $(this).removeClass('is-invalid').addClass('is-valid').data('is-valid', true);
    }

    $('#trip-details-content .tab-pane.fade.show.active').find('input, select').filter(':not("[readonly]")').each(function(){
      var check = $(this).data('is-valid');
      if (check) {
        activate_next = true;
      } else {
        $(this).focus();
        activate_next = false;
        return false;
      }
    });

    if (activate_next) {
      $('.next-pane').removeClass('disabled').attr('disabled', false).focus();
    }

  })
  $('.next-pane').click(function(){
    var activate_next = false;
    var next_pane = $('#add_trip_progress')
      .find('.nav-link.active')
      .parent().next().find('a');

    next_pane.attr('disabled', false)
      .removeClass('disabled')
      .tab('show');

    $('.progress-bar').css('width', next_pane.attr('progress') + "%");

    next_pane.on('shown.bs.tab', function(){
      $('#trip-details-content .tab-pane.fade.show.active').find('input, select').filter(':not("[readonly]")').each(function(){
        var check = $(this).data('is-valid');
        if (check) {
          activate_next = true;
        } else {
          $(this).focus();
          activate_next = false;
          return false;
        }
      });

      if (activate_next) {
        $('.next-pane').removeClass('disabled').attr('disabled', false).focus();
      }
    })


  });
  $('#trip-confirmation-tab').on('show.bs.tab', function(){
    var source = $('#trip-details-content');
    var destination = $('#trip-confirmation-pane');

    var truck = source.find('.truckid');
    var trailer = source.find('.trailerid');
    var drivers = $('#listed-drivers').children();
    var miles = source.find('.total_distance');
    var rate = source.find('.trip-rate');
    var rpm = Math.round((rate.val() / miles.html())*100)/100 ;
    var broker = source.find('.selected-broker');
    var reference = source.find('.broker-reference');
    var movs = {};
    var appointment_date = source.find('.appointment.date').val();
    var appointment_hour = source.find('.appointment.hour').val();
    var appointment_minute = source.find('.appointment.minute').val();
    var tripno = source.find('.tripid').val();
    var tripid = source.find('.tripid').attr('db-id');

    $('#lh-details-pane-add').find('.movement').each(function(){
      var zip = $(this).find('.zipinput').val();
      var type = $(this).find('.mov-type').val();
      var kind = $(this).find('.google-location-input').attr('kind');
      var location = $(this).find('.google-location-input').val();
      movs[zip] = {
        type: type,
        kind: kind
      }
      // if (typeof(type) !== 'undefined') {
      // }
    });

    console.log(movs);

    for (var mov in movs) {
      if (movs.hasOwnProperty(mov)) {
        if (typeof movs[mov].type !== 'undefined') {
          destination.find(".ozip:contains(" + mov + ")").parents('.movement').find('.mov-type').html(movs[mov].type)
        }
        console.log(mov);
        switch (movs[mov].kind) {
          case 'origin':
            destination.find(".ozip:contains(" + mov + ")").attr('kind', movs[mov].kind);
            break;

          case 'destination':
            destination.find(".dzip:contains(" + mov + ")").attr('kind', movs[mov].kind);
            break;
          default:
          //Did not match either origin or destination.
        }
      }
    }

    destination.find('.confirm-truck-number').attr('db-id', truck.attr('db-id')).html(truck.val());
    destination.find('.confirm-truck-plates').html(truck.attr('plates'));
    destination.find('.confirm-trailer-number').html(trailer.val()).attr('db-id', trailer.attr('db-id'));
    destination.find('.confirm-trailer-plates').html(trailer.attr('plates'));
    destination.find('.confirm-driver-list').html(drivers.clone()).find('.remove-driver').remove();
    destination.find('.confirm-driver-list').find('p').removeClass().addClass('mb-0');
    destination.find('.total-miles').html(miles.html());
    destination.find('.trip-rate-confirmation').html(rate.val());
    destination.find('.rpm-confirmation').html(rpm);
    destination.find('.brokerid-confirmation').html(broker.val()).attr('db-id', broker.attr('db-id'));
    destination.find('.broker-reference-confirmation').html(reference.val());
    $('#linehaul-appointment').find('.date').html(appointment_date);
    $('#linehaul-appointment').find('.hour').html(appointment_hour);
    $('#linehaul-appointment').find('.minutes').html(appointment_minute);
    destination.find('.confirm-trip-info').html(tripno).attr('db-id', tripid);


    $('.next-pane-buttons').hide();
    $('.add-trip-buttons').show();


  });
  $('#trip-confirmation-tab').on('hide.bs.tab',function(){
    $('.add-trip-buttons').hide();
    $('.next-pane-buttons').show();
  })
  $('.add-extra-stop').click(function(){
    var place_template = $('<div class="form-group row movement"><label for="" class="col-sm-2 col-form-label text-right"><i class="fas fa-times text-danger mr-2 remove-row" role="button"></i>Extra Stop</label><div class="col-lg-7"><input type="text" class="form-control google-location-input" name="" value=""></div><div class="col-lg-2"><select class="form-control mov-type"><option value="L" selected>Loaded</option><option value="E">Empty</option></select></div></div>');
    var id = "";
    var html = "";

    place_template.uniqueId();
    id = place_template.attr('id');
    html = place_template.find('.google-location-input')[0];
    $(this).parent().after(place_template);

    google_autocompletes[id] = {
      id: id,
      goo_ac: new google.maps.places.Autocomplete(html, google_autocomplete_options)
    }

    google.maps.event.addListener(google_autocompletes[id].goo_ac, 'place_changed', function(){catchLocation(id)});
  });
  $('#lh-details-pane').on('click', '.remove-row', function(){
    $(this).parents('.row.movement').remove();
  });
  $('#add_trip_progress').on('shown.bs.tab', '[data-toggle="tab"]', function(){
    $('#addLinehaulModal').find('.tab-pane.fade.active.show').find('input').first().focus();
  });
  $('.add-linehaul').click(function(){
    var source = $('#trip-confirmation-pane');
    var data = {
      trailer: {
        id: source.find('.confirm-trailer-number').attr('db-id'),
        number: source.find('.confirm-trailer-number').html(),
        plates: source.find('.confirm-trailer-plates').html(),
      },
      truck:{
        id: source.find('.confirm-truck-number').attr('db-id'),
        number: source.find('.confirm-truck-number').html(),
        plates: source.find('.confirm-truck-plates').html(),
      },
      broker:{
        id: source.find('.brokerid-confirmation').attr('db-id'),
        name: source.find('.brokerid-confirmation').html(),
      },
      linehaul:{
        trip:{
          id: source.find('.confirm-trip-info').attr('db-id'),
          number: source.find('.confirm-trip-info').html()
        },
        reference: source.find('.broker-reference-confirmation').html(),
        rate: source.find('.trip-rate-confirmation').html(),
        appt: {
          date: $('#linehaul-appointment').find('.date').html(),
          hour: $('#linehaul-appointment').find('.hour').html(),
          minute: $('#linehaul-appointment').find('.minutes').html(),
        },
        origin:{
          zip: source.find('.ozip[kind="origin"]').html(),
          city: source.find('.ozip[kind="origin"]').parents('.movement').find('.ocity').html(),
          state: source.find('.ozip[kind="origin"]').parents('.movement').find('.ostate').html(),
        },
        destination:{
          zip: source.find('.dzip[kind="destination"]').html(),
          city: source.find('.dzip[kind="destination"]').parents('.movement').find('.dcity').html(),
          state: source.find('.dzip[kind="destination"]').parents('.movement').find('.dstate').html(),
        },
        routes: {},
        drivers:{}
      },
    }
    console.log(data);
    source.find('.confirm-driver-list p').each(function(i){
      data.linehaul.drivers[i] = {};
      var driver = $(this).children('span');
      data.linehaul.drivers[i]['id'] = driver.attr('db-id');
      data.linehaul.drivers[i]['name'] = driver.html();
    })
    source.find('#movement-confirmation').find('.movement').each(function(i){
      var ocity, ostate, ozip, dcity, dstate, dzip, miles
      var route = $(this);
      data.linehaul.routes[i] = {
        ocity: route.find('.ocity').html(),
        ostate: route.find('.ostate').html(),
        ozip: route.find('.ozip').html(),

        dcity: route.find('.dcity').html(),
        dstate: route.find('.dstate').html(),
        dzip: route.find('.dzip').html(),

        miles: route.find('.distance').html(),
        type: route.find('.mov-type').html()
      }

    })

    var put_trip = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/addNewLinehaul.php',
    });

    put_trip.done(function(r){
      // console.log(r);
      r = JSON.parse(r);

      if (r.code == 1) {
        location.reload();
      } else {
        alertify.error("There was a problem adding the record :(");
        console.warn(r.message);
      }
    }).fail(function(x){
      console.error(x)
    });
  });

  // $('#lh-details-pane').on('change', '.google-location-input', function(){
  //   var id = $(this).attr('id');
  //   do {
  //     console.log("Checking place:");
  //     console.log(google_autocompletes[id].goo_ac.getPlace());
  //   } while (typeof google_autocompletes[id].goo_ac.getPlace() == 'undefined');
  //   // setTimeout(function () {
  //   //   var place = google_autocompletes[id].goo_ac.getPlace();
  //   //   console.log(place);
  //   // }, 100);
  //   var count = 0;
  //   // do {
  //   // } while (typeof place === 'undefined');
  //
  //   // console.log(place);

  // })

  /** This functions/listeners will control the google autocomplete inputs **/

  var google_autocomplete_options = { //This options manage the autocomplete settings throughtout the page.
    // types: ['(regions)'],
    componentRestrictions: {country: 'us'}
  }
  google_autocompletes = {}; //This object will contain all the autocomplete objects.

  $('.google-location-input').each(function(){ //We instantiate the autocomplete object for each of the location inputs.
    var id = $(this).attr('id');
    var html = $(this)[0];
    google_autocompletes[id] = {
      id: id,
      goo_ac: new google.maps.places.Autocomplete(html, google_autocomplete_options)
    }

    google.maps.event.addListener(google_autocompletes[id].goo_ac, 'place_changed', function(){catchLocation(id)});
  });

  // google_autocompletes.origin_location.goo_ac.addListener('place_changed', catchLocation);

  // origin = new google.maps.places.Autocomplete(origin_input, google_autocomplete_options);
  // destination = new google.maps.places.Autocomplete(destination_input, google_autocomplete_options);



});

$(document).keydown(function(e){
  if (e.keyCode == 38 || e.keyCode == 40){
    if ($(document.activeElement).attr('id-display') !== undefined) {
      var target = $(document.activeElement).attr('id-display') + " p";
      var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";

      if ($(targetFocus).length == 0) {
        $(target).first().addClass('hovered');
      } else {
        if (e.keyCode == 40) {
          $(targetFocus).removeClass('hovered').next().addClass('hovered');
        }

        if (e.keyCode == 38) {
          $(targetFocus).removeClass('hovered').prev().addClass('hovered');
        }
      }

    }
  }

  if (e.keyCode === 13 || e.keyCode === 9) {
    var targetFocus = $(document.activeElement).attr('id-display') + " p" + ".hovered";

    var dbid = $(targetFocus).attr('db-id');
    var inputTarget = $(targetFocus).parent().attr('id');
    $("[id-display='#" + inputTarget+ "']").attr("value", $(targetFocus).html()).attr('db-id', $(targetFocus).attr('db-id'));
    $("[id-display='#" + inputTarget+ "']").prop("value", $(targetFocus).html());
    $('.popup-list').slideUp();

  }


});
