
$(document).ready(function() {
    
 // Anonymous source tracker cluetip
  $('.title').cluetip({
        width: '350px', 
        splitTitle: '|', 
        clickThrough: true,
        positionBy: 'mouse',
        leftOffset: 30,
        tracking: true,
        showTitle: false
  });
  

  // About the anonymous source tracker
  $('#about_text a').addClass('underline');
  
    $("#sortabletable").tablesorter({ 
        headers: { 
            2: { 
                sorter: false 
            }, 
            3: { 
                sorter: false 
            } 
        } 
    }); 
    
   $('#about_text').hide();
   $('#about_link').click(function() {
        $('#about_text').toggle();
   });
   
  
});