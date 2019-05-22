$(document).ready(function(){

  $(function(){
    console.log("fuck you bitch!");
    $('#table_mx_trucks').trigger('fetch');
  }) //Init function...


  //Main Events
  $('#table_mx_trucks').on('fetch', function(){
    var fetch_trucks = $.ajax({
      method: 'POST',
      url: 'actions/trucks/fetch.php',
    });

    fetch_trucks.done(function(r){
      console.log(r);
      r = JSON.parse(r);
      if (r.code == 1) {
        $('#table_mx_trucks').html(r.data);
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
