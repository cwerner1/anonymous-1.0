
$(document).ready(function(){
   $('#btn').click(function() {
       
       $("#output").html('');
       $("#doc-message").html('');
       $("#editor-doc").html('');
       $('#output-message').html('');
       
       var replacement;
       
       $("#message").html('<p><img src="/anonymous/public/images/spinner.gif" alt="Wait" /></p><p>Please wait ...</p>');
       var textboxContent = $('#storytext').val();
       $.ajax({
              url: 'http://schaver.com/anonymous/application/models/retrieveOpenCalaisEntities.php',
              type: 'post',
              data: {content: textboxContent},
              success: function(data) {
                  var entities = data.split("|");
                  entities = entities.slice(0,entities.length-1);
//                  entities = $.unique(entities);
                  entities.sort(function(x,y){
                        var a = String(x).toUpperCase();
                        var b = String(y).toUpperCase();
                        if (a > b)
                           return 1
                        if (a < b)
                           return -1
                        return 0;
                      });
                  $('#output').append(entities.join('<br>').toString());
                  $("#message").html('');
                  $('#output-message').html('<br><h3>Here are the entities extracted from the text:</h3>');
                  for(i=0;i<=entities.length;i++){
                    if(entities[i]){
                       replacement = "("+entities[i]+")";
                    }
                    // console.log(replacement);
                    // TODO: Calais started returning extra slash with some results; Temporary or need permanent fix?
                    replacement = replacement.replace('\\','');
                    
                    textboxContent = textboxContent.replace(new RegExp(replacement,"gim"),"<span class=\"highlight\">$1</span>");
                  }
                  textboxContent = textboxContent.replace(new RegExp("\\n","g"),"<br>");
                  $("#editor-doc").html(textboxContent);
                  $("#doc-message").html("<br><h3>Here's what you pasted:</h3>");
                  var doc_height = $("#editor-doc").height();
                  var output_height = $("#output").height();
                  h = doc_height + output_height + 500;
                  if(h<800){ h=800; }
                  $('#editor-space').height(h);
              }
       });
   });        
});
