jQuery(document).ready(function($){
	$( '#accordion' ).each(function(){
		$(this).accordion({
			collapsible: true
		});
	});

	$( '#membership-add' ).submit(function(){
		var data = {
			action: 'jmembers_membership_add',
			name: $(this).find('#name').attr('value'),
			nonce: $(this).find('#jmember_nonce').attr('value')
		}

		$.post( ajaxurl, data, function(response){
			if( response == 1 ){
				$(this).find('#name').attr('value','');
				location.reload();
			} else {
				alert( response );
			}
		});

		return false;
	});

	$( '#membership-delete' ).click(function(){
		var membership_id = $(this).attr('rel');

		var data = {
			action: 'jmembers_membership_delete',
			membership_id: membership_id
		}

		$.post( ajaxurl, data, function(response){
			if( response == 1 ){
				location.reload();
			} else {
				alert(response);
			}
		});
	});
});