function fetch_brokers(){

  $.ajax({
    method: 'POST',
    url: 'actions/fetchBrokers.php',
    success: function(result){
      response = JSON.parse(result);
      if (response.code == 1) {
        console.log('fetching brokers');
        console.log(response);
        $('#brokersDash').html(response.data);
      } else {
        console.log(response.message);
      }
    },
    error: function(exception){
      console.error(exception);
    }
  })
}


function delete_broker_handler(){
  $('.deleteDriver').click(function(e){
    e.stopPropagation();
    var brokerid = $(this).attr('brokerid');

    $('#actionToConfirm').html("delete the broker")
    $('#confirmationModal').modal('show');


    $('#confirmNo').unbind().click(function(){
      $('.modal').modal('hide');
      return false;
    })

    $('#confirmYes').unbind().click(function(){
      $.ajax({
        method: 'POST',
        data: {brokerid: brokerid},
        url: 'actions/deleteDriver.php',
        success: function(result){
          response = JSON.parse(result);
          if (response.code == 1) {
            alert("Driver deleted successfully");
            fetch_brokers();
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
function edit_broker_handler(){
  $('tr').click(function(e){
    e.stopPropagation();
    var brokerid = $(this).attr('brokerid');
    window.location.href = 'driverDetails.php?driverid=' + driverid;
  })
}

$(document).ready(function(){

  fetch_brokers();

  $('#addBrokerSubmit').click(function(){

    var inputs = {
      name: $('#nb-name').val(),
      main_contact: $('#nb-cname').val(),
      main_contact_phone: $('#nb-ph').val(),
      main_contact_extension: $('#nb-xt').val(),
      main_contact_cellphone: $('#nb-cell').val(),
      main_contact_email: $('#nb-em').val(),
      businesshours_from: $('#nb-bf').val(),
      businesshours_to: $('#nb-bt').val()
    };

    for (var key in inputs) {
      if (inputs[key] == "") {
        alert("Es necesario agregar todos los campos.");
        return false;
      }
    }

    $.ajax({
      method: 'POST',
      data: inputs,
      url: 'actions/addBroker.php',
      success: function(result){
        response = JSON.parse(result);
        if (response.code == 1) {
          $('.modal').modal('hide');
        } else {
          console.log(response.message);
        }
        fetch_brokers();
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

});
