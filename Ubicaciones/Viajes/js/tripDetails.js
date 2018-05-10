
function loadingScreen(message){
  //console.log("Loading screen active!");
  $('body').append("<div class='overlay d-flex align-items-center' style='z-index: 2000'><div class='overlay-loading justify-content-center d-flex align-items-center'><p><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i></p><p>" + message +"</p><div></div>")
}

function getCityStateListener(){
  $('.zipInput').unbind().blur(function(){

    el = $(this)
    var txt = el.val();

    message = "Loading..."

    var getCityState = $.ajax({
      method: 'POST',
      beforeSend: function(){
        loadingScreen(message);
      },
      data: {txt: txt},
      url: 'actions/fetchCityState.php',
    });

    getCityState.done(function(result){
      var rsp = JSON.parse(result);
      //console.log(rsp);
      if (rsp.code == 1) {
        el.parent().siblings().find('.stateInput').val(rsp.data.state).change();
        el.parent().siblings().find('.cityInput').val(rsp.data.city).change();
      } else {
        console.log(rsp.message);
      }
      $('.overlay').remove();
    }).fail(function(jqXHR, textStatus, errorThrown){
      console.log(textStatus + ': ' + errorThrown);
      $('.overlay').remove();
    });

  })

}

function fetchAllLinehauls(tripyear, tripid){
  var data = {
    tripid: tripid,
    tripyear: tripyear
  }

  var fetchAllLinehauls = $.ajax({
    method: 'POST',
    data: data,
    url: 'actions/fetchAllLinehauls.php'
  });

  fetchAllLinehauls.done(function(result){
    rsp = JSON.parse(result);
    if (rsp.query.code == 1) {
      $('#trip-lh-dash').html(rsp.data);
    } else {
      console.error("There was an error showing the data.");
    }
  }).fail(function(jqXHR, textStatus, errorThrown) {
  // If fail
  console.log(textStatus + ': ' + errorThrown);
  });;

}

