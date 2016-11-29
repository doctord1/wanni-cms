<?php
#@ Wanni CMS
#
	
# Replace CHANGE ME with your addon's name
# Note that addon names should be short, and use underscores not spaces 

#-----------------------------------------------------

//$my_addon_name = "CHANGE ME";
 
$my_addon_name = "draws"; 

$my_addon_desc = "daily and weekly draws where randomly selected winner takes it all."; // Short clear description of what addon does.

$version = "0.01.dev"; 

$config_path = "draws/config";

$settings_path = "draws";


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
