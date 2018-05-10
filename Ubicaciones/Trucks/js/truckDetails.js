

$(document).ready(function(){


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

    var values = {
        brand: inputs.brand.val(),
        vin:  inputs.vin.val(),
        year: inputs.year.val(),
        plates: inputs.plates.val(),
        number: inputs.number.val(),
        owner: inputs.owner.val(),
        truck_id: inputs.truck_id.val()
    }


    $.ajax({
      method: 'POST',
      data: values,
      url: 'actions/editTruck.php',
      success: function(result){
        response = JSON.parse(result);
        console.log(response);
        // if (response.code == 1) {
        //
        // } else {
        //   alert("Hubo un error al intentar actualizar el registro. Favor de consultar con soporte técnico.");
        //   console.log(response.message);
        // }
      },
      error: function(exception){
        console.error(exception);
      }
    });

  });


});