function fetchMovements(lhid){
  var data = {
    lhid: lhid
  }
  return $.ajax({
    method: 'POST',
    data: data,
    url: 'actions/fetchMovements.php'
  });
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

function show_lh_details(lhid = undefined){
  var data = {
    lhid: lhid
  }

  var pullLh = $.ajax({
    method: 'POST',
    data: data,
    url: 'actions/pullLinehaul.php'
  });

  var pullMov = $.ajax({
    method: 'POST',
    data: data,
    url: 'actions/pullMovements.php'
  });

  pullMov.done(function(r){
    r = JSON.parse(r);
    if (r.code == 1) {
      console.log(r);
      $('#mov-dash').html(r.data);
    }
  });

  pullLh.done(function(r){
    r = JSON.parse(r);
    console.log(r);

    for (var key in r.data) {
      if ($('.' + key).is('select')) {
        continue;
      }
      if (r.data.hasOwnProperty(key)) {
        $('.' + key).html(r['data'][key]).val(r['data'][key]);
        if ( typeof($('.'+key).attr('db-id')) != 'undefined' && $('.'+key).attr('db-id') !== false) {
          $('.' + key).attr('db-id', r['data'][key + 'id']);
        }
      }
    }

    $('.finalizeRecord.linehaul').attr('recordid', r.data.linehaulid);
    $('.lh-status-button').removeClass('Open Pending Delivery Closed Closure Cancelled').addClass(r.data.lh_status);

    if (r.data.lh_status == 'Closed' || r.data.lh_status == 'Cancelled') {
      $('.finalizeRecord.closelh').hide();
      $('.addMovement').hide();
      $('#lh-edit-enabled').hide();
      if (r.data.lh_status == 'Closed') {
        $('#lh-edit-disabled').show();
      }
    } else {
      $('.finalizeRecord.closelh').show();
      $('.addMovement').show();
      $('#lh-edit-enabled').show();
      $('#lh-edit-disabled').fadeOut();
    }

    if (r.data.appointment) {
      $('.appointment.date').val(r.data.appointment.date);
      $('.appointment.hour').val(r.data.appointment.time.hour);
      $('.appointment.minute').val(r.data.appointment.time.minute);
    } else {
      $('.appointment.date').val('');
      $('.appointment.hour').find('option').attr('selected', false);
      $('.appointment.minute').find('option').attr('selected', false);
    }
    if (r.data.departure) {
      $('.departure.date').val(r.data.departure.date);
      $('.departure.hour').val(r.data.departure.time.hour);
      $('.departure.minute').val(r.data.departure.time.minute);
    }else {
      $('.departure.date').val('');
      $('.departure.hour').find('option').attr('selected', false);
      $('.departure.minute').find('option').attr('selected', false);
    }
    if (r.data.arrival) {
      $('.arrival.date').val(r.data.arrival.date);
      $('.arrival.hour').val(r.data.arrival.time.hour);
      $('.arrival.minute').val(r.data.arrival.time.minute);
    }else {
      $('.arrival.date').val('');
      $('.arrival.hour').find('option').attr('selected', false);
      $('.arrival.minute').find('option').attr('selected', false);
    }
    if (r.data.delivery) {
      $('.delivery.date').val(r.data.delivery.date);
      $('.delivery.hour').val(r.data.delivery.time.hour);
      $('.delivery.minute').val(r.data.delivery.time.minute);
      if (r.data.lh_status != 'Closed' && r.data.lh_status != 'Cancelled') {
        $('.finalizeRecord.closelh').show();
      }
    }else {
      $('.delivery.date').val('');
      $('.delivery.hour').find('option').attr('selected', false);
      $('.delivery.minute').find('option').attr('selected', false);
      $('.finalizeRecord.closelh').hide();
    }

    if (r.data.lh_status == 'Closed' || r.data.lh_status == 'Cancelled') {
      $('#lh-fields').prop('disabled', true);
    } else {
      $('#lh-fields').prop('disabled', false);
    }

  });


  $('#trip-information').fadeOut(function(){$('#linehaul-information').fadeIn()});
}

function show_trip_info(){
  var data = {
    id: $('#trip-identifier').val(),
    year: $('#year-identifier').val()
  }

  console.log(data);
  var pullTrip = $.ajax({
    method: 'POST',
    data: data,
    url: 'actions/pullTrip.php'
  });

  pullTrip.done(function(r){
    var r = JSON.parse(r);
    console.log(r);

    for (var key in r.data.trip) {
      // console.log('Test');
      if (r.data.trip.hasOwnProperty(key)) {
        $('#' + key).html(r['data']['trip'][key]);
      }
    }
    $('#set-trip-status-button').attr('Class', 'mr-2 trip ' + r.data.trip.trip_status);
    $('#summary-dash').html(r.data.linehauls);
  });

  $('#linehaul-information').fadeOut(function(){$('#trip-information').fadeIn()});

}

// function pull_movements(lhid = undefined){
//   data = {
//     lhid: lhid
//   }
//
//   var pullMov = $.ajax({
//     method: 'POST',
//     data: data,
//     url: 'actions/pull_movements'
//   });
//
//   pullMov.done(function(r){
//     r = JSON.parse(r);
//     if (r.code == 1) {
//       $('#mov-dash').html(r.data);
//     }
//   });
// }

// function validateForm(form_inputs){
//   var isValid = true;
//   form_inputs.each(function(){
//     if ($(this).val() === "") {
//       isValid = false;
//       return false;
//     }
//   })
//   return isValid;
// }

$(document).ready(function(){
  show_trip_info();

  $(function(){
    $('[data-toggle="tooltip"]').tooltip({
      container: 'body',
      trigger: 'hover'
    })
  });

  $('#trip-lh-dash').delegate('tr', 'click', function(){

    var data = {
      lhid: $(this).attr('db-id'),
      hdyr: $(this).attr('tripyear'),
      tripnumber: $(this).attr('tripnumber'),
      lhnm: $(this).attr('lh-number')
    }
    $('#lhdetailsId').html(data.hdyr + pad(data.tripnumber, 4) + pad(data.lhnm, 2)).attr('lhid', data.lhid);

    var fetchLinehaul = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/fetchLinehaul.php'
    });

    fetchLinehaul.done(function(result){
      rsp = JSON.parse(result);
      if (rsp.code == 1) {
        $('.saveLhChanges').attr('linehaulid', rsp.data.linehaulid);
        $('#lh-details-status').html(rsp.data.status);
        $('.status_button').removeClass('Open').removeClass('Closed').removeClass('Closure').removeClass('Cancelled').removeClass('Pending').addClass(rsp.data.status);
        $('.finalizeRecord').attr('recordid', rsp.data.linehaulid).attr('tripyear', rsp.data.trip_year);
        $('.lhdBroker').attr('db-id', rsp.data.brokerid);
        $('.lhdBroker').val(rsp.data.broker_name);
        $('.lhdBrokerReference').val(rsp.data.broker_reference);
        $('.lhdTripRate').val(rsp.data.rate);
        $('.ldhozipInput').val(rsp.data.origin_zip);
        $('.ldhostateInput').val(rsp.data.origin_state);
        $('.ldhocityInput').val(rsp.data.origin_city);
        $('.ldhdzipInput').val(rsp.data.destination_zip);
        $('.ldhdstateInput').val(rsp.data.destination_state);
        $('.ldhdcityInput').val(rsp.data.destination_city);
        $('.lhdEmptyMiles').val(rsp.data.empty_miles);
        $('.lhdLoadedMiles').val(rsp.data.loaded_miles);
        $('.lhdTotalMiles').val(rsp.data.total_miles);
        $('#departure_time').val(rsp.data.departure.date);
        $('#departure_time_hour').val(rsp.data.departure.time.hour);
        $('#departure_time_minute').val(rsp.data.departure.time.minute);
        $('#arrival_time').val(rsp.data.arrival.date);
        $('#arrival_time_hour').val(rsp.data.arrival.time.hour);
        $('#arrival_time_minute').val(rsp.data.arrival.time.minute);
        $('#delivery_time').val(rsp.data.delivery.date);
        $('#delivery_time_hour').val(rsp.data.delivery.time.hour);
        $('#delivery_time_minute').val(rsp.data.delivery.time.minute);
        $('#appt_time').val(rsp.data.appointment.date);
        $('#appt_time_hour').val(rsp.data.appointment.time.hour);
        $('#appt_time_minute').val(rsp.data.appointment.time.minute);
        $('.lhdRPM').val(Math.round((rsp.data.rate / rsp.data.total_miles)*100)/100);

        // if ( rsp.data.status == 'Cancelled') {
        //   $('.finalizeRecord.linehaul.cancel').removeClass('btn-danger').addClass('btn-info').html("<i class='fa fa-undo'></i> Restore").attr('action', 'Open').show();
        //   $('.status_button').attr('data-prefix', 'far');
        // } else {
        //   $('.status_button').attr('data-prefix', 'fas');
        //   $('.finalizeRecord.linehaul.cancel').removeClass('btn-info').addClass('btn-danger').html("<i class='fa fa-ban'></i> Cancel").attr('action', 'Cancelled').show();
        // }

        if (rsp.data.status == 'Closed' || rsp.data.status == 'Cancelled') {
          $('#newMovBtn').hide();
          $('#addMovementBtn').hide();
          // $('#lh-details-status').attr('class', 'Closed');
          $('#lhdetailsform').find('input, select').addClass('disabled').attr('disabled', true);
          $('.t-details-enable-editing').hide();
          $('.t-details-buttons').hide();
          if (rsp.data.status == 'Closed') {
            $('.t-details-buttons').hide();
            $('.t-details-enable-editing').show();
          } else {
          }
        } else {
          $('.finalizeRecord.linehaul').show();
          $('#newMovBtn').show();
          $('#saveLhChanges').show();
          $('#addMovementBtn').show();
          $('#lh-details-status').attr('class', '');
          $('#lhdetailsform').find('input, select').removeClass('disabled').attr('disabled', false);
          $('.t-details-buttons').show();
          $('.t-details-enable-editing').hide();
        }




        if (rsp.data.departure.date != "" && rsp.data.arrival.date != "" && rsp.data.delivery.date != "") {
          $('.finalizeRecord.linehaul.closelh').attr('disabled', false).removeClass('disabled');
        } else {
          $('.finalizeRecord.linehaul.closelh').attr('disabled', true).addClass('disabled');
        }

        var fetchMov = fetchMovements(data.lhid);

        fetchMov.done(function(result){
          var rsp = JSON.parse(result);
          if (rsp.query.code == 1) {
            $('#movement-list').html(rsp.data);
          }
        }).fail(function(jqXHR, textStatus, errorThrown){
          console.error(textStatus + ': ' + errorThrown);
        })

      } else {
        console.error(rsp.message);
        console.info(rsp);
      }
    });

    $('#linehaul-details').fadeIn().addClass("d-flex");
    $('.modal-sidebar-dialogue-right').animate({right: "0%"}, 650)
  })

  $('.saveLhChanges').click(function(){

    var parent_form = $(this).attr('form-parent');
    var dep = new Date($(parent_form).find('.departure.date').val());
    var arriv = new Date($(parent_form).find('.arrival.date').val());
    var delivery = new Date($(parent_form).find('.delivery.date').val());
    var appt = new Date($(parent_form).find('#appt').val());

    if (dep != 'Invalid Date') {
      if ($('#departure_time_hour').val() == "" || $('#departure_time_minute').val() == "") {
        $('#departure_time_hour').addClass('is-invalid');
        $('#departure_time_minute').addClass('is-invalid');
        $('#linehaulSavedNotice').html('Please include complete date and time for departure').addClass('alert-danger').removeClass('alert-success').fadeIn();
        setTimeout(function(){
          $('#linehaulSavedNotice').fadeOut();
        }, 3500);
        return false;
      } else {
        $('#departure_time_hour').removeClass('is-invalid');
        $('#departure_time_minute').removeClass('is-invalid');
      }
    }
    if (arriv != 'Invalid Date') {
      if ($('#arrival_time_hour').val() == "" || $('#arrival_time_minute').val() == "") {
        $('#arrival_time_hour').addClass('is-invalid');
        $('#arrival_time_minute').addClass('is-invalid');
        $('#linehaulSavedNotice').html('Please include complete date and time for arrival').addClass('alert-danger').removeClass('alert-success').fadeIn();
        setTimeout(function(){
          $('#linehaulSavedNotice').fadeOut();
        }, 3500);
        return false;
      } else {
        $('#arrival_time_hour').removeClass('is-invalid');
        $('#arrival_time_minute').removeClass('is-invalid');
      }
    }
    if (delivery != 'Invalid Date') {
      if ($('#delivery_time_hour').val() == "" || $('#delivery_time_minute').val() == "") {
        $('#delivery_time_hour').addClass('is-invalid');
        $('#delivery_time_minute').addClass('is-invalid');
        $('#linehaulSavedNotice').html('Please include complete date and time for delivery').addClass('alert-danger').removeClass('alert-success').fadeIn();
        setTimeout(function(){
          $('#linehaulSavedNotice').fadeOut();
        }, 3500);
        return false;
      } else {
        $('#delivery_time_hour').removeClass('is-invalid');
        $('#delivery_time_minute').removeClass('is-invalid');
      }
    }

    if   ((arriv.getTime() < dep.getTime()) && (arriv != 'Invalid Date')) {
      // alert("Arrival date cannot be after departure date!");
      swal("Oops!", "Arrival date cannot be before departure date!", "error");
      return false;
    }

    if (arriv.getTime() == dep.getTime()) {
      if (($('#arrival_time_hour').val() < $('#departure_time_hour').val()) && (arriv != 'Invalid Date')) {
        swal("Oops!", "Arrival time cannot be before departure time!", "error");
        return false;
      }
      if ($('#arrival_time_hour').val() == $('#departure_time_hour').val()) {
        if (($('#arrival_time_minute').val() < $('#departure_time_minute').val()) && (arriv != 'Invalid Date')) {
          swal("Oops!", "Arrival time cannot be before departure time!", "error");
          return false;
        }
      }
    }

    if   ((delivery.getTime() < arriv.getTime()) && (delivery != 'Invalid Date')) {
      // alert("Arrival date cannot be after departure date!");
      swal("Oops!", "Delivery date cannot be before arrival date!", "error");
      return false;
    }

    if (delivery.getTime() == arriv.getTime()) {
      if (($('#delivery_time_hour').val() < $('#arrival_time_hour').val()) && (delivery != 'Invalid Date')) {
        swal("Oops!", "Delivery time cannot be before arrival time!", "error");
        return false;
      }
      if ($('#delivery_time_hour').val() == $('#arrival_time_hour').val()) {
        if (($('#delivery_time_minute').val() < $('#arrival_time_minute').val()) && (delivery != 'Invalid Date')) {
          swal("Oops!", "Delivery time cannot be before arrival time!", "error");
          return false;
        }
      }
    }



    trip = {
      year: $(this).attr('tripyear'),
      id: $(this).attr('tripid'),
    }

    data = {
      lid: $(parent_form).find('.linehaulid').val(),
      broker: $(parent_form).find('.broker').attr('db-id'),
      broker_reference: $(parent_form).find('.broker_reference').val(),
      triprate: $(parent_form).find('.rate').val(),
      ozip: $(parent_form).find('.origin_zip').val(),
      ostate: $(parent_form).find('.origin_state').val(),
      ocity: $(parent_form).find('.origin_city').val(),
      dzip: $(parent_form).find('.destination_zip').val(),
      dstate: $(parent_form).find('.destination_state').val(),
      dcity: $(parent_form).find('.destination_city').val(),
      empty_miles: $(parent_form).find('.empty_miles').val(),
      lodaded_miles: $(parent_form).find('.loaded_miles').val(),
      total_miles: $(parent_form).find('.total_miles').val(),
      rpm: $(parent_form).find('.rpm').val(),
      status: $(parent_form).find('.lh_status').val(),
      departure: {
        date: $(parent_form).find('.departure.date').val(),
        time: {
          hour: $(parent_form).find('.departure.hour').val(),
          minute: $(parent_form).find('.departure.minute').val()
        }
      },
      arrival: {
        date: $(parent_form).find('.arrival.date').val(),
        time: {
          hour: $(parent_form).find('.arrival.hour').val(),
          minute: $(parent_form).find('.arrival.minute').val()
        }
      },
      delivery: {
        date: $(parent_form).find('.delivery.date').val(),
        time: {
          hour: $(parent_form).find('.delivery.hour').val(),
          minute: $(parent_form).find('.delivery.minute').val()
        }
      },
      appt: {
        date: $(parent_form).find('.appointment.date').val(),
        time: {
          hour: $(parent_form).find('.appointment.hour').val(),
          minute: $(parent_form).find('.appointment.minute').val()
        }
      }
    }

    if (data.departure.date != '' && data.status != 'Closed') {
      data.status = "Open";
    }

    if (data.arrival.date != '' && data.status != 'Closed') {
      data.status = "Pending Delivery"
    }

    if (data.delivery.date != '' && data.status != 'Closed') {
      data.status = "Pending Closure"
    }

    console.log(data);
    var updateLinehaul = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/updateLinehaul.php'
    });



    updateLinehaul.done(function(result){
      rsp = JSON.parse(result);
      console.log(rsp);
      if (rsp.code == 1) {
        $('.linehaulSavedNotice').html('Linehaul updated successfully').addClass('alert-success').removeClass('alert-danger').fadeIn();
        show_lh_details($('#linehaulid').val());

        setTimeout(function(){
          $('.linehaulSavedNotice').fadeOut();
        }, 3500);
      } else if (rsp.code == 2) {
        console.error(rsp);
        $('.linehaulSavedNotice').html('No changes were detected.').addClass('alert-danger').removeClass('alert-success').fadeIn();
        setTimeout(function(){
          $('.linehaulSavedNotice').fadeOut();
        }, 3500);
      } else {
        console.error("Error with query[" + rsp.code + "]: " + rsp.message);
      }
    }).fail(function(jqXHR, textStatus, errorThrown) {
    // If fail
    console.log(textStatus + ': ' + errorThrown);
    });
  });

  $('.close-sidebar').click(function(e){
    if (event.target !== event.currentTarget) {
      return
    }
    $('.modal-sidebar-dialogue-right').animate({right: "-60%"}, 650)
    $('.modal-sidebar-dialogue-left').animate({left: "-40%"}, 650)
    $('#linehaul-details').fadeOut(function(){
      $('#linehaul-details').removeClass("d-flex")
    });
    $()
  })

  $('#addMovementBtn').click(function(){
    $('.modal-sidebar-dialogue-left').animate({left: "0%"}, 650)

    $('#movementHeader').html("Add Movement");
    $('.submitMovement').html("Add Movement");
    $('#mvneworedit').val("1").attr('value', '1').prop('value', '1');
    $('#newMovementF').find('.teamDriverCheck').attr('checked', false).change();
    $('#newMovementF').find('input[type=text]').val('');
    $('#newMovementF').find('[name=movement_type][value="E"]').prop('checked', true);
    $('#newMovementF').find('[db-id]').attr('db-id', '');
  })

  $('.close-left-sidebar').click(function(e){
    $('.modal-sidebar-dialogue-left').animate({left: "-40%"}, 650)
  })

  $('.popup-input').keyup(function(e){
    if (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 13 || e.keyCode === 9){return false;}
    if ($(this).attr('readonly')) {return false;}
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
    $("[id-display='#" + inputTarget+ "']").attr("value", $(this).html()).attr('db-id', $(this).attr('db-id'));
    $("[id-display='#" + inputTarget+ "']").prop("value", $(this).html()).change();
    $('.popup-list').slideUp();

  });

  $('.popup-list').on('mouseenter', 'p', function(){
    $('.hovered').attr('class', '');
    $(this).attr('class', 'hovered');
  });

  $('.popup-list').on('mouseleave', 'p', function(){
    $(this).attr('class', '')
  });

  $('.teamDriverCheck').change(function(){
    valid = $(this).prop('checked');
    target = $(this).attr('target');

    if(valid){
      $(target).fadeIn();
    } else {
      $(target).fadeOut();
    }
  })

  $('#getMiles').click(function(){
    origZ = $('#modaloZip').val();
    origS = $('#modaloState').val();
    origC = $('#modaloCity').val();

    destZ = $('#modaldZip').val();
    destS = $('#modaldState').val();
    destC = $('#modaldCity').val();

    // origin = "zip " + origZ;
    // destination = "zip " + destZ;
    //
    // console.log("Origin: " + origin);
    // console.log("destination: " + destination);
    //
    // console.warn("Starting calculation!!");
    // $('body').append("<div class='overlay d-flex align-items-center' style='z-index: 2000'><div class='overlay-loading text-center d-flex align-items-center'><p><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i></p><p>Calculating miles per state, please wait...</p><div></div>")
    // calculateRoute(origin, destination, 1);
  })

  $('.addMovement').click(function(){
    amount = $('.extra.movement').length;

    if (amount == 0) {
      space = $(this).parent();
    } else {
      space = $('.extra.movement:last')
    }

    space = $('#sortableMovements');


    toAdd = "<div class='form-group row extra movement'><label for='' class='col-form-label col-3 text-right'><i class='fa fa-ban mr-2 text-danger deleteExtraMovement' role='button'></i>Extra Movement</label><div class='form-group col-3'><input class='form-control zipInput' type='text' autocomplete='new-password' name='dZip' id='modaldZip' value='' placeholder='Zip Code'></div><div class='form-group col-2'><input class='form-control stateInput disabled' disabled type='text' autocomplete='new-password' name='dSt' id='modaldState' value='' placeholder='State'></div><div class='form-group col-3'><input class='form-control cityInput disabled' disabled type='text' autocomplete='new-password' name='dCity' id='modaldCity' value='' placeholder='City'></div><div class='form-group col-1'><input type='text' class='googleMiles' exists='' name='' value='' hidden><input type='text' class='existsInDatabase' exists='' name='' value='' hidden><select class='form-control movement_type' name=''><option value='E'>Empty</option><option value='L' selected>Loaded</option></select></div></div>";




    space.append(toAdd);
    $('.deleteExtraMovement').unbind().click(function(){
      $(this).parents('.extra.movement').remove();
    });

    getCityStateListener();
  });

  $('#calculateMiles').click(function(){
    var zipCodes = []
    $('.movement').find('.zipInput').each(function(){
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
        loadingScreen(message)
      },
      url: 'actions/findRoute.php'
    });

    calculateMiles.done(function(result){
      $('.overlay').remove();
      rsp = JSON.parse(result);
      $('#googleMiles').val(rsp.totalMiles);
      if ($('.linehaulRate').val() != "") {
        $('.ratepermile').val(Math.round(($('.linehaulRate').val() / rsp.totalMiles)*100)/100);
      }

      var allZipInputs = $('.movement').find('.zipInput');

      for (var key in rsp.routes) {
        if (rsp.routes.hasOwnProperty(key)) {
          if (rsp['routes'][key]['route_code'] != 2) {
            break;
          }
          routeCode = rsp['routes'][key]['route_code'];
          routeDestination = rsp['routes'][key]['destination'];
          routeDistance = rsp['routes'][key]['distance'];
          allZipInputs.each(function(){
            if ($(this).val() == routeDestination) {
                var dads = $(this).parents('.movement.row');
                dads.find('.googleMiles').val(routeDistance);
                dads.find('.existsInDatabase').val('false');
                // dads.find('label, input').addClass('is-invalid').addClass('text-danger');
            }
          })
        }
      }

    }).fail(function(jqXHR, textStatus, errorThrown){
      console.error(textStatus + ': ' + errorThrown);
    })

  })

  $('.linehaulRate').keyup(function(){
    var rpm = ($(this).val() / $('#googleMiles').val()).toFixed(2);
    $('.ratepermile').val(rpm);
  })

  $('#sortableMovements').sortable();

  $('#addLinehaulSubmit').click(function(){

    var zipCodes = [];
    $('.movement').find('.zipInput').each(function(){
      if ($(this).val() == 0 || $(this).val() == undefined) {
        cont = false;
        return false;
      }
      cont = true;
      zipCodes.push($(this).val());
    });

    var movArray = [];
    $('.movement').each(function(){
      movArray.push($(this));
    });

    if (!(cont)) {
      return false;
    }
    //zipCodes = JSON.stringify(zipCodes);
    message = "Adding trip, please wait...";

    linehaul = {
      origin: {
        zip: $('.origin').find('.zipInput').val(),
        city: $('.origin').find('.cityInput').val(),
        state: $('.origin').find('.stateInput').val()
      },
      destination: {
        zip: $('.destination').find('.zipInput').val(),
        city: $('.destination').find('.cityInput').val(),
        state: $('.destination').find('.stateInput').val()
      },
      broker: $('.brokerid').attr('db-id'),
      broker_reference: $('#broker-reference').val(),
      tripid: $('#tripid').val(),
      tripyear: $('#tripyear').val(),
      rate: $('.linehaulRate').val(),
      rpm: $('.ratepermile').val()
    }

    movements = {}

    for (var i = 0; i < movArray.length; i++) {
      var zip = movArray[i].find('.zipInput').val();
      var mov_city = movArray[i].find('.cityInput').val();
      var mov_state = movArray[i].find('.stateInput').val();
      var exists = movArray[i].find('.existsInDatabase').val();
      var miles = movArray[i].find('.googleMiles').val();
      var mov_type = movArray[i].find('.movement_type').val();

      movements[i] = {
        zip_code: zip,
        city: mov_city,
        state: mov_state,
        route_exists: exists,
        google_miles: miles,
        type: mov_type
      }
    }

    conveyance = {
      tractorid: $('.truckid').attr('db-id'),
      driver: {
        id: $('.driverid').attr('db-id'),
        name: $('.driverid').val()
      },
      team:{
        id: $('.teamdriverid').attr('db-id'),
        name: $('.teamdriverid').val()
      }
    }

    data = {
      linehaul, conveyance, movements
    }

    // for (var key in movements) {
    //   if (movements.hasOwnProperty(key)) {
    //     if (key == 0) {
    //       console.log("It broke!!!!");
    //       continue;
    //     }
    //     if (movements[key]['route_exists'] === false) {
    //       continue;
    //     } else {
    //       // var prev = Math.
    //       var prev = key - 1;
    //       var orig = "Zip " + movements[prev]['zip_code'];
    //       var destin = "Zip " + movements[key]['zip_code'];
    //     }
    //   }
    // }

    $('.overlay').remove();



    var addLinehaul = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/addNewLinehaul.php'
    });

    addLinehaul.done(function(result){
      rsp = JSON.parse(result);
      if (rsp.query.code == 1) {
        //console.log(rsp);
        location.reload()
      } else {
        swal({
          title: "Trip not added :(",
          text: rsp.query.message,
          icon: 'error'
        });
      }
    })


  });

  $('.new_movement').blur(function(){
    zipCodes = [];

    el = $(this)
    var txt = el.val();

    message = "Loading..."

    var getCityState = $.ajax({
      method: 'POST',
      beforeSend: function(){
        loadingScreen(message);
      },
      data: {txt: txt},
      url: 'actions/fetchCityState.php',
      async: false
    });

    getCityState.done(function(result){
      rsp = JSON.parse(result);
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
    });

    $('.new_movement').each(function(index){
      if ($(this).val() == "") {
        cont =  false;
      } else {
        zipCodes.push($(this).val());
        cont = true;
      }
    })

    if (cont) {
      message = "Calculating total distance, please wait...";
      zipCodes = JSON.stringify(zipCodes);
      var calculateMiles = $.ajax({
        method: 'POST',
        data: {zips: zipCodes},
        beforeSend: function(){
          loadingScreen(message)
        },
        url: 'actions/findRoute.php'
      });

      calculateMiles.done(function(result){
        $('.overlay').remove();
        rsp = JSON.parse(result);
        $('.imMiles').val(rsp.totalMiles);
      }).fail(function(jqXHR, textStatus, errorThrown){
        console.error(textStatus + ': ' + errorThrown);
      })

    }


  });

  $('.submitMovement').click(function(){

    var data = {
      lhid: $('#linehaulid').val(),
      ozip: $('#newMovementF').find('.origin.new_movement').val(),
      ocity: $('#newMovementF').find('.origin.cityInput').val(),
      ostate: $('#newMovementF').find('.origin.stateInput').val(),
      dzip: $('#newMovementF').find('.dest.new_movement').val(),
      dcity: $('#newMovementF').find('.dest.cityInput').val(),
      dstate: $('#newMovementF').find('.dest.stateInput').val(),
      miles: $('#newMovementF').find('.imMiles').val(),
      type: $('#newMovementF').find('[name=movement_type]:checked').val(),
      extra_stop: $('#newMovementF').find('[name=extra_stop]:checked').val(),
      eal: $('#newMovementF').find('[name=empty_as_loaded]:checked').val(),
      truck: $('#newMovementF').find('.truckInput').attr('db-id'),
      driver: $('#newMovementF').find('.driverInput').attr('db-id'),
      team: $('#newMovementF').find('.teamInput').attr('db-id'),
    }

    if ($('#mvneworedit').attr('value') == 1) {
      url_to_exec = 'actions/addMovement.php';
    } else {
      url_to_exec = 'actions/updateMovement.php';
      data.mvid = $('#mvid').val();
    }
    var submitMov = $.ajax({
      method: 'POST',
      data: data,
      url: url_to_exec
    });

    submitMov.done(function(result){
      var rsp = JSON.parse(result);
      if (rsp.query.code == 1) {
        $('.modal').modal('hide');
        show_lh_details(data.lhid);
      } else {
        console.error("Error: " + rsp.query.message);
      }
    }).fail(function(jqXHR, textStatus, errorThrown){
      console.log(textStatus + ': ' + errorThrown);
    });

    // fetchMov.done(function(mvresult){
    //   var mv = JSON.parse(mvresult);
    //   if (mv.query.code == 1) {
    //     console.log(mv);
    //     $('#movement-list').html(mv.data);
    //   }
    // }).fail(function(jqXHR, textStatus, errorThrown){
    //   console.log(textStatus + ': ' + errorThrown);
    // })

  })

  $('#movement-list').delegate('tr', 'click', function(){
    var data = {
      mvid: $(this).attr('db-id')
    }

    var movement = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/fetchMovement.php'
    });

    movement.done(function(result){
      var rsp = JSON.parse(result);
      if (rsp.query.code == 1) {
        $('#movementHeader').html("Movement Details");
        $('.submitMovement').html("Save Changes");
        $('#mvneworedit').val("2").attr('value', '2').prop('value', '2');
        $('#mvid').val(data.mvid);
        $('#newMovementF').find('.origin.new_movement').val(rsp.data.ozip);
        $('#newMovementF').find('.origin.cityInput').val(rsp.data.ocity);
        $('#newMovementF').find('.origin.stateInput').val(rsp.data.ostate);
        $('#newMovementF').find('.dest.new_movement').val(rsp.data.dzip);
        $('#newMovementF').find('.dest.cityInput').val(rsp.data.dcity);
        $('#newMovementF').find('.dest.stateInput').val(rsp.data.dstate);
        $('#newMovementF').find('.imMiles').val(rsp.data.miles);
        $('#newMovementF').find('[name=movement_type][value='+rsp.data.type+']').prop('checked', true);
        $('#newMovementF').find('[name=extra_stop][value='+rsp.data.extra_stop+']').prop('checked', true);
        $('#newMovementF').find('[name=empty_as_loaded][value='+rsp.data.eal+']').prop('checked', true);
        $('#newMovementF').find('.truckInput').val(rsp.data.truck_number).attr('db-id', rsp.data.truckid);
        $('#newMovementF').find('.driverInput').val(rsp.data.driver).attr('db-id', rsp.data.driverid);
        if (!(rsp.data.teamid == "" || rsp.data.teamid == undefined)) {
          $('#newMovementF').find('.teamDriverCheck').attr('checked', true).change();
          $('#newMovementF').find('.teamInput').val(rsp.data.team_driver).attr('db-id');
        } else {
          $('#newMovementF').find('.teamDriverCheck').attr('checked', false).change();
        }
        $('.modal-sidebar-dialogue-left').animate({left: "0%"}, 650)
      } else {
        console.error("Error while loading movement.");
      }
    })


  });

  $('#addLinehaulForm').find('input').change(function(){
    var ct = $(this);
    var form_inputs = $('#addLinehaulForm').find('input').not($('.teamdriverid, .existsInDatabase, .googleMiles, .hidden, [type=checkbox], .disabled'));
    valid = validateForm(form_inputs, ct);
    if (valid) {
      $('#addLinehaulSubmit').removeClass('disabled').attr('disabled', false);
    } else {
      $('#addLinehaulSubmit').addClass('disabled').attr('disabled', true);
    }
  });

  $('#newMovementF').find('input').change(function(){
    var form_inputs = $('#newMovementF').find('input').not($('.teamdriverid, .existsInDatabase, .teamInput, .googleMiles, .hidden, [type=checkbox], [type=radio]'));
    valid = validateForm(form_inputs);
    if (valid) {
      $('#newMovBtn').removeClass('disabled').attr('disabled', false);
    } else {
      $('#newMovBtn').addClass('disabled').attr('disabled', true);
    }
  });

  $('.finalizeRecord').click(function(){

    var parent_form = $(this).attr('form-parent');

    var data = {
      record_to_edit: $(this).attr('type-of-record'),
      id: $(this).attr('recordid'),
      year: $(this).attr('tripyear'),
      action: $(this).attr('action')
    }

    // if ($('#departure_time').val() == "" && data.record_to_edit == 'linehaul') {
    //   data.action = "Pending";
    // }


    var warnText = "";

    var remove_btn = $(this);

    switch (data.action) {
      case "Cancelled":
        warnText = "This will change the linehaul status to cancelled!";
        break;
      case "Closed":
        warnText = "No edits will be possible after closing the " + data.record_to_edit;
        break;
      case "Open":
        warnText = "This will change the linehaul status to Open";
        break;
      case "Pending":
        warnText = "This will change the linehaul status to Pending";
        break;
      default:
    }

    swal({
      title: "Are you sure?",
      text: warnText,
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        var finalizeRecord = $.ajax({
          method: 'POST',
          url: 'actions/finalizeRecord.php',
          data: data
        });
        finalizeRecord.done(function(result){
          rsp = JSON.parse(result);
          if (rsp.code == 1) {
            if (data.record_to_edit == 'linehaul') {
              $(remove_btn).fadeOut();
              $(parent_form).find('.saveLhChanges').fadeOut();
              // $('#addMovementBtn').fadeOut();
              // $('#lh-details-status').html(data.action);
            } else if (data.record_to_edit == 'trip') {
              $('[data-target="#addLinehaulModal"]').addClass('disabled').attr('disabled', true);
            }
            location.reload();
          } else {
            swal({
              title: "Record was not closed :(",
              text: rsp.message,
              icon: 'error'
            })
          }
        }).fail(function(jqXHR, textStatus, errorThrown){
          console.log(textStatus + ': ' + errorThrown);
        });
      } else {

      }
    });
  })

  $('#delivery_time, #delivery_time_hour, #delivery_time_minute').change(function(){
    var date = $('#delivery_time').val() == "";
    var hour = $('#delivery_time_hour').val() == "";
    var min = $('#delivery_time_min').val() == "";

    if (date && hour && min) {
      $('#arrival_time').val($('#delivery_time').val());
      $('#arrival_time_hour').val($('#delivery_time_hour').val());
      $('#arrival_time_minute').val($('#delivery_time_minute').val());
    }

  })

  $('#summary-dash').on('click', 'tr', function(){
    show_lh_details($(this).attr('lhid'));
  });

  $('#show-trip').click(function(){
    show_trip_info();
  })

  $('#enable-editing').click(function(){
    $('#lh-edit-disabled').fadeOut(function(){
      $('#lh-fields').attr('disabled', false);
      $('#cancel-editing').fadeIn();
      $('#lh-edit-enabled').fadeIn();
    })
  });

  $('#cancel-editing-button').click(function(){
    $('#lh-edit-enabled').fadeOut(function(){
      $('#lh-fields').attr('disabled', true);
      $('#cancel-editing').fadeOut();
      $('#lh-edit-disabled').fadeIn();
    })
  });

  $('.enable-lh-editing').click(function(){
    var parent_form = $(this).attr('form-parent');
    $(parent_form).find('input, select').removeClass('disabled').attr('disabled', false);
    $(this).fadeOut(function(){$('.cancel-lh-editing, .saveLhChanges.edit').fadeIn()})
  })

  $('.cancel-lh-editing').click(function(){
    $('.cancel-lh-editing, .saveLhChanges.edit').fadeOut(function(){$('.enable-lh-editing').fadeIn()})
    var parent_form = $(this).attr('form-parent');
    $(parent_form).find('input, select').addClass('disabled').attr('disabled', true);
  })

  $('[id-display="#driver-popup-list-modal"]').change(function(){
    data = {
      driver_id: $(this).attr('db-id')
    }

    var defaultTruck = $.ajax({
      method: 'POST',
      url: 'actions/fetchDefaultTruck.php',
      data: data
    });

    defaultTruck.done(function(result){
      rsp = JSON.parse(result);

      if (rsp.code == 1) {
        $('[id-display="#truck-popup-list-modal"]').attr('db-id', rsp.data.truck_id).val(rsp.data.truck_number);
      } else {
        console.log(rsp.message);
      }
    })
  })

  $('.collapse').on('show.bs.collapse', function(){
    var tgt = '#' + $(this).attr('aria-labelledby');
    $(tgt).removeClass('bg-white');
  })

  $('.collapse').on('hide.bs.collapse', function(){
    var tgt = '#' + $(this).attr('aria-labelledby');
    $(tgt).addClass('bg-white');
  })

  $('.add-movement').click(function(){
      $('.header-nm').html('Add Movement');
      $('.submitMovement').html("Add Movement");
      $()
      "linehaulid"
      $('#mvneworedit').val("1").attr('value', '1').prop('value', '1');
      $('#addMovementLh').modal('show');
  });

  $('#mov-dash').on('click', 'tr', function(){

    $('.header-nm').html("Movement Details");
    $('.submitMovement').html("Save Changes");
    $('#mvneworedit').val("2").attr('value', '2').prop('value', '2');

    var data = {
      mvid: $(this).attr('db-id')
    }

    var movement = $.ajax({
      method: 'POST',
      data: data,
      url: 'actions/fetchMovement.php'
    });

    movement.done(function(result){
      var rsp = JSON.parse(result);
      if (rsp.query.code == 1) {
        $('#movementHeader').html("Movement Details");
        $('.submitMovement').html("Save Changes");
        $('#mvneworedit').val("2").attr('value', '2').prop('value', '2');
        $('#mvid').val(data.mvid);
        $('#newMovementF').find('.origin.new_movement').val(rsp.data.ozip);
        $('#newMovementF').find('.origin.cityInput').val(rsp.data.ocity);
        $('#newMovementF').find('.origin.stateInput').val(rsp.data.ostate);
        $('#newMovementF').find('.dest.new_movement').val(rsp.data.dzip);
        $('#newMovementF').find('.dest.cityInput').val(rsp.data.dcity);
        $('#newMovementF').find('.dest.stateInput').val(rsp.data.dstate);
        $('#newMovementF').find('.imMiles').val(rsp.data.miles);
        $('#newMovementF').find('[name=movement_type][value='+rsp.data.type+']').prop('checked', true);
        $('#newMovementF').find('[name=extra_stop][value='+rsp.data.extra_stop+']').prop('checked', true);
        $('#newMovementF').find('[name=empty_as_loaded][value='+rsp.data.eal+']').prop('checked', true);
        $('#newMovementF').find('.truckInput').val(rsp.data.truck_number).attr('db-id', rsp.data.truckid);
        $('#newMovementF').find('.driverInput').val(rsp.data.driver).attr('db-id', rsp.data.driverid);
        if (!(rsp.data.teamid == "" || rsp.data.teamid == undefined)) {
          $('#newMovementF').find('.teamDriverCheck').attr('checked', true).change();
          $('#newMovementF').find('.teamInput').val(rsp.data.team_driver).attr('db-id');
        } else {
          $('#newMovementF').find('.teamDriverCheck').attr('checked', false).change();
        }
        $('.modal-sidebar-dialogue-left').animate({left: "0%"}, 650)
      } else {
        console.error("Error while loading movement.");
      }

      $('#newMovementF').find('.is-valid').removeClass('is-valid');
      $('#newMovementF').find('.is-invalid').removeClass('is-invalid');
      $('#addMovementLh').modal('show');
    });


  });

  getCityStateListener();

})

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
