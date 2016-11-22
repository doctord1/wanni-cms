<?php 
session_start(); # Finds the session


if (isset($_COOKIE[session_name()])) { # Expire session cookie
	
	setcookie(session_name(), '', time()-50000, '/');
	}
	
session_destroy(); # Destroys session 

header("Location: ../?page_name=home&logout=true");
?>
