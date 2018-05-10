$(document).ready(function(){


    var fetch_trips = $.ajax({
      method: 'POST',
      url: 'zactions/load_active.php',
    });

    fetch_trips.done(function(r){
      var r = JSON.parse(r);
      if (r.query.code == 1) {
        $('#tbody-open-trips').html(r.data);
      } else {
        $('#tbody-open-trips').html("<tr><td>No trips found.</td></tr>");
        console.log(r);
      }
    }).fail(function(x){
      console.error(x);
      $('#tbody-open-trips').html("<tr><td>There was an error, if problem persists call IT</td></tr>");
    });
});
