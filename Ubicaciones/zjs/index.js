function get_dashboard_data(date){
  var data = {
    date: date
  }

  var pull_data = $.ajax({
    method: 'POST',
    data: data,
    url: 'zactions/get_dash_data.php'
  });

  pull_data.done(function(r){
    console.log(r);
    r = JSON.parse(r);

    if (r.code == 1) {
      $('#tt_miles').html(r.data.all_trips.miles);
      $('#tt_rate').html(r.data.all_trips.rate);
      $('#tt_rpm').html(r.data.all_trips.rpm);

      $('#sb_miles').html(r.data.sb_trips.miles);
      $('#sb_rate').html(r.data.sb_trips.rate);
      $('#sb_rpm').html(r.data.sb_trips.rpm);
    } else {
      console.error(r);
    }
  }).fail(function(x){
    console.error(x);
  });

}

$(document).ready(function(){

    $('#dash-date').datepicker();
    $('#dash-date').change(function(){
      var date = $(this).val();
      get_dashboard_data(date);
    })

    get_dashboard_data($('#dash-date').val());

    var fetch_trips = $.ajax({
      method: 'POST',
      url: 'zactions/load_active.php',
    });

    fetch_trips.done(function(r){
      var r = JSON.parse(r);
      if (r.query.code == 1) {
        $('#tbody-open-trips').html(r.data);
        $('#amt-open-trips').html(r.number)
      } else {
        $('#tbody-open-trips').html("<tr><td>No trips found.</td></tr>");
        console.log(r);
      }
    }).fail(function(x){
      console.error(x);
      $('#tbody-open-trips').html("<tr><td>There was an error, if problem persists call IT</td></tr>");
    });
});
