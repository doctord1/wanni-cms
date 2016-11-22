<?php ob_start();
require_once('includes/session.php');
	if(isset($_POST['do_search'])){
		
	$_SESSION['search_table'] = $_POST['table'];
	$_SESSION['search_column'] = $_POST['search_column'];
	$_SESSION['search_term'] = $_POST['search_term'];
	$_SESSION['do_search'] = $_POST['do_search'];
	$destination = $_POST['destination'];
	#echo "<script> window.location.replace('{$destination}') </script>";
	
	header("Location: $destination");exit;
	#print_r($_SESSION);
	}
	
	if(isset($_GET['delete_pic'])){
	remove_file();	
		
		
	$destination = '';
	header("Location: $destination");exit;	
		
	}

?>
