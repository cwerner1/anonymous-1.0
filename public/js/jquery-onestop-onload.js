 $(function() {
    
    // The my_fields array holds the ID for each search button
    var my_fields = [
        '#q_text',
        '#g_text',
        '#yahoo_text',
        '#all_text',
        '#ask_text',
        '#live_text',
        '#yippy_text',
        '#jux2_text',
        '#twingine_text',
        '#g_groups_text',
        '#search',
        '#twitter_text',
        '#sname',
        '.search_field',
        '#zoom_text',
        '#yellow',
        '#facebook_text',
        '#myspace_text',
        '#f_finance_text',
        '#yahoo_finance_text',
        '#gao_reports',
        '#docuticker_text',
        '#googleblog_text',
        '#googlescholar_text',
        '#refseek_text',
        '#wikipedia_searchInput',
        '#manta_text'
        ];

    // Entering text fills all the search fields by looping through my_fields array
    $("#q_text").keyup(function(e) {
        var my_query = $("#q_text").val();
        for(var i in my_fields) {

            // If it's the yellow book search
            // TODO: Use regex to split on one or MORE spaces
            if(my_fields[i] == '#yellow'){

                var mySplitQuery = [];

                mySplitQuery = my_query.split(" ");

                // split my_query into first, last, city, state
                var first_name = mySplitQuery[0];
                var last_name = mySplitQuery[1];
                var city = mySplitQuery[2];
                var state = mySplitQuery[3];

                $("#yellow_first").val(first_name);
                $("#yellow_last").val(last_name);
                $("#yellow_city").val(city);
                $("#yellow_state").val(state);

            } else {
                //console.info(my_fields[i]);
                $(my_fields[i]).val(my_query);
            }
        }
    });

    $("#tabs").tabs();

});
