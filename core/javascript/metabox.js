jQuery(document).ready(function($){
	var active = 0;

	$('#jmembers-more-link').click(function(){
		$('#jmembers_metabox').find('.waiting').show();

		if( active == 0 ){
			var data = {
				action: 'jmembers_more_link',
				active: active
			}

			$.post( ajaxurl, data, function(response){
				$('#jmembers-more').show();
				$('#jmembers-more-link').text(response);
				$('#jmembers_metabox').find('.waiting').hide();
				active = 1;
			});

		} else {
			var data = {
				action: 'jmembers_more_link',
				active: active
			}

			$.post( ajaxurl, data, function(response){
				$('#jmembers-more').hide();
				$('#jmembers-more-link').text(response);
				$('#jmembers_metabox').find('.waiting').hide();
				active = 0;
			});
		}

		return false;
	});
});