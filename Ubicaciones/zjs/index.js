function load_tables(){
  var load_them = $.ajax({
    method: 'POST',
    url: 'zactions/summary/ops_summary.php'
  });

  load_them.done(function(r){
    r = JSON.parse(r);
    console.log(r);

    if (r.code == 1) {
      $('#northbound-trips tbody').html(r.data.nb_trips.table);
      $('#nb-count').html(r.data.nb_trips.count);

      $('#southbound-trips tbody').html(r.data.sb_trips.table);
      $('#sb-count').html(r.data.sb_trips.count);

      $('#pending-return-trips tbody').html(r.data.pr_trips.table);
      $('#pr-count').html(r.data.pr_trips.count);

      $('#pending-invoice-trips tbody').html(r.data.pi_trips.table);
      $('#pi-count').html(r.data.pi_trips.count + " ($" + r.data.pi_trips.amount + ")");

      $('#pending-payment-trips tbody').html(r.data.pp_trips.table);
      $('#pp-count').html(r.data.pp_trips.count + " ($" + r.data.pp_trips.amount + ")");
    }

    }).fail(function(x){
    console.error(x);
  })
}

$(document).ready(function(){

  load_tables();

});
