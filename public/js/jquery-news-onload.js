
$(document).ready(function() {

 // Home page clue tip
  $('.load-local').cluetip({
        height: 'auto',
        local:true,
        positionBy: 'mouse',
        width:600,
        clickThrough: true,
        tracking: true,
        sticky: false
  });

   // Home page about
  $('#about a').addClass('underline');
  
  $('#twitter-stream').height(($('#news-stream').height()-253));
  
});