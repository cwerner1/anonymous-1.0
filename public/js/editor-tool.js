
$(document).ready(function(){
    
    $("#storytext").keyup(function(e) {
        var my_text = $("#storytext").val();
        my_text = my_text.replace(/\,/g,"");
        my_text = my_text.replace(/\./g,"");
        my_text = my_text.replace(/\s+/g," ");
        var matches = my_text.match(/\b[A-Z](\w|\.|,|)+\s(?:[A-Z](\w|\.|,)+\s){0,10}/g);
        matches.sort();
        $("#output").html(matches.join('<br>').toString());
        var h = $("#output").height();
        h = h + 150;
        if(h<800){ h=800; }
        $('#editor-space').height(h);
     });
     
});
