function fetch_trucks(){
  $.ajax({
    method: 'POST',
    url: 'actions/fetchTrucks.php',
    success: function(result){
      response = JSON.parse(result);

      if (response.code == 1) {
        $('#truckDash').html(response.data);
        delete_truck_handler();
        edit_truck_handler();

      }
    },
    error: function(exception){
      console.error(exception);
    }
  })
}


function delete_truck_handler(){
  $('.deleteTruck').click(function(e){
    e.stopPropagation();
    var truckid = $(this).attr('truckid');

    $('#actionToConfirm').html("delete the truck")
    $('#confirmationModal').modal('show');


    $('#confirmNo').unbind().click(function(){
      $('.modal').modal('hide');
      return false;
    })

    $('#confirmYes').unbind().click(function(){
      $.ajax({
        method: 'POST',
        data: {truckid: truckid},
        url: 'actions/deleteTruck.php',
        success: function(result){
          response = JSON.parse(result);
          if (response.code == 1) {
            fetch_trucks();
            alert("Truck deleted successfully");
          } else {
            alert("Driver was not deleted, please contact support.");
            console.error(response.message);
          }
        },
        error: function(exception){
          alert("Something went terribly wrong, please call support.")
          console.error(exception);
        }
      });
      $('.modal').modal('hide');
    });

  })

}

function edit_truck_handler(){
  $('tr').click(function(e){
    e.stopPropagation();
    var truckid = $(this).attr('truckid');
    window.location.href = 'truckDetails.php?truckid=' + truckid;
  })
}

$(document).ready(function(){

  fetch_trucks();

  $('#addTruckSubmit').click(function(){

    var inputs = {};
    inputs.ow = $('#nt-ow').val();
    inputs.vin = $('#nt-vin').val();
    inputs.br = $('#nt-br').val();
    inputs.ye = $('#nt-ye').val();
    inputs.tn = $('#nt-tn').val();

    for (var key in inputs) {
      if (inputs[key] == "" || inputs[key] == undefined) {
        alert("Es necesario agregar todos los campos.");
        return false;
      }
    }

    $.ajax({
      method: 'POST',
      data: inputs,
      url: 'actions/addTruck.php',
      success: function(result){
        response = JSON.parse(result);
        if (response.code == 1) {
          $('.modal').modal('hide');
        } else {
          console.log(response.message);
        }
        fetch_trucks();
      },
      error: function(exception){
        console.error(exception);
      }
    });

  });

  $('#editTruckNumberButton').click(function(){
    $('#truckNumberLabel').fadeOut(function(){
      $('#truckNumberEditLabel').fadeIn();
    })
  });

  $('#saveTruckNumberButton').click(function(){
    $('#truckNumberEditLabel').fadeOut(function(){
      $('#truckNumberLabel').fadeIn();
    })
  });


  $('#saveTruckDetails').click(function(){

    // console.log("Val() is -> " + $('#tBrand').val());
    // console.log("().prop('value') is -> " + $('#tBrand').attr('value'));
    // return false;

    var inputs = {};
    // inputs.brand = $('#tBrand').val();
    // inputs.vin = $('#tVIN').val();
    // inputs.year = $('#tYear').val();
    // inputs.plates = $('#tPlates').val();
    // inputs.number = $('#newTruckNumber').val();
    // inputs.owner = $('#tOwnedBy').val();
    // inputs.truck_id = $('#truck_id').val();

    inputs.brand = $('#tBrand');
    inputs.vin = $('#tVIN');
    inputs.year = $('#tYear');
    inputs.plates = $('#tPlates');
    inputs.number = $('#newTruckNumber');
    inputs.owner = $('#tOwnedBy');
    inputs.truck_id = $('#truck_id');

    var cont = true;

    for (var key in inputs) {
      if (inputs[key].val() == "" || inputs[key].val() == undefined) {
        alert("Es necesario agregar todos los campos.");
        return false;
      }

      if (inputs[key].val() == inputs[key].attr('value')) {
        cont * true
      } else {
        cont * false
      }


    }

    if (cont) {
      console.log("Cont es: " + cont);
      for (var key in inputs) {
        console.log(key + ": " + inputs[key].val() + '->' + inputs[key].attr('value'));
      }
    } else {
      console.log("Cont es: " + cont);
      for (var key in inputs) {
        console.log(key + ": " + inputs[key].val() + '->' + inputs[key].attr('value'));
      }
      return false;
    }


    $.ajax({
      method: 'POST',
      data: inputs,
      url: 'actions/editTruck.php',
      success: function(result){
        response = JSON.parse(result);
        if (response.code == 1) {
          location.reload();
        } else {
          alert("Hubo un error al intentar actualizar el registro. Favor de consultar con soporte técnico.");
          console.log(response.message);
        }
      },
      error: function(exception){
        console.error(exception);
      }
    });

  });


});
