jQuery(document).ready(function($){
	$('input#use_userdata_0').live( 'change', function(){
		if( $(this).prop('checked') == true ){
			$('#use_userdata_no').slideUp('fast', function(){
				$(this).find('input').prop('disabled', true);
				$(this).find('textarea').prop('disabled', true);
				$(this).find('select').prop('disabled', true);

				$('#use_userdata').slideDown('fast', function(){
					$(this).find('input').prop('disabled', false);
					$(this).find('textarea').prop('disabled', false);
					$(this).find('select').prop('disabled', false);
				});
			});
		}
	});

	$('input#use_userdata_1').live( 'change', function(){
		if( $(this).prop('checked') == true ){
			$('#use_userdata').slideUp('fast', function(){
				$(this).find('input').prop('disabled', true);
				$(this).find('textarea').prop('disabled', true);
				$(this).find('select').prop('disabled', true);

				$('#use_userdata_no').slideDown('fast', function(){
					$(this).find('input').prop('disabled', false);
					$(this).find('textarea').prop('disabled', false);
					$(this).find('select').prop('disabled', false);
				});
			});
		}
	});
});