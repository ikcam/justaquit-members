jQuery(document).ready(function($){
	$( 'form[id^="package-add-"]' ).submit(function(){
		var parent = $(this);

		$(this).find('#submit').prop('disabled',true);
		$(this).find('.waiting').show();

		var display_registration = 0, display_upgrade = 0, display_extend = 0, billing = 0;

		if( $(this).find('#display_registration').is(':checked') ){
			display_registration = 1;
		}

		if( $(this).find('#display_upgrade').is(':checked') ){
			display_upgrade = 1;
		}

		if( $(this).find('#display_extend').is(':checked') ){
			display_extend = 1;
		}

		var payment_pppro = 0, payment_ppstandard = 0, payment_1sc = 0;

		if( $(this).find('#payment_pppro').is(':checked') ){
			payment_pppro = 1;
		}

		if( $(this).find('#payment_ppstandard').is(':checked') ){
			payment_ppstandard = 1;
		}

		if( $(this).find('#payment_1sc').is(':checked') ){
			payment_1sc = 1;
		}

		if( $(this).find('#billing_1').is(':checked') ){
			billing = 1;
		}

		var data = {
			action:               'jmembers_package_add',
			nonce:                $(this).find('#jmember_nonce').val(),
			membership_id:        $(this).find('#membership_id').val(),
			duration:             $(this).find('#duration').val(),
			duration_type:        $(this).find('#duration_type').val(),
			price:                $(this).find('#price').val(),
			billing:              billing,
			description:          $(this).find('#description').val(),
			expired_package:      $(this).find('#expired_package').val(),
			display_registration: display_registration,
			display_upgrade:      display_upgrade,
			display_extend:       display_extend,
			payment_pppro:        payment_pppro,
			payment_ppstandard:   payment_ppstandard,
			payment_1sc:          payment_1sc,
			menu_order:           $(this).find('#menu_order').val()
		}

		$.post(ajaxurl, data, function(response){
			
			if( response == 1 ){
				location.reload();
			} else {
				alert(response);
				$(parent).find('#submit').prop('disabled',false);
				$(parent).find('.waiting').hide();
			}

		});

		return false;
	});

	$( 'form[id^="package-update-"]' ).submit(function(){
		var parent = $(this);

		$(this).find('#submit').prop('disabled',true);
		$(this).find('.waiting').show();

		var display_registration = 0, display_upgrade = 0, display_extend = 0, billing = 0;

		if ( $(this).find('#display_registration').is(':checked') ){
			display_registration = 1;
		}

		if ( $(this).find('#display_upgrade').is(':checked') ){
			display_upgrade = 1;
		}

		if ( $(this).find('#display_extend').is(':checked') ){
			display_extend = 1;
		}

		var payment_pppro = 0, payment_ppstandard = 0, payment_1sc = 0;
		
		if( $(this).find('#payment_pppro').is(':checked') ){
			payment_pppro = 1;
		}

		if( $(this).find('#payment_ppstandard').is(':checked') ){
			payment_ppstandard = 1;
		}

		if( $(this).find('#payment_1sc').is(':checked') ){
			payment_1sc = 1;
		}

		if( $(this).find('#billing_1').is(':checked') ){
			billing = 1;
		}

		if( $(this).find('#billing_1').is(':checked') ){
			billing = 1;
		}

		var data = {
			action:               'jmembers_package_update',
			nonce:                $(this).find('#jmember_nonce').val(),
			package_id:           $(this).find('#package_id').val(),
			membership_id:        $(this).find('#membership_id').val(),
			duration:             $(this).find('#duration').val(),
			duration_type:        $(this).find('#duration_type').val(),
			price:                $(this).find('#price').val(),
			billing:              billing,
			description:          $(this).find('#description').val(),
			expired_package:      $(this).find('#expired_package').val(),
			display_registration: display_registration,
			display_upgrade:      display_upgrade,
			display_extend:       display_extend,
			payment_pppro:        payment_pppro,
			payment_ppstandard:   payment_ppstandard,
			payment_1sc:          payment_1sc,
			menu_order:           $(this).find('#menu_order').val()
		}

		$.post( ajaxurl, data, function(response){
			if( response == 1 ){
				location.reload();
			} else {
				alert(response);
				$(parent).find('#submit').prop('disabled',false);
				$(parent).find('.waiting').hide();
			}
		});

		return false;
	});


	$( 'form[id^="package-delete-"]' ).submit(function(){
		var parent = $(this);

		$(this).find('#submit').prop('disabled',true);
		$(this).find('.waiting').show();
		
		var data = {
			action:    'jmembers_package_delete',
			nonce:      $(this).find('#jmember_nonce').val(),
			package_id: $(this).find('#package_id').val()
		}

		$.post( ajaxurl, data, function(response){
			if( response == 1 ){
				location.reload();
			} else {
				alert(response);
				$(parent).find('#submit').prop('disabled',false);
				$(parent).find('.waiting').hide();
			}
		});

		return false;
	});

});