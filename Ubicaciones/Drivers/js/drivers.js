function fetch_drivers(){

  $.ajax({
    method: 'POST',
    url: 'actions/fetchDrivers.php',
    success: function(result){
      response = JSON.parse(result);
      if (response.code == 1) {
        $('#driversDash').html(response.data);
        delete_driver_handler();
        edit_driver_handler();
      } else {
        console.log(response);
      }
    },
    error: function(exception){
      console.error(exception);
    }
  })
}


function delete_driver_handler(){
  $('.deleteDriver').click(function(e){
    e.stopPropagation();
    var driverid = $(this).attr('driverid');

    $('#actionToConfirm').html("delete the driver")
    $('#confirmationModal').modal('show');


    $('#confirmNo').unbind().click(function(){
      $('.modal').modal('hide');
      return false;
    })

    $('#confirmYes').unbind().click(function(){
      $.ajax({
        method: 'POST',
        data: {driverid: driverid},
        url: 'actions/deleteDriver.php',
        success: function(result){
          response = JSON.parse(result);
          if (response.code == 1) {
            alert("Driver deleted successfully");
            fetch_drivers();
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
function edit_driver_handler(){
  $('tr').click(function(e){
    e.stopPropagation();
    var driverid = $(this).attr('driverid');
    window.location.href = 'driverDetails.php?driverid=' + driverid;
  })
}

$(document).ready(function(){

  fetch_drivers();

  $('#addDriverSubmit').click(function(){

    var inputs = {};
    inputs.fn = $('#nd-fn').val();
    inputs.ln = $('#nd-ln').val();
    inputs.pn = $('#nd-pn').val();
    inputs.em = $('#nd-em').val();
    inputs.ow = $('#nd-ow').val();
    inputs.dr = $('#nd-dr').val();

    for (var key in inputs) {
      if (inputs[key] == "") {
        alert("Es necesario agregar todos los campos.");
        return false;
      }
    }

    $.ajax({
      method: 'POST',
      data: inputs,
      url: 'actions/addDriver.php',
      success: function(result){
        response = JSON.parse(result);
        if (response.code == 1) {
          $('.modal').modal('hide');
        }
        fetch_drivers();
      },
      error: function(exception){
        console.error(exception);
      }
    });

  });

  $('#saveDriverDetails').click(function(){
    var driver = {};
    driver.id = $('#dIdDriver').val();
    driver.nameF = $('#dFirstName').val();
    driver.nameL = $('#dLastName').val();
    driver.phone = $('#dPhone').val();
    driver.email = $('#dEmail').val();
    driver.driver = $('#dIsDriver').val();
    driver.owner = $('#dIsOwner').val();
    driver.stNumber = $('#dStNumber').val();
    driver.stName = $('#dStName').val();
    driver.addrLine2 = $('#dAddrLine2').val();
    driver.city = $('#dCity').val();
    driver.state = $('#dState').val();
    driver.zip = $('#dZipCode').val();
    driver.country = $('#dCountry').val();
    driver.default_truck = $('#defaultTruck').val();

    $('#actionToConfirm').html("modify the data for " + $('#dFirstName').attr('value') + " " + $('#dLastName').attr('value'));
    $('#confirmationModal').modal('show');

    $('#confirmNo').unbind().click(function(){
      $('.modal').modal('hide');
      return false;
    })

    $('#confirmYes').unbind().click(function(){
      $.ajax({
        method: 'POST',
        data: driver,
        url: 'actions/editDriver.php',
        success: function(result){
          response = JSON.parse(result);
          if (response.code == "1") {
            location.reload(true);
          } else {
            alert("Hubo un error al modificar los datos, porfavor notíficar a soporte técnico.");
            $('.modal').modal('hide');
            console.log(response.message);
          }
        },
        error: function(exception){
          Alert("Something went wrong while editing the driver, please contact support.");
          console.error(exception);
        }
      });
    });

  });

  $('.popup-input').keyup(function(e){
    if (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 13){return false;}
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

});
