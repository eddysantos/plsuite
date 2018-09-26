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

      $('#pending-delivery-trips tbody').html(r.data.pd_trips.table);
      $('#pp-count').html(r.data.pd_trips.count + " ($" + r.data.pd_trips.amount + ")");
    }

    }).fail(function(x){
    console.error(x);
  })
}

$(document).ready(function(){

  load_tables();

  $('.this-week-toggle').click(function(){
    var $this = $(this);
    var $span = $this.find('span');
    var data = {
      this_week: true
    }

    if ($this.attr('disabled') == true) {
      return false;
    }

    $this.attr('disabled', true).addClass('disabled');

    if ($span.html() == "-") {
      $span.html('+');
    } else {
      $span.html('-');
      data.this_week = false;
    }

    var get_data = $.ajax({
      method: 'POST',
      url: 'zactions/summary/ops_summary_pi_only.php',
      data: data
    });

    get_data.done(function(r){
      r = JSON.parse(r);
      if (r.code == 1) {
        $('#pending-invoice-trips tbody').html(r.data.pi_trips.table);
        $('#pi-count').html(r.data.pi_trips.count + " ($" + r.data.pi_trips.amount + ")");
      }
      $this.attr('disabled', false).removeClass('disabled')
    }).fail(function(x){
      console.error(x);
      $this.attr('disabled', false).removeClass('disabled')
    });

  });

});
