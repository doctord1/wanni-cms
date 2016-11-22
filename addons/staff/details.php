<?php
#@ Wanni CMS
#
	
# Replace CHANGE ME with your addon's name
# Note that addon names should be short, and use underscores not spaces 

#-----------------------------------------------------

//$my_addon_name = "CHANGE ME";
 
$my_addon_name = "staff"; 

$my_addon_desc = "Add and remove members of staff"; // Short clear description of what addon does.

$version = "0.01.dev"; 

$config_path = "staff/config";

$settings_path = "staff";


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
