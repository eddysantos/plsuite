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
  // var template = $("<div class='movement'><p class='d-inline'><span class='origin'></span><p class='d-inline'> - </p><p class='d-inline'><span class='destination'></span></span> (<span class='distance'></span> <span class='mov-type'></span> Miles)</p></div>");
  var template = $("<div class='movement row'><div class='origin col-sm-6'></div><div class='col-sm-6'><span class='destination'></span>(<span class='distance'></span> <span class='mov-type'></span> Miles)</div></div>");

  var html_movement = $("<div><div class='row'><div class='col-sm-6 text-center text-dark'>Origin</div><div class='col-sm-6 text-center text-dark'>Destination</div></div></div>");

  for (var movement in movements) {
    if (movements.hasOwnProperty(movement) && movement != 'total_distance') {

      origin = movements[movement].origin.address;
      origin_attributes = movements[movement].origin.attributes;
      destination = movements[movement].destination.address;
      destination_attributes = movements[movement].destination.attributes;

      distance = movements[movement].distance;

      template.find('.origin').html(origin);
      for (var attribute in origin_attributes) {
        if (origin_attributes.hasOwnProperty(attribute)) {
          template.find('.origin').attr(attribute, origin_attributes[attribute]);
        }
      }
      template.find('.destination').html(destination);
      for (var attribute in origin_attributes) {
        if (destination_attributes.hasOwnProperty(attribute)) {
          template.find('.destination').attr(attribute, destination_attributes[attribute]);
        }
      }
      template.find('.distance').html(distance);
      template.find('.mov-type').html(origin_attributes.mov_type);
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

function catchLocation(id = 'testvalue', change_place = true){


  if (change_place) {
    var place = google_autocompletes[id].goo_ac.getPlace();

    var componentForm = {
      street_number: 'short_name',
      route: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'short_name',
      country: 'long_name',
      postal_code: 'short_name'
    };
    var address_fields = {};
    var mov_type = "movement";

    for (var i = 0; i < place.address_components.length; i++) {
      var address_type = place.address_components[i].types[0];
      if (componentForm[address_type]) {
        var value = place.address_components[i][componentForm[address_type]];
        address_fields[address_type] = value;
      }
    }

    $('#' + id).val(place.formatted_address).attr('value', place.formatted_address).prop('value', place.formatted_address);

    for (var component in address_fields) {
      if (address_fields.hasOwnProperty(component)) {
        $('#' + id).attr(component, address_fields[component]);
      }
    }
  }

  $('#movement-details').trigger('calculate-distances');

}

$(document).ready(function(){

  /** This listeners control the popup snippet to select trailers, drivers, tractors and brokers.  **/
  $('.popup-input').keydown(function(e){
    if (e.keyCode === 13 || e.keyCode === 9) {
      // e.preventDefault();
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

  $(function () { //Initialize required libraries widgets.
    $('[data-toggle="tooltip"]').tooltip()
    $('#movement-details').sortable({
    items: "> tr",
    stop: function(){
      $('#movement-details').trigger('calculate-distances');
      $('#movement-details').trigger('flag-od');
    }
  });
  })



  /** This listeners control the inputs validation and modifications that need to happen during the new trip entry. **/

  $('#movement-details').on('change', '[name="na-appt"]', function(){
    var na = this.checked;

    if (na) {
      $(this).parents('td').find('.appt').attr('disabled', true).addClass('disabled');
    } else {
      $(this).parents('td').find('.appt').attr('disabled', false).removeClass('disabled');
    }

  });
  $('#movement-details').on('change', '[name="origin-flag"], [name="destination-flag"]', function(){
    var checked = this.checked;
    var name = this.name;
    var remove_od_flag = "";

    if (!checked) {
      return false;
    }

    switch (name) {
      case 'origin-flag':
        remove_od_flag = $(this).parents('tr').find('[name="destination-flag"]');
        break;

      case 'destination-flag':
        remove_od_flag = $(this).parents('tr').find('[name="origin-flag"]');
        break;
      default:
    }

    remove_od_flag.prop('checked', false);

  });
  $('#add-location').click(function(){
      var tr = '<tr id="ui-id-1" class="ui-sortable-handle"><td><i class="" data-fa-i2svg=""><svg class="svg-inline--fa fa-sort fa-w-10" aria-hidden="true" data-prefix="fas" data-icon="sort" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M41 288h238c21.4 0 32.1 25.9 17 41L177 448c-9.4 9.4-24.6 9.4-33.9 0L24 329c-15.1-15.1-4.4-41 17-41zm255-105L177 64c-9.4-9.4-24.6-9.4-33.9 0L24 183c-15.1 15.1-4.4 41 17 41h238c21.4 0 32.1-25.9 17-41z"></path></svg></i></td><td><span class="origin-destination-flag"><span></td><td> <input type="text" class="form-control form-control-sm google-location-input" name="" value="" placeholder="Enter a location" autocomplete="off"> </td><td class="mov-type-td"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="mov-type-ui-id-1" value="E"><label class="form-check-label" for="inlineRadio1">E</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="mov-type-ui-id-1" value="L"><label class="form-check-label" for="inlineRadio1">L</label></div></td><td><select class="form-control form-control-sm" name="eal"><option value="Yes">Yes</option><option value="No" selected="">No</option></select></td><td class="appt-td"><div class="form-group m-0 form-inline"><div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="na-appt" value="NA"><label class="form-check-label" for="na-appt">N/A</label></div><input type="date" class="form-control form-control-sm appt" name="appt-date" value=""><div class=""><select class="form-control form-control-sm ml-1 appt" name="appt-from-hour"><option value="">Hrs</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option></select> : <select class="form-control form-control-sm appt" name="appt-from-min"><option value="">Mins</option><option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option></select> - <select class="form-control form-control-sm appt" name="appt-to-hour"><option value="">Hrs</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option></select> : <select class="form-control form-control-sm appt" name="appt-to-min"><option value="">Mins</option><option value="00">00</option><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option><option value="50">50</option><option value="55">55</option></select></div></div></td><td><div class="form-control form-control-sm readonly distance" value=""></div></td><td><i class="text-danger remove-row" data-fa-i2svg=""><svg class="svg-inline--fa fa-times fa-w-11" aria-hidden="true" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" data-fa-i2svg=""><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></i></td></tr>'

      tr = $(tr);

      tr.attr('id', '');
      tr.uniqueId();

      var id = tr.attr('id');
      tr.find('[name^="mov-type"]').attr('name', 'mov-type-' + id);
      var gi = tr.find('.google-location-input');
      gi.uniqueId();

      var html = gi[0];
      google_autocompletes[gi.attr('id')] = {
        id: gi.attr('id'),
        goo_ac: new google.maps.places.Autocomplete(html, google_autocomplete_options)
      }
      google_autocompletes[gi.attr('id')].goo_ac.setFields(['address_components', 'formatted_address']);
      google.maps.event.addListener(google_autocompletes[gi.attr('id')].goo_ac, 'place_changed', function(){catchLocation(gi.attr('id'))});

      tr.appendTo('#movement-details');
      $('#movement-details').trigger('flag-od');

  });
  $('#movement-details').on('click', '.remove-row', function(){
    $(this).parents('tr').remove();
  });
  $('#movement-details').on('calculate-distances', function(){
    var locations = [];
    var id_order = {};
    var proceed = true;
    var distances = {};
    var panel = $(this);

    $(this).find('.google-location-input').each(function(){
      var id = this.id;
      var value = this.value;

      if (value == "") {
        proceed = false;
        return false;
      }

      locations.push(value);
      id_order[value] = id;
    });

    if (!proceed) {
      return false;
    }

    $(this).find('.distance').addClass('spinner-grow');

    $('body').prepend('<div class="remove-this" style="position: absolute; background-color: transparent; width: 100%; height: 100%; z-index: 9999"><div>');
    // $('.total_distance').html('<i class="fas fa-spinner fa-spin align-self-center"></i>');
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
      panel.find('.distance').first().removeClass('spinner-grow').val(0).html(0);
      for (var destination in route_details.destinations) {
        if (route_details.destinations.hasOwnProperty(destination)) {
          var dist = parseInt(Math.round(r.rows[destination].elements[destination].distance.value / 1609.34));
          var to_id = id_order[route_details.destinations[destination]];
          var distance_box = $('#' + to_id).parents('tr').find('.distance');
          distance_box.removeClass('spinner-grow').val(dist).html(dist);
          distances[to_id] = {
            distance: dist
          }
        }
      }
      console.log(distances);
      $('.remove-this').remove();

    });



  });
  $('#movement-details').on('flag-od', function(){
      var table = $(this);
      var first = table.find('tr').first();
      var last = table.find('tr').last();

      table.find('.origin-destination-flag').removeClass('text-danger text-success').html('');
      first.find('.origin-destination-flag').removeClass('text-danger text-success').addClass('text-danger').html('Origin');
      last.find('.origin-destination-flag').removeClass('text-danger text-success').addClass('text-success').html('Destination');
  });


  $('#trip--details--content').on('change', 'input, select', function(e){
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

    $('#trip-details-content .tab-pane.fade.active.show').find('input, select').filter(':not("[readonly]")').each(function(){

      var check = $(this).data('is-valid');
      console.log(check);
      if (check) {
        activate_next = true;
      } else {
        console.log("Next shlud be disabled");
        $(this).focus();
        activate_next = false;
        return false;
      }
    });

    if (activate_next) {
      $('.next-pane').removeClass('disabled').attr('disabled', false).focus();
    }

  }); //Disabled by changing the selector...
  $('.next-pane').click(function(){
    next_pane = $('#add_trip_progress')
      .find('.nav-link.active')
      .parent().next().find('a');
    next_pane.removeClass('disabled').tab('show');

  });
  $('a[data-toggle="tab"]').on('hide.bs.tab', function(e){
    var current = e.target.getAttribute('progress');
    var previous = e.relatedTarget.getAttribute('progress');
    console.log(previous);
    console.log(current);
    var prior = parseInt(previous) < parseInt(current);
    console.log(prior);

    if (prior) {
      return true;
    };

    id = $('.tab-pane.fade.active.show').attr('id');
    validation = {status: false};
    next_pane = $('#add_trip_progress')
      .find('.nav-link.active')
      .parent().next().find('a');
    $('#'+id).trigger('validate', [validation]);

    if (validation.status) {
      next_pane.attr('disabled', false)
      .removeClass('disabled')
      $('.progress-bar').css('width', next_pane.attr('progress') + "%");
      return true
    } else {
      next_pane.attr('disabled', true)
      .addClass('disabled')
      return false;
    }



  });

  $('.appointment.from.hour').change(function(){
    var hour = $('.appointment.from.hour').val();
    $('.appointment.to.hour').val(hour).change();
  });
  $('.appointment.from.minute').change(function(){
    var minute = $('.appointment.from.minute').val();
    $('.appointment.to.minute').val(minute).change();
  });

  //Pane validations.
  $('#lh-details-pane').on('validate', function(){

    // var origin_flag = $('[name="origin-flag"]');
    // var destination_flag = $('[name="destination-flag"]');
    var movement_type_tds = $('.mov-type-td');
    var locations = $('.google-location-input');
    var appts = $('.appt-td');
    // var of_validation, df_validation, loc_validation = false;
    var of_validation  = false;
    var df_validation = false;
    var loc_validation = true;
    var movement_type_validation = true;
    var appt_validation = true;

    //Validate origination bullets being selected.
    // origin_flag.each(function(){
    //   // console.log("Value: " + this.value);
    //   if (this.checked) {
    //     of_validation = true;
    //   }
    // });
    //
    // if (of_validation) {
    //   origin_flag.parents('td').removeClass('table-danger');
    // } else {
    //   origin_flag.parents('td').addClass('table-danger');
    // }


    //Validate destination bullets being selected.
    // destination_flag.each(function(){
    //   // console.log("Value: " + this.value);
    //   if (this.checked) {
    //     df_validation = true;
    //   }
    // });
    // if (df_validation) {
    //     destination_flag.each(function(){
    //       $(this).parents('td').removeClass('table-danger');
    //     });
    // } else {
    //     destination_flag.each(function(){
    //       $(this).parents('td').addClass('table-danger');
    //     });
    // }

    //Validate locations inputs being filled out properly.
    locations.each(function(){
      var location = this.value;

      if (location == "") {
        $(this).parents('td').addClass('table-danger');
      } else {
        $(this).parents('td').removeClass('table-danger');
      }
    });


    //Validate movement type fields.
    movement_type_tds.each(function(){
      if ($(this).find('input:hidden').length > 0) {
        $(this).removeClass('table-danger');
        return true;
      }

      var validate = $(this).find('input:checked').val();
      if (validate) {
        $(this).removeClass('table-danger')
      } else {
        movement_type_validation = false;
        $(this).addClass('table-danger')
      }
    });

    //Validate appointments fields.
    appts.each(function(){
      var na = $(this).find('[name="na-appt"]:checked').val();
      var fail = true;
      var inputs = $(this).find('.appt');

      if (na) {
        inputs.each(function(){
          $(this).removeClass('is-invalid');
        })
        $(this).removeClass('table-danger')
        return true;
      }

      inputs.each(function(){
        var val = this.value;
        if (val == "") {
          $(this).addClass('is-invalid');
          fail = false;
        } else {
          $(this).removeClass('is-invalid');
        }
      })

      if (fail) {
        $(this).removeClass('table-danger');
      } else {
        appt_validation = false;
        $(this).addClass('table-danger');
      }

    });

    if (loc_validation && movement_type_validation && appt_validation) {
      // console.log(true);
      validation.status = true;
    } else {
      // console.log(false);
      validation.status = false;
    }


  });
  $('#trip-details-pane').on('validate', function(){
    var validate = true;
    var inputs = $(this).find('input');

    inputs.each(function(){
      var dbid = $(this).attr('db-id');
      if (typeof dbid === 'undefined') {
        if (this.value == "") {
          validate = false;
          $(this).addClass('is-invalid');
        } else {
          $(this).removeClass('is-invalid')
        }
      } else {
        if (dbid == "") {
          $(this).addClass('is-invalid');
          validate = false;
        } else {
          $(this).removeClass('is-invalid')
        }
      }
    });

    validation.status = validate;

  });
  $('#conveyance-details-pane').on('validate', function(){
    var validate = true;
    var truck = $(this).find('.truckid').val() != "" && $(this).find('.truckid').attr('db-id') != "";
    var drivers = $(this).find('#listed-drivers').length > 0;

    if (!truck) {
      $(this).find('.truckid').addClass('is-invalid');
      validate = false;
    } else {
      $(this).find('.truckid').removeClass('is-invalid')
    }

    if (!drivers) {
      $(this).find('.driverid').addClas('is-invalid');
      validate = false;
    } else {
      $(this).find('.driverid').removeClass('is-invalid');
    }

    if (validate) {
      validation.status = true;
    }

  });



  $('#trip-confirmation-tab').on('show.bs.tab', function(){

        //Get all the information from the different panes.
    var tripDet = $('#trip-details-pane');
    var lhDet = $('#lh-details-pane');
    var convDet = $('#conveyance-details-pane');

    var truck = convDet.find('.truckid');
    var drivers = {};
      convDet.find('#listed-drivers').find('[db-id]').each(function(i){
        drivers[i] = {
          id: $(this).attr('db-id'),
          name:$(this).html(),
        }
      });
    var trailer = tripDet.find('.trailerid');
    var route = {};
    var totalMiles = 0;
    var rate = tripDet.find('.trip-rate');
    var broker = tripDet.find('.selected-broker');
    var reference = tripDet.find('.broker-reference');
    lhDet.find('#movement-details').find('tr').each(function(i){
      var origin_flag = $(this).find('[name=origin-flag]').prop('checked');
      var destination_flag = $(this).find('[name=destination-flag]').prop('checked');
      var id = this.id;
      var loc = $(this).find('.google-location-input');
      var location = {
        formatted: loc.val(),
        city: loc.attr('locality'),
        state: loc.attr('administrative_area_level_1'),
        country: loc.attr('country'),
        street_number: loc.attr('street_number'),
        street: loc.attr('route'),
        zip: loc.attr('postal_code')
      }
      var emptyLoaded = $(this).find('[name=mov-type-' + id + ']:checked').val();
      var eal = $(this).find('[name=eal]').val();
      var appt = {
        isna: $(this).find('[name=na-appt]').prop('checked'),
        date: $(this).find('[name=appt-date]').val(),
        from_hour: $(this).find('[name=appt-from-hour]').val(),
        from_min: $(this).find('[name=appt-from-min]').val(),
        to_hour: $(this).find('[name=appt-to-hour]').val(),
        to_min: $(this).find('[name=appt-to-min]').val(),
      }
      var miles = $(this).find('.distance').val();

      route[i] = {
        is_origin: origin_flag,
        is_dest: destination_flag,
        location: location,
        type: emptyLoaded,
        eal: eal,
        appt: appt,
        miles: miles
      }

      if (!isNaN(parseInt(miles))) {
        totalMiles += parseInt(miles);
      }
    });


    //Insert information into confirmation pane.
    var $this = $($(this).attr('href'));
    $this.find('.confirm-truck-number').attr('db-id', truck.attr('db-id')).html(truck.val()); //Truck data.
    $this.find('.confirm-truck-plates').html(truck.attr('plates'));
    $this.find('.confirm-trailer-number').attr('db-id', trailer.attr('db-id')).html(trailer.val()); //Truck data.
    $this.find('.confirm-trailer-plates').html(trailer.attr('plates'));

    $this.find('.confirm-driver-list').html('');

    for (var driver in drivers) {
      if (drivers.hasOwnProperty(driver)) {
        var confirmed_driver = $('<p></p>');
        confirmed_driver
          .addClass('mb-0')
          .html('<span><span>');
        confirmed_driver.find('span').attr('db-id', drivers[driver].id).html(drivers[driver].name);

        confirmed_driver.appendTo($this.find('.confirm-driver-list'));
      }
    }

    $this.find('#movement-confirmation').html('');
    for (var r in route) {
      if (route.hasOwnProperty(r)) {
          var this_route = $('<div class="row movement"></div>');
          this_route.append('<div class="col-md-6 address"></div>');
          this_route.append('<div class="col-md-3 appt"></div>');

          this_route.find('.address').html(route[r].location.formatted);
          if (!route[r].appt.isna) {
            this_route.find('.appt').html(
              route[r].appt.date + " " +
              route[r].appt.from_hour + ":" +
              route[r].appt.from_min + " - " +
              route[r].appt.to_hour + ":" +
              route[r].appt.to_min
            );
          } else {
            this_route.find('.appt').html("No appointment");
          }

          if (route[r].is_origin) {
            this_route.attr('mov-nature', 'origin');
          }

          if (route[r].is_dest) {
            this_route.attr('mov-nature', 'destination');
          }

          this_route.append('<div class="col-md-2"><span class="miles"></span> Miles</div>');
          this_route.find('.miles').html(route[r].miles);
          this_route.appendTo($this.find('#movement-confirmation'));

          //Add all the address elements to the row.

          for (var data_element in route[r].location) {
            if (route[r].location.hasOwnProperty(data_element)) {
              if (data_element == "formatted") {
                continue;
              }
              this_route.attr(data_element, route[r].location[data_element]);
            }
          }
      }
    }

    $this.find('.total-miles').html(totalMiles);
    $this.find('.trip-rate-confirmation').html(rate.val());
    $this.find('.rpm-confirmation').html(rate.val() / totalMiles);
    $this.find('.rpm-confirmation').html(Math.round((rate.val() / totalMiles) * 100)/100);
    $this.find('.brokerid-confirmation').html(broker.val()).attr('db-id', broker.attr('db-id'));
    $this.find('.broker-reference-confirmation').html(reference.val());


    $('.add-trip-buttons').show();
    $('.next-pane-buttons').hide();

  });
  $('#trip-confirmation-tab').on('hide.bs.tab',function(){
    $('.add-trip-buttons').hide();
    $('.next-pane-buttons').show();
  });
  $('.add-extra-stop').click(function(){
    var place_template = $('<div class="form-group row movement"><label for="" class="col-sm-2 col-form-label text-right"><i class="fas fa-times text-danger mr-2 remove-row" role="button"></i>Extra Stop</label><div class="col-lg-7"><input type="text" class="form-control google-location-input" name="" value=""></div><div class="col-lg-2"><select class="form-control mov-type"><option value="L" selected>Loaded</option><option value="E">Empty</option></select></div></div>');
    var id = "";
    var html = "";

    place_template.find('.google-location-input').uniqueId();
    id = place_template.find('.google-location-input').attr('id');
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
    var first_id = $('.google-location-input').first().attr('id');
    catchLocation(first_id, false);
  });
  $('#add_trip_progress').on('shown.bs.tab', '[data-toggle="tab"]', function(){
    $('#addLinehaulModal').find('.tab-pane.fade.active.show').find('input').first().focus();
  });
  $('.add-linehaul').click(function(){
    var source = $('#trip-confirmation-pane');
    var origin = source.find('[mov_type="origin"]');
    var destination = source.find('[mov_type="destination"]');
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
          from: {
            hour: $('#linehaul-appointment').find('.hour').html(),
            minute: $('#linehaul-appointment').find('.minutes').html(),
          },
          to:{
            hour: $('#linehaul-appointment').find('.to-hour').html(),
            minute: $('#linehaul-appointment').find('.to-minutes').html(),
          }
        },
        origin:{
          zip: origin.attr('postal_code'),
          city: origin.attr('locality'),
          state: origin.attr('administrative_area_level_1'),
          street: origin.attr('route'),
          street_number: origin.attr('street_number'),
          country: origin.attr('country'),
          formatted: origin.html(),
        },
        destination:{
          zip: destination.attr('postal_code'),
          city: destination.attr('locality'),
          state: destination.attr('administrative_area_level_1'),
          street: destination.attr('route'),
          street_number: destination.attr('street_number'),
          country: destination.attr('country'),
          formatted: destination.html(),
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

      var origin = $(this).find('.origin');
      var destination = $(this).find('.destination');

      data.linehaul.routes[i] = {
        origin:{
          zip: origin.attr('postal_code'),
          city: origin.attr('locality'),
          state: origin.attr('administrative_area_level_1'),
          street: origin.attr('route'),
          street_number: origin.attr('street_number'),
          country: origin.attr('country'),
          formatted: origin.html(),
        },
        destination:{
          zip: destination.attr('postal_code'),
          city: destination.attr('locality'),
          state: destination.attr('administrative_area_level_1'),
          street: destination.attr('route'),
          street_number: destination.attr('street_number'),
          country: destination.attr('country'),
          formatted: destination.html(),
        },
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
    google_autocompletes[id].goo_ac.setFields(['address_components', 'formatted_address']);
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
