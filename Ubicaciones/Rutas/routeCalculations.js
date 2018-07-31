function create_new_route(origin, destination){
  $.ajax({
    method: 'POST',
    data: {origen: origin, destino: destination},
    url: '/plsuite/Resources/PHP/Rutas/addNewRoute.php',
    success: function(result){
      rsp = JSON.parse(result);
      console.log(rsp);
      if (rsp.code == 201) {
        return false;
      } else if (rsp.code == 1) {
          var msg = "Calculating route from " + origin + " to " + destination;
          console.log(msg);
          loadingScreen(msg);
          directionsRequest.origin = origin;
          directionsRequest.destination = destination;
          routeId = rsp.systemMessage;
          directionsService.route(directionsRequest, init);
      }
    },
    error: function(exception){
      console.error(exception);
    }
  })
}

function loadingScreen(message){
  //console.log("Loading screen active!");
  $('body').append("<div class='overlay d-flex align-items-center' style='z-index: 2000'><div class='overlay-loading d-flex flex-column align-items-center'><div class=''><div class='row align-items-baseline'><div class='col-3'><p><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i></p></div><div class='col-9'><p>" + message + "</p></div></div></div><div class=''><button class='btn btn-danger' id='stopCalculations'>Stop</button></div></div></div>")

  $('#stopCalculations').click(function(){
    $('#calcPendingRoutes').attr('stop', true);
    $(this).html('Stopping...').attr('disabled', true).addClass('disabled');
  });
}

function addRouteDetails(routeDetails, routeId){
  $.ajax({
    method: 'POST',
    async: false,
    data: {routeDetails, routeId: routeId},
    url: '/plsuite/Resources/PHP/Rutas/addRouteDetails.php',
    success: function(result){
        window.location.reload();
      // $.ajax({
      //   method: 'POST',
      //   url: 'fetchRutas.php',
      //   success: function(result){
      //     rsp = JSON.parse(result);
      //     $('#dashruts').html(rsp.data);
      //     var verification = $('#calcPendingRoutes').attr('stop');
      //     window.location.reload();
      //   }
      // });
    },
    error: function(exception){
      console.error(exception);
    }
  })
}

depTime = new Date();
depTime.setMinutes(depTime.getMinutes() + 120);

var directionsRequest = {
  origin: "New York, NY", //default
  destination: "Los Angeles, LA", //default
  optimizeWaypoints: true,
  provideRouteAlternatives: false,
  travelMode: google.maps.TravelMode.DRIVING,
  drivingOptions: {
    departureTime: depTime,
    trafficModel: google.maps.TrafficModel.PESSIMISTIC
  }
};

// directionsRequest.origin = "Sprinfield, MO";
// directionsRequest.destination = "Laredo, TX";
var starttime = new Date();

var geocoder  = new google.maps.Geocoder();
var startState;
var currentState;
var routeData;
var index = 0;
var stateChangeSteps = [];
var borderLatLngs = {};
var startLatLng;
var endLatLng;
var routeId = "";

directionsService = new google.maps.DirectionsService();
//directionsService.route(directionsRequest, init);

function init(data){
  routeData = data;
  // displayRoute();
  startLatLng = data.routes[0].legs[0].start_location;
  endLatLng = data.routes[0].legs[0].end_location;
  geocoder.geocode({location:data.routes[0].legs[0].start_location}, assignInitialState)

}

function assignInitialState(data){
  startState = getState(data);
  currentState = startState;
  compileStates(routeData);
}

function getState(data){
  for (var i = 0; i < data.length; i++) {
    if (data[i].types[0] === "administrative_area_level_1") {
      var state = data[i].address_components[0].short_name;
    }
  }
  return state;
}

function compileStates(data, this_index){
  if(typeof(this_index) == "undefined"){
    index = 1;
    geocoder.geocode({location:data.routes[0].legs[0].steps[0].start_location}, compileStatesReceiver);
  }else{
    if(index >= data.routes[0].legs[0].steps.length){
      console.log(stateChangeSteps);
      index = 0;
      startBinarySearch();
      return;
    }
    // geocoder.geocode({location:data.routes[0].legs[0].steps[index].start_location}, compileStatesReceiver);
    setTimeout(function(){
            geocoder.geocode({location:data.routes[0].legs[0].steps[index].start_location}, compileStatesReceiver);
        }, 3000)

  }

}

function compileStatesReceiver(response){
  state = getState(response);
  console.log(state);
  if(state != currentState){
    currentState = state;
    stateChangeSteps.push(index-1);
  }
  index++;
  compileStates(routeData, index);

}



var stepIndex = 0;
var stepStates = [];
var binaryCurrentState = "";
var stepNextState;
var stepEndState;
var step;

