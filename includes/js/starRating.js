$(document).ready(function(){

    $('.ratings_stars').hover(
        // Handles the mouseover
        function() {
            $(this).prevAll().andSelf().addClass('ratings_over');
            $(this).nextAll().removeClass('ratings_vote'); 
        },
        // Handles the mouseout
        function() {
            $(this).prevAll().andSelf().removeClass('ratings_over');
            set_votes($(this).parent());
        }
    );

    $('.rate_widget').each(function(i) {
    var widget = this;
    var out_data = {
        widget_id : $(widget).attr('id'),
        fetch: 1
    };
    $.post(
        'ratings.php',
        out_data,
        function(INFO) {
            $(widget).data( 'fsr', INFO );
            set_votes(widget);
        },
        'json'
    );
});
    

});

