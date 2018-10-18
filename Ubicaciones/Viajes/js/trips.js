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

function toggle_view(element_object){
  $.each(element_object, function(index, value){
    value.toggle();
  })
  // for (var element_object in object) {
  //   if (object.hasOwnProperty(element_object)) {
  //     object.toggle();
  //   }
  // }
}

function add_driver(driver, dbid){
  $('#listed-drivers').append("<p class='d-flex justify-content-between'><span db-id='" + dbid + "'>" + driver + "</span><span class='remove-driver' role='button'><i class='fas fa-user-times text-danger'></i></span></p>");
  $('.popup-list').slideUp();
  $('#driver-popup-list-modal').html('');
  $("[id-display='#driver-popup-list-modal']").val("").attr('value', "").prop('value', "").change();
}

function construct_movement(movements){

  var template = $("<div class='movement'><p class='d-inline'><span class='ocity'></span>, </p><p class='d-inline'><span class='ostate'></span> <span class='ozip'></span></p><p class='d-inline'> - </p><p class='d-inline'><span class='dcity'></span>, </p><p class='d-inline'><span class='dstate'></span> <span class='dzip'></span> (<span class='distance'></span> <span class='mov-type'></span> Miles)</p></div>");

  var html_movement = $("<div></div>");

  var ocity = "";
  var ostate = "";
  var ozip = "";
  var dcity = "";
  var dstate = "";
  var dzip = "";
  var distance = "";

  for (var movement in movements) {
    if (movements.hasOwnProperty(movement) && movement != 'total_distance') {
      // html_movement = template;

      ocity = movements[movement].origin.city;
      ostate = movements[movement].origin.state;
      ozip = movements[movement].origin.zip;

      dcity = movements[movement].destination.city;
      dstate = movements[movement].destination.state;
      dzip = movements[movement].destination.zip;

      distance = movements[movement].distance;

      template.find('.ocity').html(ocity);
      template.find('.ostate').html(ostate);
      template.find('.ozip').html(ozip);
      template.find('.dcity').html(dcity);
      template.find('.dstate').html(dstate);
      template.find('.dzip').html(dzip);
      template.find('.distance').html(distance);
      template.clone().appendTo(html_movement);
    }

  }
  $('#movement-confirmation').html(html_movement);

  // return new_movement;

}

