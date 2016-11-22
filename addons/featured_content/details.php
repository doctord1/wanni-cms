<?php
#@ Wanni CMS
#
	
# Replace CHANGE ME with your addon's name
# Note that addon names should be short, and use underscores not spaces 

#-----------------------------------------------------

//$my_addon_name = "CHANGE ME";
 
$my_addon_name = "featured_content"; 

$my_addon_desc = "Show featured content in slideshows"; // Short clear description of what addon does.

$version = "0.01.dev"; 

$config_path = "featured_content/config";

$settings_path = "featured_content";


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
