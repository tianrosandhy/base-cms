(function($) {
  'use strict';
  $(function() {
    var body = $('body');
    var contentWrapper = $('.content-wrapper');
    var scroller = $('.container-scroller');
    var footer = $('.footer');
    var sidebar = $('.sidebar');


    //Change sidebar

    $('[data-toggle="minimize"]').on("click", function() {
      body.toggleClass('sidebar-icon-only');
    });

    //checkbox and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    //initialize sidebar simplebar
    if($("#sidebar").length > 0){
      new SimpleBar(document.getElementById('sidebar'));
    }
    $("#sidebar").on('click', function(){
      new SimpleBar(document.getElementById('sidebar'));
    });
  });
})(jQuery);