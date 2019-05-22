$(document).ready(function(){

  $(function(){
    $('#table_mx_drivers').trigger('fetch');
  }) //Init function...

  //Add Driver Modal
  $('#addDriver_btn').click(function(){
    var form = $('#agregarOperador_modal').find('form');
    var inputs = form.find('input');
    var validate = true;
    var data = {};

    inputs.each(function(){
      var value = this.value;
      var required = this.required;
      data[this.id] = this.value;

      if (required && value == "") {
        $(this).addClass('is-invalid').removeClass('is-valid');
        validate = false;
      } else {
        if (required) {
          $(this).addClass('is-valid').removeClass('is-invalid');
        }
      }
    });


    if (validate) {
      var add_driver = $.ajax({
        method: 'POST',
        url: 'actions/drivers/add.php',
        data: data
      });

      add_driver.done(function(r){
        r = JSON.parse(r);

        if (r.code == 1) {
          $('#table_mx_drivers').trigger('fetch');
          $('.modal.fade.show').modal('hide');
          alertify.success("El cliente fue agregado exitosamente.");
        } else {
          alertify.message(r.message)
        }
      }).fail(function(x,y,z){
        alertify.error(z);
        console.log(x);
        console.log(y);
      })
    }

  })


  //Main Events
  $('#table_mx_drivers').on('fetch', function(){
    var fetch_drivers = $.ajax({
      method: 'POST',
      url: 'actions/drivers/fetch.php',
    });

    fetch_drivers.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        $('#table_mx_drivers').html(r.data);
      } else {
        alertify.notify(r.message);
      }
    }).fail(function(x, y, z){
      alertify.error(z);
      console.log(x);
      console.log(y);
    })
});

});
