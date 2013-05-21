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
			fetch : 1
		};
		$.post(
			'rating.php',
			out_data,
			function(INFO) {
				$(widget).data( 'fsr', INFO );
				set_votes(widget);				
			},
			'json'
		)
		.done(function() { //alert("second success");
		 });
    });

    $('.ratings_stars').bind('click', function() {
            var star = this;
            var widget = $(this).parent();	
			var vote = $(star).attr('class').substring(5,6);
			if (eval(vote) > 1)
				vote = 'You rated: ' + vote + ' stars.';
            else
				vote = 'You rated: ' + vote + ' star.';			
			$('span#user_ratings_'+widget.attr('id')).text(vote);
			
            var clicked_data = {
                clicked_on : $(star).attr('class'),
                widget_id : widget.attr('id')
            };
            $.post(
                'rating.php',
                clicked_data,
                function(INFO) {
                    widget.data( 'fsr', INFO );
                    set_votes(widget);
                },
                'json'
            ); 
        }); 

});

function set_votes(widget) {	

    var id = $(widget).attr('id');
    var avg = $(widget).data('fsr').avg;
    var total_votes = $(widget).data('fsr').total_votes;

    $(widget).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote');
    $(widget).find('.star_' + avg).nextAll().removeClass('ratings_vote');
    //$(widget).find('.total_votes').text("Average: " + avg + ' Total: ' + total_votes );
	$(widget).attr("title", 'Avg: ' + avg + ' Total: ' + total_votes );
	$(widget).find('.hidden_avg_sorter').text(avg +''+ total_votes);
    //$(widget).find('.total_votes').text( votes + ' votes recorded (' + exact + ' rating)' );
	//$('span#user_ratings_'+id).text(votes);
	//update the other widget as well:
	widget2 = $("div[rel='static_widget_"+id+"']");
	$(widget2).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote');
    $(widget2).find('.star_' + avg).nextAll().removeClass('ratings_vote');   
	$(widget2).attr("title", 'Avg: ' + avg + ' Total: ' + total_votes );
	$(widget2).find('.hidden_avg_sorter').text(avg +''+ total_votes);	
	
}
    