$(document).ready(function(){

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

  $('#tripDashTable').on('click', 'tr', function(){
    tripid = $(this).attr('db-id');
    tripyear = $(this).attr('ty');
    if (typeof tripid === 'undefined' || typeof tripyear === 'undefined') {
      return false;
    }
    window.location.href = "tripDetails.php?tripid=" + tripid
  });

  $('#newTripForm').find('input').on('blur', function(e){
    var current_target = $(this);
    var form_inputs = $('#newTripForm').find('input').not($('[type=checkbox], [style="display: none"], .disabled'));
    valid = validateForm(form_inputs, current_target);
    if (valid) {
      $('#addTripButton').removeClass('disabled').attr('disabled', false);
    } else {
      $('#addTripButton').addClass('disabled').attr('disabled', true);
    }

  });
  $('#addTripButton').click(function(){
    var data = {};
    data.truckid = $('[id-display="#truck-popup-list"]').attr('db-id');
    data.trailerid = $('[id-display="#trailer-popup-list"]').attr('db-id');
    data.driverid = $('[id-display="#driver-popup-list"]').attr('db-id');

    data.broker = {
      brokerid: $('[id-display="#broker-popup-list"]').attr('db-id'),
      broker_reference: $('#broker-reference').val()
    }

    data.trip = {
      origin: {
        state: $('#oState').val(),
        city: $('#oCity').val(),
        zip: $('#oZip').val()
      },
      destination: {
        state: $('#dState').val(),
        city: $('#dCity').val(),
        zip: $('#dZip').val()
      },
      rate: $('#tRate').val(),
      appt: {
        date: $('#appointment_date_add').val(),
        hour: $('#appointment_time_hour_add').val(),
        min: $('#appointment_time_minute_add').val()
      },
      conveyance: {
        driver: $('.driverid').attr('db-id'),
        truck: $('.truckid').attr('db-id'),
        team: $('.teamdriverid').attr('db-id')
      }
    }

    var toggle_buttons = [
      $(this),
      $($(this).attr('loading'))
    ]

    toggle_view(toggle_buttons);

    $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/addNewTrip.php',
      success: function(result){
        resp = JSON.parse(result);
        console.log(resp);
        if (resp.query.code == 1) {
          window.location.href = "tripDetails.php?tripid=" + resp.query.insertid + "&tripyear=" + resp.query.tripyear
        } else {
          swal({
            title: "Trip not added :(",
            text: resp.query.message,
            icon: 'error'
          })
          toggle_view(toggle_buttons);
        }
      },
      error: function(exception){
        toggle_view(toggle_buttons);
        console.error(exception);
      }
    })

  })
  $('.teamDriverCheck').change(function(){
    valid = $(this).prop('checked');
    target = $(this).attr('target');

    if(valid){
      $(target).fadeIn();
    } else {
      $(target).fadeOut();
    }
  })
  $('[data-toggle="popover"]').popover({
     trigger: 'click',
     container: 'body',
     title: 'Quick Broker Add',
     placement: 'bottom',
     html: true,
     content: function(){
       return $('#addBrokerQuick').html()
     },
     template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
   }).on('shown.bs.popover', function(){

     $('.qa-broker-submit').click(function(){
       var data = {
         name: $('.popover').find('.qa-broker-name').val(),
         contact: $('.popover').find('.qa-broker-contact').val()
       }

       if (data.name == "" || data.contact == "") {
         swal({
           title: "Oops! Name and contact must be specified!",
           text: "Please verify information and try again. If the problem persists, please contact support.",
           icon: 'error'
         });
         return false;
       }

       console.log(data);
       var quick_add_broker = $.ajax({
         method: 'POST',
         url: 'actions/quickAddBroker.php',
         data: data
       });

       quick_add_broker.done(function(result){
         console.log(result);
         rsp = JSON.parse(result);
         if (rsp.code == 1) {
           $('.popover').popover('hide');
           $('#brokerName').attr('db-id', rsp.data).val(data.name);
         } else {
           swal({
             title: "Oops! There was an issue adding the broker. :(",
             text: rsp.message,
             icon: 'error'
           });
         }
       });
     });
   })

  $('#addTripModal').on('hidden.bs.modal', function(){
    $(this).find('input').val('').attr('value', '').data('is-valid', false);
    $(this).find('select').find('option').first().attr('selected', true);
    $(this).find('#listed-drivers').html('');
    $('#add_trip_progress').find('a:not(:first)').removeClass('active').addClass('disabled').attr('disabled', true);
    $('#add_trip_progress').find('a:first').addClass('active');
    $('.next-pane').addClass('disabled').attr('disabled', true);
    $('#trip-details-content').children().removeClass('show active');
    $('#trip-details-content').children().first().addClass('show active');
    $('.progress-bar').css('width', "0%")
    $('.popup-list').html('');
    $('.total_distance').html('');
  });
  $('#addTripModal').on('shown.bs.modal', function(){
    $(this).find('.tab-pane.fade.active.show').find('input').first().focus();
  });
  $('#lh-details-pane').on('change', '.zipInput', function(){
    el = $(this);
    var txt = el.val();
    var zips = [];
    el.attr('zip', txt);
    var skip = false;

    var distances = {
      total_distance: 0
    };

    //Validate non-repeat number.

    var pull_city_state = $.ajax({
      method: 'POST',
      data: {txt: txt},
      url: 'actions/fetchCityState.php'
    });

    pull_city_state.done(function(result){
      rsp = JSON.parse(result);
      // console.log(rsp);
      if (rsp.code == 1) {
        el.parents('.row').find('.stateinput').val(rsp.data.state).change();
        el.parents('.row').find('.cityinput').val(rsp.data.city).change();
      } else {
        el.parents('.row').find('.stateinput').val("").change();
        el.parents('.row').find('.cityinput').val("").change();
      }

      $('#lh-details-pane').find('.zipinput').each(function(i){
        var thisval = $(this).val();
        var validate = $(this).parents('.row').find('.stateinput').val() != "" && $(this).parents('.row').find('.cityinput').val() != "" && thisval != "";

        if (validate) {
          zips.push(thisval);
        } else {
          return zips = false;
        }

      });

      if (zips) {
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

        for (var i = 0; i < zips.length - 1; i++) {
          origin = "Zip " + zips[i];
          destination = "Zip " + zips[i + 1];
          route_details.origins.push(origin);
          route_details.destinations.push(destination);
        }

        // console.log(destinations);

        distanceService.getDistanceMatrix(route_details, function(r, s){
          for (var destination in route_details.destinations) {
            if (route_details.destinations.hasOwnProperty(destination)) {
              var origin_element = $('[zip="' + route_details.origins[destination].match(parse_zip)[0] + '"]');
              var destination_element = $('[zip="' + route_details.destinations[destination].match(parse_zip)[0] + '"]');

              distances[destination] = {
                origin: {
                  zip: origin_element.val(),
                  state: origin_element.parents('.row').find('.stateinput').val(),
                  city: origin_element.parents('.row').find('.cityinput').val()
                },
                destination: {
                  zip: destination_element.val(),
                  state: destination_element.parents('.row').find('.stateinput').val(),
                  city: destination_element.parents('.row').find('.cityinput').val()
                },
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

  });
  $('.next-pane').click(function(){
    var next_pane = $('#add_trip_progress')
      .find('.nav-link.active')
      .parent().next().find('a');

    next_pane.attr('disabled', false)
      .removeClass('disabled')
      .tab('show');

    $('.progress-bar').css('width', next_pane.attr('progress') + "%");
  });
  $('#lh-details-pane').on('click', '.remove-row', function(){
    $(this).parents('.row').remove();
    $('.zipInput').change();
  });
  $('#add_trip_progress').on('shown.bs.tab', '[data-toggle="tab"]', function(){
    $('#addTripModal').find('.tab-pane.fade.active.show').find('input').first().focus();
  });
  $('[tab-type="addTripModal"]').on('show.bs.tab', function(){
    var next_pane = $(this).parent().next().find('a');

    var validate = next_pane.attr('disabled') == 'disabled';

    if (validate) {
      $('.next-pane').addClass('disabled').attr('disabled', true);
    }
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
    var mov_types = {};
    var appointment_date = source.find('.appointment.date').val();
    var appointment_hour = source.find('.appointment.hour').val();
    var appointment_minute = source.find('.appointment.minute').val();

    $('#lh-details-pane').find('.movement').each(function(){
      var zip = $(this).find('.zipInput').val();
      var type = $(this).find('.mov-type').val();
      if (typeof(type) !== 'undefined') {
        mov_types[zip] = type
      }
    });

    for (var mov_type in mov_types) {
      if (mov_types.hasOwnProperty(mov_type)) {
        destination.find(".ozip:contains(" + mov_type + ")").parents('.movement').find('.mov-type').html(mov_types[mov_type]);
      }
    }

    console.log(mov_types);

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


    $('.next-pane-buttons').hide();
    $('.add-trip-buttons').show();


  });
  $('#trip-confirmation-tab').on('hide.bs.tab',function(){
    $('.add-trip-buttons').hide();
    $('.next-pane-buttons').show();
  })
  $('.add-extra-stop').click(function(){
    $(this).parent().after('<div class="form-group row movement"><label for="" class="col-sm-2 col-form-label text-right"><i class="fas fa-times text-danger mr-2 remove-row"></i>Extra Stop</label><div class="col-lg-2"><input type="text" class="form-control zipInput" autocomplete="new-password" name="" value="" placeholder="Zip Code"><small class="invalid-feedback font-italic" style="position:relative; width:300px">This field cannot be empty.</small></div><div class="col-lg-2" readonly><input type="text" class="form-control stateInput" name="" value="" placeholder="State" readonly disabled></div><div class="col-lg-3"><input type="text" class="form-control cityInput" name="" value="" placeholder="City" readonly disabled></div><div class="col-lg-2"><select class="form-control mov-type"><option value="L" selected>Loaded</option><option value="E">Empty</option></select></div></div>');
  });
  $('#trip-details-content').on('change', 'input, select', function(e){
    // if ($(this).attr('readonly')) {
    //   return false;
    // }


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

    $('#trip-details-content .tab-pane.fade.show.active').find('input, select').each(function(){
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
  $('#listed-drivers').on('click', 'span', function(){
    $(this).parent().remove();

    var count = $('#listed-drivers p').length;

    if (count == 0) {
      $('.next-pane').addClass('disabled').attr('disabled', true);
    }

  });
  $('.add-trip').click(function(){
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
        reference: source.find('.broker-reference-confirmation').html(),
        rate: source.find('.trip-rate-confirmation').html(),
        appt: {
          date: $('#linehaul-appointment').find('.date').html(),
          hour: $('#linehaul-appointment').find('.hour').html(),
          minute: $('#linehaul-appointment').find('.minutes').html(),
        },
        routes: {},
        drivers:{}
      },
    }
    source.find('.confirm-driver-list p').each(function(i){
      data.linehaul.drivers[i] = {};
      var driver = $(this).children('span');
      data.linehaul.drivers[i]['id'] = driver.attr('db-id');
      data.linehaul.drivers[i]['name'] = driver.html();
    })
    source.find('#movement-confirmation').find('.movement').each(function(i){
      var ocity, ostate, ozip, dcity, dstate, dzip, miles
      var route = $(this);
      console.log(route);
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

    // console.log(data);

    var put_trip = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/addNewTrip.php',
    });

    put_trip.done(function(r){
      // console.log(r);
      r = JSON.parse(r);

      if (r.code == 1) {
        window.location.href = "tripDetails.php?tripid=" + r.insertid;
      } else {
        alertify.error("There was a problem adding the record :(");
        console.warn(r.message);
      }
    }).fail(function(x){
      console.error(x)
    });
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

  });


});
