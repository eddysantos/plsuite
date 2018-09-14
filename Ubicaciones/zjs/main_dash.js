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
    var r = JSON.parse(r);
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
  // return false;
  //Truck Miles Summary Chart:
  if (graph_name == 'tms_chart' || graph_name == "") {
    var data = {
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
      var r = JSON.parse(r);
      //console.log(r);
      // return false;
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
            labels: {
              format: function(v, id, i, j){
                if (v != 0) {
                  return v;
                }
              }
            },
            type: 'bar',
            groups: [['Loaded', 'Empty', 'Incomplete Loaded', 'Incomplete Empty']],
            colors:{
              Goal:'#EC3737',
              Loaded: '#1B62A5',
              Empty: '#F86D16',
              'Incomplete Loaded': '#B2C5D7',
              'Incomplete Empty': '#F7B68E'
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
  }

  //Miles summary chart:
  if (graph_name == 'ms_chart' || graph_name == "") {

    $('#dash-cat-select').addClass('disabled').attr('disabled', true);
    $('#miles-summary-chart').fadeIn();
    $('[target=ms_chart]').fadeIn();
    $('.dropdown-menu.show').removeClass('show');
    $('#dash-cat-input').fadeIn();

    var data = {
      dbid: '10',
      category: 'truck',
      date_from: $('#ms_chart_date_from').val(),
      date_to: $('#ms_chart_date_to').val(),
      period: $('#ms_chart_period').val(),
      pull: 'first pull'
    }

    var pull_data = $.ajax({
      method: 'POST',
      data: data,
      url: 'zactions/get_miles_summary_chart.php'
    })

    pull_data.done(function(r){
      var r = JSON.parse(r);
      if (r.code == 1) {
        ms_chart = c3.generate({
          bindto: '#miles-summary-chart',
          data:{
            x: "x",
            columns: r.to_chart,
            labels: true
          },
          axis: {
            x: {
              type: 'timeseries',
              tick: {
                format: '%Y-%m-%d',
              }
            }
          }
        });
      }

      }).fail(function(x){
        console.error(x);
    });

    pull_data_2 = pull_data.then(function(x){
      // console.log(x);
      datos = {
        dbid: '23',
        category: 'truck',
        date_from: $('#ms_chart_date_from').val(),
        date_to: $('#ms_chart_date_to').val(),
        period: $('#ms_chart_period').val(),
        pull: 'second pull'
      }

      return $.ajax({
        method: 'POST',
        data: datos,
        url: 'zactions/get_miles_summary_chart.php'
      });
    });

    pull_data_2.done(function(s){
      var s = JSON.parse(s);
      //console.log(s);
      if (s.code == 1) {
        ms_chart.load({
          columns: s.to_chart
        });
      }

      }).fail(function(x){
      console.error(x);
    });

    pull_data_3 = pull_data_2.then(function(){
      datos = {
        dbid: '24',
        category: 'truck',
        date_from: $('#ms_chart_date_from').val(),
        date_to: $('#ms_chart_date_to').val(),
        period: $('#ms_chart_period').val(),
        pull: 'third pull'
      }

      return $.ajax({
        method: 'POST',
        data: datos,
        url: 'zactions/get_miles_summary_chart.php'
      });
    });

    pull_data_3.done(function(s){
      var s = JSON.parse(s);
      //console.log(s);
      if (s.code == 1) {
        ms_chart.load({
          columns: s.to_chart
        });
      }

      }).fail(function(x){
      console.error(x);
    });





    // ms_chart = c3.generate({
    //   bindto: '#miles-summary-chart',
    //   data:{
    //     x: "x",
    //     columns: [],
    //     labels: true
    //   },
    //   axis: {
    //     x: {
    //       type: 'timeseries',
    //       tick: {
    //         format: '%Y-%m-%d',
    //       }
    //     }
    //   },
    //   zoom:{
    //     enabled: true
    //   }
    // });

    if (!cont) {
      return false;
    }
  }

  //Sales summary chart:
  if (graph_name == 'ss_chart' || graph_name == "") {

    $('#dash-cat-select-ss').addClass('disabled').attr('disabled', true);
    $('#sales-summary-chart').fadeIn();
    $('[target=ss_chart]').fadeIn();
    $('.dropdown-menu.show.sales-summary').removeClass('show');
    $('#dash-cat-input-ss').fadeIn();

    data = {
      date_from: $('#ss_chart_date_from').val(),
      date_to: $('#ss_chart_date_to').val(),
      period: $('#ss_chart_period').val()
    }

    var get_data = $.ajax({
      method: 'POST',
      data: data,
      url: 'zactions/get_sales_summary_chart.php'
    });

    get_data.done(function(r){
      //console.log(r);
      var r = JSON.parse(r);
      if (r.code == 1) {
        ss_chart = c3.generate({
          bindto: '#sales-summary-chart',
          data:{
            x: "x",
            columns: r.to_chart,
            labels: {
              format:function(v, id, i, j){
                var num = v.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                var output = "$" + num;
                return output;
              }
            }
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
            enabled: false
          }
        });
      }
      // console.log(r['to_chart'][1].shift());
      // console.log(r['to_chart'][1].splice(1));
      // console.log(r);

    }).fail(function(x){
        console.error(x);
      });

    if (!cont) {
      return false;
    }
  }


  //Invoice summary chart:
  if (graph_name == 'is_chart' || graph_name == "") {

    $('#invoice-summary-chart').fadeIn();
    // $('.dropdown-menu.show.sales-summary').removeClass('show');
    // $('#dash-cat-input-ss').fadeIn();

    data = {
      date_from: $('#is_chart_date_from').val(),
      date_to: $('#is_chart_date_to').val(),
      period: $('#is_chart_period').val()
    }

    var get_data = $.ajax({
      method: 'POST',
      data: data,
      url: 'zactions/get_invoice_summary_chart.php'
    });

    get_data.done(function(r){
      // console.log(r);

      var r = JSON.parse(r);
      if (r.code == 1) {
        is_chart = c3.generate({
          bindto: '#invoice-summary-chart',
          data:{
            x: "x",
            columns: r.to_chart,
            labels: {
              format:function(v, id, i, j){
                var num = v.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                var output = "$" + num;
                return output;
              }
            }
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
            enabled: false
          }
        });
      }
      // console.log(r['to_chart'][1].shift());
      // console.log(r['to_chart'][1].splice(1));
      // console.log(r);

    }).fail(function(x){
        console.error(x);
      });

    if (!cont) {
      return false;
    }
  }


  //Paid - Collected summary chart:
  if (graph_name == 'cs_chart' || graph_name == "") {

    $('#collected-summary-chart').fadeIn();
    // $('.dropdown-menu.show.sales-summary').removeClass('show');
    // $('#dash-cat-input-ss').fadeIn();

    data = {
      date_from: $('#cs_chart_date_from').val(),
      date_to: $('#cs_chart_date_to').val(),
      period: $('#cs_chart_period').val()
    }

    var get_data = $.ajax({
      method: 'POST',
      data: data,
      url: 'zactions/get_paid_summary_chart.php'
    });

    get_data.done(function(r){

      var r = JSON.parse(r);
      console.log(r);
      if (r.code == 1) {
        is_chart = c3.generate({
          bindto: '#collected-summary-chart',
          data:{
            x: "x",
            columns: r.to_chart,
            labels: {
              format:function(v, id, i, j){
                var num = v.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                var output = "$" + num;
                return output;
              }
            },
            colors:{
              Goal:'#EC3737'
            },
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
            enabled: false
          }
        });
      }
      // console.log(r['to_chart'][1].shift());
      // console.log(r['to_chart'][1].splice(1));
      // console.log(r);

    }).fail(function(x){
        console.error(x);
      });

    if (!cont) {
      return false;
    }
  }

  //RPM Summary Chart:
  if (graph_name == 'rpm_chart' || graph_name == "") {
    data = {
      date_from: $('#ts_chart_date_from').val(),
      date_to: $('#ts_chart_date_to').val(),
      period: $('#ts_chart_period').val()
    }

    var get_data = $.ajax({
      method: 'POST',
      data: data,
      url: 'zactions/get_trip_summary_chart.php'
    });

    get_data.done(function(r){
      // console.log(JSON.parse(r));

      var r = JSON.parse(r);
      if (r.code == 1) {
        rpm_chart = c3.generate({
          bindto: '#rpm-summary-chart',
          data:{
            x: "x",
            columns: r.to_chart,
            labels: true,
            colors:{
              Goal:'#EC3737'
            },
          },
          axis: {
              x: {
                  type: 'category',
                  tick: {
                      format: '%Y-%m-%d',
                  }
              }
          },
        });
      }
      // console.log(r['to_chart'][1].shift());
      // console.log(r['to_chart'][1].splice(1));
      // console.log(r);

    }).fail(function(x){
        console.error(x);
      });

    if (!cont) {
      return false;
    }
  }


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
            var resp = JSON.parse(result);

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

    $('#dash-cat-select-ss').change(function(){
      var cat = $(this).val();

      if (cat == "") {
        $('#dash-cat-input-ss').fadeOut();
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

    $('#load_trip_summary_chart').click(function(){ //This is the RPM Summary chart re-load button.

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

        var r = JSON.parse(r);
        if (r.code == 1) {
          rpm_chart.load({
            columns: r.to_chart,
          })
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
        query_type: 'not_on_load',
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
        var r = JSON.parse(r);

        if (r.code == 1) {
          ms_chart.load({
            columns: r.to_chart
          });
        }

        $('#dash-cat-select').addClass('disabled').attr('disabled', true);
        $('#miles-summary-chart').fadeIn();
        $('[target=ms_chart]').fadeIn();
        $('.dropdown-menu.show.miles-summary').removeClass('show');
        }).fail(function(x){
          console.error(x);
        });


    })

    $('#load_sales_summary_chart').click(function(){
      var data = {
        // query_type: 'not_on_load',
        // dbid: $(this).siblings('input').attr('db-id'),
        // category: $(this).siblings('input').attr('category'),
        date_from: $('#ss_chart_date_from').val(),
        date_to: $('#ss_chart_date_to').val(),
        period: $('#ss_chart_period').val()
      }

      if (data.date_from == "" || data.date_to == "" || data.period == "") {
        swal('Oops', 'You need to enter date and periodicity before continuing.', 'error');
        return false;
      }

      // if (data.cat == "") {
      //   swal('Oops', 'You need to enter a category before continuing.', 'error');
      //   return false;
      // }

      var pull_data = $.ajax({
        method: 'POST',
        data: data,
        url: 'zactions/get_sales_summary_chart.php'
      });

      pull_data.done(function(r){
        var r = JSON.parse(r);

        if (r.code == 1) {
          ss_chart.load({
            columns: r.to_chart
          });
        }

        $('#dash-cat-select-ss').addClass('disabled').attr('disabled', true);
        $('#sales-summary-chart').fadeIn();
        $('[target=ss_chart]').fadeIn();
        $('.dropdown-menu.show.sales-summary').removeClass('show');
        }).fail(function(x){
          console.error(x);
        });


    });

    $('#load_collected_summary_chart').click(function(){
      var data = {
        // query_type: 'not_on_load',
        // dbid: $(this).siblings('input').attr('db-id'),
        // category: $(this).siblings('input').attr('category'),
        date_from: $('#cs_chart_date_from').val(),
        date_to: $('#cs_chart_date_to').val(),
        period: $('#cs_chart_period').val()
      }

      if (data.date_from == "" || data.date_to == "" || data.period == "") {
        swal('Oops', 'You need to enter date and periodicity before continuing.', 'error');
        return false;
      }

      // if (data.cat == "") {
      //   swal('Oops', 'You need to enter a category before continuing.', 'error');
      //   return false;
      // }

      var pull_data = $.ajax({
        method: 'POST',
        data: data,
        url: 'zactions/get_paid_summary_chart.php'
      });

      pull_data.done(function(r){
        var r = JSON.parse(r);

        if (r.code == 1) {
          cs_chart.load({
            columns: r.to_chart
          });
        }

        $('#collected-summary-chart').fadeIn();
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
        var r = JSON.parse(r);

        //console.log(r);

        if (r.code == 1) {

          // tms_chart.unload();
          tms_chart.load({
            columns: r.to_chart,
            unload: ['Loaded', 'Empty', 'Incomplete Loaded', 'Incomplete Empty', 'Goal']
          })
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

        case 'ss_chart':
          ss_chart.unload();
          $('#sales-summary-chart').fadeOut();
          $('#dash-cat-select-ss').removeClass('disabled').attr('disabled', false);
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
