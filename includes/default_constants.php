<?php
require_once('connect.php');
	
// CONFIGURATION OPTIONS AND CONSTANTS 

# SETS APPLICATION NAME
$result = mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT * from config LIMIT 1') 
or die('could not fetch Configuration Settings!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
$config = mysqli_fetch_array($result);

$app_name = $config['application_name'];

define('APPLICATION_NAME', $app_name);
// APPLICATION_NAME is now available as a constant in any page

//~ $site_currency = "USD";
//~ define('SITE_CURRENCY', $site_currency);
//~ // SITE_CURRENCY is now available as a constant in any page


# SETS WELCOME MESSAGE
$welcome_message = $config['welcome_message'];

define('WELCOME_MESSAGE', $welcome_message);
// WELCOME MESSAGE is now available as a constant


# SETS BASE PATH
if (isset($config['base_path'])){
$base_path = $config['base_path'];
}
else {$base_path = "/var/www/html/";}

define('BASE_PATH', $base_path);
// BASE_PATH is now available as a constant in any page


# SETS ADMIN FOLDER PATH
$admin_folder_name = $config['admin_folder_name'];

define('ADMIN_PATH', BASE_PATH .$admin_folder_name);
// ADMIN_PATH is now available as a constant in any page 

# SETS PATH TO DEFAULT FUNCTION FILE FOR EASY INCLUDING

define('DEFAULT_FUNCTIONS', $config['default_functions']);
// DEFAULT_FUNUCTIONS is now available to any addon or module

# SETS PATH TO STYLESHEET FILE FOR EASY THEMEING

define('STYLESHEET', $config['stylesheet']);
// STYLESHEET is now available to any addon or function

$online = array();
?>
