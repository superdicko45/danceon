$.widget.bridge('uibutton', $.ui.button);


$(document).ajaxStart(function(){
  $.LoadingOverlay("show", {
    image       : "",
    fontawesome : "fa fa-spinner fa-spin"
  });
});

$(document).ajaxStop(function(){
    $.LoadingOverlay("hide");
});

$(document).on("select2:select", 'select', function(e){
  e.preventDefault();
  $(this).closest('.form-group').find('label.error').remove();
});

$('input[type=number]').on('keydown', function(evt) {
  var key = evt.charCode || evt.keyCode || 0;

  return (key == 8 ||
            key == 9 ||
            key == 46 ||
            key == 110 ||
            key == 190 ||
            (key >= 35 && key <= 40) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
});

$('#search_sidebar_btn').on('click', function(evt) {
  var q = $('#sidebar_cbarras').val();
  if(q.length > 7) window.location.replace("/" + q);
});

$('#sidebar_cbarras').on('keydown', function(evt) {

  if(evt.keyCode == 13){
    var q = $('#sidebar_cbarras').val();
    window.location.replace("/" + q);
  }

});

var determineActive = (function(){
  var location = window.location.pathname.split('/');
  cur_url = location[2];

  $('#li-link-' + cur_url).addClass('active');
}());
