jQuery(document).ready(function($){
	$( '#accordion' ).each(function(){
		$(this).accordion({
			collapsible: true
		});
	});

	$( '#membership-add' ).submit(function(){
		var parent = $(this);

		$(this).find('.waiting').show();
		$(this).find('#name').prop('disabled',true);
		$(this).find('#submit').prop('disabled',true);

		var data = {
			action: 'jmembers_membership_add',
			name: $(this).find('#name').val(),
			nonce: $(this).find('#jmember_nonce').val()
		}

		$.post( ajaxurl, data, function(response){
			if( response == 1 ){
				location.reload();
			} else {
				alert( response );
				$(parent).find('#name').prop('disabled',false);
				$(parent).find('#name').focus();
				$(parent).find('#submit').prop('disabled',false);
				$(parent).find('.waiting').hide();
			}
		});

		return false;
	});

	$( 'form[id^="membership-delete-"]' ).submit(function(){
		var parent = $(this);

		$(this).find('#submit').prop('disabled',true);
		$(this).find('.waiting').show();

		var data = {
			action: 'jmembers_membership_delete',
			nonce: $(this).find('#jmember_nonce').val(),
			membership_id: $(this).find('#membership_id').val()
		}

		$.post( ajaxurl, data, function(response){
			if( response == 1 ){
				location.reload();
			} else {
				alert(response);
				$(parent).find('#submit').prop('disabled',false);
				$(this).find('.waiting').hide();
			}
		});

		return false;
	});

	$( 'form[id^="package-add-"]' ).submit(function(){
		var parent = $(this);

		$(this).find('#submit').prop('disabled',true);
		$(this).find('.waiting').show();

		var data = {
			action: 'jmembers_package_add',
			nonce: $(this).find('#jmember_nonce').val()
		}

		$.post(ajaxurl, data, function(response){
			alert(response);
		});
	});
});