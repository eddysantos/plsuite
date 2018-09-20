

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


    var inputs = {};

    inputs.status = $('#tStatus');
    inputs.brand = $('#tBrand');
    inputs.vin = $('#tVIN');
    inputs.year = $('#tYear');
    inputs.plates = $('#tPlates');
    inputs.number = $('#tNumber');
    inputs.owner = $('#tOwnedBy');
    inputs.truck_id = $('#truck_id');
    inputs.ppm = $('#tPayPerMile');
    // inputs.apply_surcharge = $('#tApplySurcharge');

    console.log(inputs);
    var cont = true;
    for (var key in inputs) {
      // console.log("Key: " + key);
      if (inputs[key].val() == "" || inputs[key].val() == undefined) {
        // console.log(key);
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
        // console.log(key + ": " + inputs[key].val() + '->' + inputs[key].attr('value'));
      }
    } else {
      console.log("Cont es: " + cont);
      for (var key in inputs) {
        // console.log(key + ": " + inputs[key].val() + '->' + inputs[key].attr('value'));
      }
      return false;
    }

    var values = {
        brand: inputs.brand.val(),
        status: inputs.status.val(),
        vin:  inputs.vin.val(),
        year: inputs.year.val(),
        plates: inputs.plates.val(),
        number: inputs.number.val(),
        owner: inputs.owner.val(),
        truck_id: inputs.truck_id.val(),
        ppm: inputs.ppm.val(),
        // as: inputs.apply_surcharge.val()
    }


    $.ajax({
      method: 'POST',
      data: values,
      url: 'actions/editTruck.php',
      success: function(result){
        response = JSON.parse(result);
        console.log(response);
        if (response.code == 1) {
          alertify.success('Record was updated correctly!');
        } else if (response.code == 600) {
          alertify.error('We found no data to modify on this record.');
        } else {
          alertify.error('There was an error updating the record. Please contact tech support.');
          console.error(response.message);
        }


      },
      error: function(exception){
        console.error(exception);
      }
    });

  });


});
