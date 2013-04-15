jQuery(document).ready(function($){
	$('input[id^="date-"]').datepicker({
		changeMonth: true,
		changeYear:true,
		dateFormat: 'dd/mm/yy'
	});

	function block_form(form){
		$(form).find('.waiting').show();
		$(form).find('input').each(function(){ $(this).prop('disabled', true) });
		$(form).find('select').each(function(){ $(this).prop('disabled', true) });
		$(form).find('textarea').each(function(){ $(this).prop('disabled', true) });
	}

	function unblock_form(form){
		$(form).find('.waiting').hide();
		$(form).find('input').each(function(){ $(this).prop('disabled', false) });
		$(form).find('select').each(function(){ $(this).prop('disabled', false) });
		$(form).find('textarea').each(function(){ $(this).prop('disabled', false) });
	}

	$('form[id^="member-update-"]').submit(function(){
		var parent = $(this);

		block_form(parent);

		var data = {
			action: 'jmembers_member_update',
			jmembers_nonce: $(this).find('#jmembers_nonce').val(),
			user: $(this).find('input[name="user"]').val(),
			status: $(this).find('select[name="status"]').val(),
			package_id: $(this).find('select[name="package"]').val(),
			datetime_packjoin: $(this).find('input[name="date-join"]').val(),
			datetime_expire: $(this).find('input[name="date-expire"]').val(),
			payment_processor: $(this).find('select[name="payment-processor"]').val(),
			payment_profile_id: $(this).find('input[name="payment-profile"]').val(),
			payment_profile_status: $(this).find('select[name="payment-status"]').val(),
		}

		$.post(ajaxurl, data, function(response){
			response = $.parseJSON(response);
			
			alert(response['message']);

			unblock_form(parent);
			return false;
		});

		return false;
	});
});