$(document).ready(function() {
  $('#confirmInfoBtn').click(function(){

    let windowOptions = {};

    windowOptions.place     = 'center';
    windowOptions.width     = $(window).width() * .90;
    windowOptions.height    = $(window).height() * .90;
    windowOptions.btnMin    = false;
    windowOptions.btnMax    = false;
    windowOptions.title     = "Confirm Trip Information";
    windowOptions.shadow    = true;
    windowOptions.draggable = false;
    windowOptions.resizable = false;

    Metro.window.create(windowOptions);
  });
});
