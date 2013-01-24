// Main Citagora Front-end Javascript Application
$(document).ready(function() {

    //Tabify (fixme with something a little better)
    if ($('#tabs').length > 0) {
        $('#tabs').tabify();
    }

    //Ratings test
    $('#ratings-test').click(function() {
        $.post(
            siteUrl + '/documents/rate/51015023ece0b92c35000035',
            { category: 'overall', value: '4' },
            function(data) {
                console.log("DONE");
                console.log(data);
            },
            'json'
        );
    });
    
});