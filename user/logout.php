<?php 

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit


session_start(); # Finds the session
$user = $_SESSION['username'];
$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE user set `logged_in`='no' where user_name='{$user}'") or die('FAiled to logout'. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
if (isset($_COOKIE[session_name()])) { # Expire session cookie
	
	setcookie(session_name(), '', time()-50000, '/');
	}
	
session_destroy(); # Destroys session 

header("Location: ../?page_name=home&logout=true");
?>
