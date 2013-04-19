jQuery(document).ready(function($){
	$('input#use_userdata_0').live( 'change', function(){
		if( $(this).prop('checked') == true ){
			$('#use_userdata_no').slideUp('fast', function(){
				$(this).find('input').prop('disabled', true);
				$(this).find('select').prop('disabled', true);
				$(this).find('textarea').prop('disabled', true);

				$('#use_userdata').slideDown('fast', function(){
					$(this).find('input').prop('disabled', false);
					$(this).find('select').prop('disabled', false);
					$(this).find('textarea').prop('disabled', false);
				});
			});
		}
	});

	$('input#use_userdata_1').live( 'change', function(){
		if( $(this).prop('checked') == true ){
			$('#use_userdata').slideUp('fast', function(){
				$(this).find('input').prop('disabled', true);
				$(this).find('select').prop('disabled', true);
				$(this).find('textarea').prop('disabled', true);

				$('#use_userdata_no').slideDown('fast', function(){
					$(this).find('input').prop('disabled', false);
					$(this).find('select').prop('disabled', false);
					$(this).find('textarea').prop('disabled', false);
				});
			});
		}
	});

	var check_country = function(){
		if( $('#use_userdata_no').find('#country').find(':selected').val() == 'US' ){
			return true;
		}

		return false;
	}

	$('#use_userdata_no select#country').live( 'change', function(){
		if( check_country() == true ){
			$('#use_userdata_no input#state').prop('disabled', true).hide(0, function(){
				$('#use_userdata_no select#state').prop('disabled', false).show(0);
			});
		} else {
			$('#use_userdata_no select#state').prop('disabled', true).hide(0, function(){
				$('#use_userdata_no input#state').prop('disabled', false).show(0);
			});
		}
	});

	$('form#transaction').submit(function(){
		$('input').each( function(){ $(this).prop( 'disabled', true ) } );
		$('select').each( function(){ $(this).prop( 'disabled', true ) } );
		$('textarea').each( function(){ $(this).prop( 'disabled', true ) } );

		var userform = function(){
			if( $('input#use_userdata_0').prop('cheked') == true ){
				return $('div#use_userdata');
			} else {
				return $('div#use_userdata_no');
			}
		}

		var state = function(){
			if( $(userform).find('#country').find(':selected').val() == 'US' ){
				return $(userform).find('select#state').find(':selected').val();
			} else {
				return $(userform).find('input#state').val();
			}
		}

		var data = {
			action: 'jmembers_transaction',
			jmembers_nonce: $('#jmembers_nonce').val(),
			user_id: $('#user').val(),
			package_id: $('#package').val(),
			post_id: $('#post').val(),
			creditcardtype: $('#creditcardtype').find(':selected').val(),
			acct: $('#acct').val(),
			cvv2: $('#cvv2').val(),
			expdate: $('#expdate_month').find(':selected').val()+$('#expdate_year').find(':selected').val(),
			user_email: $(userform).find('#user_email').val(),
			first_name: $(userform).find('#first_name').val(),
			last_name: $(userform).find('#last_name').val(),
			address: $(userform).find('#address').val(),
			city: $(userform).find('#city').val(),
			state: state(),
			zip: $(userform).find('#zip').val(),
			country: $(userform).find('#country').find(':selected').val(),
		}

		$.post( ajaxurl, data, function(response){
			response = $.parseJSON(response);

			$(location).attr( 'href', response['url'] );

			return false;
		});

		return false;
	});
});