var myLatLng = {lat:39.8282, lng:-98.5795};
// var map = new google.maps.Map(document.getElementById('map'), {
  //   zoom: 4,
  //   center: myLatLng
  // });

  // function displayRoute() {
  //   directionsDisplay = new google.maps.DirectionsRenderer();
  //   directionsDisplay.setMap(map);
  //   directionsDisplay.setDirections(routeData);
  // }

  var orderedLatLngs = [];
  function startBinarySearch(iterating){
    if(stepIndex >= stateChangeSteps.length){
      for(step in borderLatLngs){
        for(state in borderLatLngs[step]){
          for(statename in borderLatLngs[step][state]){
            // $("#results").append("<br>Cross into "+statename+" at "+JSON.stringify(borderLatLngs[step][state][statename], null, 4));
            orderedLatLngs.push([borderLatLngs[step][state][statename], statename]);
          }
        }
      }
      compileMiles(true);
      return;

    }
    step = routeData.routes[0].legs[0].steps[stateChangeSteps[stepIndex]];
    console.log("Looking at step "+stateChangeSteps[stepIndex]);
    borderLatLngs[stepIndex] = {};
    if(!iterating){
      binaryCurrentState = startState;
    }
    geocoder.geocode({location:step.end_location},
      function(data){
        if(data === null){
          // startBinarySearch(true);
          setTimeout(function(){startBinarySearch(true);}, 6000);
        }else{
          stepNextState = getState(data);
          stepEndState = stepNextState;
          binaryStage2(true);
        }
      });
    }

    var minIndex;
    var maxIndex;
    var currentIndex;
    function binaryStage2(init){
      if (typeof(init) != "undefined"){
        minIndex = 0;
        maxIndex  = step.path.length - 1;
      }
      if((maxIndex-minIndex)<2){
        borderLatLngs[stepIndex][maxIndex]={};
        borderLatLngs[stepIndex][maxIndex][stepNextState]=step.path[maxIndex];
        // var marker = new google.maps.Marker({
        //   position: borderLatLngs[stepIndex][maxIndex][stepNextState],
        //   map: map,
        // });
        if(stepNextState != stepEndState){
          minIndex = maxIndex;
          maxIndex = step.path.length - 1;
          binaryCurrentState = stepNextState;
          stepNextState = stepEndState;

        }else{
          stepIndex++;
          binaryCurrentState = stepNextState;
          startBinarySearch(true);
          return;
        }
      }
      // console.log("Index starts: "+minIndex+" "+maxIndex);
      // console.log("current state is "+binaryCurrentState);
      // console.log("next state is "+ stepNextState);
      // console.log("end state is "+ stepEndState);

      currentIndex = Math.floor((minIndex+maxIndex)/2);
      setTimeout(function(){
        geocoder.geocode({location:step.path[currentIndex]}, binaryStage2Reciever);
        // $("#status").html("Searching for division between "+binaryCurrentState+" and "+stepNextState+" between indexes "+minIndex+" and "+maxIndex+"...")
      }, 3000);


    }

    function binaryStage2Reciever(response){
      if(response === null){
        // binaryStage2()
        setTimeout(binaryStage2, 6000);
      }else{
        state = getState(response)
        if(state == binaryCurrentState){
          minIndex = currentIndex +1;
        }else{
          maxIndex = currentIndex - 1
          if(state != stepNextState){
            stepNextState = state;
          }
        }
        binaryStage2();
      }
    }

    var currentStartPoint;
    var compileMilesIndex = 0;
    var stateMiles = {};
    var trueState;
    function compileMiles(init){
      if(typeof(init)!= "undefined"){
        currentStartPoint = startLatLng;
        trueState = startState;
      }
      if(compileMilesIndex == orderedLatLngs.length){
        directionsRequest.destination = endLatLng;
      }else{
        directionsRequest.destination = orderedLatLngs[compileMilesIndex][0];
      }
      directionsRequest.origin = currentStartPoint;
      currentStartPoint = directionsRequest.destination;
      directionsService.route(directionsRequest, compileMilesReciever)

    }

    function compileMilesReciever(data){
      if(data===null){
        // compileMiles();
        setTimeout(compileMiles, 6000);
      }else{
        if(compileMilesIndex == orderedLatLngs.length){
          stateMiles[stepEndState]=data.routes[0].legs[0].distance["value"];
          // $("#results").append("<br><br><b>Distances Traveled</b>");
          for(state in stateMiles){
            // $("#results").append("<br>"+state+": "+stateMiles[state]);
          }
          var endtime = new Date();
          totaltime = endtime - starttime;
          // $("#results").append("<br><br>Operation took "+Math.floor(totaltime/60000)+" minute(s) and "+(totaltime%60000)/1000+" second(s) to run.");
          $('.overlay').remove();
          console.log("Finished!!");
          addRouteDetails(stateMiles, routeId);
          return;
        }else{
          stateMiles[trueState]=data.routes[0].legs[0].distance["value"];
        }
        trueState = orderedLatLngs[compileMilesIndex][1];
        compileMilesIndex++;
        // compileMiles();
        setTimeout(compileMiles, 3000);
      }
    }

    function initialize_calculations(el){
      // depTime.setMinutes(depTime.getMinutes() + 20);
      // stateMiles = {};
      depTime.setMinutes(depTime.getMinutes() + 20);
      console.log("Testing bitch");
      var row = $('tr.no-existe').first();
      var orig = row.find('.origen').html();
      var destino = row.find('.destino').html();

      var stop = $('#calcPendingRoutes').attr('stop');
      if (stop == "true") {
        alert("Calculations have been stopped.");
        return false;
      }

      // console.log(orig);
      // console.log(destino);
      create_new_route(orig, destino);
    }










$(document).ready(function(){
  initialize_calculations();
  $('#calcPendingRoutes').click(function(el){
    $(this).attr('stop', false);
    // console.log( $('tr.no-existe').first());
    initialize_calculations();
    // $('tbody tr').each(function(){
    //   if ($(this).find('.exists').html() == "") {
    //     return false;
    //   }
    //   var orig = $(this).find('.origen').html();
    //   var destino = $(this).find('.destino').html();
    //   create_new_route(orig, destino);
      // if ($(this).find('.existe').html() == "") {
      //   console.log("Esta no esta en la bd: " + $(this).find('.id').html());
      // }
    // });
  });
});
