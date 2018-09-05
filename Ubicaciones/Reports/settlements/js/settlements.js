function init(){

  var list_s_trucks = $.ajax({
    method: 'POST',
    url: 'actions/get_s_trucks.php'
  });


}

$(document).ready(function(){
  init();
});
