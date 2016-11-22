<?php 
function redirect_to($string=''){
	if($string !==''){$destination = BASE_PATH .$string;}
	else{$destination = BASE_PATH . $_GET['destination'];}
	header("Location: $destination");exit;
	}
?>
