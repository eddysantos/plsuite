$(document).ready(function() {

    /***** Google Library (Places) Code *******/
    let input = $('[google-autocomplete]');
    input.each(function(){
        thisInput = $(this);
        createGoogleAutocomplete(thisInput);
    });

    /******* General handlers *******/
    //Botón para confirmar la información del viaje.
    $('#confirmInfoBtn').click(function(){

      let tripData = {
        stops: {}
      };

      $('[trip-input]').each(function(){

        if (typeof $(this).data('dbid') != 'undefined') {
          tripData[this.name + '-id'] = $(this).data('dbid');
        }
        tripData[this.name] = this.value;
      });
      $('.trip-stop').each(function(i){
        let stopData = {}

        $(this).find('[stop-input]').each(function(){
          let elementType = $(this).get(0).tagName;

          if (elementType == "INPUT"|| elementType == "SELECT") {
            stopData[this.name] = this.value;
          } else {
            stopData[$(this).attr('name')] = $(this).html();
          }
        });

        tripData.stops[i] = stopData;
      });

      tripData.trip_rate = parseInt(tripData.trip_rate);

      console.log(tripData);

      let tripConfirmationStops = ``;
      let origins = [];
      let destinations = [];
      let totalDistance = 0;

      Object.values(tripData.stops).forEach((stop, i) => {

        let dateFrom = stop.appt_date_from + " " + stop.appt_hour_from + ":" + stop.appt_minute_from;
        let dateTo = stop.appt_date_to + " " + stop.appt_hour_to + ":" + stop.appt_minute_to;

        if (stop.miles) {
          totalDistance += parseInt(stop.miles);
        }

        tripConfirmationStops += `
          <div class="border-top bd-gray mt-2 pt-2"><b>${stop.stop_label}</b><span class="float-right"><span>${dateFrom}</span> to <span>${dateTo}</span></span></div>
          <b class="name m-0">${stop.location_name}</b>
          <div class="">
              <span class="street_number trip-input" name="street_number">${stop.street_number}</span> <span class="route trip-input">${stop.street_address}</span>
          </div>
          <div class="">
              <span class="locality trip-input" name="city">${stop.city}</span> <span class="administrative_area_level_1 trip-input" name="state">${stop.city}</span> <span class="postal_code trip-input" name="zip-code">${stop.zip_code}</span>
          </div>
          <div>
              <span class="country trip-input" name="country">${stop.country}</span>
          </div>
        `
      });

      tripData.totalDistance = totalDistance;

      if (totalDistance > 0) {
        tripData.rpm = tripData.trip_rate / totalDistance;
        tripData.rpm = Math.round((tripData.rpm + Number.EPSILON) * 100) / 100
      } else {
        tripData.rpm = 0;
      }



      let tripConfirmationData = `
        <div class="d-flex flex-column h-100">
          <div><b>Client:</b> <span>${tripData.client_name}</span></div>
          <div><b>Client Reference:</b> <span>${tripData.client_reference}</span></div>
          <div><b>Rate:</b>$ <span>${tripData.trip_rate}</span></div>
          <div><b>Miles:</b> <span class="miles">${totalDistance}</span></div>
          <div><b>RPM:</b>$ <span class="rpm">${tripData.rpm}</span></div>

          ${tripConfirmationStops}

          <div class="border-top bd-gray mt-1 pt-1"><b>Trailer:</b> <span>${tripData.trailer}</span></div>
          <div class=""><b>Tractor:</b> <span>${tripData.tractor}</span></div>
          <div class=""><b>Driver:</b> <span>${tripData.driver}</span></div>
          <div class=""><b>Team Driver:</b> <span>${tripData.team_driver}</span></div>
        </div>
      `

      Metro.dialog.create({
        title: 'Confirm Trip Information',
        content: tripConfirmationData,
        actions: [
          {
            caption: 'Add Trip',
            cls: 'js-dialog-close alert',
            onclick: function(){alertify.message('Add trip.'); console.log(tripData);}
          },
          {
            caption: 'Cancel',
            cls: 'js-dialog-close',
            onclick: function(){alertify.message('Did not add trip.')}
          },
        ],
        removeOnClose: true,
        width: 650
      });

    });

    /******* Client Information Handlers *********/
    $('#clientInput').autocomplete({
      serviceUrl: 'actions/clients/fetchListPopup.php',
      type: 'POST',
      onSelect: function (suggestion) {
        $(this).data('dbid', suggestion.data);
      }
    });
    $('[driver-input]').autocomplete({
      serviceUrl: 'actions/driver/fetchListPopup.php',
      type: 'POST',
      onSelect: function (suggestion) {
        $(this).data('dbid', suggestion.data);
      }
    });
    $('[trailer-input]').autocomplete({
      serviceUrl: 'actions/trailer/fetchListPopup.php',
      type: 'POST',
      onSelect: function (suggestion) {
        $(this).data('dbid', suggestion.data);
      }
    });
    $('[truck-input]').autocomplete({
      serviceUrl: 'actions/tractor/fetchListPopup.php',
      type: 'POST',
      onSelect: function (suggestion) {
        $(this).data('dbid', suggestion.data);
      }
    });



    /******* Trip Information Handlers *******/
    //Agregar un Stop en la lista de paradas
    $('#addStopBtn').click(function(){
        let locationTemplate =`
        <div class="mb-1 mt-4 trip-stop">
            <div class="d-flex flex-justify-between w-100">
                <h5 class="stop-heading m-0" stop-input name="stop_label">Stop 1</h5>
                <button class="button link float-left fg-red float-right remove-stop" style="height: auto"> <span class="mif-cross-light"></span> </button>
            </div>
            <div class="row">
                <div class="cell-md-6">
                  <label for="">Location</label>
                  <input type="text" class="input input-small" google-autocomplete stop-input data-role="input" placeholder="Type the location zip, name or address..." name="google_address" value="">
                  <div class="gac-info pt-2">
                      <h5 class="name m-0" stop-input name="location_name"></h5>
                      <div class="">
                          <span class="street_number" stop-input name="street_number"></span> <span class="route" stop-input name="street_address">You must select a location from the dropdown</span>
                      </div>
                      <div class="">
                          <span class="locality" stop-input name="city"></span> <span class="administrative_area_level_1" stop-input name="state"></span> <span class="postal_code" stop-input name="zip_code"></span>
                      </div>
                      <div>
                          <span class="country" stop-input name="country"></span>
                      </div>
                  </div>
                </div>
                <div class="cell-md-3">
                  <label for="">Appt From</label>
                  <input type="date" class="input input-small" stop-input data-role="input" name="appt_date_from" value="2020-11-26">
                  <div class="d-flex mt-1">
                    <select class="input-small mr-1" stop-input data-role="select" data-filter="false"  name="appt_hour_from">
                        <option value="">Hour</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10" selected>10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                    </select>
                    <select class="input-small ml-1" stop-input data-role="select" data-filter="false"  name="appt_minute_from">
                        <option value="">Minute</option>
                        <option value="00">00</option>
                        <option value="15">15</option>
                        <option value="30" selected>30</option>
                        <option value="45">45</option>
                    </select>
                  </div>
                </div>
                <div class="cell-md-3">
                  <label for="">Appt To</label>
                  <input type="date" class="input input-small" stop-input data-role="input" name="appt_date_to" value="2020-11-26">
                  <div class="d-flex mt-1">
                    <select class="input-small mr-1" stop-input data-role="select" data-filter="false"  name="appt_hour_to">
                        <option value="">Hour</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13" selected>13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                    </select>
                    <select class="input-small ml-1" stop-input data-role="select" data-filter="false"  name="appt_minute_to">
                        <option value="">Minute</option>
                        <option value="00">00</option>
                        <option value="15">15</option>
                        <option value="30" selected>30</option>
                        <option value="45">45</option>
                    </select>
                  </div>
                  <div class="f-flex mt-1">
                    <select class="input input-small" stop-input name="movement_type">
                      <option value="L" selected>Loaded</option>
                      <option value="E">Empty</option>
                      <!-- <option value="EAL">Emtpy As Loaded</option> -->
                    </select>
                    <div class="text-right">
                      <span class="miles" stop-input name="miles">0</span> Miles
                    </div>
                  </div>
                </div>
            </div>
        </div>
         `
        let locationObject = $(locationTemplate);

        locationObject.appendTo('#stopList')
        createGoogleAutocomplete(locationObject.find('[google-autocomplete]'));
        $('#stopList').trigger('reorderStops')

    });

    //Volver a poner los stops de 1 - N cada que se hace una modificación.
    $('#stopList').on('reorderStops', function(){
     let children = $(this).children().filter(':not(:first)');
     children.each(function(i){
         let stopNumber = i + 1;
         $(this).find('.stop-heading').html('Stop ' + stopNumber );
     });
    });

    //Quitar un stop al hacer click en el botón.
    $('#stopList').on('click', '.remove-stop', function(){
     let stop = $(this).parents('.trip-stop');
     stop.css('background-color', '#ff9494')
     setTimeout(function () {
        stop.remove();
        $('#stopList').trigger('reorderStops');
     }, 150);
    });

    //Medir las distancias entre los stops.
    $('#stopList').on('getDistance', function(){
      let stops = $(this).find('.trip-stop');
      let places = []

      stops.each(function(){
        let place = $(this).find(['name=google-address']);
        if (place != "") {
          places.push(place);
        }
      });
      return false;

      DistanceMatrixServiceOptions.origins.push(data.origin);
      DistanceMatrixServiceOptions.destinations.push(data.destinations);

      DistanceMatrixService.getDistanceMatrix(DistanceMatrixServiceOptions, function(response, status){
        if (status == 'OK') {
          let origins = response.originAddresses;
          let destinations = response.destinationAddresses;

          for (var i = 0; i < origins.length; i++) {
            let results = response.rows[i].elements;
            for (var j = 0; j < results.length; j++) {
              let element = results[j];
              let distance = element.distance.text;
              let duration = element.duration.text;
              let from = origins[i];
              let to = destinations[j];

              console.log(from + " - " + to + ":" + distance);
            }
          }
        }
      });

    });

    //



});
const componentForm = {
    name: "name",
    street_number: "short_name",
    route: "long_name",
    locality: "long_name",
    administrative_area_level_1: "short_name",
    country: "short_name",
    postal_code: "short_name",
};
function createGoogleAutocomplete(input){
    ac = new google.maps.places.Autocomplete(input[0]);
    ac.setFields(["address_component", "name"]);
    ac.setComponentRestrictions({country:["us", "mx"]})
    ac.addListener('place_changed', autoFillAddress);
    ac.jqInput = input;
}
function autoFillAddress(){
    ac = this;

    dumpInfo = $(this.jqInput).parent().siblings('.gac-info');
    stop = $(this.jqInput).parents('.trip-stop');
    prevStop = stop.prev();

    const place = ac.getPlace();

    for (const component in componentForm) {
        dumpInfo.find('.'+component).html("");
    }


    dumpInfo.find('.name').html(place.name);
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[i][componentForm[addressType]];
          dumpInfo.find('.' + addressType).html(val);
          // document.getElementById(addressType).value = val;
        }
    }

    if (prevStop.length != 0) {

      let origin = prevStop.find('[name=google_address]').val();
      let destination = stop.find('[name=google_address]').val();

      DistanceMatrixServiceOptions.origins = [origin];
      DistanceMatrixServiceOptions.destinations = [destination];

      DistanceMatrixService.getDistanceMatrix(DistanceMatrixServiceOptions, function(response, status){
        if (status == 'OK') {
          let origins = response.originAddresses;
          let destinations = response.destinationAddresses;

          for (var i = 0; i < origins.length; i++) {
            let results = response.rows[i].elements;
            for (var j = 0; j < results.length; j++) {
              let element = results[j];
              let distance = element.distance.text;
              let duration = element.duration.text;
              let from = origins[i];
              let to = destinations[j];

              let distanceValue = element.distance.value / 1609;
              distanceValue = Math.round(distanceValue, 0);

              stop.find('.miles').html(distanceValue);

            }
          }
        }
      });
    }


}

let DistanceMatrixService = new google.maps.DistanceMatrixService();
let DistanceMatrixServiceOptions = {
  origins: [],
  destinations: [],
  travelMode: 'DRIVING',
  // transitOptions: TransitOptions,
  // drivingOptions: DrivingOptions,
  unitSystem: google.maps.UnitSystem.IMPERIAL,
  avoidHighways: false,
  avoidTolls: false,
}
