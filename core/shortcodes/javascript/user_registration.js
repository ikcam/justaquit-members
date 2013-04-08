jQuery(document).ready(function($){
	function check_user(){
		var user_login = $('#user_login').val();

		if( user_login == '' )
			return false;

		$('img#user_login_status').hide(0, function(){
			$('img#user_login_waiting').show();
		});

		var data = {
			action: 'jmembers_check_user',
			user_login: user_login,
		}

		$.post( ajaxurl, data, function(response){
			response = $.parseJSON(response);

			$('#user_login').val(response.user_login);
			
			$('img#user_login_waiting').hide(0, function(){
				$('img#user_login_status').attr('src', response.image).show();
				$('#user_login_message').text(response.message);
			});

			if( response.match == 1 ){
				$('.user_row th').css('background', '#FFF');
				$('.user_row td').css('background', '#FFF');

				return true;
			} else if( response.match == 2 ){
				$('.user_row th').css('background', '#fffb99');
				$('.user_row td').css('background', '#fffb99');

				return false;
			} else{
				$('.user_row th').css('background', '#e9a394');
				$('.user_row td').css('background', '#e9a394');

				return false;
			}

		});
	}

	function check_email(){
		var user_email = $('#user_email').val();

		if( user_email == '' )
			return false;

		$('img#user_email_status').hide(0, function(){
			$('img#user_email_waiting').show();
		});

		var data = {
			action: 'jmembers_check_email',
			user_email: user_email,
		}

		$.post( ajaxurl, data, function(response){
			response = $.parseJSON(response);

			$('#user_email').val(response.user_email);

			$('img#user_email_waiting').hide(0, function(){
				$('img#user_email_status').attr('src', response.image).show();
				$('#user_email_message').text(response.message);
			});

			if( response.match == 1 ){
				$('.email_row th').css('background', '#FFF');
				$('.email_row td').css('background', '#FFF');

				return true;
			} else if( response.match == 2 ){
				$('.email_row th').css('background', '#fffb99');
				$('.email_row td').css('background', '#fffb99');

				return false;
			} else{
				$('.email_row th').css('background', '#e9a394');
				$('.email_row td').css('background', '#e9a394');

				return false;
			}
		});
	}

	function check_pass(){
		var match;

		if( $('#user_pass').val() == $('#user_pass_confirm').val() ){
			match = 1;
		} else {
			match = 2;
		}

		$('img#user_pass_status').hide(0,function(){
			$('img#user_pass_waiting').show();
		});

		var data = {
			action: 'jmembers_check_pass',
			match: match,
		}
		
		$.post(ajaxurl, data, function(response){
			response = $.parseJSON(response);

			$('img#user_pass_waiting').hide(0, function(){
				$('img#user_pass_status').attr('src', response.image).show();
				$('#user_pass_message').text(response.message);
			});

			if( response.match == 1 ){
				$('.pass_row th').css('background', '#FFF');
				$('.pass_row td').css('background', '#FFF');

				return true;
			} else{
				$('.pass_row th').css('background', '#e9a394');
				$('.pass_row td').css('background', '#e9a394');

				return false;
			}

		});
	}

	function check_package(){
		if( $('input[name=package_id]').prop('checked') == true ){
			$('.package_row').css('background', '#FFF');
			return true;
		} else {
			$('.package_row').css('background', '#e9a394');
			return false;
		}		
	}

	function check_processor(){
		if( $('input[name=payment_processor]').prop('checked') == true ){
			$('.processor_row').css('background', '#FFF');
			return true;
		} else {
			$('.processor_row').css('background', '#e9a394');
			return false;
		}
	}

	function check_terms(){
		if( $('input#terms').prop('checked') == true )
			return true;

		return false;
	}

	function check_country(){
		var country = $('select#country').find(':selected').val();

		if( country == 'US' )
			return true;

		return false;
	}

	$('select#country').live( 'change', function(){
		if( check_country() == false ){
			$('select#state').prop('disabled', true).prop('required', true).hide(0, function(){
				$('input#state').prop('disabled', false).prop('required', true).show(0);
			});
		} else {
			$('input#state').prop('disabled', true).prop('required', false).hide(0, function(){
				$('select#state').prop('disabled', false).prop('required', true).show(0);
			});
		}
	});

	$('#user_login').live('change', function(){ check_user() } );
	
	$('#user_email').live('change', function(){ check_email() } );
	
	$('#user_pass').live( 'change', function(){ check_pass() } );
	
	$('#user_pass_confirm').live( 'change', function(){ check_pass() } );

	$('form#user_registration').submit(function(){
		var parent = $(this);
		//$(this).find('#submit').prop('disabled', true);
		$(parent).find('input').each(function(){ $(this).prop('disabled', true) });
		$(parent).find('select').each(function(){ $(this).prop('disabled', true) });
		$(parent).find('textarea').each(function(){ $(this).prop('disabled', true) });

		if( check_user() == false )
			return false;

		if( check_email() == false )
			return false;

		if( check_pass() == false )
			return false;

		if( check_package() == false )
			return false;

		if( check_processor() == false )
			return false;

		if( check_terms() == false )
			return false;

		var state = function(){
			if( check_country() == false ){
				return $('input#state').val();
			} else {
				return $('select#state').find(':selected').val();
			}
		}

		var data = {
			action: 'jmembers_user_registration',
			jmembers_nonce: $('#jmembers_nonce').val(),
			user_login: $('input#user_login').val(),
			user_email: $('input#user_email').val(),
			user_pass: $('input#user_pass').val(),
			first_name: $('input#first_name').val(),
			last_name: $('input#last_name').val(),
			address: $('textarea#address').val(),
			city: $('input#city').val(),
			state: state,
			zip: $('input#zip').val(),
			country: $('select#country').find(':selected').val(),
			package_id: $('input[name=package_id]:checked').val(),
			payment_processor: $('input[name=payment_processor]:checked').val(),
		}

		$.post( ajaxurl, data, function(response){
			response = $.parseJSON(response);

			if( response['user_id'] == 0 ){
				alert( response['message'] );
				$(parent).find('input').each(function(){ $(this).prop('disabled', false) });
				$(parent).find('select').each(function(){ $(this).prop('disabled', false) });
				$(parent).find('textarea').each(function(){ $(this).prop('disabled', false) });

				if( check_country() == false ){
					$('select#state').prop('disabled', true);
				} else {
					$('input#state').prop('disabled', true);
				}
			} else {
				if( response['message'] != '' ){
					alert(response['message']);
				}

				$(location).attr( 'href', response['url'] );
			}

			return false;
		});

		return false;
	});
});