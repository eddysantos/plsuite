$(document).ready(function() {

    let input = $('.google-autocomplete');
    input.each(function(){
        thisInput = $(this);
        createGoogleAutocomplete(thisInput);
    });

    $('#confirmInfoBtn').click(function(){

    let windowOptions = {};

    windowOptions.place     = 'center';
    windowOptions.width     = $(window).width() * .90;
    windowOptions.height    = $(window).height() * .90;
    windowOptions.btnMin    = false;
    windowOptions.btnMax    = false;
    windowOptions.title     = "Confirm Trip Information";
    windowOptions.shadow    = true;
    windowOptions.draggable = false;
    windowOptions.resizable = false;

    Metro.window.create(windowOptions);
    });

    $('#addStopBtn').click(function(){
        let locationTemplate =`
         <div class="border-top bd-gray p-2 mb-1 stop">
         <div class="d-flex flex-justify-between w-100">
             <h5 class="stop-heading">Stop 1</h5>
             <button class="button link float-left fg-red float-right remove-stop"> <span class="mif-cross-light"></span> </button>
         </div>
           <div class="row">
             <div class="cell-md-6">
               <label for="">Location</label>
               <input type="text" class="input input-small google-autocomplete" placeholder="Type the location zip, name or address..." name="" value="">
               <div class="gac-info pt-2">
                   <h5 class="name m-0"></h5>
                   <div class="">
                       <span class="street_number"></span> <span class="route"></span>
                   </div>
                   <div class="">
                       <span class="locality"></span> <span class="administrative_area_level_1"></span> <span class="postal_code"></span>
                   </div>
                   <div>
                       <span class="country"></span>
                   </div>
               </div>
             </div>
             <div class="cell-md-3">
               <label for="">Appt From</label>
               <input type="date" class="input input-small" name="" value="">
               <div class="d-flex mt-1">
                 <select class="input-small mr-1" data-role="select" data-filter="false"  name="">
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
                 <select class="input-small ml-1" data-role="select" data-filter="false"  name="">
                     <option value="00">00</option>
                     <option value="15">15</option>
                     <option value="30">30</option>
                     <option value="45">45</option>
                 </select>
               </div>
             </div>
             <div class="cell-md-3">
               <label for="">Appt To</label>
               <input type="date" class="input input-small" name="" value="">
               <div class="d-flex mt-1">
                 <select class="input-small mr-1" data-role="select" data-filter="false"  name="">
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
                 <select class="input-small ml-1" data-role="select" data-filter="false"  name="">
                     <option value="00">00</option>
                     <option value="15">15</option>
                     <option value="30">30</option>
                     <option value="45">45</option>
                 </select>
               </div>
             </div>
           </div>
           <div class="row">
             <div class="cell-3 offset-9">
               <select class="input input-small" name="">
                 <option value="L">Loaded</option>
                 <option value="E">Empty</option>
                 <!-- <option value="EAL">Emtpy As Loaded</option> -->
               </select>
             </div>
           </div>
         </div>
         `
        let locationObject = $(locationTemplate);

        locationObject.appendTo('#stopList')
        createGoogleAutocomplete(locationObject.find('.google-autocomplete'));
        $('#stopList').trigger('reorderStops')

    });
    $('#stopList').on('reorderStops', function(){
     let children = $(this).children().filter(':not(:first)');
     children.each(function(i){
         let stopNumber = i + 1;
         $(this).find('.stop-heading').html('Stop ' + stopNumber );
     });
    });
    $('#stopList').on('click', '.remove-stop', function(){
     let stop = $(this).parents('.stop');
     stop.css('background-color', '#ff9494')
     setTimeout(function () {
        stop.remove();
        $('#stopList').trigger('reorderStops');
     }, 150);
    });

    $('#stopList').on('place_changed', autoFillAddress);

});
const componentForm = {
    name: "name",
    street_number: "short_name",
    route: "long_name",
    locality: "long_name",
    administrative_area_level_1: "short_name",
    country: "long_name",
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
    dumpInfo = $(this.jqInput).siblings('.gac-info');
    const place = ac.getPlace();

    console.log(place);

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



}
