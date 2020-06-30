$('.acdn-button').click(function() {
  $(this).next('.acdn-target').slideToggle('fast');
});

$(function () {
  $('#nav-toggle').on('click', function() {
      $('body').toggleClass('open');
  });
});