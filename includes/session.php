<?php
include_once(BASE_PATH.'details.php');
session_start(); 
$_SESSION['free_view_count'] = 0;
$_SESSION['SITE_VERSION'] = SITE_VERSION;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    // session started more than 30 minutes ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$_SESSION['prev_url'] = $_SERVER['HTTP_REFERER'];
$_SESSION['current_url'] = 'http://'.$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
if(empty($_SESSION['username'])){
	$_SESSION['role'] = 'anonymous';
	}

?>
