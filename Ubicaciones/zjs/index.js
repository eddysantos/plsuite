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

function start_graphs(graph_name = "", cont = true){
  //Miles summary chart:
  if (graph_name == 'ms_chart' || graph_name == "") {
    ms_chart = c3.generate({
      bindto: '#miles-summary-chart',
      data:{
        x: "x",
        columns: [],
        labels: true
      },
      axis: {
        x: {
          type: 'timeseries',
          tick: {
            format: '%Y-%m-%d',
          }
        }
      },
      zoom:{
        enabled: true
      }
    });

    if (!cont) {
      return false;
    }
  }

  // if (graph_name == 'tms_chart' || graph_name == "") {
  //
  //   tms_chart = c3.generate({
  //     bindto: '#tms-summary-chart',
  //     data:{
  //       x: 'x',
  //       columns: [],
  //       labels: true,
  //       type: 'bar',
  //       groups: [['Loaded', 'Empty']]
  //     },
  //     grid: {
  //       y: {
  //           lines: [{value:0}]
  //       }
  //     }
  //   });
  //   if (!cont) {
  //     return false;
  //   }
  // }

}

$(document).ready(function(){
    start_graphs();

    $('.dropdown-menu>form').click(function(e){
    	e.stopPropagation();
    });

    $('.popup-input').keyup(function(e){
      if (e.keyCode === 38 || e.keyCode === 40 || e.keyCode === 13 || e.keyCode === 9){return false;}
      var data = {}
      var pop = $(this).attr('id-display');
      var cat = $(this).attr('category');
      var url = "";
      data.txt = $(this).val();

      console.log(cat);

      if (data.txt == "") {
        $('.popup-list').slideUp();
        return false;
      } else {

        switch (cat) {
          case 'trailer':
            url = "zactions/fetchTrailersPopup.php"
            break;
          case 'truck':
            url = "zactions/fetchTrucksPopup.php"
            break;
          case 'driver':
            url = "zactions/fetchDriversPopup.php"
            break;
          case 'broker':
            url = "zactions/fetchBrokersPopup.php"
            break;
          default:
            swal('Error', 'There was an error processing the request.', 'error');
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

    $('.popup-list').delegate('p', 'click', function(){
      var dbid = $(this).attr('db-id');
      var inputTarget = $(this).parent().attr('id');
      $("[id-display='#" + inputTarget+ "']").attr("value", $(this).html()).attr('db-id', $(this).attr('db-id')).change();
      $("[id-display='#" + inputTarget+ "']").prop("value", $(this).html()).change();
      $('.popup-list').slideUp();

    });

    $('.popup-list').on('mouseenter', 'p', function(){
      $('.hovered').attr('class', '');
      $(this).attr('class', 'hovered');
    });

    $('.popup-list').on('mouseleave', 'p', function(){
      $(this).attr('class', '')
    });

    $('#dash-cat-select').change(function(){
      var cat = $(this).val();

      if (cat == "") {
        $('#dash-cat-input').fadeOut();
        return false;
      }

      $('#dash-cat-input input').attr('category', cat).attr('placeholder', 'Enter which ' + cat);
      $('#dash-cat-input').fadeIn();
    });

    $('.date-selector').datepicker();

    $('#dash-date').change(function(){
      var date = $(this).val();
      get_dashboard_data(date);
    })

    $('#load_trip_summary_chart').click(function(){

      data = {
        date_from: $('#ts_chart_date_from').val(),
        date_to: $('#ts_chart_date_to').val(),
        period: $('#ts_chart_period').val()
      }

      if (data.date_from == "" || data.date_to == "") {
        swal('Oops', "You must type date from and to before loading the chart!", "error");
        return false;
      }

      var get_data = $.ajax({
        method: 'POST',
        data: data,
        url: 'zactions/get_trip_summary_chart.php'
      });

      get_data.done(function(r){
        // console.log(r);

        r = JSON.parse(r);
        console.log(r);
        if (r.code == 1) {
          c3.generate({
            bindto: '#rpm-summary-chart',
            data:{
              x: "x",
              columns: r.to_chart,
              labels: true
            },
            axis: {
                x: {
                    type: 'category',
                    tick: {
                        format: '%Y-%m-%d',
                    }
                }
            }
          });
        }
        // console.log(r['to_chart'][1].shift());
        // console.log(r['to_chart'][1].splice(1));
        // console.log(r);

      }).fail(function(x){
          console.error(x);
        })

    })

    $('#ms-add-graph-item').click(function(){
      var data = {
        dbid: $(this).siblings('input').attr('db-id'),
        category: $(this).siblings('input').attr('category'),
        date_from: $('#ms_chart_date_from').val(),
        date_to: $('#ms_chart_date_to').val(),
        period: $('#ms_chart_period').val()
      }

      if (data.date_from == "" || data.date_to == "" || data.period == "") {
        swal('Oops', 'You need to enter date and periodicity before continuing.', 'error');
        return false;
      }

      if (data.cat == "") {
        swal('Oops', 'You need to enter a category before continuing.', 'error');
        return false;
      }

      var pull_data = $.ajax({
        method: 'POST',
        data: data,
        url: 'zactions/get_miles_summary_chart.php'
      });

      pull_data.done(function(r){
        r = JSON.parse(r);
        console.log(r);

        ms_chart.load({
          columns: r.to_chart
        });

        $('#dash-cat-select').addClass('disabled').attr('disabled', true);
        $('#miles-summary-chart').fadeIn();
        $('[target=ms_chart]').fadeIn();
        $('.dropdown-menu.show').removeClass('show');
        }).fail(function(x){
          console.error(x);
        });


    })

    $('#load_tms_chart').click(function(){
      data = {
        date_from: $('#tms_chart_date_from').val(),
        date_to: $('#tms_chart_date_to').val()
      }

      if (data.date_from == "" || data.date_to == "") {
        swal('Oops', "You must type date from and to before loading the chart!", "error");
        return false;
      }

      var get_data = $.ajax({
        method: 'POST',
        data: data,
        url: 'zactions/get_tms_summary_chart.php'
      });

      get_data.done(function(r){
        r = JSON.parse(r);
        console.log(r);

        if (r.code == 1) {
          tms_chart = c3.generate({
            bindto: '#tms-summary-chart',
            data:{
              x: 'x',
              columns: r.to_chart,
              type: 'bar',
              types: {
                Goal: 'line'
              },
              labels: true,
              type: 'bar',
              groups: [['Loaded', 'Empty']],
              colors:{
                Goal:'#EC3737'
              },
              onclick: function(d, element){console.log(this);}
            },
            grid: {
              y: {
                  lines: [{value:0}]
              }
            },
            axis: {
              x: {
                type: 'category'
                // tick: {
                //   format: '%Y-%m-%d',
                // }
              }
            },
          });

          // tms_chart.load({
          //   columns: r.to_chart
          // })
        }

        }).fail(function(x){
        console.error(x);
      });

    })

    $('.reset-chart').click(function(){
      var chart = $(this).attr('target');
      switch (chart) {
        case 'ms_chart':
          ms_chart.unload();
          $('#miles-summary-chart').fadeOut();
          $('#dash-cat-select').removeClass('disabled').attr('disabled', false);
          break;
        default:

      }
      $(this).fadeOut();
    })

    get_dashboard_data($('#dash-date').val());

    // var fetch_trips = $.ajax({
    //   method: 'POST',
    //   url: 'zactions/load_active.php',
    // });
    //
    // fetch_trips.done(function(r){
    //   var r = JSON.parse(r);
    //   if (r.query.code == 1) {
    //     $('#tbody-open-trips').html(r.data);
    //     $('#amt-open-trips').html(r.number)
    //   } else {
    //     $('#tbody-open-trips').html("<tr><td>No trips found.</td></tr>");
    //     console.log(r);
    //   }
    //   }).fail(function(x){
    //     console.error(x);
    //     $('#tbody-open-trips').html("<tr><td>There was an error, if problem persists call IT</td></tr>");
    // });
});
