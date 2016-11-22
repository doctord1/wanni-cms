<?php
#@ Wanni CMS
#
	
# Replace CHANGE ME with your addon's name
# Note that addon names should be short, and use underscores not spaces 

#-----------------------------------------------------


$my_addon_name = "payment"; 

$my_addon_desc = "Payments processing and Account funding"; // Short clear description of what addon does.

$version = "0.01.dev"; 

$config_path = "payment/config";

$settings_path = "payment";


#------------------------------------------------------

# dev for development version
# stable for stable or fully working version
# alpha or beta in between



$details = array(
'name' =>$my_addon_name ,
'desc' => $my_addon_desc,
'version' => $version, 
'config_path' => $config_path,
'settings_path' => $settings_path);

?>
