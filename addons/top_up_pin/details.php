<?php
#@ Wanni CMS
#
	
# Replace CHANGE ME with your addon's name
# Note that addon names should be short, and use underscores not spaces 

#-----------------------------------------------------


$my_addon_name = "top_up_pin"; 

$my_addon_desc = "Top up vouchers for user accounts"; // Short clear description of what addon does.

$version = "0.01.dev"; 

$config_path = "top_up_pin/config";

$settings_path = "top_up_pin";


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
