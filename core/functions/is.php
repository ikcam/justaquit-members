<?php
function is_administrator(){
	$user_id = get_current_user_id();

	if( $user_id == 0 )
		return FALSE;

	if( get_userdata( $user_id )->user_level == 10 )
		return TRUE;

	return FALSE;
}
?>