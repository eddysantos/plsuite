function fetch_trailers(){
  console.log("Fetching trailers...");
  $.ajax({
    method: 'POST',
    url: 'actions/fetchTrailers.php',
    success: function(result){
      response = JSON.parse(result);

      if (response.code == 1) {
        $('#trailerDash').html(response.data);
        delete_trailer_handler();
        edit_trailer_handler();

      }
    },
    error: function(exception){
      console.error(exception);
    }
  })
}


function delete_trailer_handler(){
  $('.deleteTrailer').click(function(e){
    e.stopPropagation();
    var trailerid = $(this).attr('trailerid');

    $('#actionToConfirm').html("delete the trailer")
    $('#confirmationModal').modal('show');


    $('#confirmNo').unbind().click(function(){
      $('.modal').modal('hide');
      return false;
    })

    $('#confirmYes').unbind().click(function(){
      $.ajax({
        method: 'POST',
        data: {trailerid: trailerid},
        url: 'actions/deleteTrailer.php',
        success: function(result){
          response = JSON.parse(result);
          if (response.code == 1) {
            fetch_trailers();
            alert("Trailer deleted successfully");
          } else {
            alert("Trailer was not deleted, please contact support.");
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
function edit_trailer_handler(){
  $('tr').click(function(e){
    e.stopPropagation();
    var trailerid = $(this).attr('trailerid');
    window.location.href = 'trailerDetails.php?trailerid=' + trailerid;
  })
}

$(document).ready(function(){

  fetch_trailers();

  $('#addTrailerSubmit').click(function(){

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
      url: 'actions/addTrailer.php',
      success: function(result){
        response = JSON.parse(result);
        if (response.code == 1) {
          $('.modal').modal('hide');
        }
        console.log(response.message);
        fetch_trailers();
      },
      error: function(exception){
        console.error(exception);
      }
    });

  });

  $('#editTrailerNumberButton').click(function(){
    $('#trailerNumberLabel').fadeOut(function(){
      $('#trailerNumberEditLabel').fadeIn();
    })
  });

  $('#saveTrailerNumberButton').click(function(){
    $('#trailerNumberEditLabel').fadeOut(function(){
      $('#trailerNumberLabel').fadeIn();
    })
  });


  $('#saveTrailerDetails').click(function(){

    // console.log("Val() is -> " + $('#tBrand').val());
    // console.log("().prop('value') is -> " + $('#tBrand').attr('value'));
    // return false;

    var inputs = {};
    // inputs.brand = $('#tBrand').val();
    // inputs.vin = $('#tVIN').val();
    // inputs.year = $('#tYear').val();
    // inputs.plates = $('#tPlates').val();
    // inputs.number = $('#newTrailerNumber').val();
    // inputs.owner = $('#tOwnedBy').val();
    // inputs.trailer_id = $('#trailer_id').val();

    inputs.brand = $('#tBrand');
    inputs.vin = $('#tVIN');
    inputs.year = $('#tYear');
    inputs.plates = $('#tPlates');
    inputs.number = $('#newTrailerNumber');
    inputs.owner = $('#tOwnedBy');
    inputs.trailer_id = $('#trailer_id');

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

      // if (cont) {
      //   console.log("Cont es: " + cont);
      //   for (var key in inputs) {
      //     console.log(key + ": " + inputs[key].val() + '->' + inputs[key].attr('value'));
      //   }
      // } else {
      //   console.log("Cont es: " + cont);
      //   for (var key in inputs) {
      //     console.log(key + ": " + inputs[key].val() + '->' + inputs[key].attr('value'));
      //   }
      // }

    }


    $.ajax({
      method: 'POST',
      data: inputs,
      url: 'actions/editTrailer.php',
      success: function(result){
        response = JSON.parse(result);
        if (response.code == 1) {
          location.reload();
        } else {
          alert("Hubo un error al intentar actualizar el registro. Favor de consultar con soporte técnico.");
          console.log(response.message);
        }
        fetch_trailers();
      },
      error: function(exception){
        console.error(exception);
      }
    });

  });


});
