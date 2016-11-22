<?php
require_once('connect.php');
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from addons WHERE status=1") 
	or die ('Could not fetch required addons!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	while($result = mysqli_fetch_array($query)){
		$required = "" .htmlentities($result['required_files']) ."";
		//$required = "'" . $required ."'" ;
		require_once($required);
	}

?>
