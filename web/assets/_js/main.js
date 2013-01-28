// Main Citagora Front-end Javascript Application
$(document).ready(function() {

    //Tabify (fixme with something a little better)
    if ($('#tabs').length > 0) {
        $('#tabs').tabify();
    }

    //Ratings test
    $('.rate-link').click(function(e) {

        //Don't go to the link...
        e.preventDefault();

        //URL
        var url    = $(this).attr('href');
        var rating = $(this).attr('data-score');
        var cat    = $(this).attr('data-category');

        //POST
        $.post(
            url,
            { 
                category: cat, 
                value: rating 
            },
            function(data) {
                //Console log
                console.log(data);
            },
            'json'
        );
    });
    
});