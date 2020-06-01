$(document).ready(function(){

  $('.tog-modal').click(function(){
    var target = $(this).attr('data-target');
    var topic = $(this).attr('data-topic');
    var report_name = $(this).attr('report-name');

    $('.datePickerTopic').html(topic);
    $(target).find('.executeReport').attr('report-name', report_name);
    $(target).modal('show');
  })

  $('.executeReport').click(function(){
    var reportName = $(this).attr('report-name');
    var from = $('#reportDateFrom').val();
    var to = $('#reportDateTo').val();
    var report_location = "/plsuite/mxportal/Reports/actions/" + reportName + ".php?from=" + from + "&to=" + to;
    window.location = report_location;
    $('.modal').modal('hide');
  })

});
